<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model([
            'audio_model',
            'user_model',
            'Activity_model'
        ]);

        $this->load->helper('url');

        // ================= AUTH =================

        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    // =====================================================
    // DASHBOARD
    // =====================================================

    public function index()
    {
        $data['title'] = 'Dashboard Monitoring Bus';

        $today = date('Y-m-d');

        // =====================================================
        // FLOW MOVEMENT HARIAN & REALTIME
        // =====================================================
        $today_start = $today . ' 00:00:00';
        $today_end   = $today . ' 23:59:59';

        // 1. TOTAL MASUK (All buses registered today)
        $data['count_masuk'] = $this->db
            ->where('type', 'bus')
            ->where('created_at >=', $today_start)
            ->where('created_at <=', $today_end)
            ->count_all_results('audio_queue');

        // 2. KEDATANGAN AKTIF (Buses currently in kedatangan area)
        $data['count_kedatangan'] = $this->db
            ->where('type', 'bus')
            ->where('area', 'kedatangan')
            ->where('created_at >=', $today_start)
            ->where('created_at <=', $today_end)
            ->count_all_results('audio_queue');

        // 3. PENGENDAPAN AKTIF (Buses currently in pengendapan area)
        $data['count_pengendapan'] = $this->db
            ->where('type', 'bus')
            ->where('area', 'pengendapan')
            ->where('created_at >=', $today_start)
            ->where('created_at <=', $today_end)
            ->count_all_results('audio_queue');

        // 4. KEBERANGKATAN AKTIF (Buses currently in keberangkatan area)
        $data['count_keberangkatan'] = $this->db
            ->where('type', 'bus')
            ->where('area', 'keberangkatan')
            ->where('created_at >=', $today_start)
            ->where('created_at <=', $today_end)
            ->count_all_results('audio_queue');

        // 5. TOTAL KELUAR (Buses that have exited today)
        $data['count_keluar'] = $this->db
            ->where('type', 'bus')
            ->where('area', 'berangkat')
            ->where('area_updated_at >=', $today_start)
            ->where('area_updated_at <=', $today_end)
            ->count_all_results('audio_queue');

        // 6. BUS AKTIF YANG MASIH DI DALAM TERMINAL
        // Yaitu gabungan dari kedatangan, pengendapan, keberangkatan serta yang masuk tapi belum ada status lanjutannya (tetap berstatus 'masuk')
        $count_active_masuk = $this->db
            ->where('type', 'bus')
            ->where('area', 'masuk')
            ->where('created_at >=', $today_start)
            ->where('created_at <=', $today_end)
            ->count_all_results('audio_queue');

        $data['count_aktif'] = $count_active_masuk + $data['count_kedatangan'] + $data['count_pengendapan'] + $data['count_keberangkatan'];

        // =====================================================
        // STATUS AREA REALTIME
        // =====================================================

        $capacities_raw = $this->db->get('area_capacities')->result();
        $capacities = [];
        foreach ($capacities_raw as $c) {
            $capacities[$c->area] = $c->capacity;
        }
        $cap_kedatangan    = $capacities['kedatangan'] ?? 30;
        $cap_pengendapan   = $capacities['pengendapan'] ?? 50;
        $cap_keberangkatan = $capacities['keberangkatan'] ?? 28;

        $data['kedatangan'] =
            $this->get_area_status('kedatangan', $cap_kedatangan);

        $data['pengendapan'] =
            $this->get_area_status('pengendapan', $cap_pengendapan);

        $data['keberangkatan'] =
            $this->get_area_status('keberangkatan', $cap_keberangkatan);

        // =====================================================
        // AKTIVITAS TERMINAL REALTIME
        // =====================================================

        $data['aktivitas'] = $this->db

            ->select('
                plat_nomor,
                nama_po,
                tujuan,
                area,
                area_updated_at,
                created_at
            ')

            ->from('audio_queue')

            ->where('type', 'bus')

            ->where('plat_nomor IS NOT NULL')

            ->where('plat_nomor !=', '')

            ->where('created_at >=', $today . ' 00:00:00')
            ->where('created_at <=', $today . ' 23:59:59')

            ->order_by('area_updated_at', 'DESC')

            ->limit(10)

            ->get()

            ->result();

        // =====================================================
        // LAPORAN BULANAN
        // =====================================================

        $data['laporan_bulanan'] =
            $this->report_bulanan();

        // =====================================================
        // LOAD VIEW
        // =====================================================

        $this->load->view('dashboard', $data);
    }

    // =====================================================
    // COUNT BUS BY AREA
    // =====================================================

    private function count_bus_by_area($area, $tanggal)
    {
        return $this->db->query("
            SELECT COUNT(*) as total

            FROM bus_history

            WHERE area = '$area'

            AND waktu_masuk >= '$tanggal 00:00:00'
            AND waktu_masuk <= '$tanggal 23:59:59'
        ")->row()->total ?? 0;
    }

    // =====================================================
    // REPORT BULANAN
    // =====================================================

    public function report_bulanan()
    {
        $bulan_ini = date('m');

        $tahun_ini = date('Y');

        $jumlah_hari = cal_days_in_month(
            CAL_GREGORIAN,
            $bulan_ini,
            $tahun_ini
        );

        $laporan = [];

        for ($tgl = 1; $tgl <= $jumlah_hari; $tgl++) {

            $tgl_cari =
                $tahun_ini . '-' .
                $bulan_ini . '-' .
                sprintf('%02d', $tgl);

            $laporan[] = [

                'tanggal' =>
                    $tgl_cari,

                'masuk' =>
                    $this->count_bus_by_area(
                        'masuk',
                        $tgl_cari
                    ),

                'kedatangan' =>
                    $this->count_bus_by_area(
                        'kedatangan',
                        $tgl_cari
                    ),

                'pengendapan' =>
                    $this->count_bus_by_area(
                        'pengendapan',
                        $tgl_cari
                    ),

                'keberangkatan' =>
                    $this->count_bus_by_area(
                        'keberangkatan',
                        $tgl_cari
                    ),

                'keluar' =>
                    $this->count_bus_by_area(
                        'berangkat',
                        $tgl_cari
                    )

            ];
        }

        return $laporan;
    }

    // =====================================================
    // COUNT AREA REALTIME
    // =====================================================

    private function count_area($area)
    {
        return $this->db

            ->where('area', $area)

            ->where('created_at >=', date('Y-m-d 00:00:00'))
            ->where('created_at <=', date('Y-m-d 23:59:59'))

            ->count_all_results('audio_queue');
    }

    // =====================================================
    // STATUS AREA
    // =====================================================

    private function get_area_status($area, $kapasitas)
    {
        $jumlah =
            $this->count_area($area);

        $persen =
            ($jumlah / $kapasitas) * 100;

        // ================= STATUS =================

        if ($persen >= 100) {

            $status = 'FULL';
            $color  = 'danger';

        } elseif ($persen >= 80) {

            $status = 'HAMPIR PENUH';
            $color  = 'warning';

        } elseif ($persen >= 50) {

            $status = 'PADAT';
            $color  = 'info';

        } else {

            $status = 'NORMAL';
            $color  = 'success';
        }

        return [

            'jumlah' =>
                $jumlah,

            'kapasitas' =>
                $kapasitas,

            'persen' =>
                round($persen),

            'status' =>
                $status,

            'color' =>
                $color

        ];
    }

    // =====================================================
    // MASTER DATA: KAPASITAS AREA
    // =====================================================
    public function kapasitas()
    {
        $data['title'] = 'Master Data Kapasitas Area';
        $data['capacities'] = $this->db->order_by('id', 'ASC')->get('area_capacities')->result();
        
        $this->load->view('dashboard/kapasitas', $data);
    }

    public function update_capacity()
    {
        $area = $this->input->post('area');
        $capacity = (int)$this->input->post('capacity');

        if (!$area || $capacity <= 0) {
            echo json_encode(['status' => false, 'message' => 'Input data tidak valid']);
            return;
        }

        // Update capacity in DB
        $updated = $this->db->where('area', $area)->update('area_capacities', ['capacity' => $capacity]);
        
        if ($updated) {
            // Log this activity
            $this->Activity_model->log('update_capacity', [
                'area' => $area,
                'capacity' => $capacity
            ]);
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui database']);
        }
    }

}