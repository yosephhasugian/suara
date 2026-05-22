<?php
class Signage_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_all() {
        return $this->db->select('signages.*, users.name as created_by_name')
                       ->from('signages')
                       ->join('users', 'users.id = signages.created_by', 'left')
                       ->order_by('id', 'ASC')
                       ->get()->result_array();
    }

    public function get_active() {
        return $this->db->where('status', 'active')->get('signages')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->where('id', $id)->get('signages')->row_array();
    }

    public function insert($data) {
        return $this->db->insert('signages', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('signages', $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete('signages');
    }
}