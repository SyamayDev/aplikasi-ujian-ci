<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapel_model extends CI_Model {

    public function get_all_mapel()
    {
        return $this->db->get('mapel')->result_array();
    }

    public function get_mapel_by_id($id)
    {
        return $this->db->get_where('mapel', ['id' => $id])->row_array();
    }

    public function insert_mapel($data)
    {
        $this->db->insert('mapel', $data);
        return $this->db->insert_id();
    }

    public function update_mapel($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('mapel', $data);
    }

    public function delete_mapel($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('mapel');
    }
}
