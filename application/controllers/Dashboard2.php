<?php
class Dashboard2 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_pnp', 'user_model']);
        $this->load->helper('url');
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    public function index()
{
    $tahun = $this->input->get('tahun') ?: date('Y');
    $bulan = $this->input->get('bulan') ?: date('m');

    // ✅ KIRIM TANGGAL RANGE UNTUK VIEW HEADER
    $data['start_date'] = "$tahun-$bulan-01";
    $data['end_date']   = date('Y-m-t', strtotime("$tahun-$bulan-01")); // last day of month

    // ✅ Data Tahunan (untuk grafik per bulan)
    $yearly_data = $this->User_pnp->get_yearly_comparison($tahun);
    $data['yearly_data_json'] = json_encode($yearly_data);

    // ✅ SUMMARY: ekstrak nilai langsung, bukan object
     $data['busberangkat'] = (int) $this->User_pnp->summary_bus_berangkat($tahun, $bulan);
    $data['busdatang']    = (int) $this->User_pnp->summary_bus_datang($tahun, $bulan);
    $data['pnpberangkat'] = (int) $this->User_pnp->summary_pnp_berangkat($tahun, $bulan);
    $data['pnpdatang']    = (int) $this->User_pnp->summary_pnp_datang($tahun, $bulan);
    $data['totalpnp']     = (int) $this->User_pnp->totalpnp_bulanan($tahun, $bulan);

    // ✅ Untuk grafik: data per tanggal, dengan split status
    $penumpang_raw = $this->User_pnp->get_data_penumpang_per_status($tahun, $bulan);
    // Contoh output: [ {tgl=>"2025-11-01", datang_bus=>5, berangkat_bus=>6, datang_pnp=>100, berangkat_pnp=>120}, ... ]

    $data['penumpang_data'] = json_encode(array_map(function($row) {
        return [
            'tanggal' => $row->tgl,
            'kedatangan_bus'     => (int) ($row->datang_bus ?? 0),
            'keberangkatan_bus'  => (int) ($row->berangkat_bus ?? 0),
            'kedatangan_pnp'     => (int) ($row->datang_pnp ?? 0),
            'keberangkatan_pnp'  => (int) ($row->berangkat_pnp ?? 0)
        ];
    }, $penumpang_raw));

     // ✅ AMBIL DATA GRAFIK & SIAPKAN UNTUK JS
    $raw_data = $this->User_pnp->get_data_penumpang($tahun, $bulan);

    // Format ulang jadi array asosiatif yang JS butuhkan
    $grafik_data = [];
    foreach ($raw_data as $row) {
        $grafik_data[] = [
            'tanggal' => $row->tanggal,
            'kedatangan_bus'     => (int) $row->kedatangan_bus,
            'keberangkatan_bus'  => (int) $row->keberangkatan_bus,   // ← pastikan ini ada
            'kedatangan_pnp'     => (int) $row->kedatangan_pnp,
            'keberangkatan_pnp'  => (int) $row->keberangkatan_pnp,   // ← pastikan ini ada
        ];
    }

    $data['penumpang_data'] = json_encode($grafik_data); // ← kirim sebagai JSON string


    // ✅ Top 5 tetap OK (object), tapi view harus sesuaikan

     $data['top10_pnp_datang']     = $this->User_pnp->top10_pnp_datang($tahun, $bulan);
    $data['top10_pnp_berangkat']  = $this->User_pnp->top10_pnp_berangkat($tahun, $bulan);
    $data['top10_bus_datang']     = $this->User_pnp->top10_bus_datang($tahun, $bulan);
    $data['top10_bus_berangkat']  = $this->User_pnp->top10_bus_berangkat($tahun, $bulan);


    

    $data['tahun'] = $tahun;
    $data['bulan'] = $bulan;
    $data['title'] = "Dashboard Penumpang";
    $data['sub_title'] = "Analytics Dashboard";
    $data['users'] = $this->user_model->get_all();
    $data['granularity'] = 'daily'; // default


    
    $this->load->view('dashboard2', $data); // pastikan view namanya benar!

    
}

public function realtime() {
    $tahun = $this->input->get('tahun');
    $bulan = $this->input->get('bulan');

    $data = $this->Dashboard_model->get_realtime($tahun, $bulan);

    echo json_encode($data);
}

}