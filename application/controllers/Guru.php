<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guru extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_guru();
        $this->load->model('Guru_model');
        $this->load->model('Hasil_model');
        $this->load->helper('url');
    }

    public function index()
    {
        $data['title'] = 'Dashboard Guru';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('guru/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function hasil()
    {
        is_guru();
        $guru_id = current_user_id();
        $data['results'] = $this->Hasil_model->get_results_by_guru($guru_id);
        $this->load->view('templates/admin_header', $data);
        $this->load->view('guru/hasil', $data);
        $this->load->view('templates/admin_footer');
    }
}
