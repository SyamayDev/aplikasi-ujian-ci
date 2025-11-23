<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket_model extends CI_Model {

    public function get_all_paket()
    {
        $this->db->select('paket_soal.*, guru.nama as nama_guru, mapel.nama_mapel');
        $this->db->from('paket_soal');
        $this->db->join('guru', 'paket_soal.guru_id = guru.id');
        $this->db->join('mapel', 'paket_soal.mapel_id = mapel.id');
        return $this->db->get()->result_array();
    }
    
    public function get_paket_by_id($id)
    {
        return $this->db->get_where('paket_soal', ['id' => $id])->row_array();
    }

    public function insert_paket($data)
    {
        $this->db->insert('paket_soal', $data);
        return $this->db->insert_id();
    }

    public function update_paket($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('paket_soal', $data);
    }
}
