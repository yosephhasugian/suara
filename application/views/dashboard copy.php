<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= count(array_filter($signages, fn($s) => $s['status'] == 'active')) ?></h3>
                            <p>Perangkat Aktif</p>
                        </div>
                        <div class="icon"><i class="fas fa-tv"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= count($signages) ?></h3>
                            <p>Total Perangkat</p>
                        </div>
                        <div class="icon"><i class="fas fa-list"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= count($users) ?></h3>
                            <p>User Terdaftar</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Status Perangkat</h3>
                            <div class="card-tools">
                                <a href="<?= site_url('signage') ?>" class="btn btn-sm btn-primary">Kelola Perangkat</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php for($i = 1; $i <= 20; $i++): 
                                    $s = null;
                                    foreach($signages as $signage) {
                                        if ($signage['name'] == "TTPG MZ " . sprintf('%02d', $i)) {
                                            $s = $signage;
                                            break;
                                        }
                                    }
                                    $is_active = $s && $s['status'] == 'active';
                                ?>
                                <div class="col-6 col-md-3 mb-3">
                                    <div class="info-box <?= $is_active ? 'bg-success' : 'bg-secondary' ?>">
                                        <span class="info-box-icon">
                                            <i class="fas fa-tv"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">TTPG MZ <?= sprintf('%02d', $i) ?></span>
                                            <span class="info-box-number"><?= $is_active ? 'ONLINE' : 'OFFLINE' ?></span>
                                            <span class="info-box-text small"><?= $s ? $s['location'] : 'Lokasi Umum' ?></span>
                                        </div>
                                        <span class="info-box-icon">
                                            <button class="btn btn-xs btn-info" 
                                                    onclick="window.open('<?= site_url('player/view/'.$i) ?>','_blank')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('templates/footer'); ?>