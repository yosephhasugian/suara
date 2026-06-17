<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');

        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            show_error('Akses ditolak. Hanya administrator yang dapat mengakses halaman ini.', 403, 'Forbidden');
        }
    }

    public function index() {
        $data['title'] = 'Manajemen User';
        $data['users'] = $this->user_model->get_all();
        $data['roles'] = [
            'admin' => 'Admin',
            'teknisi' => 'Teknisi',
            'petugas_masuk' => 'Petugas Masuk',
            'petugas_keluar' => 'Petugas Keluar',
            'petugas_kedatangan' => 'Petugas Kedatangan',
            'petugas_keberangkatan' => 'Petugas Keberangkatan',
            'petugas_pengendapan' => 'Petugas Pengendapan',
        ];
        $this->load->view('users/index', $data);
    }

    public function save() {
        if ($this->input->method() !== 'post') {
            redirect('users');
        }

        $id = $this->input->post('id');
        $username = trim($this->input->post('username'));
        $name = trim($this->input->post('name'));
        $role = $this->input->post('role');
        $password = $this->input->post('password');

        if (!$username || !$name || !$role) {
            $this->session->set_flashdata('error', 'Username, nama, dan role harus diisi.');
            redirect('users');
        }

        if ($this->user_model->username_exists($username, $id ?: null)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan. Silakan pilih username lain.');
            redirect('users');
        }

        if ($id) {
            $update = [
                'username' => $username,
                'name' => $name,
                'role' => $role,
            ];

            if ($password) {
                $update['password'] = $password;
            }

            $this->user_model->update($id, $update);
            $this->session->set_flashdata('success', 'Data user berhasil diperbarui.');
        } else {
            if (!$password) {
                $this->session->set_flashdata('error', 'Password harus diisi saat membuat user baru.');
                redirect('users');
            }

            $this->user_model->insert([
                'username' => $username,
                'password' => $password,
                'name' => $name,
                'role' => $role,
            ]);
            $this->session->set_flashdata('success', 'User baru berhasil dibuat.');
        }

        redirect('users');
    }

    public function delete($id = null) {
        if (!$id || !is_numeric($id)) {
            redirect('users');
        }

        if ((int) $id === 1) {
            $this->session->set_flashdata('error', 'User admin utama tidak dapat dihapus.');
            redirect('users');
        }

        $this->user_model->delete($id);
        $this->session->set_flashdata('success', 'User berhasil dihapus.');
        redirect('users');
    }
}
