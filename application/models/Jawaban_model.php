<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jawaban_model extends CI_Model {

    public function get_jawaban_by_hasil($hasil_id)
    {
        return $this->db->get_where('jawaban_siswa', ['hasil_id' => $hasil_id])->result_array();
    }
    
    public function save_jawaban($data)
    {
        // Using INSERT ... ON DUPLICATE KEY UPDATE logic
        $sql = $this->db->insert_string('jawaban_siswa', $data) .
               ' ON DUPLICATE KEY UPDATE jawaban = VALUES(jawaban), waktu_jawab = VALUES(waktu_jawab)';
        
        // We need a unique key on (hasil_id, soal_id) for this to work.
        // Let's assume we will add it to the SQL schema.
        
        // A quick check if the unique key exists might be needed, but for now, we'll try to add it.
        // The table needs to be altered:
        // ALTER TABLE `jawaban_siswa` ADD UNIQUE `unique_jawaban`(`hasil_id`, `soal_id`);

        // A safer way in CI without direct SQL execution is to check first.
        $this->db->where('hasil_id', $data['hasil_id']);
        $this->db->where('soal_id', $data['soal_id']);
        $q = $this->db->get('jawaban_siswa');

        if ( $q->num_rows() > 0 ) 
        {
            $this->db->where('hasil_id', $data['hasil_id']);
            $this->db->where('soal_id', $data['soal_id']);
            $this->db->update('jawaban_siswa', ['jawaban' => $data['jawaban'], 'waktu_jawab' => $data['waktu_jawab']]);
        } else {
            $this->db->insert('jawaban_siswa', $data);
        }
    }
}
