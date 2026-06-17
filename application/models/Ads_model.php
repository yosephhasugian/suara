<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ads_model extends CI_Model {

    protected $table = 'ads_schedule';

    public function get_all($filters = []) {
        if (!empty($filters['keyword'])) {
            $this->db->group_start()
                ->like('ad_title', $filters['keyword'])
                ->or_like('ad_text', $filters['keyword'])
                ->group_end();
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $this->db->where('is_active', (int) $filters['status']);
        }

        return $this->db
            ->order_by('is_active', 'DESC')
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => (int) $id])->row();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => (int) $id]);
    }

    public function count_all_ads() {
        return $this->db->count_all($this->table);
    }

    public function count_active() {
        return $this->db
            ->where('is_active', 1)
            ->count_all_results($this->table);
    }

    public function count_due_now() {
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
        $day_of_week = date('w');

        return $this->db->query("
            SELECT COUNT(*) AS total
            FROM {$this->table}
            WHERE is_active = 1
              AND start_date <= ?
              AND end_date >= ?
              AND start_time <= ?
              AND end_time >= ?
              AND (
                    repeat_days IS NULL
                    OR repeat_days = ''
                    OR repeat_days = '[]'
                    OR repeat_days LIKE ?
              )
        ", [
            $today,
            $today,
            $current_time,
            $current_time,
            '%"' . $day_of_week . '"%'
        ])->row()->total ?? 0;
    }
}
