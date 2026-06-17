<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manajemen_iklan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Ads_model');
        $this->load->model('Activity_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');

        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    public function index() {
        $filters = [
            'keyword' => trim($this->input->get('keyword', TRUE) ?? ''),
            'status' => $this->input->get('status', TRUE)
        ];

        $data = [
            'title' => 'Manajemen Iklan',
            'ads' => $this->Ads_model->get_all($filters),
            'filters' => $filters,
            'stats_total' => $this->Ads_model->count_all_ads(),
            'stats_active' => $this->Ads_model->count_active(),
            'stats_due_now' => $this->Ads_model->count_due_now()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('manajemen_iklan/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $data = [
            'title' => 'Tambah Iklan',
            'mode' => 'create',
            'ad' => null,
            'selected_days' => []
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('manajemen_iklan/form', $data);
        $this->load->view('templates/footer');
    }

    public function store() {
        $payload = $this->build_payload();
        $error = $this->validate_payload($payload);

        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('manajemen_iklan/create');
        }

        $payload['created_at'] = date('Y-m-d H:i:s');
        $id = $this->Ads_model->insert($payload);

        $this->Activity_model->log('create_ads_schedule', [
            'ads_schedule_id' => $id,
            'ad_title' => $payload['ad_title']
        ]);

        $this->session->set_flashdata('success', 'Jadwal iklan berhasil ditambahkan.');
        redirect('manajemen_iklan');
    }

    public function edit($id) {
        $ad = $this->Ads_model->get_by_id($id);
        if (!$ad) {
            show_404();
        }

        $selected_days = json_decode($ad->repeat_days ?: '[]', true);
        if (!is_array($selected_days)) {
            $selected_days = [];
        }

        $data = [
            'title' => 'Edit Iklan',
            'mode' => 'edit',
            'ad' => $ad,
            'selected_days' => $selected_days
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('manajemen_iklan/form', $data);
        $this->load->view('templates/footer');
    }

    public function update($id) {
        $ad = $this->Ads_model->get_by_id($id);
        if (!$ad) {
            show_404();
        }

        $payload = $this->build_payload();
        $error = $this->validate_payload($payload);

        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('manajemen_iklan/edit/' . (int) $id);
        }

        $payload['last_played'] = null;
        $this->Ads_model->update($id, $payload);

        $this->Activity_model->log('update_ads_schedule', [
            'ads_schedule_id' => (int) $id,
            'ad_title' => $payload['ad_title']
        ]);

        $this->session->set_flashdata('success', 'Jadwal iklan berhasil diperbarui.');
        redirect('manajemen_iklan');
    }

    public function toggle($id) {
        $ad = $this->Ads_model->get_by_id($id);
        if (!$ad) {
            show_404();
        }

        $new_status = (int) !$ad->is_active;
        $this->Ads_model->update($id, ['is_active' => $new_status]);

        $this->Activity_model->log('toggle_ads_schedule', [
            'ads_schedule_id' => (int) $id,
            'is_active' => $new_status
        ]);

        $this->session->set_flashdata('success', $new_status ? 'Iklan diaktifkan.' : 'Iklan dinonaktifkan.');
        redirect('manajemen_iklan');
    }

    public function delete($id) {
        $ad = $this->Ads_model->get_by_id($id);
        if (!$ad) {
            show_404();
        }

        $this->Ads_model->delete($id);
        $this->Activity_model->log('delete_ads_schedule', [
            'ads_schedule_id' => (int) $id,
            'ad_title' => $ad->ad_title
        ]);

        $this->session->set_flashdata('success', 'Jadwal iklan berhasil dihapus.');
        redirect('manajemen_iklan');
    }

    private function build_payload() {
        $repeat_days = $this->input->post('repeat_days');
        if (!is_array($repeat_days)) {
            $repeat_days = [];
        }

        $repeat_days = array_values(array_intersect($repeat_days, ['0', '1', '2', '3', '4', '5', '6']));

        return [
            'ad_title' => trim($this->input->post('ad_title', TRUE) ?? ''),
            'ad_text' => trim($this->input->post('ad_text', TRUE) ?? ''),
            'interval_minutes' => (int) $this->input->post('interval_minutes', TRUE),
            'start_date' => $this->input->post('start_date', TRUE),
            'end_date' => $this->input->post('end_date', TRUE),
            'start_time' => $this->input->post('start_time', TRUE),
            'end_time' => $this->input->post('end_time', TRUE),
            'repeat_days' => json_encode($repeat_days),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];
    }

    private function validate_payload($payload) {
        if ($payload['ad_text'] === '') {
            return 'Pesan iklan wajib diisi.';
        }

        if ($payload['interval_minutes'] < 1) {
            return 'Interval putar wajib dipilih.';
        }

        foreach (['start_date', 'end_date', 'start_time', 'end_time'] as $field) {
            if (empty($payload[$field])) {
                return 'Tanggal dan jam jadwal wajib diisi lengkap.';
            }
        }

        if ($payload['start_date'] > $payload['end_date']) {
            return 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.';
        }

        if ($payload['start_time'] >= $payload['end_time']) {
            return 'Jam selesai harus setelah jam mulai.';
        }

        return null;
    }
}
