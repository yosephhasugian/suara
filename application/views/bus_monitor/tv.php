<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DISPLAY BUS TERMINAL PULO GEBANG</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@500;900&display=swap" rel="stylesheet">

    <style>
        body {
            background: #000;
            color: white;
            font-family: 'Roboto', sans-serif;
            text-transform: uppercase; /* Huruf besar semua */
            overflow: hidden;
            height: 100vh;
        }

        /* HEADER RINGKAS */
        .header {
            background: #111;
            border-bottom: 3px solid #ffc107;
            padding: 10px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 26px;
            margin: 0;
            color: #ffc107;
            font-weight: 900;
            letter-spacing: 1px;
        }
        #tanggal {
            font-family: 'Orbitron', sans-serif;
            font-size: 16px;
            color: #fff;
        }

        /* TABEL PADAT (HIGH DENSITY) */
        .table-container {
            padding: 10px;
        }
        .table {
            color: white;
            margin-bottom: 0;
            border: 1px solid #333;
        }
        .table thead th {
            background-color: #222 !important;
            color: #ffc107 !important;
            font-size: 18px;
            padding: 10px;
            border: 1px solid #444;
            text-align: center;
        }
        .table tbody td {
            padding: 8px 6px !important; 
            font-size: 19px; 
            font-weight: 700;
            border: 1px solid #222;
            vertical-align: middle;
        }

        /* HIGHLIGHT KOLOM */
        .plat-nomor {
            color: #ffc107;
            font-family: 'Orbitron', sans-serif;
            font-size: 22px !important;
        }
        .tujuan { color: #00ff88; }
        .waktu { font-family: 'Orbitron', sans-serif; color: #00d4ff; }

        /* BADGE STATUS AREA */
        .status-badge {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            width: 100%;
            font-weight: 900;
            text-align: center;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.5);
        }
        
        /* WARNA STATUS PER AREA */
        .area-masuk { background: #28a745; color: white; }
        .area-kedatangan { background: #17a2b8; color: white; }
        .area-pengendapan { background: #6c757d; color: white; }
        .area-keberangkatan { background: #007bff; color: white; }
        .area-berangkat { background: #dc3545; color: white; animation: pulse-red 1s infinite; }

        @keyframes pulse-red {
            0% { background-color: #dc3545; }
            50% { background-color: #8b0000; }
            100% { background-color: #dc3545; }
        }

        tbody tr:nth-child(even) { background: rgba(255,255,255,0.05); }
    </style>
</head>

<body>

<div class="header">
    <h1>🚍 INFORMASI OPERASIONAL BUS AKAP - PULO GEBANG</h1>
    <div id="tanggal"></div>
</div>

<div class="table-container">
    <table class="table table-sm">
        <thead>
            <tr>
                <th width="4%">NO</th>
                <th width="16%">PLAT NOMOR</th>
                <th width="24%">NAMA PERUSAHAAN (PO)</th>
                <th width="24%">TUJUAN AKHIR</th>
                <th width="10%">JAM</th>
                <th width="22%">STATUS LOKASI</th>
            </tr>
        </thead>
        <tbody id="tvTable" class="text-center">
            </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const BASE_URL = "<?= base_url(); ?>";

// JAM & TANGGAL REALTIME
setInterval(() => {
    let d = new Date();
    document.getElementById('tanggal').innerText = d.toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long'
    }) + " | " + d.toLocaleTimeString('id-ID');
}, 1000);

// LOGIKA PENAMAAN AREA
function getAreaStatus(area) {
    switch(area) {
        case 'masuk': 
            return ['MASUK TERMINAL', 'area-masuk'];
        case 'kedatangan': 
            return ['AREA KEDATANGAN', 'area-kedatangan'];
        case 'pengendapan': 
            return ['AREA PENGENDAPAN', 'area-pengendapan'];
        case 'keberangkatan': 
            return ['AREA KEBERANGKATAN', 'area-keberangkatan'];
        case 'pintu_keluar': 
        case 'berangkat': 
            return ['SUDAH BERANGKAT', 'area-berangkat'];
        default: 
            return ['AREA TERMINAL', 'area-masuk'];
    }
}

function loadTV() {
    $.get(BASE_URL + 'bus_monitor/get_tv_data', function(res) {
        let html = '';
        let no = 1;

        if (!res || res.length === 0) {
            html = '<tr><td colspan="6" class="p-5 text-muted">TIDAK ADA JADWAL BUS AKTIF</td></tr>';
        } else {
            res.forEach(b => {
                // Filter agar data NULL atau Plat kosong tidak tampil
                if (b.plat_nomor && b.plat_nomor.trim() !== "") {
                    let [text, cls] = getAreaStatus(b.area);
                    
                    // Format Jam
                    let jam = '--:--';
                    if (b.created_at) {
                        let dt = new Date(b.created_at);
                        jam = dt.getHours().toString().padStart(2, '0') + ":" + dt.getMinutes().toString().padStart(2, '0');
                    }

                    html += `
                        <tr>
                            <td class="text-muted small">${no++}</td>
                            <td class="plat-nomor">${b.plat_nomor}</td>
                            <td class="text-start ps-4">${b.nama_po || '-'}</td>
                            <td class="tujuan text-start ps-4">${b.tujuan || 'belum lapor'}</td>
                            <td class="waktu">${jam}</td>
                            <td><div class="status-badge ${cls}">${text}</div></td>
                        </tr>
                    `;
                }
            });
        }
        $('#tvTable').html(html);
    }, 'json').fail(function() {
        console.error("Gagal mengambil data server.");
    });
}

// Update setiap 5 detik agar real-time dengan admin
setInterval(loadTV, 5000);
loadTV();
</script>

</body>
</html>