<?php
$is_edit = $mode === 'edit';
$action = $is_edit ? site_url('manajemen_iklan/update/' . $ad->id) : site_url('manajemen_iklan/store');
$days = ['0' => 'Minggu', '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu'];

$value = function($field, $default = '') use ($ad) {
    return $ad ? $ad->{$field} : $default;
};
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark font-weight-bold">
                        <i class="fas fa-bullhorn mr-2 text-warning"></i><?= $is_edit ? 'Edit Iklan' : 'Tambah Iklan' ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('manajemen_iklan') ?>">Manajemen Iklan</a></li>
                        <li class="breadcrumb-item active"><?= $is_edit ? 'Edit' : 'Tambah' ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <i class="fas fa-ban mr-2"></i><?= html_escape($this->session->flashdata('error')) ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= $action ?>">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card card-outline card-warning shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold">
                                    <i class="fas fa-pen mr-1"></i>Konten Iklan
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Judul Iklan</label>
                                    <input type="text" name="ad_title" class="form-control" value="<?= html_escape($value('ad_title')) ?>" placeholder="Contoh: Himbauan Menjaga Kebersihan">
                                </div>
                                <div class="form-group">
                                    <label>Pesan Iklan <span class="text-danger">*</span></label>
                                    <textarea name="ad_text" rows="6" class="form-control" required placeholder="Ketik pesan yang akan dibacakan oleh sistem suara"><?= html_escape($value('ad_text')) ?></textarea>
                                    <small class="text-muted">Durasi bacaan mengikuti panjang teks dan kecepatan TTS di Audio System.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card card-outline card-primary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold">
                                    <i class="fas fa-calendar-alt mr-1"></i>Jadwal Putar
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Interval Putar</label>
                                    <?php $interval = (string) $value('interval_minutes', '30'); ?>
                                    <select name="interval_minutes" class="form-control" required>
                                        <option value="5" <?= $interval === '5' ? 'selected' : '' ?>>Setiap 5 menit</option>
                                        <option value="10" <?= $interval === '10' ? 'selected' : '' ?>>Setiap 10 menit</option>
                                        <option value="15" <?= $interval === '15' ? 'selected' : '' ?>>Setiap 15 menit</option>
                                        <option value="30" <?= $interval === '30' ? 'selected' : '' ?>>Setiap 30 menit</option>
                                        <option value="60" <?= $interval === '60' ? 'selected' : '' ?>>Setiap 1 jam</option>
                                    </select>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Tanggal Mulai</label>
                                        <input type="date" name="start_date" class="form-control" value="<?= html_escape($value('start_date', date('Y-m-d'))) ?>" required>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Tanggal Selesai</label>
                                        <input type="date" name="end_date" class="form-control" value="<?= html_escape($value('end_date', date('Y-m-d'))) ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Jam Mulai</label>
                                        <input type="time" name="start_time" class="form-control" value="<?= html_escape(substr($value('start_time', '08:00:00'), 0, 5)) ?>" required>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Jam Selesai</label>
                                        <input type="time" name="end_time" class="form-control" value="<?= html_escape(substr($value('end_time', '17:00:00'), 0, 5)) ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Hari Putar</label>
                                    <div class="border rounded p-2">
                                        <?php foreach ($days as $day_value => $day_label): ?>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="day<?= $day_value ?>" name="repeat_days[]" value="<?= $day_value ?>" <?= in_array($day_value, $selected_days, true) ? 'checked' : '' ?>>
                                                <label class="custom-control-label" for="day<?= $day_value ?>"><?= $day_label ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <small class="text-muted">Jika tidak ada hari dipilih, jadwal dianggap berlaku setiap hari.</small>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="isActive" name="is_active" value="1" <?= (!$ad || (int) $value('is_active') === 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="isActive">Aktifkan jadwal</label>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="<?= site_url('manajemen_iklan') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-warning font-weight-bold">
                                    <i class="fas fa-save mr-1"></i>Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
