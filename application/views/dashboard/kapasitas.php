<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-weight-bold m-0 text-dark">
                    <i class="fas fa-warehouse text-success mr-2"></i> Master Data Kapasitas Area
                </h4>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                </a>
            </div>

            <!-- TABLE CARD -->
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-gradient-success text-white py-3">
                    <h5 class="card-title font-weight-bold m-0">
                        <i class="fas fa-sliders-h mr-2"></i> Pengaturan Batas Kapasitas Terminal
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="bg-light text-secondary text-uppercase text-xs font-weight-bold">
                                <tr>
                                    <th class="py-3 px-4" style="width: 80px;">No</th>
                                    <th class="py-3 px-4">Nama Area Pelayanan</th>
                                    <th class="py-3 px-4" style="width: 250px;">Batas Kapasitas (Bus)</th>
                                    <th class="py-3 px-4" style="width: 150px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach($capacities as $c): 
                                    $areaDisplay = '';
                                    $badgeColor = '';
                                    switch($c->area) {
                                        case 'kedatangan':
                                            $areaDisplay = '📥 KEDATANGAN';
                                            $badgeColor = 'badge-info';
                                            break;
                                        case 'pengendapan':
                                            $areaDisplay = '🅿️ PENGENDAPAN';
                                            $badgeColor = 'badge-dark';
                                            break;
                                        case 'keberangkatan':
                                            $areaDisplay = '🟢 KEBERANGKATAN';
                                            $badgeColor = 'badge-primary';
                                            break;
                                        default:
                                            $areaDisplay = strtoupper($c->area);
                                            $badgeColor = 'badge-secondary';
                                    }
                                ?>
                                <tr>
                                    <td class="py-3 px-4 font-weight-bold text-muted"><?= $no++ ?></td>
                                    <td class="py-3 px-4">
                                        <span class="badge <?= $badgeColor ?> px-3 py-2 text-sm shadow-sm" style="border-radius: 30px;">
                                            <?= $areaDisplay ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-weight-bold text-dark text-lg">
                                        <?= $c->capacity ?> <span class="text-xs text-muted font-weight-normal">BUS</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <button class="btn btn-warning btn-sm fw-bold px-3 py-2 shadow-sm text-dark btn-edit"
                                                onclick="editCapacity('<?= $c->area ?>', <?= $c->capacity ?>)"
                                                style="border-radius: 8px;">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- INFO CARD -->
            <div class="alert alert-info border-0 shadow-sm mt-3" style="border-radius: 12px; background: rgba(23, 162, 184, 0.08); border-left: 5px solid #17a2b8 !important;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle text-info fa-2x mr-3"></i>
                    <div>
                        <strong class="text-info">Petunjuk Penggunaan:</strong>
                        <p class="text-muted mb-0 small">
                            Mengubah kapasitas di sini akan secara real-time memperbarui kapasitas batas atas (denominator) serta perhitungan persentase keterisian progress bar pada dashboard pemantauan bus.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function editCapacity(area, currentCapacity) {
    Swal.fire({
        title: 'Ubah Kapasitas Area',
        html: `
            <div class="text-left mb-2">
                <label class="font-weight-bold mb-1">Area: <span class="text-primary text-uppercase">${area}</span></label>
            </div>
        `,
        input: 'number',
        inputValue: currentCapacity,
        inputPlaceholder: 'Masukkan angka kapasitas...',
        inputAttributes: {
            min: 1,
            step: 1,
            autofocus: 'true'
        },
        showCancelButton: true,
        confirmButtonText: '💾 Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        inputValidator: (value) => {
            if (!value || isNaN(value) || parseInt(value) <= 0) {
                return 'Kapasitas harus berupa angka bulat positif!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let newCap = parseInt(result.value);
            $.ajax({
                url: '<?= site_url("dashboard/update_capacity") ?>',
                type: 'POST',
                data: {
                    area: area,
                    capacity: newCap
                },
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function(res) {
                    if (res.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil! 🎉',
                            text: 'Kapasitas area ' + area.toUpperCase() + ' diubah menjadi ' + newCap + ' bus.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', res.message || 'Gagal mengubah kapasitas', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Koneksi gagal: ' + xhr.status, 'error');
                }
            });
        }
    });
}
</script>

<?php $this->load->view('templates/footer'); ?>
