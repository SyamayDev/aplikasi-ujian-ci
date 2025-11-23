<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_model extends CI_Model {

    public function get_all_settings() {
        $settings = $this->db->get('pengaturan')->result();
        $config = [];
        foreach ($settings as $setting) {
            $config[$setting->key] = $setting->value;
        }
        return $config;
    }

    public function get_setting($key) {
        $this->db->where('key', $key);
        $result = $this->db->get('pengaturan')->row();
        return $result ? $result->value : null;
    }

    public function update_settings($data) {
        $this->db->trans_start();
        foreach ($data as $key => $value) {
            $this->db->where('key', $key);
            $this->db->update('pengaturan', ['value' => $value]);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
