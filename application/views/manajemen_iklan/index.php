<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark font-weight-bold">
                        <i class="fas fa-bullhorn mr-2 text-warning"></i>Manajemen Iklan
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Manajemen Iklan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm">
                    <i class="fas fa-check mr-2"></i><?= html_escape($this->session->flashdata('success')) ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <i class="fas fa-ban mr-2"></i><?= html_escape($this->session->flashdata('error')) ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Jadwal</span>
                            <span class="info-box-number"><?= number_format($stats_total) ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success"><i class="fas fa-toggle-on"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Aktif</span>
                            <span class="info-box-number"><?= number_format($stats_active) ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Masuk Waktu Saat Ini</span>
                            <span class="info-box-number"><?= number_format($stats_due_now) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-filter mr-1"></i>Filter Iklan
                    </h3>
                    <div class="card-tools">
                        <a href="<?= site_url('manajemen_iklan/create') ?>" class="btn btn-warning btn-sm font-weight-bold">
                            <i class="fas fa-plus mr-1"></i>Tambah Iklan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= site_url('manajemen_iklan') ?>" class="row align-items-end">
                        <div class="col-md-5">
                            <label class="text-xs text-muted text-uppercase font-weight-bold">Cari Judul / Pesan</label>
                            <input type="text" class="form-control form-control-sm" name="keyword" value="<?= html_escape($filters['keyword']) ?>" placeholder="Contoh: kebersihan">
                        </div>
                        <div class="col-md-3 mt-2 mt-md-0">
                            <label class="text-xs text-muted text-uppercase font-weight-bold">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="">Semua Status</option>
                                <option value="1" <?= $filters['status'] === '1' ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $filters['status'] === '0' ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-2 mt-md-0 d-flex">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill mr-2">
                                <i class="fas fa-search mr-1"></i>Filter
                            </button>
                            <a href="<?= site_url('manajemen_iklan') ?>" class="btn btn-outline-secondary btn-sm flex-fill">
                                <i class="fas fa-redo mr-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-table mr-2 text-warning"></i>Daftar Jadwal Iklan
                    </h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped text-sm mb-0">
                        <thead class="bg-light text-muted text-uppercase text-xs">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Judul & Pesan</th>
                                <th style="width: 170px;">Tanggal</th>
                                <th style="width: 160px;">Jam</th>
                                <th style="width: 180px;">Hari</th>
                                <th style="width: 120px;">Interval</th>
                                <th style="width: 150px;">Terakhir Putar</th>
                                <th style="width: 110px;">Status</th>
                                <th style="width: 170px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ads)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <p class="mb-0">Belum ada jadwal iklan.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php
                                $days = ['0' => 'Min', '1' => 'Sen', '2' => 'Sel', '3' => 'Rab', '4' => 'Kam', '5' => 'Jum', '6' => 'Sab'];
                                foreach ($ads as $ad):
                                    $repeat_days = json_decode($ad->repeat_days ?: '[]', true);
                                    if (!is_array($repeat_days)) $repeat_days = [];
                                ?>
                                    <tr>
                                        <td class="font-weight-bold text-muted">#<?= (int) $ad->id ?></td>
                                        <td>
                                            <div class="font-weight-bold text-dark"><?= html_escape($ad->ad_title ?: 'Tanpa Judul') ?></div>
                                            <?php
                                                $preview = strip_tags($ad->ad_text ?? '');
                                                if (strlen($preview) > 120) {
                                                    $preview = substr($preview, 0, 117) . '...';
                                                }
                                            ?>
                                            <div class="text-muted"><?= html_escape($preview) ?></div>
                                        </td>
                                        <td>
                                            <span class="d-block"><?= html_escape($ad->start_date) ?></span>
                                            <span class="text-muted">s/d <?= html_escape($ad->end_date) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light border"><?= html_escape(substr($ad->start_time, 0, 5)) ?></span>
                                            <span class="text-muted">-</span>
                                            <span class="badge badge-light border"><?= html_escape(substr($ad->end_time, 0, 5)) ?></span>
                                        </td>
                                        <td>
                                            <?php if (empty($repeat_days)): ?>
                                                <span class="badge badge-info">Setiap Hari</span>
                                            <?php else: ?>
                                                <?php foreach ($repeat_days as $day): ?>
                                                    <span class="badge badge-secondary"><?= $days[$day] ?? '-' ?></span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= (int) $ad->interval_minutes ?> menit</td>
                                        <td><?= $ad->last_played ? html_escape($ad->last_played) : '<span class="text-muted">Belum pernah</span>' ?></td>
                                        <td>
                                            <?php if ((int) $ad->is_active === 1): ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('manajemen_iklan/edit/' . $ad->id) ?>" class="btn btn-warning btn-xs" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= site_url('manajemen_iklan/toggle/' . $ad->id) ?>" class="btn btn-info btn-xs" title="Aktif/Nonaktif">
                                                <i class="fas fa-power-off"></i>
                                            </a>
                                            <a href="<?= site_url('manajemen_iklan/delete/' . $ad->id) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Hapus jadwal iklan ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
