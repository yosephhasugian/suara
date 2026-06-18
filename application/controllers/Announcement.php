<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends CI_Controller {
    public function __construct(){
        parent::__construct();
        // Load helpers/models if needed
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
    }

    // Display the quick announcement form
    public function quick(){
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('announcement/quick_announcement');
        $this->load->view('templates/footer');
    }

    // Handle form submission
    public function save(){
        $title   = $this->input->post('title', true);
        $message = $this->input->post('message', true);
        if(!$title || !$message){
            $this->session->set_flashdata('error', 'Judul dan pesan wajib diisi');
            redirect('quick_announcement');
            return;
        }
        $data = [
            'title'      => $title,
            'message'    => $message,
            'created_at' => date('Y-m-d H:i:s'),
            'is_active'  => 1
        ];
        $this->db->insert('announcements', $data);
        $this->session->set_flashdata('success', 'Pengumuman berhasil disimpan');
        redirect('quick_announcement');
    }
}
?>
