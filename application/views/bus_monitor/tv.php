<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DISPLAY BUS TERMINAL PULO GEBANG</title>

    <!-- Bootstrap & Premium Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ================= HIGH CONTRAST DARK MODE SYSTEM ================= */
        :root {
            --bg-color: #000000;
            --accent-yellow: #ffc107;
            --accent-green: #00ff87;
            --accent-blue: #00d4ff;
            --accent-red: #ff4a5a;
            --text-main: #ffffff;
            --glass-bg: #090a0f;
            --glass-border: #1f2330;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Arial', sans-serif;
            text-transform: uppercase;
            overflow: hidden;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        /* Ambient Glowing Backgrounds - Disabled for high contrast */
        .glow-bg {
            display: none !important;
        }

        /* ================= HEADER ================= */
        .header {
            background: #090a0f;
            border-bottom: 2px solid var(--glass-border);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.6);
            border-radius: 0 0 20px 20px;
            margin: 0 15px;
            z-index: 10;
        }
        .header h1 {
            font-size: 23px;
            margin: 0;
            color: #fff;
            font-weight: 900;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #fff 0%, #ffeaa7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header h1 i {
            color: var(--accent-yellow);
        }
        #tanggal {
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            color: #ffffff;
            text-align: right;
            line-height: 1.3;
            font-weight: 700;
        }
        #tanggal span {
            color: var(--accent-yellow);
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
        }

        /* ================= TABLE SYSTEM ================= */
        .table-container {
            padding: 15px;
            height: calc(100vh - 145px);
            overflow: hidden;
        }
        .table-wrapper {
            height: 100%;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 193, 7, 0.6) #000;
            border-radius: 16px;
            background: var(--glass-bg);
            border: 2px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
        }
        .table-wrapper::-webkit-scrollbar { width: 6px; }
        .table-wrapper::-webkit-scrollbar-track { background: #000; }
        .table-wrapper::-webkit-scrollbar-thumb { background: rgba(255, 193, 7, 0.6); border-radius: 3px; }

        .table {
            font-family: 'Arial', sans-serif !important;
            color: var(--text-main);
            margin-bottom: 0;
            border: none;
            background: transparent;
            width: 100%;
        }
        .table thead th {
            background: #12131a !important;
            color: #ffffff !important;
            font-size: 15px;
            padding: 18px 12px;
            border-bottom: 3px solid var(--glass-border);
            border-top: none;
            border-left: none;
            border-right: none;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
            font-weight: 900;
            letter-spacing: 0.5px;
        }
        .table tbody tr {
            border-bottom: 1px solid #1a1c26;
            transition: all 0.2s ease;
        }
        .table tbody td {
            padding: 14px 12px !important;
            font-size: 18px;
            font-weight: 700;
            border: none;
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background: rgba(255, 193, 7, 0.1) !important;
        }

        /* ================= COLUMN HIGHLIGHTS ================= */
        .plat-nomor {
            color: var(--accent-yellow);
            font-family: 'Arial', sans-serif;
            font-size: 21px !important;
            font-weight: 900;
            letter-spacing: 1px;
            text-shadow: 0 0 8px rgba(255, 193, 7, 0.3);
        }
        .tujuan { color: var(--accent-green); font-weight: 800; }
        .waktu { font-family: 'Arial', sans-serif; color: #fff; font-weight: 700; }
        .po-name { color: #ffffff; font-weight: 900; font-size: 19px; }

        /* ================= BADGE STATUS AREA ================= */
        .status-badge {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 50px;
            display: inline-block;
            width: 170px;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .area-masuk { background: rgba(40, 167, 69, 0.2); color: var(--accent-green); border-color: rgba(40, 167, 69, 0.4); }
        .area-kedatangan { background: rgba(0, 212, 255, 0.15); color: var(--accent-blue); border-color: rgba(0, 212, 255, 0.3); }
        .area-pengendapan { background: rgba(255, 255, 255, 0.06); color: #b5b5c3; border-color: rgba(255, 255, 255, 0.15); }
        .area-keberangkatan { 
            background: rgba(13, 110, 253, 0.2); 
            color: #6ea8fe; 
            border-color: rgba(13, 110, 253, 0.4); 
            animation: pulse-badge-blue 2s infinite; 
        }
        .area-berangkat { 
            background: rgba(220, 53, 69, 0.25); 
            color: #ff6b6b; 
            border-color: rgba(220, 53, 69, 0.5); 
            animation: pulse-badge-red 1.5s infinite; 
        }

        @keyframes pulse-badge-blue {
            0%, 100% { border-color: rgba(13, 110, 253, 0.4); box-shadow: 0 0 10px rgba(13, 110, 253, 0.2); }
            50% { border-color: rgba(0, 212, 255, 0.7); box-shadow: 0 0 18px rgba(13, 110, 253, 0.4); }
        }
        @keyframes pulse-badge-red {
            0%, 100% { border-color: rgba(220, 53, 69, 0.5); box-shadow: 0 0 10px rgba(220, 53, 69, 0.2); }
            50% { border-color: rgba(255, 74, 90, 0.8); box-shadow: 0 0 18px rgba(220, 53, 69, 0.4); }
        }

        /* ================= ROW STRIPING ================= */
        tbody tr:nth-child(even) { background: #0b0c10; }
        tbody tr:nth-child(odd) { background: #11131a; }

        /* ================= EMPTY STATE ================= */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: rgba(255,255,255,0.4);
        }
        .empty-state i {
            font-size: 60px;
            color: var(--accent-yellow);
            margin-bottom: 20px;
            opacity: 0.8;
            text-shadow: 0 0 20px rgba(255, 193, 7, 0.3);
        }
        .empty-state p { font-size: 20px; margin: 5px 0; font-weight: 700; color: #fff; }
        
        /* ================= DYNAMIC RUNNING TICKER ================= */
        .ticker-wrap {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 48px;
            background: #090a0f;
            border-top: 2px solid var(--glass-border);
            display: flex;
            align-items: center;
            overflow: hidden;
            z-index: 100;
        }
        .ticker-title {
            background: linear-gradient(135deg, #b58900, #b56500);
            color: white;
            padding: 0 25px;
            height: 100%;
            display: flex;
            align-items: center;
            font-weight: 900;
            font-size: 14px;
            letter-spacing: 1px;
            box-shadow: 5px 0 15px rgba(0,0,0,0.5);
            z-index: 2;
            white-space: nowrap;
        }
        .ticker {
            display: flex;
            white-space: nowrap;
            padding-left: 100%;
            animation: marquee 25s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }
        .ticker-item {
            display: inline-block;
            padding: 0 35px;
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
        }
        .ticker-item i {
            color: var(--accent-yellow);
            margin-right: 8px;
        }

        /* ================= TIMELINE CELLS & BLACK TEXT BADGES ================= */
        .timeline-cell {
            text-align: center;
            vertical-align: middle;
        }
        .status-pill {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 10px;
            min-width: 145px;
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }
        
        .timeline-time {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 8px;
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            font-weight: 800;
            min-width: 110px;
        }
        
        /* TIMELINE BADGES IN BLACK TEXT */
        .timeline-time.completed {
            color: #000000 !important;
            background: #cbd5e0 !important;
        }
        .timeline-time.active {
            color: #000000 !important;
            background: var(--accent-yellow) !important;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #000000;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px rgba(0,0,0,0.5);
            animation: dot-pulse 1.5s infinite;
        }
        @keyframes dot-pulse {
            0% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(0.8); opacity: 0.5; }
        }

        /* ================= TOTAL DURATION STYLING (BLACK TEXT) ================= */
        .status-pill.completed-total {
            background: #a0aec0 !important;
            color: #000000 !important;
        }
        .status-pill.completed-total .total-val {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            font-weight: 900;
        }
        
        .status-pill.active-total {
            background: #00d4ff !important;
            color: #000000 !important;
            animation: pulse-total-glow 2s infinite alternate;
        }
        .status-pill.active-total .total-val {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            font-weight: 900;
        }
        
        @keyframes pulse-total-glow {
            0% {
                box-shadow: 0 0 8px rgba(0, 212, 255, 0.3);
            }
            100% {
                box-shadow: 0 0 15px rgba(0, 212, 255, 0.7);
            }
        }
        
        /* Muted / Not Reached Status */
        .status-muted {
            color: #4b5563;
            font-weight: 800;
            font-size: 18px;
        }
    </style>
</head>

<body>

<!-- Ambient Glowing Backgrounds -->
<div class="glow-bg glow-1"></div>
<div class="glow-bg glow-2"></div>

<!-- HEADER -->
<div class="header">
    <h1><i class="fas fa-bus-alt mr-2"></i> INFORMASI OPERASIONAL BUS AKAP - TERMINAL PULO GEBANG</h1>
    <div id="tanggal">-- | --:--:--</div>
</div>

<!-- TABLE -->
<div class="table-container">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 4%; text-align: center;">NO</th>
                    <th style="width: 18%; text-align: left; padding-left: 20px;">PO / PLAT NOMOR</th>
                    <th style="width: 12%; text-align: left; padding-left: 20px;">TUJUAN AKHIR</th>
                    <th style="width: 11%; text-align: center;">1. MASUK</th>
                    <th style="width: 11%; text-align: center;">2. KEDATANGAN</th>
                    <th style="width: 11%; text-align: center;">3. PENGENDAPAN</th>
                    <th style="width: 11%; text-align: center;">4. KEBERANGKATAN</th>
                    <th style="width: 11%; text-align: center;">5. KELUAR</th>
                    <th style="width: 11%; text-align: center;">TOTAL DURASI</th>
                </tr>
            </thead>
            <tbody id="tvTable" class="text-center">
                <!-- Data loaded dynamically via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<!-- DYNAMIC RUNNING TICKER -->
<div class="ticker-wrap">
    <div class="ticker-title"><i class="fas fa-bullhorn mr-2"></i> INFO PENTING TERMINAL</div>
    <div class="ticker">
        <div class="ticker-item"><i class="fas fa-check-circle"></i> Selamat Datang di Terminal Pulo Gebang Jakarta. Seluruh pergerakan bus terpantau real-time pada sistem display digital.</div>
        <div class="ticker-item"><i class="fas fa-info-circle"></i> LAYANAN BARANG HILANG: Ditemukan barang tertinggal? Segera laporkan ke petugas informasi. Rekaman barang dapat dipantau di menu Lost & Found.</div>
        <div class="ticker-item"><i class="fas fa-exclamation-triangle"></i> Jagalah barang bawaan berharga Anda. Jangan menitipkan barang atau menerima makanan dari orang asing selama perjalanan.</div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const BASE_URL = "<?= base_url(); ?>";
const REFRESH_INTERVAL = 5000;

// JAM & TANGGAL REALTIME
setInterval(() => {
    let d = new Date();
    let dateStr = d.toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    }).toUpperCase();
    let timeStr = d.toLocaleTimeString('id-ID');
    document.getElementById('tanggal').innerHTML = dateStr + " | <span>" + timeStr + "</span>";
}, 1000);

// DURATION UTILITIES: SHOWS ONLY HOURS AND MINUTES (UNABBREVIATED)
function formatDuration(seconds) {
    if (seconds === null || seconds === undefined) return '';
    seconds = parseInt(seconds);
    if (isNaN(seconds) || seconds <= 0) return '0 menit';
    let minutes = Math.floor(seconds / 60);
    if (minutes <= 0) return '0 menit';
    if (minutes < 60) {
        return minutes + ' menit';
    }
    let hours = Math.floor(minutes / 60);
    let remainingMinutes = minutes % 60;
    if (remainingMinutes === 0) {
        return hours + ' jam';
    }
    return hours + ' jam ' + remainingMinutes + ' menit';
}

function formatTanggalJam(timeStr) {
    if (!timeStr) return '';
    let dt = new Date(timeStr.replace(/-/g, "/"));
    if (isNaN(dt.getTime())) return '';
    
    let day = dt.getDate().toString().padStart(2, '0');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    let month = months[dt.getMonth()];
    
    let hour = dt.getHours().toString().padStart(2, '0');
    let minute = dt.getMinutes().toString().padStart(2, '0');
    
    return `${day} ${month} ${hour}:${minute}`;
}

function renderAreaStatus(areaKey, entryTime, currentArea) {
    if (!entryTime) {
        return '<span class="status-muted">-</span>';
    }
    
    let isCurrent = (currentArea === areaKey);
    let formattedTime = formatTanggalJam(entryTime);
    
    if (isCurrent) {
        return `
            <div class="timeline-time active">
                <span class="pulse-dot"></span>
                <span class="time-val">${formattedTime}</span>
            </div>
        `;
    } else {
        return `
            <div class="timeline-time completed">
                <span class="time-val">${formattedTime}</span>
            </div>
        `;
    }
}

function renderTotalDuration(masukTime, keluarTime) {
    if (!masukTime) return '<span class="status-muted">-</span>';
    
    if (keluarTime) {
        let start = new Date(masukTime.replace(/-/g, "/"));
        let end = new Date(keluarTime.replace(/-/g, "/"));
        let diffSec = Math.max(0, Math.floor((end - start) / 1000));
        return `
            <div class="status-pill completed-total">
                <div class="total-val">${formatDuration(diffSec)}</div>
            </div>
        `;
    } else {
        return `
            <div class="status-pill active-total">
                <div class="total-val running-total-duration" data-start="${masukTime}">--</div>
            </div>
        `;
    }
}

function loadTV() {
    $.get(BASE_URL + 'bus_monitor/get_tv_data', function(res) {
        let html = '';
        let no = 1;

        if (!res || res.length === 0) {
            html = `<tr><td colspan="9"><div class="empty-state">
                <i class="fas fa-bus"></i>
                <p>TIDAK ADA JADWAL BUS AKTIF HARI INI</p>
                <small>Informasi bus lapor otomatis sinkron di layar monitor</small>
            </div></td></tr>`;
        } else {
            res.forEach(b => {
                if (b.plat_nomor && b.plat_nomor.trim() !== "") {
                    let tdMasuk = renderAreaStatus('masuk', b.masuk_masuk, b.current_area);
                    let tdKedatangan = renderAreaStatus('kedatangan', b.kedatangan_masuk, b.current_area);
                    let tdPengendapan = renderAreaStatus('pengendapan', b.pengendapan_masuk, b.current_area);
                    let tdKeberangkatan = renderAreaStatus('keberangkatan', b.keberangkatan_masuk, b.current_area);
                    let tdKeluar = renderAreaStatus('berangkat', b.berangkat_masuk, b.current_area);
                    let tdTotalDurasi = renderTotalDuration(b.masuk_masuk, b.berangkat_masuk);

                    html += `
                        <tr>
                            <td class="text-muted text-center" style="font-size:15px">${no++}</td>
                            <td class="text-start ps-4">
                                <div class="po-name">${b.nama_po || '-'}</div>
                                <div class="plat-nomor" style="font-size: 15px !important; margin-top: 2px;">${b.plat_nomor}</div>
                            </td>
                            <td class="tujuan text-start ps-4" style="font-size:16px">${b.tujuan || 'Belum Lapor'}</td>
                            <td class="timeline-cell">${tdMasuk}</td>
                            <td class="timeline-cell">${tdKedatangan}</td>
                            <td class="timeline-cell">${tdPengendapan}</td>
                            <td class="timeline-cell">${tdKeberangkatan}</td>
                            <td class="timeline-cell">${tdKeluar}</td>
                            <td class="timeline-cell">${tdTotalDurasi}</td>
                        </tr>
                    `;
                }
            });
        }
        $('#tvTable').html(html);
        updateRunningTotalDurations();
    }, 'json').fail(function() {
        console.error("Gagal mengambil data dari server.");
    });
}

function updateRunningTotalDurations() {
    $('.running-total-duration').each(function() {
        let startTime = $(this).data('start');
        if (startTime) {
            let start = new Date(startTime.replace(/-/g, "/"));
            let now = new Date();
            let diffSec = Math.max(0, Math.floor((now - start) / 1000));
            $(this).text(formatDuration(diffSec));
        }
    });
}

// Update running total durations every second
setInterval(updateRunningTotalDurations, 1000);

// Update setiap 5 detik agar real-time dengan admin
setInterval(loadTV, REFRESH_INTERVAL);
$(document).ready(function() {
    loadTV();
});
</script>

</body>
</html>