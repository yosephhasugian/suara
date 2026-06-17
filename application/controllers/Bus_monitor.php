<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bus_monitor extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('Bus_model');
        $this->load->model('Activity_model');
        $this->load->helper('url');
        $this->load->library('session');

        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    private function authorize_page(array $allowed_roles)
    {
        $role = $this->session->userdata('role');

        if ($role !== 'admin' && !in_array($role, $allowed_roles, true)) {
            show_error('Akses ditolak. Anda tidak memiliki hak untuk halaman ini.', 403, 'Forbidden');
        }
    }

    private function authorize_area_action(string $area)
    {
        $role = $this->session->userdata('role');
        $map = [
            'masuk' => 'petugas_masuk',
            'kedatangan' => 'petugas_kedatangan',
            'pengendapan' => 'petugas_pengendapan',
            'keberangkatan' => 'petugas_keberangkatan',
            'berangkat' => 'petugas_keluar',
        ];

        if ($role === 'admin') {
            return;
        }

        if (!isset($map[$area]) || $map[$area] !== $role) {
            echo json_encode([
                'status' => false,
                'message' => 'Akses ditolak. Anda tidak dapat melakukan aksi ini untuk area tersebut.'
            ]);
            exit;
        }
    }

    // ================= HALAMAN ADMIN =================
    public function index(){
        $role = $this->session->userdata('role');

        switch ($role) {
            case 'admin':
            case 'petugas_keberangkatan':
                redirect('bus_monitor/keberangkatan');
                break;
            case 'petugas_masuk':
                redirect('bus_monitor/masuk');
                break;
            case 'petugas_kedatangan':
                redirect('bus_monitor/kedatangan');
                break;
            case 'petugas_pengendapan':
                redirect('bus_monitor/pengendapan');
                break;
            case 'petugas_keluar':
                redirect('bus_monitor/pintu_keluar');
                break;
            default:
                show_error('Akses tidak dikenal. Silakan hubungi administrator.', 403, 'Forbidden');
                break;
        }
    }

    public function keberangkatan() {
        $this->authorize_page(['petugas_keberangkatan']);
        $data['title'] = 'Admin Keberangkatan';
        $this->load->view('bus_monitor/keberangkatan', $data);
    }

    public function masuk() {
        $this->authorize_page(['petugas_masuk']);
        $data['title'] = 'Bus Masuk';
        $this->load->view('bus_monitor/bus_masuk', $data);
    }

    public function kedatangan() {
        $this->authorize_page(['petugas_kedatangan']);
        $data['title'] = 'Admin Kedatangan';
        $this->load->view('bus_monitor/kedatangan', $data);
    }

    public function pengendapan() {
        $this->authorize_page(['petugas_pengendapan']);
        $data['title'] = 'Admin Pengendapan';
        $this->load->view('bus_monitor/pengendapan', $data);
    }

    public function pintu_keluar() {
        $this->authorize_page(['petugas_keluar']);
        $data['title'] = 'Pintu Keluar';
        $this->load->view('bus_monitor/pintu_keluar', $data);
    }

    // ================= HALAMAN TV DISPLAY (UMUM) =================
    public function tv_display(){
        $this->authorize_page(['admin']);
        $data['title'] = 'Display TV Bus - All Areas';
        $this->load->view('bus_monitor/tv', $data);
    }

    // ================= HALAMAN TV PER AREA (NEW) =================
     // 🔵 TV: Area Kedatangan - Display View
    public function tv_kedatangan(){
        $this->authorize_page(['admin']);
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
        $this->authorize_page(['admin']);
        $data = $this->Bus_model->get_bus_by_area('kedatangan', 15);
        echo json_encode($data);
    }
    // 🟢 TV: BUS MASUK TERMINAL
    public function tv_masuk(){
        $this->authorize_page(['admin']);
        $data['title'] = 'Display TV - Bus Masuk';
        $data['area_label'] = 'MASUK TERMINAL';
        $data['area_code'] = 'masuk';
        $data['badge_class'] = 'area-masuk';
        $this->load->view('bus_monitor/tv_masuk', $data);
    }

    // 🔵 TV: AREA KEBERANGKATAN
    public function tv_keberangkatan(){
        $this->authorize_page(['admin']);
        $data['title'] = 'Display TV - Area Keberangkatan';
        $data['area_label'] = 'AREA KEBERANGKATAN';
        $data['area_code'] = 'keberangkatan';
        $data['badge_class'] = 'area-keberangkatan';
        $this->load->view('bus_monitor/tv_keberangkatan', $data);
    }

    // ⚪ TV: AREA PENGENDAPAN
    public function tv_pengendapan(){
        $this->authorize_page(['admin']);
        $data['title'] = 'Display TV - Area Pengendapan';
        $data['area_label'] = 'AREA PENGENDAPAN';
        $data['area_code'] = 'pengendapan';
        $data['badge_class'] = 'area-pengendapan';
        $this->load->view('bus_monitor/tv_pengendapan', $data);
    }

    // 🔴 TV: PINTU KELUAR / BERANGKAT
    public function tv_keluar(){
        $this->authorize_page(['admin']);
        $data['title'] = 'Display TV - Pintu Keluar';
        $data['area_label'] = 'SUDAH BERANGKAT';
        $data['area_code'] = 'berangkat';
        $data['badge_class'] = 'area-berangkat';
        $this->load->view('bus_monitor/tv_keluar', $data);
    }

    // ================= API: GET DATA TV (ALL AREAS) =================
    public function get_tv_data(){
        $this->authorize_page(['admin']);
        $data = $this->db->query("
            SELECT 
                aq.id, 
                aq.plat_nomor, 
                aq.nama_po, 
                aq.tujuan, 
                aq.created_at, 
                aq.area AS current_area,
                
                -- MASUK
                MAX(CASE WHEN bh.area = 'masuk' THEN bh.waktu_masuk END) AS masuk_masuk,
                MAX(CASE WHEN bh.area = 'masuk' THEN bh.durasi_detik END) AS masuk_durasi,
                
                -- KEDATANGAN
                MAX(CASE WHEN bh.area = 'kedatangan' THEN bh.waktu_masuk END) AS kedatangan_masuk,
                MAX(CASE WHEN bh.area = 'kedatangan' THEN bh.durasi_detik END) AS kedatangan_durasi,
                
                -- PENGENDAPAN
                MAX(CASE WHEN bh.area = 'pengendapan' THEN bh.waktu_masuk END) AS pengendapan_masuk,
                MAX(CASE WHEN bh.area = 'pengendapan' THEN bh.durasi_detik END) AS pengendapan_durasi,
                
                -- KEBERANGKATAN
                MAX(CASE WHEN bh.area = 'keberangkatan' THEN bh.waktu_masuk END) AS keberangkatan_masuk,
                MAX(CASE WHEN bh.area = 'keberangkatan' THEN bh.durasi_detik END) AS keberangkatan_durasi,
                
                -- BERANGKAT
                MAX(CASE WHEN aq.area = 'berangkat' THEN aq.area_updated_at END) AS berangkat_masuk,
                MAX(CASE WHEN aq.area = 'berangkat' THEN TIMESTAMPDIFF(SECOND, aq.created_at, aq.area_updated_at) END) AS berangkat_durasi

            FROM audio_queue aq
            LEFT JOIN bus_history bh ON bh.bus_id = aq.id
            WHERE aq.type = 'bus'
              AND aq.plat_nomor IS NOT NULL
              AND aq.plat_nomor != ''
              AND aq.created_at >= ?
              AND aq.created_at <= ?
              -- Saran 1: Sembunyikan bus yang sudah keluar jika waktu keluarnya sudah lebih dari 15 menit
              AND (
                  aq.area != 'berangkat'
                  OR aq.id NOT IN (
                      SELECT bus_id 
                      FROM bus_history 
                      WHERE area = 'berangkat' 
                        AND waktu_masuk < DATE_SUB(NOW(), INTERVAL 15 MINUTE)
                  )
              )
            GROUP BY aq.id
            ORDER BY aq.created_at DESC
            LIMIT 50
        ", [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->result();

        echo json_encode($data);
    }

    // ================= API: GET DATA PER AREA (NEW) =================
    
    // 🟢 API: Bus Masuk
    public function get_tv_masuk(){
        $this->authorize_page(['admin']);
        // Query: Ambil SEMUA bus yang created_at hari ini
        // TIDAK filter by area → jadi bus yang sudah pindah tetap tampil
        $data = $this->db
            ->select('id, plat_nomor, nama_po, tujuan, created_at, area')
            ->from('audio_queue')
            ->where('type', 'bus')
            ->where('plat_nomor IS NOT NULL')
            ->where('plat_nomor !=', '')
            ->where('created_at >=', date('Y-m-d 00:00:00'))
            ->where('created_at <=', date('Y-m-d 23:59:59'))  // Hari ini saja
            ->order_by('created_at', 'DESC')  // Terbaru di atas
            ->limit(50)  // Cukup 50, karena max ~400/hari
            ->get()
            ->result();
        
        echo json_encode($data);
    }

    // 🔵 API: Area Keberangkatan (SUDAH BENAR - Filter by area)
    public function get_tv_keberangkatan(){
        $this->authorize_page(['admin']);
        $data = $this->Bus_model->get_bus_by_area('keberangkatan', 50); // limit 50
        echo json_encode($data);
    }

    // ⚪ API: Area Pengendapan
    public function get_tv_pengendapan(){
        $this->authorize_page(['admin']);
        $data = $this->Bus_model->get_bus_by_area('pengendapan', 15);
        echo json_encode($data);
    }

    // 🔴 API: Pintu Keluar / Berangkat
    public function get_tv_keluar(){
        $this->authorize_page(['admin']);
        $today_start = date('Y-m-d 00:00:00');
        $today_end = date('Y-m-d 23:59:59');
        
        $data = $this->db->query("
            SELECT aq.id, aq.plat_nomor, aq.nama_po, aq.tujuan, aq.created_at, aq.area, aq.area_updated_at
            FROM audio_queue aq
            WHERE aq.area = 'berangkat'
              AND aq.type = 'bus'
              AND aq.plat_nomor IS NOT NULL
              AND aq.plat_nomor != ''
              AND aq.created_at >= ?
              AND aq.created_at <= ?
              -- Hanya boleh muncul jika pernah melewati kedatangan, pengendapan, atau keberangkatan
              AND aq.id IN (
                  SELECT DISTINCT bus_id 
                  FROM bus_history 
                  WHERE area IN ('kedatangan', 'pengendapan', 'keberangkatan')
              )
            ORDER BY aq.area_updated_at DESC
            LIMIT 50
        ", array($today_start, $today_end))->result();

        echo json_encode($data);
    }

    // ================= GET BUS TODAY (ALL) =================
    public function get_bus_today() {
        $this->authorize_page(['admin', 'petugas_keluar']);
        $data = $this->db
            ->select('*')
            ->from('audio_queue')
            ->where('type', 'bus')
            ->where('created_at >=', date('Y-m-d 00:00:00'))
            ->where('created_at <=', date('Y-m-d 23:59:59'))
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

        if (!$area) {
            echo json_encode(['status' => false, 'message' => 'Area tidak valid']);
            return;
        }

        $this->authorize_area_action($area);

        // 🔥 PENCEGAHAN BYPASS: Jika status dirubah ke berangkat (keluar)
        if ($area === 'berangkat') {
            $has_valid_transition = $this->db->query("
                SELECT COUNT(*) as cnt 
                FROM bus_history 
                WHERE bus_id = ? 
                  AND area IN ('kedatangan', 'pengendapan', 'keberangkatan')
            ", array($id))->row()->cnt;

            if ($has_valid_transition == 0) {
                echo json_encode([
                    'status' => false, 
                    'message' => 'Gagal! Bus tidak boleh langsung keluar dari area masuk tanpa melewati area pelayanan terminal (Kedatangan / Pengendapan / Keberangkatan)!'
                ]);
                return;
            }
        }

        // Fetch current bus info before updating
        $prev_bus = $this->db->select('plat_nomor, nama_po, area')->where('id', $id)->get('audio_queue')->row();
        if (!$prev_bus) {
            echo json_encode(['status' => false, 'message' => 'Bus tidak ditemukan']);
            return;
        }

        // ✅ Update area + timestamp khusus area change
        // Database trigger `after_update_audio_queue` otomatis mencatat & menutup history bus
        $update_data = [
            'area' => $area,
            'tujuan' => $tujuan,
            'updated_at' => date('Y-m-d H:i:s'),
            'area_updated_at' => date('Y-m-d H:i:s')  // ✨ FIELD BARU
        ];

        // Jika berpindah dari pengendapan ke kedatangan atau keberangkatan, nyalakan kembali announcer
        if ($prev_bus->area === 'pengendapan' && in_array($area, ['kedatangan', 'keberangkatan'])) {
            $area_text_id = ($area === 'kedatangan') ? "area kedatangan" : "area keberangkatan";
            $area_text_en = ($area === 'kedatangan') ? "the arrival area" : "the departure area";
            
            $plat_spelled = implode(' ', str_split(str_replace(' ', '', $prev_bus->plat_nomor)));
            $text_id = "Perhatian. Bus " . $prev_bus->nama_po . " dengan nomor polisi " . $plat_spelled . " telah memasuki " . $area_text_id . ". Terima kasih.";
            $text_en = "Attention. Bus " . $prev_bus->nama_po . " with license plate number " . $plat_spelled . " has entered " . $area_text_en . ". Thank you.";
            $text = $text_id . " | " . $text_en;

            $update_data['text'] = $text;
            $update_data['status'] = 'pending';
        }

        $this->db->where('id', $id)->update('audio_queue', $update_data);

        $this->Activity_model->log('update_bus', [
            'bus_id' => $id,
            'tujuan' => $tujuan,
            'area' => $area
        ]);

        echo json_encode(['status' => true]);
    }

    // ================= DETAIL HISTORY BUS =================
    public function get_history($bus_id){
        $this->authorize_page(['admin']);
        $data = $this->db
            ->where('bus_id', $bus_id)
            ->order_by('waktu_masuk', 'ASC')
            ->get('bus_history')
            ->result();
        echo json_encode($data);
    }

    public function get_bus_for_form($current_area = '') {
    $this->authorize_page(['admin','petugas_kedatangan','petugas_pengendapan','petugas_keberangkatan']);

    $this->db->select('id, plat_nomor, nama_po, tujuan, created_at, area');
    $this->db->from('audio_queue');
    $this->db->where('type', 'bus');
    $this->db->where('plat_nomor IS NOT NULL');
    $this->db->where('plat_nomor !=', '');
    $this->db->where('created_at >=', date('Y-m-d 00:00:00'));
    $this->db->where('created_at <=', date('Y-m-d 23:59:59'));

    // ✅ Jangan tampilkan bus yang sudah keluar
    $this->db->where('area !=', 'berangkat');

    // ✨ FILTER AREA
    if ($current_area) {
        $this->db->where('area !=', $current_area);
    }

    $this->db->order_by('created_at', 'DESC');

    $data = $this->db->get()->result();

    echo json_encode($data);
}

public function get_po_by_plat()
{
    $this->authorize_page(['admin','petugas_masuk']);

    $plat = $this->normalize_plat($this->input->post('plat_nomor'));
    $plat_clean = str_replace(' ', '', $plat);

    // 🔥 CHECK JIKA BUS MASIH DI DALAM TERMINAL (BELUM KELUAR)
    $active_bus = $this->db->query("
        SELECT id, nama_po, area 
        FROM audio_queue 
        WHERE type = 'bus' 
          AND REPLACE(plat_nomor, ' ', '') = ?
          AND area != 'berangkat'
        LIMIT 1
    ", array($plat_clean))->row();

    if ($active_bus) {
        echo json_encode([
            'status'  => false,
            'nama_po' => null,
            'is_active' => true,
            'message' => 'Bus masih aktif di area: ' . strtoupper($active_bus->area)
        ]);
        return;
    }

    // ================= DATABASE 2 =================
    $db2 = $this->load->database('db2', TRUE);

    // ================= QUERY JOIN =================
    $bus = $db2->query("
        SELECT 
            tb.nopol,
            tb.id_po,
            po.nama_po

        FROM terminal_boardingpass tb

        LEFT JOIN tbl_po po
            ON po.id_po = tb.id_po

        WHERE REPLACE(tb.nopol, ' ', '') = ?

        LIMIT 1
    ", array($plat_clean))->row();

    // ================= RESPONSE =================
    if($bus){

        echo json_encode([
            'status'  => true,
            'nama_po' => $bus->nama_po
        ]);

    } else {

        echo json_encode([
            'status'  => false,
            'nama_po' => null
        ]);
    }
}

public function save_bus_masuk()
{
    $this->authorize_page(['admin','petugas_masuk']);

    $plat = $this->normalize_plat($this->input->post('plat_nomor'));
    $plat_clean = str_replace(' ', '', $plat);
    $target_area = $this->input->post('target_area');
    $tujuan = $this->input->post('tujuan');

    // 🔥 CHECK JIKA BUS MASIH DI DALAM TERMINAL (BELUM KELUAR)
    $active_bus = $this->db->query("
        SELECT id, nama_po, area 
        FROM audio_queue 
        WHERE type = 'bus' 
          AND REPLACE(plat_nomor, ' ', '') = ?
          AND area != 'berangkat'
        LIMIT 1
    ", array($plat_clean))->row();

    if ($active_bus) {
        echo json_encode([
            'status'  => false,
            'message' => 'Gagal! Bus dengan plat nomor ini masih aktif berada di dalam terminal (Area: ' . strtoupper($active_bus->area) . ').'
        ]);
        return;
    }

    // ================= DATABASE 2 =================
    $db2 = $this->load->database('db2', TRUE);

    // ================= AMBIL DATA PO =================
    $bus = $db2->query("
        SELECT 
            tb.nopol,
            tb.id_po,
            po.nama_po

        FROM terminal_boardingpass tb

        LEFT JOIN tbl_po po
            ON po.id_po = tb.id_po

        WHERE REPLACE(tb.nopol, ' ', '') = ?

        LIMIT 1
    ", array($plat_clean))->row();

    // ================= DEFAULT =================
    $nama_po = 'PO Tidak Dikenal';

    if($bus){
        $nama_po = $bus->nama_po;
    }

    // ================= INSERT =================
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

    $plat_spelled = implode(' ', str_split(str_replace(' ', '', $plat)));
    $text_id = "Perhatian. Bus " . $nama_po . " dengan nomor polisi " . $plat_spelled . " telah memasuki " . $area_text_id . ". Terima kasih.";
    $text_en = "Attention. Bus " . $nama_po . " with license plate number " . $plat_spelled . " has entered " . $area_text_en . ". Thank you.";
    $text = $text_id . " | " . $text_en;

    $data = [
        'plat_nomor'     => $plat,
        'nama_po'        => $nama_po,
        'tujuan'         => $tujuan,
        'type'           => 'bus',
        'text'           => $text,
        'area'           => 'masuk',
        'status'         => ($target_area === 'pengendapan' ? 'done' : 'pending'),
        'priority'       => 3,
        'created_at'     => date('Y-m-d H:i:s'),
        'area_updated_at'=> date('Y-m-d H:i:s')
    ];

    $this->db->trans_start();

    $this->db->insert('audio_queue', $data);
    $bus_id = $this->db->insert_id();

    // 🔥 INSERT KE HISTORY AGAR TV DISPLAY MENAMPILKAN WAKTU MASUK
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

    $this->Activity_model->log('save_bus_masuk', [
        'plat_nomor' => $plat,
        'nama_po' => $nama_po,
        'target_area' => $target_area
    ]);

    echo json_encode([
        'status' => true
    ]);
}
        
public function get_bus_masuk()
{
    $this->authorize_page(['admin','petugas_masuk']);

    $data = $this->db
        ->select('
            id,
            plat_nomor,
            nama_po,
            area,
            created_at
        ')
        ->from('audio_queue')
        ->where('type', 'bus')
        ->where('created_at >=', date('Y-m-d 00:00:00'))
        ->where('created_at <=', date('Y-m-d 23:59:59'))
        ->order_by('created_at', 'DESC')
        ->get()
        ->result();

    echo json_encode($data);
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