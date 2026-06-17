<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log an activity
     * 
     * @param string $action The action performed (e.g. 'login', 'update_bus', etc.)
     * @param string|array $details Additional details for the activity
     * @return int The insert ID of the log entry
     */
    public function log($action, $details = null) {
        $user_id = $this->session->userdata('user_id');
        $username = $this->session->userdata('username') ?: 'SYSTEM';
        
        if (is_array($details) || is_object($details)) {
            $details = json_encode($details);
        }

        $data = [
            'user_id' => $user_id,
            'username' => $username,
            'action' => $action,
            'details' => $details,
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('activity_logs', $data);
        return $this->db->insert_id();
    }

    /**
     * Get recent logs
     * 
     * @param int $limit Max logs to fetch
     * @return array
     */
    public function get_recent($limit = 100) {
        return $this->db->order_by('created_at', 'DESC')
                        ->limit($limit)
                        ->get('activity_logs')
                        ->result_array();
    }
}
