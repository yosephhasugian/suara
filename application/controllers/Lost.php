<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lost extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Lost_model');
        $this->load->helper('url'); // Tambahkan ini jika pakai redirect/base_url
    
    if($this->input->is_ajax_request()) {
        $this->output->set_header('X-CSRF-TOKEN: '.$this->security->get_csrf_hash());
    }
    }

    public function index(){
        $data['title'] = 'Manajemen Lost & Found';
        $data['data'] = $this->Lost_model->get_all();
        $this->load->view('lost/index', $data);
    }

    public function tambah(){
        if($_POST){
            $config['upload_path'] = FCPATH . 'assets/uploads/images/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $this->load->library('upload',$config);

            $foto = '';
            if($this->upload->do_upload('foto')){
                $foto = $this->upload->data('file_name');
            }

            $data = [
                'nama_barang' => $this->input->post('nama_barang'),
                'kategori' => $this->input->post('kategori'),
                'deskripsi' => $this->input->post('deskripsi'),
                'lokasi_ditemukan' => $this->input->post('lokasi'),
                'tanggal_ditemukan' => $this->input->post('tanggal'),
                'nama_penemu' => $this->input->post('penemu'),
                'kontak_penemu' => $this->input->post('kontak'),
                'bukti_foto' => $foto
            ];

            $this->Lost_model->insert($data);
            redirect('lost');
        }

        $this->load->view('lost/tambah');
    }

    public function ambil($id){

    if($_POST){

        // upload config
        $config['upload_path'] = FCPATH . 'assets/uploads/images/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $this->load->library('upload', $config);

        // FOTO PENGAMBILAN
        $foto_pengambilan = '';
        if($this->upload->do_upload('foto_pengambilan')){
            $foto_pengambilan = $this->upload->data('file_name');
        }

        // FOTO IDENTITAS
        $foto_identitas = '';
        if($this->upload->do_upload('foto_identitas')){
            $foto_identitas = $this->upload->data('file_name');
        }

        $data = [
            'status' => 'diambil',
            'nama_pengambil' => $this->input->post('nama'),
            'no_hp_pengambil' => $this->input->post('hp'),
            'no_identitas' => $this->input->post('identitas'),
            'alamat_pengambil' => $this->input->post('alamat'),
            'nama_petugas' => $this->input->post('petugas'),
            'tanggal_diambil' => date('Y-m-d'),
            'waktu_pengambilan' => date('Y-m-d H:i:s'),
            'foto_pengambilan' => $foto_pengambilan,
            'foto_identitas' => $foto_identitas
        ];

        $this->Lost_model->update($id, $data);
        redirect('lost');
    }

    $data['item'] = $this->Lost_model->get_by_id($id);
    $this->load->view('lost/ambil', $data);
}

    public function hapus($id){
        $this->Lost_model->delete($id);
        redirect('lost');
    }
}