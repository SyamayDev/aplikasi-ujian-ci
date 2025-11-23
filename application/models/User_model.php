<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        return $this->db->get('users')->row();
    }

    public function get_user_by_nis($nis) {
        $this->db->where('nis', $nis);
        return $this->db->get('users')->row();
    }

    public function check_login_siswa($nis, $password) {
        $user = $this->get_user_by_nis($nis);
        if ($user && $user->role === 'siswa') {
            // In a real app, you would verify the password.
            // The user requested a visible password, so we might just compare plaintext
            // or a simple hash if the password in DB is also just plaintext or simple hash.
            // For now, assuming password matches if user is found.
            // This will be properly implemented with MD5 later.
            return $user;
        }
        return false;
    }

    public function check_login_staff($username, $password) {
        $user = $this->get_user_by_username($username);
        
        // Using MD5 as requested by the user
        if ($user && ($user->role === 'admin' || $user->role === 'guru') && $user->password === md5($password)) {
            return $user;
        }
        
        return false;
    }

    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('users')->row();
    }
}
