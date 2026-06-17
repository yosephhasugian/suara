<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper p-3">
    <h4 class="mb-3">🔵 Admin Kedatangan</h4>

    <div class="row">
        <!-- LIST BUS -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-list me-2"></i>Bus Menunggu Kedatangan
                </div>
                <div class="card-body" id="busList" style="max-height:70vh; overflow:auto;">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                        <p>Memuat data bus...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM INPUT -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-arrow-down me-2"></i>Input Kedatangan
                </div>
                <div class="card-body">
                    <input type="hidden" id="id">

                    <label class="fw-bold small text-muted">Plat Nomor</label>
                    <input type="text" id="nopol" class="form-control mb-3" 
                           placeholder="Ketik atau pilih plat..." 
                           list="nopolList" autocomplete="off">
                    <datalist id="nopolList"></datalist>

                    <label class="fw-bold small text-muted">Asal / Tujuan</label>
                    <input type="text" id="tujuan" class="form-control mb-3" 
                           placeholder="Contoh: Jakarta, Surabaya...">

                    <button class="btn btn-success w-100 py-2 fw-bold" onclick="simpan()">
                        <i class="fas fa-check-circle me-2"></i>✔️ Konfirmasi Kedatangan
                    </button>
                    
                    <button class="btn btn-outline-secondary w-100 mt-2" onclick="resetForm()">
                        <i class="fas fa-undo me-2"></i>Reset Form
                    </button>
                </div>
            </div>

            <!-- INFO BOX -->
            <div class="alert alert-info mt-3 small">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Info:</strong> List hanya menampilkan bus yang BELUM di area kedatangan. 
                Bus yang sudah dikonfirmasi akan otomatis hilang dari list.
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const BASE_URL = "<?= base_url(); ?>";
const CURRENT_AREA = "kedatangan"; // ✨ AREA: KEDATANGAN

$(document).ready(function() {
    loadBus(); 
    setInterval(loadBus, 5000);
});

// ================= LOAD BUS (FILTER: Jangan tampilkan yang sudah di area kedatangan) =================
function loadBus(){
    $.get(BASE_URL + 'bus_monitor/get_bus_for_form/' + CURRENT_AREA, function(res){
        let html = '';
        let options = '';

        if(!res || res.length === 0){
            html = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <h5>Semua bus sudah diproses!</h5>
                    <small class="d-block">Tidak ada bus menunggu di area ${CURRENT_AREA}</small>
                </div>`;
        } else {
            res.forEach(b => {
                if(b.plat_nomor && b.plat_nomor.trim() !== "") {
                    let badgeColor = 'bg-secondary';
                    if(b.area == 'masuk') badgeColor = 'bg-success';
                    if(b.area == 'pengendapan') badgeColor = 'bg-dark';
                    if(b.area == 'keberangkatan') badgeColor = 'bg-primary';
                    
                    let jam = '--:--';
                    if(b.created_at) {
                        let dt = new Date(b.created_at);
                        jam = dt.getHours().toString().padStart(2,'0') + ':' + dt.getMinutes().toString().padStart(2,'0');
                    }

                    html += `
                        <div class="card mb-2 shadow-sm border-start border-4 border-info" 
                             style="cursor:pointer; transition:all 0.2s;"
                             onclick="pilih('${b.id}', '${b.plat_nomor}', '${b.tujuan ?? ''}')">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-primary fs-5">${b.plat_nomor}</strong>
                                        <div class="text-muted small">${b.nama_po ?? '-'}</div>
                                        <div class="text-muted small">🎯 ${b.tujuan ?? 'Belum ditentukan'}</div>
                                        <div class="text-muted small">⏰ Masuk: ${jam}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge ${badgeColor} text-white text-uppercase mb-2">
                                            ${b.area ?? 'Antre'}
                                        </span>
                                        <br>
                                        <small class="text-muted">Klik untuk pilih</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    options += `<option value="${b.plat_nomor}">`;
                }
            });
        }

        $('#busList').html(html);
        $('#nopolList').html(options);

    }, 'json').fail(function(xhr){
        console.error("❌ Error load bus:", xhr.status);
        $('#busList').html(`
            <div class="text-center text-danger py-5">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <p>Gagal memuat data</p>
                <button class="btn btn-sm btn-outline-danger" onclick="loadBus()">Coba Lagi</button>
            </div>
        `);
    });
}

// ================= PILIH BUS =================
function pilih(id, nopol, tujuan){
    $('#id').val(id);
    $('#nopol').val(nopol);
    $('#tujuan').val(tujuan);
    
    // Visual feedback
    $('.card[onclick*="'+id+'"]').addClass('border-success').siblings().removeClass('border-success');
    
    setTimeout(() => {
        document.querySelector('button[onclick="simpan()"]')?.focus();
    }, 100);
}

// ================= SIMPAN / KONFIRMASI =================
function simpan(){
    let id = $('#id').val();
    let nopol = $('#nopol').val().trim();
    let tujuan = $('#tujuan').val().trim();

    if(!id || !nopol || !tujuan){
        Swal.fire({
            icon: 'warning',
            title: 'Data Belum Lengkap',
            text: 'Plat Nomor dan Tujuan wajib diisi!',
            confirmButtonColor: '#0dcaf0'
        });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Kedatangan?',
        html: `
            <div class="text-start">
                <strong>🚌 ${nopol}</strong><br>
                <strong>🎯 ${tujuan}</strong><br>
                <small class="text-muted">Bus akan dipindah ke area: <strong>kedatangan</strong></small>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Ya, Konfirmasi!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0dcaf0',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if(result.isConfirmed) {
            $.ajax({
                url: BASE_URL + 'bus_monitor/update_bus',
                type: 'POST',
                data: { 
                    id: id, 
                    tujuan: tujuan,
                    area: 'kedatangan'
                },
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function(res){
                    if(res.status){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil! 🎉',
                            text: 'Bus ' + nopol + ' sudah dikonfirmasi kedatangan',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        resetForm();
                        loadBus();
                    } else {
                        Swal.fire('Error!', res.message || 'Gagal update data', 'error');
                    }
                },
                error: function(xhr){
                    Swal.fire('Error!', 'Koneksi gagal: ' + xhr.status, 'error');
                }
            });
        }
    });
}

// ================= RESET FORM =================
function resetForm(){
    $('#id').val('');
    $('#nopol').val('');
    $('#tujuan').val('');
    $('.card').removeClass('border-success');
}

// ================= KEYBOARD SHORTCUT =================
document.addEventListener('keydown', function(e){
    if(e.key === 'Enter' && $('#id').val() && !e.target.matches('textarea')){
        e.preventDefault();
        simpan();
    }
    if(e.key === 'Escape'){
        resetForm();
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>