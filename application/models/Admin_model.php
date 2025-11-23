<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function getAdminByUsername($username)
    {
        return $this->db->get_where('admin', ['username' => $username])->row_array();
    }

    // Add other admin-related database functions here
}