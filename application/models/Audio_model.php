<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Audio_model extends CI_Model {
    
    public function get_pending_queue($limit = 20) {
        return $this->db->where('status', 'pending')
                       ->order_by('priority', 'ASC')
                       ->order_by('created_at', 'ASC')
                       ->limit($limit)
                       ->get('audio_queue')
                       ->result();
    }
    
    public function get_next_pending() {
        return $this->db->where('status', 'pending')
                       ->order_by('priority', 'ASC')
                       ->order_by('created_at', 'ASC')
                       ->limit(1)
                       ->get('audio_queue')
                       ->row();
    }
    
    public function create_queue_item($type, $text, $priority = 5, $ref_id = null) {
        $data = [
            'type' => $type,
            'text' => $text,
            'priority' => $priority,
            'status' => 'pending',
            'ref_id' => $ref_id
        ];
        $this->db->insert('audio_queue', $data);
        return $this->db->insert_id();
    }
    
    public function update_status($id, $status) {
        return $this->db->where('id', $id)->update('audio_queue', ['status' => $status]);
    }
    
    public function replay_item($id) {
        return $this->db->where('id', $id)->update('audio_queue', [
            'status' => 'pending',
            'priority' => 1 // Prioritas tinggi saat di-replay
        ]);
    }
    
    public function insert_playlist($data) {
        $this->db->insert('youtube_playlist', $data);
        return $this->db->insert_id();
    }
    
    public function get_active_playlist() {
        return $this->db->where('is_active', 1)->order_by('play_count', 'ASC')->get('youtube_playlist')->result();
    }
    
    public function insert_ads_schedule($data) {
        $this->db->insert('ads_schedule', $data);
        return $this->db->insert_id();
    }
    
    // ✅ Cek iklan berjadwal yang aktif SEKARANG
   public function get_active_scheduled_ad() {
    $today = date('Y-m-d');
    $current_time = date('H:i:s');
    $day_of_week = date('w');

    return $this->db->where('is_active', 1)
        ->where('start_date <=', $today)
        ->where('end_date >=', $today)
        ->like('repeat_days', '"' . $day_of_week . '"') // 🔥 FIX DISINI
        ->where('start_time <=', $current_time)
        ->where('end_time >=', $current_time)
        ->order_by('created_at', 'ASC')
        ->limit(1)
        ->get('ads_schedule')
        ->row();
}


    
    // ✅ Proses iklan berjadwal (dipanggil via cron tiap menit)
    public function process_scheduled_ads() {
        $active = $this->get_active_scheduled_ad();
        if($active) {
            // Cek apakah sudah ada queue untuk iklan ini dalam 2 menit terakhir (hindari duplikat)
            $exists = $this->db->where('type', 'ads')
                              ->where('ref_id', $active->id)
                              ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-2 minutes')))
                              ->where('status !=', 'done')
                              ->count_all_results('audio_queue');
            
            if($exists == 0) {
                $this->create_queue_item('ads', $active->ad_text, 1, $active->id);
                log_message('info', "Scheduled ad queued: {$active->ad_title}");
            }
        }
    }
      // ✅ GET: Queue items
   public function get_all_queue() {

    // 🔥 Inject iklan
    $scheduled_ad = $this->get_active_scheduled_ad();

    if($scheduled_ad) {
        $exists = $this->db->where('type', 'ads')
            ->where('ref_id', $scheduled_ad->id)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 minutes')))
            ->where('status !=', 'done')
            ->count_all_results('audio_queue');

        if($exists == 0) {
            $this->create_queue_item(
                'ads',
                $scheduled_ad->ad_text,
                1,
                $scheduled_ad->id
            );
        }
    }

    // ✅ AMBIL DATA HARI INI SAJA + TIDAK TAMPILKAN DONE
    return $this->db
        ->where('DATE(created_at)', date('Y-m-d')) // 🔥 penting
        ->where_in('status', ['pending','playing']) // 🔥 jangan tampilkan done
        ->order_by('priority', 'ASC')
        ->order_by('created_at', 'ASC')
        ->get('audio_queue')
        ->result();
}
}