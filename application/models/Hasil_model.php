<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hasil_model extends CI_Model
{

    public function create_hasil($data)
    {
        $this->db->insert('hasil_ujian', $data);
        return $this->db->insert_id();
    }

    public function get_hasil_by_room_and_siswa($room_id, $siswa_id)
    {
        return $this->db->get_where('hasil_ujian', [
            'room_id' => $room_id,
            'siswa_id' => $siswa_id
        ])->row_array();
    }

    public function update_hasil($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('hasil_ujian', $data);
    }

    public function get_hasil_by_room($room_id)
    {
        $this->db->select('hasil_ujian.*, siswa.nama, siswa.nisn, kelas.kode_kelas');
        $this->db->from('hasil_ujian');
        $this->db->join('siswa', 'hasil_ujian.siswa_id = siswa.id');
        $this->db->join('kelas', 'siswa.kelas_id = kelas.id');
        $this->db->where('hasil_ujian.room_id', $room_id);
        return $this->db->get()->result_array();
    }

    public function get_results_by_guru($guru_id)
    {
        $this->db->select('hasil_ujian.*, siswa.nama, siswa.nisn, kelas.kode_kelas, paket_soal.nama_paket, room.nama_room');
        $this->db->from('hasil_ujian');
        $this->db->join('room', 'hasil_ujian.room_id = room.id');
        $this->db->join('paket_soal', 'room.paket_id = paket_soal.id');
        $this->db->join('siswa', 'hasil_ujian.siswa_id = siswa.id');
        $this->db->join('kelas', 'siswa.kelas_id = kelas.id');
        $this->db->where('room.guru_id', $guru_id);
        return $this->db->get()->result_array();
    }

    public function reset_ujian($hasil_id)
    {
        // First, delete related answers
        $this->db->delete('jawaban_siswa', ['hasil_id' => $hasil_id]);
        // Then, delete the exam result itself
        return $this->db->delete('hasil_ujian', ['id' => $hasil_id]);
    }
}
