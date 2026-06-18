<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DISPLAY KEBERANGKATAN - PULO GEBANG' ?></title>
    
    <!-- Bootstrap & Premium Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ================= PREMIUM GLASSMORPHISM SYSTEM ================= */
        :root {
            --bg-color: #05060c;
            --primary-glow: rgba(0, 123, 255, 0.16);
            --secondary-glow: rgba(0, 255, 135, 0.1);
            --accent-blue: #00d4ff;
            --accent-green: #00ff87;
            --accent-yellow: #ffc107;
            --text-main: #f3f4f6;
            --glass-bg: rgba(20, 22, 38, 0.55);
            --glass-border: rgba(255, 255, 255, 0.08);
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

        /* Ambient Glowing Backgrounds */
        .glow-bg {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(150px);
            pointer-events: none;
            z-index: -1;
            opacity: 0.8;
            animation: float-glow 10s ease-in-out infinite alternate;
        }
        .glow-1 {
            top: -150px;
            left: -100px;
            background: radial-gradient(circle, var(--primary-glow) 0%, rgba(0,0,0,0) 70%);
        }
        .glow-2 {
            bottom: -150px;
            right: -100px;
            background: radial-gradient(circle, var(--secondary-glow) 0%, rgba(0,0,0,0) 70%);
        }
        @keyframes float-glow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 40px) scale(1.1); }
        }

        /* ================= GLASSMOPHIC HEADER ================= */
        .header { 
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-border);
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            border-radius: 0 0 20px 20px;
            margin: 0 15px;
            z-index: 10;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .header h1 { 
            font-size: 24px; 
            margin: 0; 
            color: #fff; 
            font-weight: 900; 
            letter-spacing: 1px; 
            background: linear-gradient(135deg, #fff 0%, #a8caff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* 🚌 BUS COUNTER BADGE */
        .bus-counter {
            background: rgba(0, 123, 255, 0.15);
            border: 1px solid rgba(0, 123, 255, 0.3);
            color: #fff;
            padding: 6px 20px;
            border-radius: 50px;
            font-family: 'Arial', sans-serif;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.2);
            animation: pulse-counter 2.5s infinite;
        }
        @keyframes pulse-counter {
            0%, 100% { border-color: rgba(0, 123, 255, 0.3); box-shadow: 0 0 15px rgba(0, 123, 255, 0.2); }
            50% { border-color: rgba(0, 212, 255, 0.6); box-shadow: 0 0 25px rgba(0, 123, 255, 0.4); }
        }
        .bus-counter i { color: var(--accent-blue); font-size: 16px; }
        .bus-counter .number { 
            font-size: 24px; 
            color: var(--accent-blue); 
            font-weight: 900;
            font-family: 'Arial', sans-serif;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
        }

        /* ================= CLOCK ================= */
        #tanggal { 
            font-size: 14px; 
            color: #b5b5c3; 
            text-align: right;
            line-height: 1.3;
        }
        #tanggal .time {
            font-family: 'Arial', sans-serif;
            font-size: 22px;
            color: var(--accent-blue);
            font-weight: 900;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
        }

        /* ================= TABLE SYSTEM ================= */
        .table-container { 
            padding: 15px; 
            height: calc(100vh - 145px); /* Adjusted for running ticker */
            overflow: hidden;
        }
        .table-wrapper {
            height: 100%;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #17a2b8 #222;
        }
        .table-wrapper::-webkit-scrollbar { width: 6px; }
        .table-wrapper::-webkit-scrollbar-track { background: #222; }
        .table-wrapper::-webkit-scrollbar-thumb { 
            background: #17a2b8; 
            border-radius: 3px; 
        }

        .table { 
            font-family: 'Arial', sans-serif !important;
            color: white; 
            margin-bottom: 0; 
            border: 1px solid #333; 
            background: rgba(0,0,0,0.3);
            width: 100%;
        }
        .table thead th { 
            background: linear-gradient(135deg, #1a1a2e, #16213e) !important; 
            color: #17a2b8 !important; 
            font-size: 18px; 
            font-weight: 700;
            padding: 12px 8px; 
            border: 1px solid #444; 
            text-align: center; 
            position: sticky;
            top: 0;
            z-index: 10;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .table tbody tr {
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .table tbody td { 
            padding: 10px 8px !important; 
            font-size: 20px; 
            font-weight: 700; 
            border: 1px solid #222; 
            vertical-align: middle; 
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            transform: scale(1.02);
            background: rgba(23, 162, 184, 0.15) !important;
            box-shadow: inset 0 0 20px rgba(23, 162, 184, 0.2);
        }

        /* ================= COLUMN STYLES ================= */
        .plat-nomor { 
            color: #ffc107; 
            font-family: 'Arial', sans-serif; 
            font-size: 24px !important; 
            font-weight: 900;
            letter-spacing: 1px;
        }
        .tujuan { color: #00ff88; font-weight: 600; }
        .waktu { font-family: 'Arial', sans-serif; color: #00d4ff; font-weight: 700; }
        .po-name { color: #e0e0e0; font-weight: 500; }

        /* ✨ STATUS BADGE */
        .status-badge {
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            width: 100%;
            font-weight: 900;
            text-align: center;
            box-shadow: inset 0 0 8px rgba(0,0,0,0.4);
            animation: pulse-badge 2s infinite;
        }
        @keyframes pulse-badge {
            0%, 100% { box-shadow: inset 0 0 8px rgba(0,0,0,0.4), 0 0 0 0 rgba(23, 162, 184, 0.4); }
            50% { box-shadow: inset 0 0 8px rgba(0,0,0,0.4), 0 0 15px 3px rgba(23, 162, 184, 0.6); }
        }
        .area-keberangkatan { 
            background: linear-gradient(135deg, #17a2b8, #138496); 
            color: white; 
        }

        /* ================= ROW STRIPING ================= */
        tbody tr:nth-child(even) { background: rgba(23, 162, 184, 0.08); }
        tbody tr:nth-child(odd) { background: rgba(0, 0, 0, 0.2); }

        /* ================= EMPTY STATE ================= */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: rgba(255,255,255,0.4);
        }
        .empty-state i {
            font-size: 60px;
            color: #17a2b8;
            margin-bottom: 20px;
            opacity: 0.8;
            text-shadow: 0 0 20px rgba(23, 162, 184, 0.3);
        }
        .empty-state p { font-size: 20px; margin: 5px 0; font-weight: 700; color: #fff; }
        .empty-state small { font-size: 14px; opacity: 0.7; }

        /* ================= DYNAMIC RUNNING TICKER ================= */
        .ticker-wrap {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 48px;
            background: rgba(10, 10, 20, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            overflow: hidden;
            z-index: 100;
        }
        .ticker-title {
            background: linear-gradient(135deg, #0d47a1, #1565c0);
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
            animation: marquee 50s linear infinite;
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
            color: #e2e8f0;
        }
        .ticker-item i {
            color: var(--accent-yellow);
            margin-right: 8px;
        }

        /* ================= REFRESH INDICATOR ================= */
        .refresh-indicator {
            position: fixed;
            bottom: 60px;
            right: 20px;
            background: rgba(0, 123, 255, 0.85);
            color: white;
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            border: 1px solid rgba(255,255,255,0.1);
            z-index: 100;
        }
        .refresh-indicator i { animation: spin 2s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .refresh-indicator.hidden { display: none; }
    </style>
</head>
<body>

<!-- Ambient Glowing Backgrounds -->
<div class="glow-bg glow-1"></div>
<div class="glow-bg glow-2"></div>

<!-- HEADER WITH COUNTER -->
<div class="header">
    <div class="header-left">
        <h1><i class="fas fa-desktop text-primary mr-2"></i> MONITOR PERGERAKAN BUS - AREA KEBERANGKATAN</h1>
        <!-- ✨ BUS COUNTER -->
        <div class="bus-counter">
            <i class="fas fa-bus"></i>
            <span class="number" id="busCount">0</span>
            <small style="font-size:11px; opacity:0.8; font-weight:800; letter-spacing:0.5px">BUS SIAP</small>
        </div>
    </div>
    <div id="tanggal">
        <div id="dateText"></div>
        <div class="time" id="timeText">--:--:--</div>
    </div>
</div>

<!-- TABLE -->
<div class="table-container">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="18%">PLAT NOMOR</th>
                    <th width="30%">NAMA PERUSAHAAN (PO)</th>
                    <th width="27%">TUJUAN AKHIR</th>
                    <th width="10%">JAM MASUK AREA</th>
                    <th width="10%">STATUS</th>
                </tr>
            </thead>
            <tbody id="tvTable" class="text-center">
                <!-- Data loaded via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<!-- DYNAMIC RUNNING TICKER -->
<div class="ticker-wrap">
    <div class="ticker-title"><i class="fas fa-bullhorn mr-2"></i> INFO TERMINAL</div>
    <div class="ticker">
        <div class="ticker-item"><i class="fas fa-check-circle"></i> Penumpang area keberangkatan harap mempersiapkan tiket resmi Anda dan bersiap-siap menuju pintu keberangkatan bus masing-masing.</div>
        <div class="ticker-item"><i class="fas fa-info-circle"></i> INFO BARANG TEMUAN: Petugas rutin menginput barang tertinggal di menu Lost & Found. Bagi penumpang yang merasa kehilangan barang, silakan memeriksa ke Pusat Layanan Informasi Terminal.</div>
        <div class="ticker-item"><i class="fas fa-exclamation-triangle"></i> Jagalah barang bawaan Anda selama menunggu di ruang tunggu terminal. Anak-anak harap dalam pengawasan orang tua.</div>
    </div>
</div>

<!-- REFRESH INDICATOR -->
<div class="refresh-indicator hidden" id="refreshIndicator">
    <i class="fas fa-sync-alt"></i>
    <span>Updating...</span>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const BASE_URL = "<?= base_url(); ?>";
const STATUS_TEXT = "AREA KEBERANGKATAN";
const REFRESH_INTERVAL = 5000;

// ================= CLOCK & DATE =================
function updateClock() {
    let d = new Date();
    let dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('dateText').innerText = d.toLocaleDateString('id-ID', dateOptions).toUpperCase();
    document.getElementById('timeText').innerText = d.toLocaleTimeString('id-ID');
}
setInterval(updateClock, 1000);
updateClock();

// ================= FORMAT JAM =================
function formatTime(timestamp, areaUpdated) {
    let timeValue = areaUpdated || timestamp;
    if (!timeValue) return '--:--';
    let dt = new Date(timeValue);
    return dt.getHours().toString().padStart(2, '0') + ":" + dt.getMinutes().toString().padStart(2, '0');
}

// ================= LOAD DATA TV =================
function loadTV() {
    $('#refreshIndicator').removeClass('hidden');
    $.get(BASE_URL + 'bus_monitor/get_tv_keberangkatan', function(res) {
        $('#refreshIndicator').addClass('hidden');
        let html = '';
        let no = 1;
        let totalCount = 0;

        if (!res || res.length === 0) {
            html = `<tr><td colspan="6"><div class="empty-state">
                <i class="fas fa-bus-alt"></i>
                <p>BELUM ADA BUS SIAP BERANGKAT</p>
                <small>Bus akan muncul otomatis saat dipindah ke area keberangkatan</small>
            </div></td></tr>`;
        } else {
            let validBuses = res.filter(b => b.plat_nomor && b.plat_nomor.trim() !== "");
            totalCount = validBuses.length;
            
            validBuses.forEach(b => {
                let jam = formatTime(b.created_at, b.area_updated_at);
                
                html += `<tr>
                    <td class="text-muted" style="font-size:18px">${no++}</td>
                    <td class="plat-nomor">${b.plat_nomor}</td>
                    <td class="text-start ps-4 po-name">${b.nama_po || '-'}</td>
                    <td class="tujuan text-start ps-4">${b.tujuan || 'Belum ditentukan'}</td>
                    <td class="waktu">${jam}</td>
                    <td><div class="status-badge area-keberangkatan">${STATUS_TEXT}</div></td>
                </tr>`;
            });
        }
        
        $('#tvTable').html(html);
        $('#busCount').text(totalCount);
        
    }, 'json').fail(function(xhr) {
        $('#refreshIndicator').addClass('hidden');
        console.error("❌ Error:", xhr.status);
        $('#tvTable').html(`<tr><td colspan="6" class="text-danger p-4 text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>Gagal memuat data dari server
        </td></tr>`);
    });
}

// ================= AUTO REFRESH =================
setInterval(loadTV, REFRESH_INTERVAL);

// ================= INITIAL LOAD =================
$(document).ready(function() {
    loadTV();
    
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) loadTV();
    });
});

// Keyboard shortcut: Tekan 'R' untuk refresh manual
document.addEventListener('keydown', function(e) {
    if (e.key.toLowerCase() === 'r' && !e.target.matches('input, textarea')) {
        e.preventDefault();
        loadTV();
    }
});
</script>

</body>
</html>