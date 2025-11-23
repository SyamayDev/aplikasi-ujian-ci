<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa_model extends CI_Model
{

    public function getSiswaByNisn($nisn)
    {
        return $this->db->get_where('siswa', ['nisn' => $nisn])->row_array();
    }

    public function get_all_siswa()
    {
        $this->db->select('siswa.*, kelas.kode_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'siswa.kelas_id = kelas.id');
        return $this->db->get()->result_array();
    }

    public function get_all_kelas_jurusan()
    {
        // Simple wrapper to return all kelas rows for forms that expect kelas list
        return $this->db->get('kelas')->result_array();
    }

    public function get_siswa_by_nis($nis)
    {
        return $this->db->get_where('siswa', ['nis' => $nis])->row_array();
    }

    public function get_siswa_by_id($id)
    {
        return $this->db->get_where('siswa', ['id' => $id])->row_array();
    }

    public function insert_siswa($data)
    {
        $this->db->insert('siswa', $data);
        return $this->db->insert_id();
    }

    public function update_siswa($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('siswa', $data);
    }

    public function delete_siswa($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('siswa');
    }
}
