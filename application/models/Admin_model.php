<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function get_admin_by_username($username)
    {
        return $this->db->get_where('admin', ['username' => $username])->row_array();
    }

    public function get_lokasi()
    {
        return $this->db->get('lokasi_absensi')->row_array();
    }

    public function update_lokasi($data)
    {
        $this->db->from('lokasi_absensi');
        if ($this->db->count_all_results() > 0) {
            return $this->db->update('lokasi_absensi', $data);
        } else {
            return $this->db->insert('lokasi_absensi', $data);
        }
    }

    public function get_setting($key)
    {
        $query = $this->db->get_where('settings', ['setting_key' => $key]);
        $result = $query->row_array();
        return $result ? $result['setting_value'] : null;
    }

    public function update_setting($key, $value)
    {
        $this->db->where('setting_key', $key);
        $this->db->update('settings', ['setting_value' => $value]);
        return $this->db->affected_rows() > 0;
    }

    public function get_all_absensi()
    {
        $this->db->order_by('waktu', 'DESC');
        return $this->db->get('absensi')->result_array();
    }

    public function get_absensi_by_id($id)
    {
        return $this->db->get_where('absensi', ['id' => $id])->row_array();
    }

    public function update_absensi($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('absensi', $data);
    }

    public function hapus_absensi($id)
    {
        return $this->db->delete('absensi', ['id' => $id]);
    }

    public function get_all_siswa()
    {
        $this->db->order_by('nama', 'ASC');
        return $this->db->get('siswa')->result_array();
    }

    public function get_siswa_by_id($id)
    {
        return $this->db->get_where('siswa', ['id' => $id])->row_array();
    }

    public function insert_siswa($data)
    {
        return $this->db->insert('siswa', $data);
    }

    public function update_siswa($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('siswa', $data);
    }

    public function delete_siswa($id)
    {
        return $this->db->delete('siswa', ['id' => $id]);
    }

    public function cek_nis_exist($nis)
    {
        $this->db->where('nis', $nis);
        $query = $this->db->get('siswa');
        return $query->num_rows() > 0;
    }

    public function get_all_kelas_jurusan()
    {
        $this->db->order_by('nama_kelas', 'ASC');
        return $this->db->get('kelas_jurusan')->result_array();
    }

    public function get_kelas_jurusan_by_id($id)
    {
        return $this->db->get_where('kelas_jurusan', ['id' => $id])->row_array();
    }

    public function insert_kelas_jurusan($data)
    {
        return $this->db->insert('kelas_jurusan', $data);
    }

    public function update_kelas_jurusan($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('kelas_jurusan', $data);
    }

    public function delete_kelas_jurusan($id)
    {
        return $this->db->delete('kelas_jurusan', ['id' => $id]);
    }

    public function cek_kelas_jurusan_exist($nama_kelas)
    {
        $this->db->where('nama_kelas', $nama_kelas);
        $query = $this->db->get('kelas_jurusan');
        return $query->num_rows() > 0;
    }

    public function insert_absensi($data)
    {
        return $this->db->insert('absensi', $data);
    }

    public function cek_absensi_hari_ini($nis)
    {
        $today = date('Y-m-d');
        $this->db->where('nis', $nis);
        $this->db->where('DATE(waktu)', $today);
        $query = $this->db->get('absensi');
        return $query->result_array();
    }

    public function get_all_admin()
    {
        return $this->db->get('admin')->result_array();
    }

    public function get_admin_by_id($id)
    {
        return $this->db->get_where('admin', ['id' => $id])->row_array();
    }

    public function cek_username_exist($username, $exclude_id = null)
    {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('admin')->num_rows() > 0;
    }

    public function insert_admin($data)
    {
        return $this->db->insert('admin', $data);
    }

    public function update_admin($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('admin', $data);
    }

    public function delete_admin($id)
    {
        return $this->db->delete('admin', ['id' => $id]);
    }
}
