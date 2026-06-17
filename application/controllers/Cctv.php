<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cctv extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Activity_model');
        $this->load->helper('url');
    }

    /**
     * Webhook API Endpoint for Automatic License Plate Recognition (ALPR)
     * URL: site_url('cctv/alpr')
     * Method: POST
     */
    public function alpr() {
        // Only accept POST requests
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Method Not Allowed. Use POST.'
            ]);
            return;
        }

        // Get payload (support both JSON and raw POST form data)
        $raw_input = file_get_contents('php://input');
        $json_data = json_decode($raw_input, true);

        $plat = '';
        $token = '';

        if ($json_data) {
            $plat = trim($json_data['plat_nomor'] ?? '');
            $token = trim($json_data['token'] ?? '');
        } else {
            $plat = trim($this->input->post('plat_nomor', TRUE) ?? '');
            $token = trim($this->input->post('token', TRUE) ?? '');
        }

        // Security Validation (Simple token-based auth for IoT device validation)
        $security_token = getenv('ALPR_SECURE_TOKEN') ?: "TTPG_ALPR_SECURE_TOKEN_2026";
        if (empty($token) || $token !== $security_token) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'Unauthorized. Invalid security token.'
            ]);
            return;
        }

        if (empty($plat)) {
            $this->output->set_status_header(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Bad Request. Plat nomor is required.'
            ]);
            return;
        }

        // Normalize plat nomor
        $plat = $this->normalize_plat($plat);
        $plat_clean = str_replace(' ', '', $plat);

        // Get PO name from db2 (Integrasi manifest)
        $db2 = $this->load->database('db2', TRUE);
        $bus = $db2->query("
            SELECT tb.nopol, tb.id_po, po.nama_po
            FROM terminal_boardingpass tb
            LEFT JOIN tbl_po po ON po.id_po = tb.id_po
            WHERE REPLACE(tb.nopol, ' ', '') = ?
            LIMIT 1
        ", array($plat_clean))->row();

        $nama_po = $bus ? $bus->nama_po : 'PO Tidak Dikenal';
        $plat_spelled = implode(' ', str_split(str_replace(' ', '', $plat)));
        $text_id = "Perhatian. Bus " . $nama_po . " dengan nomor polisi " . $plat_spelled . " telah memasuki area terminal. Terima kasih.";
        $text_en = "Attention. Bus " . $nama_po . " with license plate number " . $plat_spelled . " has entered the terminal area. Thank you.";
        $text = $text_id . " | " . $text_en;

        // Insert into audio_queue
        $queue_data = [
            'type' => 'bus',
            'text' => $text,
            'plat_nomor' => $plat,
            'nama_po' => $nama_po,
            'area' => 'masuk',
            'status' => 'pending',
            'priority' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'area_updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('audio_queue', $queue_data);
        $bus_id = $this->db->insert_id();

        // Insert into bus_history
        $this->db->insert('bus_history', [
            'bus_id' => $bus_id,
            'area' => 'masuk',
            'waktu_masuk' => date('Y-m-d H:i:s')
        ]);

        // Audit Trail
        $this->Activity_model->log('alpr_webhook_detection', [
            'plat_nomor' => $plat,
            'nama_po' => $nama_po,
            'bus_id' => $bus_id,
            'source' => 'CCTV_ALPR_CAMERA'
        ]);

        // Return successful response
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'status' => 'success',
                 'message' => 'Bus entry recorded automatically via ALPR CCTV',
                 'data' => [
                     'bus_id' => $bus_id,
                     'plat_nomor' => $plat,
                     'nama_po' => $nama_po,
                     'area' => 'masuk',
                     'recorded_at' => date('Y-m-d H:i:s')
                 ]
             ]));
    }

    private function normalize_plat($plat) {
        $plat = strtoupper(trim($plat));
        $plat_clean = str_replace(' ', '', $plat);
        if (preg_match('/^([A-Z]{1,2})([0-9]{1,4})([A-Z]{1,3})$/', $plat_clean, $matches)) {
            return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
        }
        return preg_replace('/\s+/', ' ', $plat);
    }
}
