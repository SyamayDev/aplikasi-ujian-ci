<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_admin')) {
    function is_admin() {
        $CI =& get_instance();
        if ($CI->session->userdata('role') === 'admin') {
            return true;
        }
        redirect('auth/blocked');
    }
}

if (!function_exists('is_guru')) {
    function is_guru() {
        $CI =& get_instance();
        if ($CI->session->userdata('role') === 'guru') {
            return true;
        }
        redirect('auth/blocked');
    }
}

if (!function_exists('is_siswa')) {
    function is_siswa() {
        $CI =& get_instance();
        if ($CI->session->userdata('role') === 'siswa') {
            return true;
        }
        redirect('auth/blocked');
    }
}

if (!function_exists('check_already_login')) {
    function check_already_login() {
        $CI =& get_instance();
        $user_session = $CI->session->userdata('role');
        if ($user_session) {
            if ($user_session == 'admin') {
                redirect('admin');
            } else if ($user_session == 'guru') {
                redirect('guru');
            } else {
                redirect('siswa');
            }
        }
    }
}