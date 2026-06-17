<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>
<style>
.card:hover {
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
}
</style>
<div class="content-wrapper p-3">

<h4 class="mb-3">🚍 Monitoring Bus</h4>

<div class="row">

    <!-- ================= KIRI ================= -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning">
                🟡 Bus Masuk Hari Ini
            </div>
            <div class="card-body" id="busList" style="max-height:70vh; overflow:auto;"></div>
        </div>
    </div>

    <!-- ================= KANAN ================= -->
    <div class="col-md-6">

    <!-- 🔵 KEBERANGKATAN -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Admin Keberangkatan</div>
        <div class="card-body">
            <input type="hidden" id="id_keberangkatan">
            <input type="text" id="nopol_keberangkatan" class="form-control mb-2" placeholder="Plat nomor">
            <input type="text" id="tujuan_keberangkatan" class="form-control mb-2" placeholder="Tujuan">
            <button class="btn btn-primary w-100" onclick="kirimKeberangkatan()">✔️ Simpan</button>
        </div>
    </div>

    <!-- 🟢 KEDATANGAN -->
    <div class="card mb-3">
        <div class="card-header bg-success text-white">Admin Kedatangan</div>
        <div class="card-body">
            <input type="hidden" id="id_kedatangan">
            <input type="text" id="nopol_kedatangan" class="form-control mb-2">
            <input type="text" id="tujuan_kedatangan" class="form-control mb-2">
            <button class="btn btn-success w-100" onclick="kirimKedatangan()">✔️ Simpan</button>
        </div>
    </div>

    <!-- 🟡 PENGENDAPAN -->
    <div class="card mb-3">
        <div class="card-header bg-warning">Admin Pengendapan</div>
        <div class="card-body">
            <input type="hidden" id="id_pengendapan">
            <input type="text" id="nopol_pengendapan" class="form-control mb-2">
            <button class="btn btn-warning w-100" onclick="kirimPengendapan()">⏳ Masukkan Pengendapan</button>
        </div>
    </div>

    <!-- 🔴 PINTU KELUAR -->
    <div class="card">
        <div class="card-header bg-danger text-white">Pintu Keluar (Final)</div>
        <div class="card-body">
            <input type="hidden" id="id_keluar">
            <input type="text" id="nopol_keluar" class="form-control mb-2">
            <button class="btn btn-danger w-100" onclick="kirimKeluar()">🚪 Konfirmasi Keluar</button>
        </div>
    </div>

</div>

</div>

</div>

<script>
const BASE_URL = "<?= base_url(); ?>";

// ================= LOAD BUS =================
function loadBus(){
    $.get(BASE_URL + 'bus_monitor/get_bus_today', function(res){

        let html = '';

        res.forEach(b => {

            let warna = 'secondary';
            let statusText = 'DALAM TERMINAL';

            if(b.area === 'keberangkatan'){
                warna = 'primary';
                statusText = 'KEBERANGKATAN';
            }
            else if(b.area === 'kedatangan'){
                warna = 'success';
                statusText = 'KEDATANGAN';
            }
            else if(b.area === 'pengendapan'){
                warna = 'warning';
                statusText = 'PENGENDAPAN';
            }
            else if(b.area === 'berangkat'){
                warna = 'danger';
                statusText = 'SUDAH KELUAR';
            }

            html += `
                <div class="card mb-2 shadow-sm border-${warna}" 
                     style="cursor:pointer; transition:0.2s"
                     onclick="pilihBus(${b.id}, '${b.plat_nomor}', '${b.nama_po}')"
                     onmouseover="this.style.transform='scale(1.02)'"
                     onmouseout="this.style.transform='scale(1)'">

                    <div class="card-body p-2">

                        <div class="d-flex justify-content-between">
                            <strong>${b.plat_nomor || '-'}</strong>
                            <span class="badge bg-${warna}">${statusText}</span>
                        </div>

                        <small>${b.nama_po || '-'}</small><br>

                        <span class="text-muted">${b.tujuan || '-'}</span>

                    </div>
                </div>
            `;
        });

        $('#busList').html(html);

    }, 'json');
}

// ================= PILIH BUS =================
function pilihBus(id, nopol, po){

    $('#id_keberangkatan').val(id);
    $('#nopol_keberangkatan').val(nopol);

    $('#id_kedatangan').val(id);
    $('#nopol_kedatangan').val(nopol);

    $('#id_pengendapan').val(id);
    $('#nopol_pengendapan').val(nopol);

    $('#id_keluar').val(id);
    $('#nopol_keluar').val(nopol);
}

// ================= ACTION =================
function kirimKeberangkatan(){
    kirimUpdate($('#id_keberangkatan').val(), $('#tujuan_keberangkatan').val(), 'keberangkatan');
}

function kirimKedatangan(){
    kirimUpdate($('#id_kedatangan').val(), $('#tujuan_kedatangan').val(), 'kedatangan');
}

function kirimPengendapan(){
    kirimUpdate($('#id_pengendapan').val(), '', 'pengendapan');
}

function kirimKeluar(){
    kirimUpdate($('#id_keluar').val(), '', 'berangkat');
}

// ================= CORE UPDATE =================
function kirimUpdate(id, tujuan, area){

    if(!id){
        alert('Pilih bus dulu!');
        return;
    }

    $.post(BASE_URL + 'bus_monitor/update_bus', {
        id: id,
        tujuan: tujuan,
        area: area
    }, function(res){
        if(res.status){
            loadBus();
            // reset input
            $('input').val('');
        } else {
            alert(res.message);
        }
    }, 'json');
}

// AUTO REFRESH
setInterval(loadBus, 3000);
loadBus();
</script>

<?php $this->load->view('templates/footer'); ?>