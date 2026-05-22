<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signage extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('signage_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('upload');

        // Proteksi login
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    public function index() {
        $data['signages'] = $this->signage_model->get_all();
        $data['title'] = 'Daftar Perangkat';
        $this->load->view('signage/list', $data);
    }

    public function create() {
        $data['title'] = 'Tambah Perangkat';
        $this->load->view('signage/create', $data);
    }

    public function store() {
        $content = [];

        // Upload gambar
        if (!empty($_FILES['image']['name'])) {
            $config = [
                'upload_path'   => './assets/uploads/images/',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size'      => 5000,
                'encrypt_name'  => TRUE,
            ];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('image')) {
                $content['image'] = 'assets/uploads/images/' . $this->upload->data('file_name');
            }
        }

        // Upload video
        if (!empty($_FILES['video']['name'])) {
            $config = [
                'upload_path'   => './assets/uploads/videos/',
                'allowed_types' => 'mp4|mov|avi|mkv',
                'max_size'      => 50000,
                'encrypt_name'  => TRUE,
            ];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('video')) {
                $content['video'] = 'assets/uploads/videos/' . $this->upload->data('file_name');
            }
        }

        if ($this->input->post('text')) {
            $content['text'] = $this->input->post('text');
        }

        $data = [
            'name'        => $this->input->post('name'),
            'location'    => $this->input->post('location'),
            'layout'      => $this->input->post('layout'),
            'content'     => json_encode($content),
            'status'      => $this->input->post('status'),
            'created_by'  => $this->session->userdata('user_id'),
        ];

        if ($this->signage_model->insert($data)) {
            redirect('signage');
        } else {
            show_error('Gagal menyimpan data.');
        }
    }

    public function edit($id) {
        $data['signage'] = $this->signage_model->get_by_id($id);
        $data['signage']['content'] = json_decode($data['signage']['content'], true);
        $data['title'] = 'Edit Perangkat';
        $this->load->view('signage/edit', $data);
    }

    public function update($id) {
        $signage = $this->signage_model->get_by_id($id);
        $content = json_decode($signage['content'], true);

        // === Upload gambar baru (jika ada) ===
        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './assets/uploads/images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 5000;
            $config['encrypt_name'] = TRUE;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('image')) {
                $content['image'] = 'assets/uploads/images/' . $this->upload->data('file_name');
            }
        }

        // === Upload video baru (jika ada) ===
        if (!empty($_FILES['video']['name'])) {
            $config['upload_path'] = './assets/uploads/videos/';
            $config['allowed_types'] = 'mp4|mov|avi|mkv';
            $config['max_size'] = 50000;
            $config['encrypt_name'] = TRUE;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('video')) {
                $content['video'] = 'assets/uploads/videos/' . $this->upload->data('file_name');
            }
        }

        if ($this->input->post('text')) {
            $content['text'] = $this->input->post('text');
        }

        $data = [
            'name' => $this->input->post('name'),
            'layout' => $this->input->post('layout'),
            'content' => json_encode($content),
            'status' => $this->input->post('status'),
        ];

        if ($this->signage_model->update($id, $data)) {
            redirect('signage');
        } else {
            show_error('Gagal memperbarui data.');
        }
    }


    public function delete($id) {
        $this->signage_model->delete($id);
        redirect('signage');
    }
}
