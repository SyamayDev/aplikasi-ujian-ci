<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Guru_model');
        $this->load->model('Siswa_model');
        $this->load->helper('ujian');
        $this->load->library('form_validation');
    }

    public function admin_login()
    {
        check_already_login(); // Keep the check to prevent already logged in users from seeing login
        $data['target_role'] = 'admin';
        $this->load->view('auth/login', $data);
    }

    public function guru_login()
    {
        check_already_login(); // Keep the check
        $data['target_role'] = 'guru';
        $this->load->view('auth/login', $data);
    }

    public function siswa_login()
    {
        check_already_login(); // Keep the check
        $data['target_role'] = 'siswa';
        $this->load->view('auth/login', $data);
    }

    public function process()
    {
        $this->form_validation->set_rules('username', 'Username / NISN', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        $target_role = $this->input->post('target_role');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($target_role ? $target_role : 'auth/siswa_login'); // Redirect to specific login or default
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $pass_md5 = md5($password);

            $authenticated = FALSE;

            // Destroy session before attempting new login to prevent role mix
            $this->session->sess_destroy();

            switch ($target_role) {
                case 'admin':
                    $admin = $this->Admin_model->getAdminByUsername($username);
                    if ($admin && $pass_md5 == $admin['password_md5']) {
                        $session_data = [
                            'user_id'  => $admin['id'],
                            'nama'     => $admin['nama'],
                            'role'     => 'admin',
                        ];
                        $this->session->set_userdata($session_data);
                        $authenticated = TRUE;
                        redirect('admin');
                    }
                    break;
                case 'guru':
                    $guru = $this->Guru_model->getGuruByUsername($username);
                    if ($guru && $pass_md5 == $guru['password_md5']) {
                        $session_data = [
                            'user_id'  => $guru['id'],
                            'nama'     => $guru['nama'],
                            'role'     => 'guru',
                        ];
                        $this->session->set_userdata($session_data);
                        $authenticated = TRUE;
                        redirect('guru');
                    }
                    break;
                case 'siswa':
                    $siswa = $this->Siswa_model->getSiswaByNisn($username);
                    if ($siswa && $pass_md5 == $siswa['password_md5']) {
                        $session_data = [
                            'user_id'  => $siswa['id'],
                            'kelas_id' => $siswa['kelas_id'],
                            'nama'     => $siswa['nama'],
                            'role'     => 'siswa',
                        ];
                        $this->session->set_userdata($session_data);
                        $authenticated = TRUE;
                        redirect('siswa');
                    }
                    break;
            }

            if (!$authenticated) {
                $this->session->set_flashdata('error', 'Username/NISN atau Password salah!');
                redirect($target_role ? $target_role : 'auth/siswa_login');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/siswa_login'); // Redirect to siswa login after logout
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }
}

