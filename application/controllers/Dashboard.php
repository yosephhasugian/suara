<?php
class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['audio_model', 'user_model']);
        $this->load->helper('url');
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    public function index(){
    $data['title'] = 'Dashboard Monitoring Bus';

    $today = date('Y-m-d');

    $count = $this->db->query("
        SELECT 
            SUM(CASE WHEN area='masuk' THEN 1 ELSE 0 END) as masuk,
            SUM(CASE WHEN area='kedatangan' THEN 1 ELSE 0 END) as kedatangan,
            SUM(CASE WHEN area='pengendapan' THEN 1 ELSE 0 END) as pengendapan,
            SUM(CASE WHEN area='keberangkatan' THEN 1 ELSE 0 END) as keberangkatan,
            SUM(CASE WHEN area='berangkat' THEN 1 ELSE 0 END) as keluar
        FROM bus_history
        WHERE DATE(waktu_masuk) = '$today'
    ")->row();

    $data['count_masuk']         = $count->masuk ?? 0;
    $data['count_kedatangan']    = $count->kedatangan ?? 0;
    $data['count_pengendapan']   = $count->pengendapan ?? 0;
    $data['count_keberangkatan'] = $count->keberangkatan ?? 0;
    $data['count_keluar']        = $count->keluar ?? 0;
     // 🔥 WAJIB ADA INI
    $data['laporan_bulanan'] = $this->report_bulanan();

    $this->load->view('dashboard', $data);
}

// Fungsi internal untuk menghitung berdasarkan plat_nomor
private function count_bus_by_area($area, $tanggal){
    return $this->db->query("
        SELECT COUNT(*) as total
        FROM bus_history
        WHERE area = '$area'
        AND DATE(waktu_masuk) = '$tanggal'
    ")->row()->total ?? 0;
}

public function report_bulanan() {
    $bulan_ini = date('m');
    $tahun_ini = date('Y');
    $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan_ini, $tahun_ini);

    $laporan = [];
    for ($tgl = 1; $tgl <= $jumlah_hari; $tgl++) {
        $tgl_cari = $tahun_ini . '-' . $bulan_ini . '-' . sprintf('%02d', $tgl);
        
        $laporan[] = [
    'tanggal'       => $tgl_cari,
    'masuk'         => $this->count_bus_by_area('masuk', $tgl_cari),
    'kedatangan'    => $this->count_bus_by_area('kedatangan', $tgl_cari),
    'pengendapan'   => $this->count_bus_by_area('pengendapan', $tgl_cari),
    'keberangkatan' => $this->count_bus_by_area('keberangkatan', $tgl_cari),
    'keluar'        => $this->count_bus_by_area('berangkat', $tgl_cari),
];
    }
    return $laporan;
}
}