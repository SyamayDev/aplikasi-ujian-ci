<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_admin();
        $this->load->model('Room_model');
        $this->load->model('Paket_model');
        $this->load->model('Kelas_model');
        $this->load->model('Hasil_model');
        $this->load->model('Siswa_model');
        $this->load->helper('url');
    }

    public function index()
    {
        $data['title'] = 'Daftar Room Ujian';
        $data['rooms'] = $this->Room_model->get_all_rooms();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/room/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function create()
    {
        $data['title'] = 'Buat Room Ujian';
        $data['paket'] = $this->Paket_model->get_all_paket();
        $data['kelas'] = $this->Kelas_model->get_all_kelas();

        $this->form_validation->set_rules('nama_room', 'Nama Room', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/admin_header', $data);
            $this->load->view('admin/room/create', $data);
            $this->load->view('templates/admin_footer');
            return;
        }

        $post = $this->input->post();
        $kelas_target = isset($post['kelas_target']) ? implode(',', $post['kelas_target']) : '';
        $room_data = [
            'nama_room' => $post['nama_room'],
            'paket_id' => $post['paket_id'],
            'guru_id' => $post['guru_id'] ?? current_user_id(),
            'durasi_menit' => intval($post['durasi_menit']),
            'mulai_datetime' => $post['mulai_datetime'],
            'selesai_datetime' => $post['selesai_datetime'],
            'kelas_target' => $kelas_target,
            'aktif' => isset($post['aktif']) ? 1 : 0
        ];
        $this->Room_model->insert_room($room_data);
        $this->session->set_flashdata('success', 'Room berhasil dibuat');
        redirect('room');
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Room';
        $data['room'] = $this->Room_model->get_room_by_id($id);
        $data['hasil'] = $this->Hasil_model->get_hasil_by_room($id);
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/room/detail', $data);
        $this->load->view('templates/admin_footer');
    }

    public function edit($id)
    {
        is_admin();
        $this->load->model('Paket_model');
        $this->load->model('Kelas_model');

        if ($this->input->method() === 'post') {
            $post = $this->input->post();
            $kelas_target = isset($post['kelas_target']) ? implode(',', $post['kelas_target']) : '';
            // normalize datetime-local values (YYYY-MM-DDTHH:MM)
            $mulai = $post['mulai_datetime'] ?? null;
            $selesai = $post['selesai_datetime'] ?? null;
            if ($mulai && strpos($mulai, 'T') !== false) {
                $mulai = str_replace('T', ' ', $mulai);
                if (strlen($mulai) == 16) $mulai .= ':00';
            }
            if ($selesai && strpos($selesai, 'T') !== false) {
                $selesai = str_replace('T', ' ', $selesai);
                if (strlen($selesai) == 16) $selesai .= ':00';
            }

            $upd = [
                'nama_room' => $post['nama_room'] ?? null,
                'paket_id' => $post['paket_id'] ?? null,
                'durasi_menit' => intval($post['durasi_menit'] ?? 60),
                'mulai_datetime' => $mulai,
                'selesai_datetime' => $selesai,
                'kelas_target' => $kelas_target,
                'aktif' => isset($post['aktif']) ? 1 : 0
            ];
            $this->Room_model->update_room($id, $upd);
            echo json_encode(['status' => 'ok', 'message' => 'Room diperbarui']);
            return;
        }

        $r = $this->Room_model->get_room_by_id($id);
        if (!$r) show_404();
        $data['room'] = $r;
        $data['paket'] = $this->Paket_model->get_all_paket();
        $data['kelas'] = $this->Kelas_model->get_all_kelas();
        $this->load->view('admin/room/modal_edit', $data);
    }

    // Preview room as admin (read-only view similar to siswa but without scoring/anti-cheat)
    public function preview($id)
    {
        is_admin();
        $room = $this->Room_model->get_room_by_id($id);
        if (!$room) show_404();
        $soal = $this->Soal_model->get_soal_by_paket($room['paket_id']);
        $data = ['room' => $room, 'soal' => $soal];
        $this->load->view('templates/admin_header', $data);
        $this->load->view('ujian/preview', $data);
        $this->load->view('templates/admin_footer');
    }

    public function reset_hasil($hasil_id)
    {
        $this->Hasil_model->reset_ujian($hasil_id);
        if ($this->input->is_ajax_request() || $this->input->method() === 'post') {
            echo json_encode(['status' => 'ok', 'message' => 'Reset ujian berhasil']);
            return;
        }
        $this->session->set_flashdata('success', 'Reset ujian berhasil');
        redirect($_SERVER['HTTP_REFERER'] ?? 'room');
    }
}
