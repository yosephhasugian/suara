<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_pnp extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('database_kedua', TRUE);
    }

    // ========== REAL-TIME HARI INI ==========
    public function get_data_penumpang_per_status($tahun, $bulan)
{
    // Query dengan conditional aggregation (CASE WHEN)
    return $this->db2->query("
        SELECT
            DATE(tanggal) AS tgl,
            COUNT(CASE WHEN status = '1' THEN id END) AS datang_bus,
            COUNT(CASE WHEN status = '2' THEN id END) AS berangkat_bus,
            COALESCE(SUM(CASE WHEN status = '1' THEN pnp END), 0) AS datang_pnp,
            COALESCE(SUM(CASE WHEN status = '2' THEN pnp END), 0) AS berangkat_pnp
        FROM terminal_retribusi
        WHERE YEAR(tanggal) = ? AND MONTH(tanggal) = ?
        GROUP BY DATE(tanggal)
        ORDER BY tgl ASC
    ", [$tahun, $bulan])->result();
}
    // ========= ✅ SUMMARY BULANAN (return INT, bukan object) =========
public function summary_bus_datang($tahun, $bulan)
{
    $result = $this->db2->select("COUNT(kode) AS total")
        ->from("terminal_retribusi")
        ->where("status", "1")
        ->where("YEAR(tanggal)", $tahun)
        ->where("MONTH(tanggal)", $bulan)
        ->get()->row();
    return (int) ($result ? $result->total : 0);
}

public function summary_bus_berangkat($tahun, $bulan)
{
    $result = $this->db2->select("COUNT(kode) AS total")
        ->from("terminal_jadwal")
        ->where("status", "2")
        ->where("YEAR(tanggal)", $tahun)
        ->where("MONTH(tanggal)", $bulan)
        ->get()->row();
    return (int) ($result ? $result->total : 0);
}

public function summary_pnp_datang($tahun, $bulan)
{
    $result = $this->db2->select("COALESCE(SUM(pnp), 0) AS total")
        ->from("terminal_retribusi")
        ->where("status", "1")
        ->where("YEAR(tanggal)", $tahun)
        ->where("MONTH(tanggal)", $bulan)
        ->get()->row();
    return (int) ($result ? $result->total : 0);
}

public function summary_pnp_berangkat($tahun, $bulan)
{
    $result = $this->db2->select("COALESCE(SUM(pnp), 0) AS total")
        ->from("terminal_jadwal")
        ->where("status", "2")
        ->where("YEAR(tanggal)", $tahun)
        ->where("MONTH(tanggal)", $bulan)
        ->get()->row();
    return (int) ($result ? $result->total : 0);
}

public function totalpnp_bulanan($tahun, $bulan)
{
    $datang = $this->summary_pnp_datang($tahun, $bulan);
    $berangkat = $this->summary_pnp_berangkat($tahun, $bulan);
    return $datang + $berangkat;
}

    public function totalpnp()
    {
        $datenow = date('Y-m-d');
        // Gabungkan dari kedua tabel
        $datang = $this->db2->select("COALESCE(SUM(pnp), 0) AS total")
            ->from("terminal_retribusi")
            ->where("status", "1")
            ->where("tanggal", $datenow)
            ->get()->row()->total;

        $berangkat = $this->db2->select("COALESCE(SUM(pnp), 0) AS total")
            ->from("terminal_jadwal")
            ->where("status", "2")
            ->where("tanggal", $datenow)
            ->get()->row()->total;

        return (object) ['total_pnp' => $datang + $berangkat];
    }

    // ========== DATA BULANAN (GRAFIK & TABEL) ==========
    
    public function get_data_penumpang($tahun, $bulan)
    {
        // Query kompleks: gabungkan data harian dari 2 tabel
        $sql = "
            SELECT 
                tgl.tanggal,
                COALESCE(datang.total_bus, 0) AS kedatangan_bus,
                COALESCE(berangkat.total_bus, 0) AS keberangkatan_bus,
                COALESCE(datang.total_pnp, 0) AS kedatangan_pnp,
                COALESCE(berangkat.total_pnp, 0) AS keberangkatan_pnp
            FROM (
                SELECT DATE(tanggal) AS tanggal
                FROM terminal_retribusi
                WHERE YEAR(tanggal) = ? AND MONTH(tanggal) = ?
                UNION
                SELECT DATE(tanggal) AS tanggal
                FROM terminal_jadwal
                WHERE YEAR(tanggal) = ? AND MONTH(tanggal) = ?
            ) tgl
            LEFT JOIN (
                SELECT 
                    DATE(tanggal) AS tanggal,
                    COUNT(kode) AS total_bus,
                    SUM(pnp) AS total_pnp
                FROM terminal_retribusi
                WHERE status = '1'
                GROUP BY DATE(tanggal)
            ) datang ON tgl.tanggal = datang.tanggal
            LEFT JOIN (
                SELECT 
                    DATE(tanggal) AS tanggal,
                    COUNT(kode) AS total_bus,
                    SUM(pnp) AS total_pnp
                FROM terminal_jadwal
                WHERE status = '2'
                GROUP BY DATE(tanggal)
            ) berangkat ON tgl.tanggal = berangkat.tanggal
            ORDER BY tgl.tanggal ASC
        ";

        return $this->db2->query($sql, [$tahun, $bulan, $tahun, $bulan])->result();
    }

    // ========== TOP 10==========
    
  public function top10_pnp_datang($tahun, $bulan)
{
    return $this->db2->select("
        t.tujuan,
        COUNT(r.id) AS jumlah_bus,
        COALESCE(SUM(r.pnp), 0) AS jumlah_pnp
    ")
    ->from('terminal_retribusi r')
    ->join('cor_manifest m', 'm.id_manifest = r.id', 'left')
    ->join('tbl_tujuan t', 't.id_tujuan = m.id_tujuan', 'left')
    ->where('r.status', '1')
    ->where("YEAR(r.tanggal)", $tahun)
    ->where("MONTH(r.tanggal)", $bulan)
    ->group_by('t.id_tujuan, t.tujuan')
    ->order_by('jumlah_pnp', 'DESC')
    ->limit(10)
    ->get()
    ->result();
}

public function top10_pnp_berangkat($tahun, $bulan)
{
    return $this->db2->select("
        t.tujuan,
        COUNT(r.id) AS jumlah_bus,
        COALESCE(SUM(r.pnp), 0) AS jumlah_pnp
    ")
    ->from('terminal_jadwal r')
    // ✅ Langsung join ke tbl_tujuan via trayek_id (tanpa cor_manifest!)
     ->join('cor_manifest m', 'm.id_manifest = r.id', 'left')
    ->join('tbl_tujuan t', 't.id_tujuan = m.id_tujuan', 'left')
    ->where('r.status', '2')
    ->where("YEAR(r.tanggal)", $tahun)
    ->where("MONTH(r.tanggal)", $bulan)
    ->group_by('t.id_tujuan, t.tujuan')
    ->order_by('jumlah_pnp', 'DESC')
    ->limit(10)
    ->get()
    ->result();
}

    public function top10_bus_datang($tahun, $bulan)
    {
        return $this->db2->select("
            p.nama_po,
            COUNT(r.id) AS total_bus
        ")
        ->from("terminal_retribusi r")
        ->join("tbl_po p", "r.id_po = p.id_po", "left")
        ->where("r.status", "1")
        ->where("YEAR(r.tanggal)", $tahun)
        ->where("MONTH(r.tanggal)", $bulan)
        ->group_by("r.id_po")
        ->order_by("total_bus", "DESC")
        ->limit(10)
        ->get()
        ->result();
    }

    public function top10_bus_berangkat($tahun, $bulan)
{
    return $this->db2->select("
        p.nama_po,
        COUNT(j.id) AS total_bus
    ")
    ->from('terminal_jadwal j')
    ->join('tbl_po p', 'j.id_po = p.id_po', 'left')
    ->where('j.status', '2')
    ->where("YEAR(j.tanggal)", $tahun)   // ✅ j.tanggal, BUKAN r.tanggal
    ->where("MONTH(j.tanggal)", $bulan)  // ✅ j.tanggal
    ->group_by('j.id_po, p.nama_po')    // tambah p.nama_po untuk keamanan
    ->order_by('total_bus', 'DESC')
    ->limit(10)
    ->get()
    ->result();
}

public function get_realtime($tahun, $bulan)
{
    return [
        'busberangkat'  => $this->count_bus_berangkat($tahun, $bulan),
        'busdatang'     => $this->count_bus_datang($tahun, $bulan),
        'pnpberangkat'  => $this->count_pnp_berangkat($tahun, $bulan),
        'pnpdatang'     => $this->count_pnp_datang($tahun, $bulan),
        'totalpnp'      => $this->count_total_pnp($tahun, $bulan),

        'dataGrafik'    => $this->get_grafik_harian($tahun, $bulan),

        'grafik' => [
            'keberangkatanPnp' => array_column($this->get_grafik_harian($tahun, $bulan), 'keberangkatan_pnp'),
            'kedatanganPnp'    => array_column($this->get_grafik_harian($tahun, $bulan), 'kedatangan_pnp'),
            'keberangkatanBus' => array_column($this->get_grafik_harian($tahun, $bulan), 'keberangkatan_bus'),
            'kedatanganBus'    => array_column($this->get_grafik_harian($tahun, $bulan), 'kedatangan_bus'),
        ],

        'top10' => [
            'pnp_datang'    => $this->top10_pnp_datang($tahun, $bulan),
            'pnp_berangkat' => $this->top10_pnp_berangkat($tahun, $bulan),
            'bus_datang'    => $this->top10_bus_datang($tahun, $bulan),
            'bus_berangkat' => $this->top10_bus_berangkat($tahun, $bulan)
        ]
    ];
}
    // ========= ✅ DATA TAHUNAN (untuk grafik per bulan) =========
public function get_yearly_comparison($tahun)
{
    // Ambil semua bulan dalam tahun tsb dari kedua tabel
    $sql = "
        SELECT 
            bulan,
            COALESCE(SUM(kedatangan_bus), 0) AS kedatangan_bus,
            COALESCE(SUM(keberangkatan_bus), 0) AS keberangkatan_bus,
            COALESCE(SUM(kedatangan_pnp), 0) AS kedatangan_pnp,
            COALESCE(SUM(keberangkatan_pnp), 0) AS keberangkatan_pnp
        FROM (
            SELECT 
                MONTH(tanggal) AS bulan,
                COUNT(kode) AS kedatangan_bus,
                0 AS keberangkatan_bus,
                SUM(pnp) AS kedatangan_pnp,
                0 AS keberangkatan_pnp
            FROM terminal_retribusi 
            WHERE status = '1' AND YEAR(tanggal) = ?
            GROUP BY MONTH(tanggal)
            
            UNION ALL
            
            SELECT 
                MONTH(tanggal) AS bulan,
                0 AS kedatangan_bus,
                COUNT(kode) AS keberangkatan_bus,
                0 AS kedatangan_pnp,
                SUM(pnp) AS keberangkatan_pnp
            FROM terminal_jadwal 
            WHERE status = '2' AND YEAR(tanggal) = ?
            GROUP BY MONTH(tanggal)
        ) combined
        GROUP BY bulan
        ORDER BY bulan ASC
    ";
    return $this->db2->query($sql, [$tahun, $tahun])->result();
}
}