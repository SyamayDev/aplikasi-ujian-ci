<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ujian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Room_model');
        $this->load->model('Soal_model');
        $this->load->model('Hasil_model');
        $this->load->model('Jawaban_model');
        $this->load->model('Siswa_model');
        $this->load->helper('url');
    }

    // List rooms available to current siswa
    public function list()
    {
        is_siswa();
        $siswa_id = current_user_id();
        $siswa = $this->Siswa_model->get_siswa_by_id($siswa_id);
        $kelas_id = $siswa['kelas_id'];
        $data['rooms'] = $this->Room_model->get_active_rooms_for_class($kelas_id);
        $this->load->view('templates/header', $data);
        $this->load->view('room/list', $data);
        $this->load->view('templates/footer');
    }

    // Start exam for a given room id
    public function start($room_id)
    {
        is_siswa();
        $siswa_id = current_user_id();
        $room = $this->Room_model->get_room_by_id($room_id);
        if (!$room) show_404();

        // check class membership
        $siswa = $this->Siswa_model->get_siswa_by_id($siswa_id);
        if (!in_array($siswa['kelas_id'], array_map('intval', explode(',', $room['kelas_target'])))) {
            show_error('Anda tidak terdaftar untuk room ini', 403);
        }

        // create hasil if not exists
        $existing = $this->Hasil_model->get_hasil_by_room_and_siswa($room_id, $siswa_id);
        if (!$existing) {
            $hid = $this->Hasil_model->create_hasil([
                'room_id' => $room_id,
                'siswa_id' => $siswa_id,
                'status' => 'in_progress',
                'started_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $hid = $existing['id'];
        }

        $soal = $this->Soal_model->get_soal_by_paket($room['paket_id']);
        $hasil_row = $this->db->get_where('hasil_ujian', ['id' => $hid])->row_array();
        $data = ['room' => $room, 'soal' => $soal, 'hasil_id' => $hid, 'hasil' => $hasil_row];
        $this->load->view('templates/header', $data);
        $this->load->view('ujian/execute', $data);
        $this->load->view('templates/footer');
    }

    // AJAX autosave endpoint
    public function save_answer()
    {
        is_siswa();
        $post = $this->input->post();
        if (!$post) {
            echo json_encode(['status' => 'error', 'message' => 'No data']);
            return;
        }
        $data = [
            'hasil_id' => $post['hasil_id'],
            'soal_id' => $post['soal_id'],
            'jawaban' => $post['jawaban'],
            'waktu_jawab' => $post['waktu_jawab'] ?? date('Y-m-d H:i:s')
        ];
        $this->Jawaban_model->save_jawaban($data);
        echo json_encode(['status' => 'success']);
    }

    // End exam (AJAX or server-side call)
    public function end()
    {
        is_siswa();
        $post = $this->input->post();
        $hasil_id = isset($post['hasil_id']) ? intval($post['hasil_id']) : 0;
        $hasil = $this->db->get_where('hasil_ujian', ['id' => $hasil_id])->row_array();
        if (!$hasil) {
            echo json_encode(['status' => 'error', 'message' => 'Hasil tidak ditemukan']);
            return;
        }

        // calculate score
        $jawaban = $this->Jawaban_model->get_jawaban_by_hasil($hasil_id);
        $total = 0;
        $benar = 0;
        $salah = 0;
        // get paket id from room
        $room = $this->Room_model->get_room_by_id($hasil['room_id']);
        $paket_id = $room ? $room['paket_id'] : 0;
        $soal_list = $this->Soal_model->get_soal_by_paket($paket_id);
        foreach ($soal_list as $s) {
            $total++;
            $ans = null;
            foreach ($jawaban as $j) {
                if ($j['soal_id'] == $s['id']) {
                    $ans = $j['jawaban'];
                    break;
                }
            }
            if ($ans !== null) {
                if (strtoupper(trim($ans)) == strtoupper(trim($s['kunci']))) $benar++;
                else $salah++;
            } else {
                $salah++;
            }
        }
        $nilai = ($total > 0) ? ($benar / $total) * 100 : 0;

        $this->Hasil_model->update_hasil($hasil_id, [
            'nilai' => $nilai,
            'benar' => $benar,
            'salah' => $salah,
            'status' => 'selesai',
            'finished_at' => date('Y-m-d H:i:s')
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Ujian diselesaikan. Nilai: ' . $nilai]);
    }
}
