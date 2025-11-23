<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Do not force is_admin() here so the `/admin` URL can show login
        // Protect individual methods explicitly to allow `/admin` -> login
        $this->load->model('Guru_model');
        $this->load->model('Siswa_model');
        $this->load->model('Kelas_model');
        $this->load->model('Mapel_model');
        $this->load->model('Paket_model');
        $this->load->model('Room_model');
        $this->load->model('Hasil_model');
    }

    public function index()
    {
        // If not logged in as admin, redirect to login page
        $CI = &get_instance();
        if ($CI->session->userdata('role') !== 'admin') {
            redirect('auth');
            return;
        }

        // Collect quick stats for dashboard
        $data['total_guru'] = count($this->Guru_model->get_all_guru());
        $data['total_siswa'] = count($this->Siswa_model->get_all_siswa());
        $all_paket = $this->Paket_model->get_all_paket();
        $data['paket_pending'] = 0;
        foreach ($all_paket as $p) {
            if (isset($p['status']) && $p['status'] === 'pending') $data['paket_pending']++;
        }
        $all_rooms = $this->Room_model->get_all_rooms();
        $active = 0;
        foreach ($all_rooms as $r) {
            if (isset($r['aktif']) && $r['aktif']) $active++;
        }
        $data['active_rooms'] = $active;

        // average score
        $avg = $this->db->select_avg('nilai')->get('hasil_ujian')->row();
        $data['avg_score'] = $avg ? round($avg->nilai, 2) : null;

        // recent results (last 5)
        $this->db->select('hasil_ujian.*, siswa.nama as siswa_nama, paket_soal.nama_paket, room.nama_room');
        $this->db->from('hasil_ujian');
        $this->db->join('siswa', 'hasil_ujian.siswa_id = siswa.id', 'left');
        $this->db->join('room', 'hasil_ujian.room_id = room.id', 'left');
        $this->db->join('paket_soal', 'room.paket_id = paket_soal.id', 'left');
        $this->db->order_by('hasil_ujian.id', 'desc');
        $this->db->limit(5);
        $data['recent_results'] = $this->db->get()->result_array();

        $data['title'] = 'Dashboard Admin';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/admin_footer');
    }

    //
    // GURU MANAGEMENT
    //
    public function guru()
    {
        is_admin();
        $data['title'] = 'Manajemen Guru';
        $data['list_guru'] = $this->Guru_model->get_all_guru();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/guru/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function tambah_guru()
    {
        is_admin();
        $data['title'] = 'Tambah Guru';
        $data['list_kelas'] = $this->Kelas_model->get_all_kelas();
        $data['list_mapel'] = $this->Mapel_model->get_all_mapel();

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[guru.username]');
        $this->form_validation->set_rules('nip', 'NIP', 'required|is_unique[guru.nip]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/guru/tambah', $data);
            $this->load->view('templates/admin_footer');
        } else {
            $post = $this->input->post();
            $guru_data = [
                'nama' => $post['nama'],
                'username' => $post['username'],
                'nip' => $post['nip'],
                'password_md5' => md5($post['password']),
                // Storing as comma-separated values as requested
                'kelas_list' => isset($post['kelas_list']) ? implode(',', $post['kelas_list']) : null,
                'mapel_list' => isset($post['mapel_list']) ? implode(',', $post['mapel_list']) : null,
            ];
            $this->Guru_model->insert_guru($guru_data);
            $this->session->set_flashdata('success', 'Data guru berhasil ditambahkan.');
            redirect('admin/guru');
        }
    }

    public function edit_guru($id)
    {
        is_admin();
        $data['title'] = 'Edit Guru';
        $data['guru'] = $this->Guru_model->get_guru_by_id($id);
        $data['list_kelas'] = $this->Kelas_model->get_all_kelas();
        $data['list_mapel'] = $this->Mapel_model->get_all_mapel();

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('nip', 'NIP', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/guru/edit', $data);
            $this->load->view('templates/admin_footer');
        } else {
            $post = $this->input->post();
            $guru_data = [
                'nama' => $post['nama'],
                'username' => $post['username'],
                'nip' => $post['nip'],
                'kelas_list' => isset($post['kelas_list']) ? implode(',', $post['kelas_list']) : null,
                'mapel_list' => isset($post['mapel_list']) ? implode(',', $post['mapel_list']) : null,
            ];
            // Only update password if it's provided
            if (!empty($post['password'])) {
                $guru_data['password_md5'] = md5($post['password']);
            }
            $this->Guru_model->update_guru($id, $guru_data);
            $this->session->set_flashdata('success', 'Data guru berhasil diperbarui.');
            redirect('admin/guru');
        }
    }

    public function hapus_guru($id)
    {
        is_admin();
        $this->Guru_model->delete_guru($id);
        $this->session->set_flashdata('success', 'Data guru berhasil dihapus.');
        redirect('admin/guru');
    }

    //
    // SISWA MANAGEMENT
    //
    public function siswa()
    {
        is_admin();
        $data['title'] = 'Manajemen Siswa';
        $data['list_siswa'] = $this->Siswa_model->get_all_siswa();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/siswa/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // ... (tambah, edit, hapus siswa)

    //
    // KELAS MANAGEMENT
    //
    public function kelas()
    {
        is_admin();
        $data['title'] = 'Manajemen Kelas';
        $data['list_kelas'] = $this->Kelas_model->get_all_kelas();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/kelas/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // ... (tambah, edit, hapus kelas)

    //
    // MAPEL MANAGEMENT
    //
    public function mapel()
    {
        is_admin();
        $data['title'] = 'Manajemen Mata Pelajaran';
        $data['list_mapel'] = $this->Mapel_model->get_all_mapel();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/mapel/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // ... (tambah, edit, hapus mapel)
}
