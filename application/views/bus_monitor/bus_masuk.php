<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper p-3">
    <h4 class="mb-3">🔵 Admin Ambalat 1</h4>

    <div class="row">
        <!-- LIST BUS -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-list me-2"></i>Bus Memasuki Area Terminal
                </div>
                <div class="card-body" id="busList" style="max-height:70vh; overflow:auto;">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                        <p>Memuat data bus...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM INPUT BUS MASUK -->
<div class="col-md-5">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-info text-white">
            <i class="fas fa-bus me-2"></i>
            Input Bus Masuk Terminal
        </div>

        <div class="card-body">

            <form id="formBusMasuk">

                <!-- PLAT NOMOR -->
                <div class="mb-3">

                    <label class="fw-bold small text-muted">
                        Plat Nomor
                    </label>

                    <input
                        type="text"
                        id="plat_nomor"
                        name="plat_nomor"
                        class="form-control"
                        placeholder="Contoh: B 1234 XYZ"
                        autocomplete="off"
                        required
                    >

                </div>

                <!-- NAMA PO OTOMATIS -->
                <div class="mb-3">

                    <label class="fw-bold small text-muted">
                        Nama PO
                    </label>

                    <input
                        type="text"
                        id="nama_po"
                        class="form-control bg-light"
                        readonly
                        placeholder="Otomatis dari database"
                    >

                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="btn btn-primary w-100 fw-bold">

                    <i class="fas fa-plus-circle me-2"></i>
                    Tambah Bus

                </button>

            </form>

        </div>

    </div>

</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

// ===========================================
// LOAD BUS REALTIME
// ===========================================

function loadBus()
{
    $.ajax({

        url: BASE_URL + 'bus_monitor/get_bus_masuk',

        type: 'GET',

        dataType: 'json',

        success: function(res)
        {
            let html = '';

            // ================= KOSONG =================

            if(!res || res.length === 0){

                html = `
                    <div class="text-center text-muted py-5">

                        <i class="fas fa-bus fa-3x mb-3"></i>

                        <h5>Belum ada bus masuk</h5>

                    </div>
                `;

            } else {

                // ================= LOOP DATA =================

                res.forEach(bus => {

                    let jam = '--:--';

                    if(bus.created_at){

                        let dt = new Date(bus.created_at);

                        jam =
                            dt.getHours().toString().padStart(2,'0')
                            + ':'
                            + dt.getMinutes().toString().padStart(2,'0');
                    }

                    html += `

                        <div class="card mb-2 shadow-sm border-start border-4 border-primary">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <h5 class="mb-1 text-primary">
                                            ${bus.plat_nomor}
                                        </h5>

                                        <div class="text-muted">

                                            🏢 ${bus.nama_po ?? '-'}

                                        </div>

                                        <small class="text-muted">

                                            ⏰ ${jam} WIB

                                        </small>

                                    </div>

                                    <div>

                                        <span class="badge bg-success text-white">

                                            MASUK

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    `;
                });
            }

            $('#busList').html(html);
        },

        error: function()
        {
            $('#busList').html(`

                <div class="text-center text-danger py-5">

                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>

                    <h5>Gagal memuat data bus</h5>

                </div>

            `);
        }

    });
}

// ===========================================
// AUTO LOAD
// ===========================================

$(document).ready(function(){

    loadBus();

    setInterval(loadBus, 5000);

});

</script>
<script>

const BASE_URL = "<?= base_url(); ?>";

// ===========================================
// AUTO GET NAMA PO
// ===========================================

$('#plat_nomor').on('keyup', function(){

    let plat = $(this).val();

    if(plat.length < 3){
        $('#nama_po').val('');
        return;
    }

    $.ajax({

        url: BASE_URL + 'bus_monitor/get_po_by_plat',

        type: 'POST',

        data: {
            plat_nomor: plat
        },

        dataType: 'json',

        success: function(res){

            if(res.status){

                $('#nama_po').val(res.nama_po);

            } else {

                $('#nama_po').val('PO tidak ditemukan');

            }
        }
    });

});

// ===========================================
// SIMPAN BUS MASUK
// ===========================================

$('#formBusMasuk').submit(function(e){

    e.preventDefault();

    $.ajax({

        url: BASE_URL + 'bus_monitor/save_bus_masuk',

        type: 'POST',

        data: {
            plat_nomor: $('#plat_nomor').val()
        },

        dataType: 'json',

        success: function(res){

            if(res.status){

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Bus berhasil masuk terminal'
                });

                $('#formBusMasuk')[0].reset();

                loadBus();

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: res.message
                });

            }

        }

    });

});

</script>

<?php $this->load->view('templates/footer'); ?>