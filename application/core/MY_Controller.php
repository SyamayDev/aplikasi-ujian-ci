<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $data = [];

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        $this->load->model('Pengaturan_model');

        // Load global settings
        $this->data['pengaturan'] = $this->Pengaturan_model->get_all_settings();
        $this->data['app_name'] = isset($this->data['pengaturan']['nama_sekolah']) ? $this->data['pengaturan']['nama_sekolah'] : 'Aplikasi Ujian';

        $this->data['user'] = $this->session->userdata('user');
        $this->data['role'] = $this->session->userdata('role');
    }

    protected function protect_admin() {
        if (!$this->data['user'] || $this->data['role'] !== 'admin') {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login sebagai Admin.');
            redirect('auth/login');
        }
    }

    protected function protect_guru() {
        if (!$this->data['user'] || $this->data['role'] !== 'guru') {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login sebagai Guru.');
            redirect('auth/login');
        }
    }

    protected function protect_siswa() {
        if (!$this->data['user'] || $this->data['role'] !== 'siswa') {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login sebagai Siswa.');
            redirect('auth/login');
        }
    }

    protected function is_logged_in() {
        if ($this->data['user']) {
            switch ($this->data['role']) {
                case 'admin':
                    redirect('admin');
                    break;
                case 'guru':
                    redirect('guru');
                    break;
                case 'siswa':
                    redirect('siswa');
                    break;
                default:
                    redirect('auth/login');
            }
        }
    }
}
