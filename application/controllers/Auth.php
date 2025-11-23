<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function login() {
        // If user is already logged in, redirect to their dashboard
        $this->is_logged_in();

        $this->form_validation->set_rules('username', 'Username atau NIS', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login', $this->data);
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Try logging in as admin or guru first
            $user = $this->User_model->check_login_staff($username, $password);

            // If not admin/guru, try logging in as siswa using NIS
            if (!$user) {
                // For now, siswa login logic is separate. User requested NISN login.
                // Assuming the 'username' field can be NIS
                $user = $this->User_model->check_login_siswa($username, $password);
            }
            
            // In the final version, the siswa password check will also use MD5
            // For now, let's unify the logic
            if(!$user) {
                $siswa_user = $this->User_model->get_user_by_nis($username);
                if ($siswa_user && $siswa_user->role === 'siswa' && $siswa_user->password === md5($password)) {
                    $user = $siswa_user;
                }
            }


            if ($user) {
                $session_data = [
                    'user_id' => $user->id,
                    'username' => $user->username ?: $user->nis,
                    'nama_lengkap' => $user->nama_lengkap,
                    'role' => $user->role,
                    'is_logged_in' => TRUE
                ];
                $this->session->set_userdata('user', $session_data);
                $this->session->set_userdata('role', $user->role);


                switch ($user->role) {
                    case 'admin':
                        redirect('admin');
                        break;
                    case 'guru':
                        redirect('guru');
                        break;
                    case 'siswa':
                        redirect('siswa');
                        break;
                }
            } else {
                $this->session->set_flashdata('error', 'Username/NIS atau Password salah!');
                redirect('auth/login');
            }
        }
    }

    public function logout() {
        $this->session->unset_userdata('user');
        $this->session->unset_userdata('role');
        $this->session->set_flashdata('success', 'Anda telah berhasil logout.');
        redirect('auth/login');
    }
}
