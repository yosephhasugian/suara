<?php
class User_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function login($username, $password) {
        $user = $this->db
            ->where('username', $username)
            ->get('users')
            ->row_array();

        if ($user && md5($password) == $user['password']) {
            return $user;
        }

        return false;
    }

    // 🔥 TAMBAHAN INI (BIAR ERROR HILANG)
    public function get_all() {
        return $this->db->get('users')->result_array();
    }

    public function insert($data) {
        $data['password'] = md5($data['password']);
        return $this->db->insert('users', $data);
    }
}