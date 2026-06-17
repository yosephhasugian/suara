<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark font-weight-bold">
                        <i class="fas fa-shield-alt mr-2 text-success"></i>Log Aktivitas Petugas
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Log Aktivitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Flash Alert -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="icon fas fa-check mr-2"></i><?= $this->session->flashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="icon fas fa-ban mr-2"></i><?= $this->session->flashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Stats Widgets -->
            <div class="row">
                <!-- Total Logs -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-history"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted text-sm font-weight-semibold">Total Log Audit</span>
                            <span class="info-box-number text-xl font-weight-bold"><?= number_format($stats_total) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Today Logs -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm mb-3">
                        <span class="info-box-icon bg-warning elevation-1 text-white"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted text-sm font-weight-semibold">Aktivitas Hari Ini</span>
                            <span class="info-box-number text-xl font-weight-bold"><?= number_format($stats_today) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Success Logins -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-sign-in-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted text-sm font-weight-semibold">Sesi Login Petugas</span>
                            <span class="info-box-number text-xl font-weight-bold"><?= number_format($stats_logins) ?></span>
                        </div>
                    </div>
                </div>

                <!-- CCTV ALPR Detections -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm mb-3">
                        <span class="info-box-icon bg-purple elevation-1 text-white" style="background-color: #6f42c1 !important;"><i class="fas fa-video"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted text-sm font-weight-semibold">Deteksi CCTV IoT</span>
                            <span class="info-box-number text-xl font-weight-bold"><?= number_format($stats_cctv) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title text-md font-weight-bold text-primary">
                        <i class="fas fa-filter mr-1"></i>Pencarian & Penyaringan Log
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body py-3">
                    <form action="<?= site_url('log') ?>" method="GET" class="row g-2 align-items-end">
                        <!-- Petugas -->
                        <div class="col-md-3">
                            <label class="form-label text-xs font-weight-bold text-muted text-uppercase">Nama Petugas</label>
                            <input type="text" name="username" class="form-control form-control-sm" 
                                   placeholder="Cari username petugas..." value="<?= htmlspecialchars($filter_username) ?>">
                        </div>
                        
                        <!-- Jenis Aksi -->
                        <div class="col-md-3">
                            <label class="form-label text-xs font-weight-bold text-muted text-uppercase">Jenis Tindakan</label>
                            <select name="action" class="form-control form-control-sm">
                                <option value="">-- Semua Tindakan --</option>
                                <?php foreach ($actions as $act): ?>
                                    <option value="<?= $act['action'] ?>" <?= $filter_action === $act['action'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($act['action']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Tanggal -->
                        <div class="col-md-3">
                            <label class="form-label text-xs font-weight-bold text-muted text-uppercase">Tanggal Kejadian</label>
                            <input type="date" name="date" class="form-control form-control-sm" 
                                   value="<?= htmlspecialchars($filter_date) ?>">
                        </div>

                        <!-- Buttons -->
                        <div class="col-md-3 mt-2 mt-md-0 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill font-weight-bold">
                                <i class="fas fa-search mr-1"></i>Filter
                            </button>
                            <a href="<?= site_url('log') ?>" class="btn btn-outline-secondary btn-sm flex-fill font-weight-bold">
                                <i class="fas fa-redo-alt mr-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title text-md font-weight-bold mb-0">
                        <i class="fas fa-list-ul mr-2 text-warning"></i>Riwayat Audit Trails (<?= number_format($total_logs) ?> records)
                    </h3>
                    <div class="card-tools ml-auto">
                        <?php if ($this->session->userdata('role') === 'admin'): ?>
                            <button class="btn btn-danger btn-xs font-weight-bold px-2 py-1 shadow-sm" onclick="confirmClearLogs()">
                                <i class="fas fa-trash-alt mr-1"></i>Kosongkan Semua Log
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card-body p-0 table-responsive" style="max-height: 550px; overflow-y: auto;">
                    <table class="table table-hover table-striped text-sm mb-0">
                        <thead class="bg-light text-muted text-uppercase text-xs font-weight-bold">
                            <tr>
                                <th style="width: 60px;" class="text-center">#ID</th>
                                <th style="width: 150px;">Petugas / Sumber</th>
                                <th style="width: 130px;" class="text-center">IP Address</th>
                                <th style="width: 200px;">Jenis Tindakan</th>
                                <th>Deskripsi / Payload Log</th>
                                <th style="width: 170px;" class="text-center">Tanggal & Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-gray"></i>
                                        <p class="mb-0 font-weight-semibold">Tidak ada log aktivitas yang cocok dengan kriteria pencarian.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <?php 
                                        // Dynamic badge class for actions
                                        $badge_class = 'badge-secondary';
                                        switch ($log['action']) {
                                            case 'login': $badge_class = 'badge-success'; break;
                                            case 'logout': $badge_class = 'badge-danger'; break;
                                            case 'update_bus': $badge_class = 'badge-primary'; break;
                                            case 'save_bus_masuk': $badge_class = 'badge-info'; break;
                                            case 'alpr_webhook_detection': $badge_class = 'badge-purple'; break;
                                            case 'purge_logs': $badge_class = 'badge-warning text-dark'; break;
                                        }

                                        // Try decoding json details or fallback to plain text
                                        $details = $log['details'];
                                        $is_json = false;
                                        if (!empty($details) && $details[0] === '{') {
                                            $decoded = json_decode($details, true);
                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                $is_json = true;
                                                $details_formatted = '';
                                                foreach ($decoded as $key => $val) {
                                                    $details_formatted .= '<strong>' . htmlspecialchars($key) . ':</strong> ' . (is_array($val) ? json_encode($val) : htmlspecialchars($val)) . ' | ';
                                                }
                                                $details_formatted = rtrim($details_formatted, ' | ');
                                            }
                                        }
                                        if (!$is_json) {
                                            $details_formatted = htmlspecialchars($details);
                                        }
                                    ?>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted"><?= $log['id'] ?></td>
                                        <td>
                                            <span class="font-weight-semibold text-dark">
                                                <i class="fas fa-user-circle mr-1 text-secondary"></i><?= htmlspecialchars($log['username']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center"><code class="text-xs bg-light px-2 py-1 rounded"><?= htmlspecialchars($log['ip_address']) ?></code></td>
                                        <td>
                                            <span class="badge <?= $badge_class ?> px-2 py-1" style="font-size: 0.75rem; font-weight: 600;">
                                                <?= htmlspecialchars($log['action']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted text-sm font-weight-normal">
                                                <?= $details_formatted ?>
                                            </div>
                                        </td>
                                        <td class="text-center text-muted font-weight-medium">
                                            <i class="far fa-calendar-alt mr-1"></i><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <?php if (!empty($pagination)): ?>
                    <div class="card-footer clearfix bg-white border-top">
                        <?= $pagination ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>
</div>

<!-- Premium Purple Badge style -->
<style>
    .badge-purple {
        background-color: #6f42c1;
        color: #fff;
    }
    .gap-2 {
        gap: 0.5rem;
    }
</style>

<!-- Alert & Confirm Dialog -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmClearLogs() {
    Swal.fire({
        title: '🛡️ Kosongkan Log Audit?',
        text: "Tindakan ini permanen dan akan menghapus seluruh data riwayat log aktivitas yang tersimpan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '🗑️ Ya, Bersihkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= site_url('log/clear_all') ?>";
        }
    });
}
</script>
