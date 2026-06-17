<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper p-3">

    <!-- ===================================================== -->
    <!-- HEADER -->
    <!-- ===================================================== -->
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded shadow-sm border">
        <div>
            <h2 class="m-0 font-weight-bold text-dark">
                🚌 Pergerakan Bus AKAP
            </h2>
            <small class="text-muted">
                Monitoring Operasional Bus AKAP Real-time
            </small>
        </div>

        <div class="text-right">
            <h4 id="clock" class="m-0 text-primary font-weight-bold">
            </h4>
            <small class="text-muted font-weight-semibold">
                <?= date('d F Y') ?>
            </small>
        </div>
    </div>

    <!-- ===================================================== -->
    <!-- KPI HARIAN (GRADIENT CARDS) -->
    <!-- ===================================================== -->
    <div class="row">
        <?php
        $cards = [
            [
                'title' => 'TOTAL MASUK',
                'value' => $count_masuk,
                'icon'  => 'fa-sign-in-alt',
                'color1'=> '#2563eb',
                'color2'=> '#1d4ed8'
            ],
            [
                'title' => 'KEDATANGAN',
                'value' => $count_kedatangan,
                'icon'  => 'fa-map-marker-alt',
                'color1'=> '#06b6d4',
                'color2'=> '#0891b2'
            ],
            [
                'title' => 'PENGENDAPAN',
                'value' => $count_pengendapan,
                'icon'  => 'fa-parking',
                'color1'=> '#f59e0b',
                'color2'=> '#d97706'
            ],
            [
                'title' => 'KEBERANGKATAN',
                'value' => $count_keberangkatan,
                'icon'  => 'fa-bus',
                'color1'=> '#22c55e',
                'color2'=> '#15803d'
            ],
            [
                'title' => 'KELUAR',
                'value' => $count_keluar,
                'icon'  => 'fa-door-open',
                'color1'=> '#ef4444',
                'color2'=> '#b91c1c'
            ],
            [
                'title' => 'BUS AKTIF',
                'value' => $count_aktif,
                'icon'  => 'fa-satellite-dish',
                'color1'=> '#8b5cf6',
                'color2'=> '#6d28d9'
            ]
        ];

        foreach($cards as $c):
        ?>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-white"
                 style="background: linear-gradient(135deg, <?= $c['color1'] ?>, <?= $c['color2'] ?>);">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50 font-weight-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <?= $c['title'] ?>
                            </small>
                            <h2 class="font-weight-bold mb-0 mt-1">
                                <?= $c['value'] ?>
                            </h2>
                        </div>
                        <div class="text-white-50">
                            <i class="fas <?= $c['icon'] ?> fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ===================================================== -->
    <!-- STATUS AREA & AKTIVITAS -->
    <!-- ===================================================== -->
    <div class="row mt-3">

        <!-- STATUS AREA -->
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-bottom bg-white py-3">
                    <h5 class="text-primary font-weight-bold m-0">
                        📡 Status Area Realtime
                    </h5>
                    <small class="text-muted">
                        Posisi aktif bus saat ini di dalam terminal
                    </small>
                </div>

                <div class="card-body">
                    <?php
                    $areas = [
                        [
                            'emoji' => '📥',
                            'title' => 'KEDATANGAN',
                            'data'  => $kedatangan
                        ],
                        [
                            'emoji' => '🅿️',
                            'title' => 'PENGENDAPAN',
                            'data'  => $pengendapan
                        ],
                        [
                            'emoji' => '🟢',
                            'title' => 'KEBERANGKATAN',
                            'data'  => $keberangkatan
                        ]
                    ];

                    foreach($areas as $a):
                        $bgColor =
                            $a['data']['color'] == 'danger' ? '#ef4444' :
                            ($a['data']['color'] == 'warning' ? '#f59e0b' :
                            ($a['data']['color'] == 'info' ? '#06b6d4' :
                            '#22c55e'));
                    ?>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold text-dark text-sm">
                                <?= $a['emoji'] ?> <?= $a['title'] ?>
                            </span>
                            <span class="badge badge-<?= $a['data']['color'] ?> px-2 py-1" style="font-size: 0.75rem;">
                                <?= $a['data']['status'] ?>
                            </span>
                        </div>

                        <h3 class="mt-2 font-weight-bold text-dark">
                            <?= $a['data']['jumlah'] ?> / <?= $a['data']['kapasitas'] ?> <span class="text-sm text-muted font-weight-normal">BUS</span>
                        </h3>

                        <div class="progress" style="height: 12px; border-radius: 10px; background: #e9ecef; overflow: hidden;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 style="width: <?= $a['data']['persen'] ?>%; background: <?= $bgColor ?>;">
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block text-right font-weight-bold"><?= $a['data']['persen'] ?>% Kapasitas Terisi</small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- AKTIVITAS -->
        <div class="col-lg-8 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-bottom bg-white py-3">
                    <h5 class="text-success font-weight-bold m-0">
                        ⚡ Aktivitas Terminal Realtime
                    </h5>
                    <small class="text-muted">
                        Riwayat mutasi area bus terbaru hari ini
                    </small>
                </div>

                <div class="card-body p-0">
                    <?php if(!empty($aktivitas)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 text-sm">
                                <thead>
                                    <tr class="bg-light text-muted font-weight-bold text-xs">
                                        <th style="width: 60px;" class="text-center">IKON</th>
                                        <th>NAMA PERUSAHAAN (PO)</th>
                                        <th>PLAT NOMOR</th>
                                        <th>TUJUAN</th>
                                        <th class="text-center">AREA SEKARANG</th>
                                        <th class="text-center">WAKTU UPDATE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($aktivitas as $a): ?>
                                        <?php
                                        $icon = '🚍';
                                        $badge = 'secondary';

                                        if($a->area == 'kedatangan'){
                                            $icon = '🛬';
                                            $badge = 'info';
                                        }
                                        elseif($a->area == 'pengendapan'){
                                            $icon = '🅿️';
                                            $badge = 'warning';
                                        }
                                        elseif($a->area == 'keberangkatan'){
                                            $icon = '🟢';
                                            $badge = 'success';
                                        }
                                        elseif($a->area == 'berangkat'){
                                            $icon = '🚪';
                                            $badge = 'danger';
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-center" style="font-size: 1.2rem;"><?= $icon ?></td>
                                            <td class="font-weight-bold text-dark"><?= htmlspecialchars($a->nama_po) ?></td>
                                            <td><code class="px-2 py-1 bg-light rounded text-dark font-weight-bold text-xs"><?= htmlspecialchars($a->plat_nomor) ?></code></td>
                                            <td class="text-muted font-weight-semibold"><?= htmlspecialchars($a->tujuan ?? 'Belum Lapor') ?></td>
                                            <td class="text-center">
                                                <span class="badge badge-<?= $badge ?> px-2 py-1 text-uppercase" style="font-size: 0.75rem;">
                                                    <?= htmlspecialchars($a->area) ?>
                                                </span>
                                            </td>
                                            <td class="text-center text-muted font-weight-medium">
                                                <?= date('H:i', strtotime($a->area_updated_at ?? $a->created_at ?? date('Y-m-d H:i:s'))) ?> WIB
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bus-alt fa-3x mb-3 text-light"></i>
                            <p class="mb-0 font-weight-semibold">Belum ada aktivitas terminal hari ini</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function updateClock() {
    const now = new Date();
    document.getElementById('clock').innerHTML = now.toLocaleTimeString('id-ID');
}
setInterval(updateClock, 1000);
updateClock();
</script>

<?php $this->load->view('templates/footer'); ?>