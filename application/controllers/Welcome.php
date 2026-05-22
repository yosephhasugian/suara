<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    // Halaman login
    public function index() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('welcome/dashboard');
        }

        $this->load->view('login');
    }

    // Proses login
    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Cari user
        $query = $this->db->get_where('users', ['username' => $username], 1);
        $user = $query->row_array();

        if (!$user) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Username tidak ditemukan.'
            ]);
            return;
        }

        if (!password_verify($password, $user['password'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Password salah.'
            ]);
            return;
        }

        // Login sukses
        $this->session->set_userdata([
            'logged_in' => true,
            'user_id' => $user['id'],
            'username' => $user['username'],
            'name' => $user['name']
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Login berhasil! Mengarahkan ke dashboard...'
        ]);
    }

    // Dashboard (setelah login)
    public function dashboard() {
        if (!$this->session->userdata('logged_in')) {
            redirect('welcome');
        }

        $data['title'] = 'Dashboard';
        $data['name'] = $this->session->userdata('name');
        $this->load->view('dashboard', $data);
    }

    // Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('welcome');
    }
}