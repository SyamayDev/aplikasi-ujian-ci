<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru_model extends CI_Model {

    public function getGuruByUsername($username)
    {
        return $this->db->get_where('guru', ['username' => $username])->row_array();
    }
    
    public function get_all_guru()
    {
        return $this->db->get('guru')->result_array();
    }

    public function get_guru_by_id($id)
    {
        return $this->db->get_where('guru', ['id' => $id])->row_array();
    }

    public function insert_guru($data)
    {
        $this->db->insert('guru', $data);
        return $this->db->insert_id();
    }

    public function update_guru($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('guru', $data);
    }

    public function delete_guru($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('guru');
    }
}
