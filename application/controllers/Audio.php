<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Audio extends CI_Controller {
    
    public function __construct() {
    parent::__construct();
    $this->load->model('Audio_model');
    $this->load->library('form_validation'); // Tambahkan ini
    $this->load->helper('url'); // Tambahkan ini jika pakai redirect/base_url
    
    if($this->input->is_ajax_request()) {
        $this->output->set_header('X-CSRF-TOKEN: '.$this->security->get_csrf_hash());
    }
}

public function index() {
    $data['title'] = 'Manajemen Audio';
    $queue = $this->Audio_model->get_pending_queue(20);
    // Ganti 'audio_view' dengan nama file .php yang ada di folder views kamu
    $this->load->view('audio', $data); 
}

  

    // ✅ GET: Next audio to play
    public function get_next_audio() {

    $row = $this->db->query("
        SELECT * FROM audio_queue 
        WHERE status = 'pending' 
        ORDER BY priority ASC, id ASC 
        LIMIT 1
    ")->row();

    if ($row) {
        // 💣 LOCK manual (anti double ambil)
        $this->db->where('id', $row->id);
        $this->db->where('status', 'pending');
        $updated = $this->db->update('audio_queue', ['status' => 'playing']);

        // 🔥 kalau gagal update = sudah diambil proses lain
        if (!$this->db->affected_rows()) {
            echo json_encode(null);
            return;
        }
    }

    echo json_encode($row);
}

    public function get_all_queue() {
    $queue = $this->Audio_model->get_all_queue();

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($queue));
}

    // ✅ POST: Tambah announcer manual
    public function add_announcer() {
        $text = sprintf(
            "Mohon perhatian. Panggilan ditujukan kepada penumpang atas nama %s. Untuk penumpang bus %s tujuan %s, ditunggu kehadiran Anda di pintu %s, dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.",
            $this->input->post('penumpang', TRUE),
            $this->input->post('po', TRUE),
            $this->input->post('jurusan', TRUE),
            $this->input->post('pintu', TRUE)
        );
        $id = $this->Audio_model->create_queue_item('announcer', $text, 2);
        $this->output->set_content_type('application/json')->set_output(json_encode(['status'=>'ok','id'=>$id]));
    }

    // ✅ POST: Bus masuk terminal
    public function add_bus() {

    $nopol = $this->input->post('nopol', TRUE);
    $po    = $this->input->post('po', TRUE);

    if(!$nopol || !$po){
        echo json_encode([
            'status' => 'error',
            'message' => 'Nopol / PO kosong'
        ]);
        return;
    }

    $text = "Perhatian. Bus $po dengan nomor polisi $nopol telah memasuki area terminal. Terima kasih.";

    $data = [
        'type' => 'bus',
        'text' => $text,
        'plat_nomor' => $nopol,
        'nama_po' => $po,
        'area' => 'masuk', // 🔥 WAJIB ADA
        'status' => 'pending',
        'priority' => 3,
        'created_at' => date('Y-m-d H:i:s')
    ];

    $insert = $this->db->insert('audio_queue', $data);

    if(!$insert){
        echo json_encode([
            'status' => 'error',
            'db_error' => $this->db->error()
        ]);
    } else {

        // 🔥 AMBIL ID BUS
        $bus_id = $this->db->insert_id();

        // 🔥 INSERT KE HISTORY (INI YANG KURANG DARI TADI)
        $this->db->insert('bus_history', [
            'bus_id' => $bus_id,
            'area' => 'masuk',
            'waktu_masuk' => date('Y-m-d H:i:s')
        ]);

        echo json_encode([
            'status'=>'ok'
        ]);
    }
}

    // ✅ POST: Pengumuman sholat
    public function add_prayer_announce()
{
    $text = $this->input->post('text');

    if(!$text){
        echo json_encode(['status'=>'error','message'=>'text kosong']);
        return;
    }

    $this->db->insert('audio_queue', [
        'text' => $text,
        'type' => 'prayer',
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s')
    ]);

    echo json_encode(['status'=>'ok']);
}

    // ✅ POST: Pengumuman cepat / ads manual
    public function add_ads() {
    $text = $this->input->post('text', TRUE);

    if(!$text) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Text kosong'
        ]);
        return;
    }

    $id = $this->Audio_model->create_queue_item('ads', $text, 4);

    echo json_encode([
        'status' => 'ok',
        'id' => $id
    ]);
}

    // ✅ POST: Tambah YouTube ke playlist
    public function add_youtube_music() {
        $url = $this->input->post('youtube_url', TRUE);
        $video_id = $this->_extract_youtube_id($url);
        if(!$video_id) {
            $this->output->set_status_header(400)->set_output(json_encode(['status'=>'error','message'=>'Link YouTube tidak valid']));
            return;
        }
        $data = [
            'title' => $this->input->post('title', TRUE) ?: 'Untitled',
            'youtube_url' => $url,
            'video_id' => $video_id
        ];
        $id = $this->Audio_model->insert_playlist($data);
        $this->output->set_content_type('application/json')->set_output(json_encode(['status'=>'ok','id'=>$id]));
    }

    // ✅ GET: List playlist YouTube
    public function get_music_list() {
        $list = $this->Audio_model->get_active_playlist();
        $this->output->set_content_type('application/json')->set_output(json_encode($list));
    }

    // ✅ GET: Mark as done
    public function done_audio($id) {
        $this->Audio_model->update_status($id, 'done');
        $this->output->set_content_type('application/json')->set_output(json_encode(['status'=>'done']));
    }

    // ✅ GET: Replay item
    public function replay_queue_item($id) {
        $this->Audio_model->replay_item($id);
        $this->output->set_content_type('application/json')->set_output(json_encode(['status'=>'ok']));
    }

    // ✅ POST: Tambah jadwal iklan (fitur premium)
    public function add_ads_schedule() {
        $this->form_validation->set_rules('ad_text', 'Pesan Iklan', 'required');
        $this->form_validation->set_rules('start_time', 'Jam Mulai', 'required|regex_match[/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/]');
        $this->form_validation->set_rules('start_date', 'Tanggal Mulai', 'required|valid_date');
        
        if($this->form_validation->run() === FALSE) {
            $this->output->set_status_header(400)->set_output(json_encode(['status'=>'error','errors'=>validation_errors()]));
            return;
        }
        
        $data = [
            'ad_title' => $this->input->post('ad_title', TRUE),
            'ad_text' => $this->input->post('ad_text', TRUE),
            'start_time' => $this->input->post('start_time', TRUE),
            'end_time' => $this->input->post('end_time', TRUE),
            'start_date' => $this->input->post('start_date', TRUE),
            'end_date' => $this->input->post('end_date', TRUE),
            'repeat_days' => implode(',', $this->input->post('repeat_days', [])),
            'duration_seconds' => $this->input->post('duration', TRUE) ?: 30,
            'is_active' => 1
        ];
        $id = $this->Audio_model->insert_ads_schedule($data);
        $this->output->set_content_type('application/json')->set_output(json_encode(['status'=>'ok','id'=>$id]));
    }

    // ✅ CRON: Check scheduled ads (jalankan tiap menit via cron)
    public function cron_check_scheduled_ads() {
        // Hanya jalankan jika diakses via CLI atau token rahasia
        if($this->input->get('cron_key') !== $this->config->item('cron_secret_key')) {
            show_404();
        }
        $this->Audio_model->process_scheduled_ads();
        echo "OK";
    }

    // Helper: Extract YouTube ID
    private function _extract_youtube_id($url) {
        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([^& \n]+)/', $url, $matches);
        return $matches[1] ?? null;
    }

   public function get_ads_schedule() {
        $data = $this->db->get('ads_schedule')->result();
        echo json_encode($data);
    }


public function save_ads_schedule()
{
    $data = [
        'ad_title' => $this->input->post('ad_title'),
        'ad_text' => $this->input->post('ad_text'),
        'duration' => $this->input->post('duration'),
        'interval_minutes' => $this->input->post('interval_minutes'),
        'start_date' => $this->input->post('start_date'),
        'end_date' => $this->input->post('end_date'),
        'start_time' => $this->input->post('start_time'),
        'end_time' => $this->input->post('end_time'),
        'repeat_days' => $this->input->post('repeat_days'),
        'created_at' => date('Y-m-d H:i:s')
    ];

    $this->db->insert('ads_schedule', $data);

    echo json_encode(['status' => 'ok']);
}

public function update_last_played() {
    $id = $this->input->post('id');

    $this->db->where('id', $id);
    $this->db->update('ads_schedule', [
        'last_played' => date('Y-m-d H:i:s')
    ]);

    echo json_encode(['status' => 'ok']);
}
}