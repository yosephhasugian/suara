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

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    // 🔥 TAMBAHAN INI (BIAR ERROR HILANG)
    public function get_all() {
        return $this->db->get('users')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->where('id', $id)->get('users')->row_array();
    }

    public function username_exists($username, $ignore_id = null) {
        $this->db->where('username', $username);
        if ($ignore_id) {
            $this->db->where('id !=', $ignore_id);
        }
        return $this->db->count_all_results('users') > 0;
    }

    public function insert($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->db->insert('users', $data);
    }

    public function update($id, $data) {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }

        return $this->db->where('id', $id)->update('users', $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete('users');
    }
}