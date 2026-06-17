<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        
        // Ensure user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
        
        $this->load->database();
        $this->load->model('Activity_model');
    }

    public function index() {
        $data['title'] = 'Log Aktivitas Sistem - TTPG';

        // Fix for PHP 8.1 ctype_digit deprecation warning in CodeIgniter Pagination library
        if (!isset($_GET['page']) || $_GET['page'] === NULL || $_GET['page'] === '') {
            $_GET['page'] = '0';
        }

        // Get filters from GET request
        $username = trim($this->input->get('username', TRUE) ?? '');
        $action = trim($this->input->get('action', TRUE) ?? '');
        $date = trim($this->input->get('date', TRUE) ?? '');
        
        // Base Query
        $this->db->from('activity_logs');
        
        // Apply Filters
        if ($username !== '') {
            $this->db->like('username', $username);
        }
        if ($action !== '') {
            $this->db->where('action', $action);
        }
        if ($date !== '') {
            $this->db->where('DATE(created_at)', $date);
        }
        
        // Clone for pagination count before adding order/limit
        $count_query = clone $this->db;
        $total_rows = $count_query->count_all_results();
        
        // Pagination Settings
        $this->load->library('pagination');
        $config['base_url'] = site_url('log/index');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 20;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        
        // AdminLTE styling for pagination links
        $config['full_tag_open'] = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $this->pagination->initialize($config);
        
        $page = $this->input->get('page') ? intval($this->input->get('page')) : 0;
        
        // Fetch Filtered Data
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($config['per_page'], $page);
        $data['logs'] = $this->db->get()->result_array();
        $data['pagination'] = $this->pagination->create_links();
        $data['total_logs'] = $total_rows;
        
        // Get Distinct Action Types for Dropdown Filter
        $data['actions'] = $this->db->select('DISTINCT(action)')->from('activity_logs')->get()->result_array();
        
        // Top Dashboard Stats
        $data['stats_total'] = $this->db->count_all('activity_logs');
        $data['stats_today'] = $this->db->where('created_at >=', date('Y-m-d 00:00:00'))
                                         ->where('created_at <=', date('Y-m-d 23:59:59'))
                                         ->count_all_results('activity_logs');
        $data['stats_logins'] = $this->db->where('action', 'login')->count_all_results('activity_logs');
        $data['stats_cctv'] = $this->db->where('action', 'alpr_webhook_detection')->count_all_results('activity_logs');
        
        // Selected filters for maintaining UI form values
        $data['filter_username'] = $username;
        $data['filter_action'] = $action;
        $data['filter_date'] = $date;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('log/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Purge all activity logs
     */
    public function clear_all() {
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Hanya administrator utama yang diizinkan mengosongkan log audit.');
            redirect('log');
        }

        $this->db->truncate('activity_logs');
        
        // Log this purge action as first entry
        $this->Activity_model->log('purge_logs', 'All previous activity logs purged by admin.');
        
        $this->session->set_flashdata('success', 'Log audit berhasil dibersihkan sepenuhnya.');
        redirect('log');
    }
}
