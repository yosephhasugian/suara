<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper text-white p-3"
     style="min-height:100vh; background:#0f172a;">

    <!-- ===================================================== -->
    <!-- HEADER -->
    <!-- ===================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded"
         style="
            background:linear-gradient(90deg,#111827,#1e293b);
            border:1px solid #334155;
         ">

        <div>
            <h2 class="m-0 font-weight-bold">
                🚌 PERGERAKAN BUS AKAP
            </h2>

            <small class="text-muted">
                Monitoring Operasional Bus AKAP Real-time
            </small>
        </div>

        <div class="text-right">
            <h4 id="clock"
                class="m-0 text-info font-weight-bold">
            </h4>

            <small>
                <?= date('d F Y') ?>
            </small>
        </div>

    </div>

    <!-- ===================================================== -->
    <!-- KPI HARIAN -->
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
                'value' =>
                    $count_kedatangan +
                    $count_pengendapan +
                    $count_keberangkatan,

                'icon'  => 'fa-satellite-dish',
                'color1'=> '#8b5cf6',
                'color2'=> '#6d28d9'
            ]

        ];

        foreach($cards as $c):

        ?>

        <div class="col-xl-2 col-md-4 col-6 mb-3">

            <div class="card border-0 shadow h-100"
                 style="
                    background:
                    linear-gradient(
                        135deg,
                        <?= $c['color1'] ?>,
                        <?= $c['color2'] ?>
                    );
                 ">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-light">
                                <?= $c['title'] ?>
                            </small>

                            <h2 class="font-weight-bold mb-0">
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
    <!-- STATUS + AKTIVITAS -->
    <!-- ===================================================== -->

    <div class="row mt-4">

        <!-- STATUS AREA -->
        <div class="col-lg-4 mb-3">

            <div class="card border-0 shadow-lg h-100"
                 style="background:#111827;">

                <div class="card-header border-0 bg-transparent">

                    <h5 class="text-warning m-0">
                        📡 STATUS AREA REALTIME
                    </h5>

                    <small class="text-muted">
                        Posisi aktif bus saat ini
                    </small>

                </div>

                <div class="card-body">

                    <?php

                    $areas = [

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

                            <span class="font-weight-bold">

                                <?= $a['emoji'] ?>

                                <?= $a['title'] ?>

                            </span>

                            <span class="badge badge-<?= $a['data']['color'] ?>">

                                <?= $a['data']['status'] ?>

                            </span>

                        </div>

                        <h3 class="mt-2 font-weight-bold">

                            <?= $a['data']['jumlah'] ?>

                            /

                            <?= $a['data']['kapasitas'] ?>

                            BUS

                        </h3>

                        <div class="progress"
                             style="
                                height:18px;
                                border-radius:20px;
                                background:#1e293b;
                                overflow:hidden;
                             ">

                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 style="
                                    width:<?= $a['data']['persen'] ?>%;
                                    background:<?= $bgColor ?>;
                                 ">

                                <?= $a['data']['persen'] ?>%

                            </div>

                        </div>

                    </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

        <!-- AKTIVITAS -->
        <div class="col-lg-8 mb-3">

            <div class="card border-0 shadow-lg h-100"
                 style="background:#111827;">

                <div class="card-header border-0 bg-transparent">

                    <h5 class="text-success m-0">
                        ⚡ AKTIVITAS TERMINAL REALTIME
                    </h5>

                    <small class="text-muted">
                        Aktivitas bus terbaru hari ini
                    </small>

                </div>

                <div class="card-body">

                    <?php if(!empty($aktivitas)): ?>

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

                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                                <div>

                                    <div class="font-weight-bold">

                                        <?= $icon ?>

                                        <?= $a->nama_po ?>

                                        <small class="text-muted">
                                            (<?= $a->plat_nomor ?>)
                                        </small>

                                    </div>

                                    <small class="text-muted">

                                        Tujuan:
                                        <?= $a->tujuan ?>

                                    </small>

                                </div>

                                <div class="text-right">

                                    <span class="badge badge-<?= $badge ?>">

                                        <?= strtoupper($a->area) ?>

                                    </span>

                                    <br>

                                    <small class="text-muted">

                                        <?= date('H:i', strtotime($a->area_updated_at)) ?> WIB

                                    </small>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="text-center py-5 text-muted">

                            Belum ada aktivitas terminal hari ini

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ===================================================== -->
<!-- CLOCK -->
<!-- ===================================================== -->

<script>

function updateClock()
{
    const now = new Date();

    document.getElementById('clock').innerHTML =
        now.toLocaleTimeString('id-ID');
}

setInterval(updateClock, 1000);

updateClock();

</script>

<?php $this->load->view('templates/footer'); ?>