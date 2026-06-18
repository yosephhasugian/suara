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
        // Reset stuck 'playing' items to 'pending' if they have been stuck for more than 60 seconds
        $stuck_time = date('Y-m-d H:i:s', time() - 60);
        $this->db->where('status', 'playing')
                 ->where('updated_at <', $stuck_time)
                 ->update('audio_queue', ['status' => 'pending']);

        // Mulai transaksi database untuk mendukung row locking (FOR UPDATE)
        $this->db->trans_start();

        // 💣 SELECT dengan FOR UPDATE untuk mengunci baris data secara eksklusif
        $row = $this->db->query("
            SELECT * FROM audio_queue 
            WHERE status = 'pending' 
            ORDER BY priority ASC, id ASC 
            LIMIT 1
            FOR UPDATE
        ")->row();

        if ($row) {
            // Update status menjadi playing di dalam transaksi yang sama
            $this->db->where('id', $row->id);
            $this->db->update('audio_queue', ['status' => 'playing']);
        }

        // Selesaikan transaksi database (Commit / Rollback)
        $this->db->trans_complete();

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
        $penumpang = $this->input->post('penumpang', TRUE);
        $po = $this->input->post('po', TRUE);
        $jurusan = $this->input->post('jurusan', TRUE);
        $pintu = $this->input->post('pintu', TRUE);
        $repeat = intval($this->input->post('repeat', TRUE));
        if ($repeat < 1) {
            $repeat = 1;
        }
        $delay = $this->input->post('delay', TRUE);
        if (!$delay || !is_numeric($delay)) {
            $delay = '1.5';
        }

        $text_id = sprintf(
            "Mohon perhatian. Panggilan ditujukan kepada penumpang atas nama %s. Untuk penumpang bus %s tujuan %s, ditunggu kehadiran Anda di pintu %s, dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.",
            $penumpang, $po, $jurusan, $pintu
        );

        // Ulangi teks sebanyak $repeat kali
        $text_array = [];
        for ($i = 0; $i < $repeat; $i++) {
            $text_array[] = $text_id;
        }
        $text = implode(" | ", $text_array);

        $id = $this->Audio_model->create_queue_item('announcer', $text, 2);
        
        // Simpan waktu jeda (delay) ke kolom title
        $this->db->where('id', $id)->update('audio_queue', ['title' => $delay]);

        $this->output->set_content_type('application/json')->set_output(json_encode(['status'=>'ok','id'=>$id]));
    }

    // ✅ POST: Bus masuk terminal
    public function add_bus() {

    $nopol = $this->input->post('nopol', TRUE);
    $po    = $this->input->post('po', TRUE);
    $target_area = $this->input->post('target_area', TRUE);
    $tujuan = $this->input->post('tujuan', TRUE);

    if(!$nopol || !$po){
        echo json_encode([
            'status' => 'error',
            'message' => 'Nopol / PO kosong'
        ]);
        return;
    }

    $nopol = $this->normalize_plat($nopol);

    $area_text_id = "area terminal";
    $area_text_en = "the terminal area";

    if ($target_area === 'kedatangan') {
        $area_text_id = "area kedatangan";
        $area_text_en = "the arrival area";
    } elseif ($target_area === 'pengendapan') {
        $area_text_id = "area pengendapan";
        $area_text_en = "the laying-over area";
    } elseif ($target_area === 'keberangkatan') {
        $area_text_id = "area keberangkatan";
        $area_text_en = "the departure area";
    }

    $nopol_spelled = implode(' ', str_split(str_replace(' ', '', $nopol)));
    $text_id = "Perhatian. Bus $po dengan nomor polisi $nopol_spelled telah memasuki $area_text_id. Terima kasih.";
    $text_en = "Attention. Bus $po with license plate number $nopol_spelled has entered $area_text_en. Thank you.";
    $text = $text_id . " | " . $text_en;

    $data = [
        'type' => 'bus',
        'text' => $text,
        'plat_nomor' => $nopol,
        'nama_po' => $po,
        'tujuan' => $tujuan,
        'area' => 'masuk', // 🔥 WAJIB ADA
        'status' => ($target_area === 'pengendapan' ? 'done' : 'pending'),
        'priority' => 3,
        'created_at' => date('Y-m-d H:i:s'),
        'area_updated_at'=> date('Y-m-d H:i:s')
    ];

    $this->db->trans_start();

    $this->db->insert('audio_queue', $data);
    $bus_id = $this->db->insert_id();

    // 🔥 INSERT KE HISTORY (INI YANG KURANG DARI TADI)
    $this->db->insert('bus_history', [
        'bus_id' => $bus_id,
        'area' => 'masuk',
        'waktu_masuk' => date('Y-m-d H:i:s')
    ]);

    // Jika target_area diisi dan valid, lakukan update area secara langsung
    if ($target_area && in_array($target_area, ['kedatangan', 'pengendapan', 'keberangkatan'])) {
        $this->db->where('id', $bus_id)->update('audio_queue', [
            'area' => $target_area,
            'updated_at' => date('Y-m-d H:i:s'),
            'area_updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database transaction failed'
        ]);
    } else {
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

    // ✅ GET: Hapus musik dari playlist
    public function delete_music($id) {
        $this->db->where('id', $id)->delete('youtube_playlist');
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'ok']));
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

    // ✅ GET: Clear all pending queue items
    public function clear_queue() {
        $this->db->where('status', 'pending')->delete('audio_queue');
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

    // Helper: Extract YouTube ID (Universal Support for watch, mobile, shorts, share links, embed)
    private function _extract_youtube_id($url) {
        $url = trim($url);
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches)) {
            return $matches[1];
        }
        return null;
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
        'interval_minutes' => $this->input->post('interval_minutes'),
        'start_date' => $this->input->post('start_date'),
        'end_date' => $this->input->post('end_date'),
        'start_time' => $this->input->post('start_time'),
        'end_time' => $this->input->post('end_time'),
        'repeat_days' => $this->input->post('repeat_days'),
        'is_active' => 1,
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

private function normalize_plat($plat) {
    $plat = strtoupper(trim($plat));
    $plat_clean = str_replace(' ', '', $plat);
    if (preg_match('/^([A-Z]{1,2})([0-9]{1,4})([A-Z]{1,3})$/', $plat_clean, $matches)) {
        return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
    }
    return preg_replace('/\s+/', ' ', $plat);
}
}
