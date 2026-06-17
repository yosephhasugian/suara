<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model'); // 🔥 FIX: huruf besar
        $this->load->model('Activity_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index() {
        if ($this->session->userdata('user_id')) {
            redirect('bus_monitor');
        }
        $this->load->view('auth/login');
    }

    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->User_model->login($username, $password);

        if ($user) {
            $this->session->set_userdata([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'role' => $user['role']
            ]);

            $this->Activity_model->log('login', 'User logged in successfully');

            echo json_encode([
                'status' => 'success',
                'message' => 'Login berhasil!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Username atau password salah!'
            ]);
        }
    }

    public function logout() {
        $this->Activity_model->log('logout', 'User logged out');
        $this->session->sess_destroy();
        redirect('auth');
    }
}