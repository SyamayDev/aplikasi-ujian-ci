<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room_model extends CI_Model
{

    public function get_all_rooms()
    {
        $this->db->select('room.*, paket_soal.nama_paket');
        $this->db->from('room');
        $this->db->join('paket_soal', 'room.paket_id = paket_soal.id');
        return $this->db->get()->result_array();
    }

    public function get_room_by_id($id)
    {
        $this->db->select('room.*, paket_soal.nama_paket');
        $this->db->from('room');
        $this->db->join('paket_soal', 'room.paket_id = paket_soal.id', 'left');
        $this->db->where('room.id', $id);
        return $this->db->get()->row_array();
    }

    public function insert_room($data)
    {
        $this->db->insert('room', $data);
        return $this->db->insert_id();
    }

    public function update_room($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('room', $data);
    }

    public function delete_room($id)
    {
        return $this->db->delete('room', ['id' => $id]);
    }

    public function get_active_rooms_for_class($kelas_id)
    {
        $this->db->where('aktif', 1);
        $this->db->where("FIND_IN_SET('$kelas_id', kelas_target) !=", 0);
        $this->db->where('mulai_datetime <=', date('Y-m-d H:i:s'));
        $this->db->where('selesai_datetime >=', date('Y-m-d H:i:s'));
        return $this->db->get('room')->result_array();
    }
}
