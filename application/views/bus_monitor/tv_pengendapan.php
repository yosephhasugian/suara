<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DISPLAY PENGENDAPAN - PULO GEBANG' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@500;900&display=swap" rel="stylesheet">
    <style>
        body { background: #000; color: white; font-family: 'Arial', sans-serif; text-transform: uppercase; overflow: hidden; height: 100vh; }
        .header { background: #111; border-bottom: 3px solid #6c757d; padding: 10px 25px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 26px; margin: 0; color: #6c757d; font-weight: 900; letter-spacing: 1px; }
        #tanggal { font-family: 'Arial', sans-serif; font-size: 16px; color: #fff; }
        .table-container { padding: 10px; }
        .table { font-family: 'Arial', sans-serif !important; color: white; margin-bottom: 0; border: 1px solid #333; }
        .table thead th { background-color: #222 !important; color: #6c757d !important; font-size: 18px; padding: 10px; border: 1px solid #444; text-align: center; }
        .table tbody td { padding: 8px 6px !important; font-size: 19px; font-weight: 700; border: 1px solid #222; vertical-align: middle; }
        .plat-nomor { color: #ffc107; font-family: 'Arial', sans-serif; font-size: 22px !important; }
        .tujuan { color: #00ff88; }
        .waktu { font-family: 'Arial', sans-serif; color: #00d4ff; }
        .status-badge { font-size: 14px; padding: 5px 10px; border-radius: 4px; display: inline-block; width: 100%; font-weight: 900; text-align: center; box-shadow: inset 0 0 5px rgba(0,0,0,0.5); }
        .area-pengendapan { background: #6c757d; color: white; }
        tbody tr:nth-child(even) { background: rgba(108,117,125,0.1); }
        tbody tr:hover { background: rgba(108,117,125,0.2); }
    </style>
</head>
<body>
<div class="header">
    <h1>⚪ AREA PENGENDAPAN - PULO GEBANG</h1>
    <div id="tanggal"></div>
</div>
<div class="table-container">
    <table class="table table-sm">
        <thead>
            <tr>
                <th width="4%">NO</th>
                <th width="18%">PLAT NOMOR</th>
                <th width="30%">NAMA PERUSAHAAN (PO)</th>
                <th width="28%">TUJUAN AKHIR</th>
                <th width="10%">JAM PARKIR</th>
                <th width="10%">STATUS</th>
            </tr>
        </thead>
        <tbody id="tvTable" class="text-center"></tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const BASE_URL = "<?= base_url(); ?>";
const AREA_CODE = "pengendapan";
const BADGE_CLASS = "area-pengendapan";
const STATUS_TEXT = "PARKIR / ISTIRAHAT";

setInterval(() => {
    let d = new Date();
    document.getElementById('tanggal').innerText = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' }) + " | " + d.toLocaleTimeString('id-ID');
}, 1000);

function loadTV() {
    $.get(BASE_URL + 'bus_monitor/get_tv_pengendapan', function(res) {
        let html = '';
        let no = 1;
        if (!res || res.length === 0) {
            html = '<tr><td colspan="6" class="p-5 text-muted">TIDAK ADA BUS DI AREA PENGENDAPAN</td></tr>';
        } else {
            res.forEach(b => {
                if (b.plat_nomor && b.plat_nomor.trim() !== "") {
                    let jam = '--:--';
                    if (b.created_at) {
                        let dt = new Date(b.created_at);
                        jam = dt.getHours().toString().padStart(2, '0') + ":" + dt.getMinutes().toString().padStart(2, '0');
                    }
                    html += `<tr>
                        <td class="text-muted small">${no++}</td>
                        <td class="plat-nomor">${b.plat_nomor}</td>
                        <td class="text-start ps-4">${b.nama_po || '-'}</td>
                        <td class="tujuan text-start ps-4">${b.tujuan || 'belum lapor'}</td>
                        <td class="waktu">${jam}</td>
                        <td><div class="status-badge ${BADGE_CLASS}">${STATUS_TEXT}</div></td>
                    </tr>`;
                }
            });
        }
        $('#tvTable').html(html);
    }, 'json').fail(() => console.error("Gagal mengambil data server."));
}
setInterval(loadTV, 5000);
loadTV();
</script>
</body>
</html>