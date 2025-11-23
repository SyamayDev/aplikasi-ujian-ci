<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Role helper
 * Provides simple role-check helpers based on CI session.
 * NOTE: using MD5 and plaintext storage was requested by the user — this is insecure.
 */

if (!function_exists('is_admin')) {
    function is_admin()
    {
        $CI = &get_instance();
        $role = $CI->session->userdata('role');
        if ($role !== 'admin') {
            if ($CI->input->is_ajax_request()) {
                show_error('Forbidden', 403);
            } else {
                redirect('auth/blocked');
            }
            exit;
        }
    }
}

if (!function_exists('is_guru')) {
    function is_guru()
    {
        $CI = &get_instance();
        $role = $CI->session->userdata('role');
        if ($role !== 'guru') {
            if ($CI->input->is_ajax_request()) {
                show_error('Forbidden', 403);
            } else {
                redirect('auth/blocked');
            }
            exit;
        }
    }
}

if (!function_exists('is_siswa')) {
    function is_siswa()
    {
        $CI = &get_instance();
        $role = $CI->session->userdata('role');
        if ($role !== 'siswa') {
            if ($CI->input->is_ajax_request()) {
                show_error('Forbidden', 403);
            } else {
                redirect('auth/blocked');
            }
            exit;
        }
    }
}

if (!function_exists('current_user_id')) {
    function current_user_id()
    {
        $CI = &get_instance();
        return $CI->session->userdata('user_id');
    }
}
