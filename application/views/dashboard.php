<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper p-4" style="background: #f4f6f9;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fas fa-bus-alt mr-2 text-primary"></i> Dashboard Monitoring Bus AKAP</h4>
        <span class="badge badge-pill badge-white shadow-sm p-2 px-3 bg-white"><?= date('d F Y') ?></span>
    </div>

    <!-- STATISTIC CARDS (Versi Berwarna & Icon) -->
    <div class="row">
        <?php 
        $stats = [
            ['label' => 'Bus Masuk', 'val' => $count_masuk, 'color' => 'primary', 'icon' => 'fa-sign-in-alt'],
            ['label' => 'Kedatangan', 'val' => $count_kedatangan, 'color' => 'info', 'icon' => 'fa-map-marker-alt'],
            ['label' => 'Pengendapan', 'val' => $count_pengendapan, 'color' => 'warning', 'icon' => 'fa-parking'],
            ['label' => 'Keberangkatan', 'val' => $count_keberangkatan, 'color' => 'success', 'icon' => 'fa-share-square'],
            ['label' => 'Keluar', 'val' => $count_keluar, 'color' => 'danger', 'icon' => 'fa-door-open']
        ];
        foreach($stats as $s): ?>
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small font-weight-bold"><?= $s['label'] ?></p>
                            <h3 class="mb-0 font-weight-bold text-<?= $s['color'] ?>"><?= $s['val'] ?></h3>
                        </div>
                        <div class="text-<?= $s['color'] ?> opacity-50">
                            <i class="fas <?= $s['icon'] ?> fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- TABLE REPORT (Versi Bersih & Polos) -->
    <div class="card border-0 shadow-sm mt-2">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-table mr-2"></i> Laporan Pergerakan Bulanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabelBulanan">
                    <thead class="bg-light text-center">
                        <tr class="text-dark">
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Kedatangan</th>
                            <th>Pengendapan</th>
                            <th>Keberangkatan</th>
                            <th>Keluar</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php foreach($laporan_bulanan as $row): ?>
                        <tr <?= ($row['tanggal'] == date('Y-m-d')) ? 'style="background-color: #fff9e6;"' : '' ?>>
                            <td class="text-left font-weight-bold"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td><?= $row['masuk'] ?></td>
                            <td><?= $row['kedatangan'] ?></td>
                            <td><?= $row['pengendapan'] ?></td>
                            <td><?= $row['keberangkatan'] ?></td>
                            <td><?= $row['keluar'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script DataTable -->
<script>
$(document).ready(function() {
    $('#tabelBulanan').DataTable({
        "pageLength": 10,
        "ordering": false,
        "info": true,
        "searching": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>

<?php $this->load->view('templates/footer'); ?>
