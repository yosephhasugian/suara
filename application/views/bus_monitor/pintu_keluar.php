<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper p-3">

<h4 class="mb-3">🚩 Admin Pintu Keluar (Gate Out)</h4>

<div class="row">

    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-danger text-white fw-bold">Daftar Bus Dalam Terminal</div>
            <div class="card-body" id="busList" style="max-height:75vh; overflow:auto; background: #fff5f5;"></div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow border-danger">
            <div class="card-header bg-danger text-white text-center fw-bold">KONFIRMASI BUS KELUAR</div>
            <div class="card-body text-center">

                <input type="hidden" id="id">
                
                <div class="mb-3">
                    <label class="fw-bold d-block text-muted">PLAT NOMOR</label>
                    <input type="text" id="nopol" class="form-control form-control-lg text-center fw-bold text-danger" readonly placeholder="KLIK LIST BUS">
                </div>

                <div class="mb-4">
                    <label class="fw-bold d-block text-muted">NAMA PO / TUJUAN</label>
                    <div id="info_bus" class="p-2 border rounded bg-light" style="min-height: 50px;">-</div>
                </div>

                <button class="btn btn-danger btn-lg w-100 fw-bold shadow" onclick="simpan()">
                    🚀 BUS LEPAS TERMINAL (KELUAR)
                </button>

            </div>
        </div>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const BASE_URL = "<?= base_url(); ?>";

function loadBus(){
    $.get(BASE_URL + 'bus_monitor/get_bus_today', function(res){
        let html = '';

        if(!res || res.length === 0){
            html = '<div class="text-center text-muted mt-5">Tidak ada bus di dalam terminal.</div>';
        } else {
            res.forEach(b => {
                // TAMPILKAN AREA KEDATANGAN, PENGENDAPAN, KEBERANGKATAN, DAN MASUK (kecuali 'berangkat')
                if(b.area !== 'berangkat' && b.plat_nomor) {
                    
                    let jam = '--:--';
                    if (b.created_at) {
                        let dt = new Date(b.created_at);
                        jam = dt.getHours().toString().padStart(2, '0') + ':' + dt.getMinutes().toString().padStart(2, '0');
                    }

                    let badgeColor = '';
                    let areaName = '';
                    let isBypass = (b.area === 'masuk') ? 1 : 0;
                    let customStyle = (b.area === 'masuk') 
                        ? 'opacity: 0.75; border-left: 5px solid #ffc107 !important; background-color: #fffdf5;' 
                        : 'border-left: 5px solid #28a745 !important;';

                    switch(b.area) {
                        case 'pengendapan': badgeColor = 'bg-dark'; areaName = 'PENGENDAPAN'; break;
                        case 'keberangkatan': badgeColor = 'bg-primary'; areaName = 'KEBERANGKATAN'; break;
                        case 'kedatangan': badgeColor = 'bg-info'; areaName = 'KEDATANGAN'; break;
                        case 'masuk': badgeColor = 'bg-warning text-dark'; areaName = 'MASUK (BELUM PELAYANAN)'; break;
                        default: badgeColor = 'bg-success'; areaName = 'MASUK';
                    }

                    html += `
                        <div class="card mb-2 shadow-sm border-0" style="cursor:pointer; ${customStyle}" 
                             onclick="pilih(${b.id}, '${b.plat_nomor}', '${b.nama_po ?? '-'}', '${b.tujuan ?? '-'}', ${isBypass}, '${jam}')">
                            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-dark" style="font-size: 1.2rem;">${b.plat_nomor}</strong><br>
                                    <small class="text-muted text-uppercase">${b.nama_po ?? '-'}</small><br>
                                    <small class="text-muted"><i class="far fa-clock me-1"></i> Masuk: ${jam} WIB</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge ${badgeColor} text-white">${areaName}</span>
                                    <div class="small text-danger fw-bold mt-1">${b.tujuan || '-'}</div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
        }
        $('#busList').html(html);
    }, 'json');
}

function pilih(id, nopol, po, tujuan, isBypass, jam){
    if (isBypass) {
        Swal.fire({
            title: 'Bypass Dicegah!',
            text: 'Bus ' + nopol + ' berstatus MASUK dan belum melewati area pelayanan (Kedatangan / Pengendapan / Keberangkatan). Silakan proses bus di area pelayanan terlebih dahulu!',
            icon: 'warning',
            confirmButtonColor: '#dc3545'
        });
        $('#id').val('');
        $('#nopol').val('');
        $('#info_bus').html('-');
        return;
    }
    $('#id').val(id);
    $('#nopol').val(nopol);
    $('#info_bus').html(`<strong>${po}</strong><br><span class="text-primary">${tujuan}</span><br><small class="text-muted"><i class="far fa-clock me-1"></i> Masuk: ${jam} WIB</small>`);
}

function simpan(){
    let id = $('#id').val();
    let nopol = $('#nopol').val();

    if(!id){
        Swal.fire('Pilih Bus', 'Bus belum dipilih!', 'warning');
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Keluar',
        text: "Bus " + nopol + " akan keluar terminal?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Keluar!'
    }).then((result) => {
        if(result.isConfirmed){

            $.post(BASE_URL + 'bus_monitor/update_bus', {
                id: id,
                tujuan: '',
                area: 'berangkat'
            }, function(res){
                if(res.status){
                    Swal.fire('Berhasil!', 'Bus keluar terminal', 'success');

                    $('#id').val('');
                    $('#nopol').val('');
                    $('#info_bus').html('-');

                    loadBus();
                } else {
                    Swal.fire('Gagal!', res.message, 'error');
                }
            }, 'json');

        }
    });
}

$(document).ready(function(){
    loadBus();
    setInterval(loadBus, 5000);
});
</script>

<?php $this->load->view('templates/footer'); ?>