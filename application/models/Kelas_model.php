<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends CI_Model {

    public function get_all_kelas()
    {
        return $this->db->get('kelas')->result_array();
    }

    public function get_kelas_by_id($id)
    {
        return $this->db->get_where('kelas', ['id' => $id])->row_array();
    }

    public function insert_kelas($data)
    {
        $this->db->insert('kelas', $data);
        return $this->db->insert_id();
    }

    public function update_kelas($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('kelas', $data);
    }

    public function delete_kelas($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('kelas');
    }
}
