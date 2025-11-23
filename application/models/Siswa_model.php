<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa_model extends CI_Model
{
    public function get_siswa_by_nis($nis)
    {
        return $this->db->get_where('siswa', ['nis' => $nis])->row_array();
    }

    public function get_all_kelas_jurusan()
    {
        $this->db->order_by('nama_kelas', 'ASC');
        return $this->db->get('kelas_jurusan')->result_array();
    }

    public function get_setting($key)
    {
        $query = $this->db->get_where('settings', ['setting_key' => $key]);
        $result = $query->row_array();
        return $result ? $result['setting_value'] : null;
    }

    public function get_lokasi()
    {
        return $this->db->get('lokasi_absensi')->row_array();
    }

    public function cek_absensi_hari_ini($nis)
    {
        $today = date('Y-m-d');
        $this->db->where('nis', $nis);
        $this->db->where('DATE(waktu)', $today);
        $query = $this->db->get('absensi');
        return $query->result_array();
    }

    public function insert_absensi($data)
    {
        return $this->db->insert('absensi', $data);
    }
}
