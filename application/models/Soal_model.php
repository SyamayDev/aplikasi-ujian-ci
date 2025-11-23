<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Soal_model extends CI_Model {

    public function insert_batch_soal($data)
    {
        return $this->db->insert_batch('soal', $data);
    }

    public function get_soal_by_paket($paket_id)
    {
        return $this->db->get_where('soal', ['paket_id' => $paket_id])->result_array();
    }
}
