<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Player extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('signage_model');
    }

    public function view($id)
    {
        $row = $this->db->get_where('signages', ['id' => $id])->row_array();
        if (!$row) show_404();

        // Decode JSON content
        $decoded = json_decode($row['content'], true);

        // Jika bukan JSON valid, fallback agar tetap tampil
        if (!$decoded) {
            $decoded = [
                'image' => $row['content'],
                'text'  => $row['name']
            ];
        }

        $data['signage'] = [
            'id'      => $row['id'],
            'name'    => $row['name'],
            'layout'  => $row['layout'] ?? 'single',
            'content' => $decoded
        ];

        $this->load->view('signage/player_view', $data);
    }
}
