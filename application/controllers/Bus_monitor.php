<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bus_monitor extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('Bus_model');
        $this->load->helper('url');
    }

    // ================= HALAMAN ADMIN =================
    public function index(){
        $data['title'] = 'Admin Keberangkatan';
        $this->load->view('bus_monitor/keberangkatan', $data);
    }

    public function keberangkatan() {
        $data['title'] = 'Admin Keberangkatan';
        $this->load->view('bus_monitor/keberangkatan', $data);
    }

    public function kedatangan() {
        $data['title'] = 'Admin Kedatangan';
        $this->load->view('bus_monitor/kedatangan', $data);
    }

    public function pengendapan() {
        $data['title'] = 'Admin Pengendapan';
        $this->load->view('bus_monitor/pengendapan', $data);
    }

    public function pintu_keluar() {
        $data['title'] = 'Pintu Keluar';
        $this->load->view('bus_monitor/pintu_keluar', $data);
    }

    // ================= HALAMAN TV DISPLAY (UMUM) =================
    public function tv_display(){
        $data['title'] = 'Display TV Bus - All Areas';
        $this->load->view('bus_monitor/tv', $data);
    }

    // ================= HALAMAN TV PER AREA (NEW) =================
     // 🔵 TV: Area Kedatangan - Display View
    public function tv_kedatangan(){
        $data['title'] = 'Display TV - Area Kedatangan';
        $data['area_label'] = 'AREA KEDATANGAN';
        $data['area_code'] = 'kedatangan';
        $data['badge_class'] = 'area-kedatangan';
        $data['header_color'] = '#17a2b8';  // Cyan color
        $data['icon'] = '🛬';
        $this->load->view('bus_monitor/tv_kedatangan', $data);
    }
    
    // 🔵 API: Get Data TV Kedatangan
    public function get_tv_kedatangan(){
        $data = $this->Bus_model->get_bus_by_area('kedatangan', 15);
        echo json_encode($data);
    }
    // 🟢 TV: BUS MASUK TERMINAL
    public function tv_masuk(){
        $data['title'] = 'Display TV - Bus Masuk';
        $data['area_label'] = 'MASUK TERMINAL';
        $data['area_code'] = 'masuk';
        $data['badge_class'] = 'area-masuk';
        $this->load->view('bus_monitor/tv_masuk', $data);
    }

    // 🔵 TV: AREA KEBERANGKATAN
    public function tv_keberangkatan(){
        $data['title'] = 'Display TV - Area Keberangkatan';
        $data['area_label'] = 'AREA KEBERANGKATAN';
        $data['area_code'] = 'keberangkatan';
        $data['badge_class'] = 'area-keberangkatan';
        $this->load->view('bus_monitor/tv_keberangkatan', $data);
    }

    // ⚪ TV: AREA PENGENDAPAN
    public function tv_pengendapan(){
        $data['title'] = 'Display TV - Area Pengendapan';
        $data['area_label'] = 'AREA PENGENDAPAN';
        $data['area_code'] = 'pengendapan';
        $data['badge_class'] = 'area-pengendapan';
        $this->load->view('bus_monitor/tv_pengendapan', $data);
    }

    // 🔴 TV: PINTU KELUAR / BERANGKAT
    public function tv_keluar(){
        $data['title'] = 'Display TV - Pintu Keluar';
        $data['area_label'] = 'SUDAH BERANGKAT';
        $data['area_code'] = 'berangkat';
        $data['badge_class'] = 'area-berangkat';
        $this->load->view('bus_monitor/tv_keluar', $data);
    }

    // ================= API: GET DATA TV (ALL AREAS) =================
    public function get_tv_data(){
        $data = $this->db
            ->select('id, plat_nomor, nama_po, tujuan, created_at, area')
            ->from('audio_queue')
            ->where('type', 'bus')
            ->where('plat_nomor IS NOT NULL')
            ->where('plat_nomor !=', '')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->order_by('created_at', 'DESC')
            ->limit(10)
            ->get()
            ->result();
        echo json_encode($data);
    }

    // ================= API: GET DATA PER AREA (NEW) =================
    
    // 🟢 API: Bus Masuk
    public function get_tv_masuk(){
        // Query: Ambil SEMUA bus yang created_at hari ini
        // TIDAK filter by area → jadi bus yang sudah pindah tetap tampil
        $data = $this->db
            ->select('id, plat_nomor, nama_po, tujuan, created_at, area')
            ->from('audio_queue')
            ->where('type', 'bus')
            ->where('plat_nomor IS NOT NULL')
            ->where('plat_nomor !=', '')
            ->where('DATE(created_at)', date('Y-m-d'))  // Hari ini saja
            ->order_by('created_at', 'DESC')  // Terbaru di atas
            ->limit(50)  // Cukup 50, karena max ~400/hari
            ->get()
            ->result();
        
        echo json_encode($data);
    }

    // 🔵 API: Area Keberangkatan (SUDAH BENAR - Filter by area)
    public function get_tv_keberangkatan(){
        $data = $this->Bus_model->get_bus_by_area('keberangkatan', 50); // limit 50
        echo json_encode($data);
    }

    // ⚪ API: Area Pengendapan
    public function get_tv_pengendapan(){
        $data = $this->Bus_model->get_bus_by_area('pengendapan', 15);
        echo json_encode($data);
    }

    // 🔴 API: Pintu Keluar / Berangkat
    public function get_tv_keluar(){
        $data = $this->Bus_model->get_bus_by_area('berangkat', 50);
        echo json_encode($data);
    }

    // ================= GET BUS TODAY (ALL) =================
    public function get_bus_today() {
        $data = $this->db
            ->select('*')
            ->from('audio_queue')
            ->where('type', 'bus')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->order_by('created_at', 'DESC')
            ->get()
            ->result();
        echo json_encode($data);
    }

    // ================= UPDATE BUS + HISTORY =================
     public function update_bus() {
        $id     = $this->input->post('id');
        $tujuan = $this->input->post('tujuan');
        $area   = $this->input->post('area');

        if(!$id){
            echo json_encode(['status' => false, 'message' => 'ID tidak ditemukan']);
            return;
        }

        // ✅ Update area + timestamp khusus area change
        $this->db->where('id', $id)->update('audio_queue', [
            'area' => $area,
            'tujuan' => $tujuan,
            'updated_at' => date('Y-m-d H:i:s'),
            'area_updated_at' => date('Y-m-d H:i:s')  // ✨ FIELD BARU
        ]);

        echo json_encode(['status' => true]);
    }

    // ================= DETAIL HISTORY BUS =================
    public function get_history($bus_id){
        $data = $this->db
            ->where('bus_id', $bus_id)
            ->order_by('waktu_masuk', 'ASC')
            ->get('bus_history')
            ->result();
        echo json_encode($data);
    }

    public function get_bus_for_form($current_area = '') {
        $this->db->select('id, plat_nomor, nama_po, tujuan, created_at, area');
        $this->db->from('audio_queue');
        $this->db->where('type', 'bus');
        $this->db->where('plat_nomor IS NOT NULL');
        $this->db->where('plat_nomor !=', '');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        
        // ✨ FILTER: Jangan tampilkan bus yang sudah di area tertentu
        if ($current_area) {
            $this->db->where('area !=', $current_area);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $data = $this->db->get()->result();
        
        echo json_encode($data);
    }

        
}