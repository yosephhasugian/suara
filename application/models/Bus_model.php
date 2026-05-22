<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bus_model extends CI_Model {
    
    // ✅ METHOD BARU: Get bus by area dengan limit
     public function get_bus_by_area($area, $limit = 50) {
        $this->db->select('id, plat_nomor, nama_po, tujuan, created_at, area, area_updated_at');
        $this->db->from('audio_queue');
        
        $this->db->where('area', $area);
        $this->db->where('type', 'bus');
        $this->db->where('plat_nomor IS NOT NULL');
        $this->db->where('plat_nomor !=', '');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        
        // ✨ Urutkan berdasarkan jam masuk area, bukan jam pertama input
        $this->db->order_by('area_updated_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    // ✅ METHOD LAMA: Get bus waiting (untuk admin panel)
    public function get_bus_waiting() {
        $this->db->select('*');
        $this->db->from('audio_queue');
        $this->db->where('area !=', 'keberangkatan'); 
        $this->db->where('plat_nomor IS NOT NULL');
        $this->db->where('plat_nomor !=', '');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get()->result();
    }

    // ✅ METHOD LAMA: Update status
    public function update_status($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('audio_queue', $data);
    }

    public function get_bus_keberangkatan_display($limit = 100) {
        return $this->db
            ->select('id, plat_nomor, nama_po, tujuan, created_at, area_updated_at, area')
            ->from('audio_queue')
            ->where('type', 'bus')
            ->where_in('area', ['keberangkatan', 'berangkat'])  // ✅ DUA STATUS
            ->where('plat_nomor IS NOT NULL')
            ->where('plat_nomor !=', '')
            // ❌ TANPA filter DATE - tampilkan semua data historis
            ->order_by('COALESCE(area_updated_at, created_at)', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }
}