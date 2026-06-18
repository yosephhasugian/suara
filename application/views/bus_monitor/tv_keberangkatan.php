<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DISPLAY KEBERANGKATAN - PULO GEBANG' ?></title>
    
    <!-- Bootstrap & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@500;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ================= BASE STYLES ================= */
        body {
            background: #000;
            color: white;
            font-family: 'Arial', sans-serif;
            text-transform: uppercase;
            overflow: hidden;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* ================= HEADER ================= */
        .header {
            background: #111;
            border-bottom: 4px solid #ffc107;  /* Yellow */
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(255, 193, 7, 0.3);
        }
        .header h1 {
            font-size: 28px;
            margin: 0;
            color: #ffc107;
            font-weight: 900;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-icon {
            font-size: 32px;
            animation: float-icon 3s ease-in-out infinite;
        }
        @keyframes float-icon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        #tanggal {
            font-family: 'Arial', sans-serif;
            font-size: 18px;
            color: #fff;
            text-align: right;
            line-height: 1.4;
        }
        #tanggal .time {
            font-size: 24px;
            color: #ffc107;
            font-weight: 700;
        }

        /* ================= TABLE CONTAINER ================= */
        .table-container {
            padding: 15px;
            height: calc(100vh - 120px);
            overflow: hidden;
        }
        .table-wrapper {
            height: 100%;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #ffc107 #222;
        }
        .table-wrapper::-webkit-scrollbar { width: 6px; }
        .table-wrapper::-webkit-scrollbar-track { background: #222; }
        .table-wrapper::-webkit-scrollbar-thumb { 
            background: #ffc107; 
            border-radius: 3px; 
        }

        /* ================= TABLE STYLES ================= */
        .table {
            font-family: 'Arial', sans-serif !important;
            color: white;
            margin-bottom: 0;
            border: 1px solid #444 !important;
            background: rgba(0,0,0,0.3);
        }
        .table thead th {
            background: linear-gradient(135deg, #1a1a2e, #16213e) !important;
            color: #ffc107 !important;
            font-size: 18px;
            font-weight: 700;
            padding: 12px 8px;
            border: 1px solid #555 !important;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .table tbody td {
            padding: 10px 8px !important;
            font-size: 20px;
            font-weight: 700;
            border: 1px solid #444 !important;
            vertical-align: middle;
            transition: all 0.2s ease;
        }
        .table tbody tr {
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .table tbody tr:hover {
            transform: scale(1.02);
            background: rgba(255, 193, 7, 0.15) !important;
            box-shadow: inset 0 0 20px rgba(255, 193, 7, 0.2);
        }

        /* ================= COLUMN HIGHLIGHTS ================= */
        .plat-nomor {
            color: #ffc107;
            font-family: 'Arial', sans-serif;
            font-size: 24px !important;
            font-weight: 900;
            letter-spacing: 1px;
        }
        .tujuan { 
            color: #00ff88; 
            font-weight: 600;
        }
        .waktu { 
            font-family: 'Arial', sans-serif; 
            color: #ffc107; 
            font-weight: 700;
        }
        .po-name {
            color: #e0e0e0;
            font-weight: 500;
        }

        /* ================= STATUS BADGE ================= */
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
            0%, 100% { box-shadow: inset 0 0 8px rgba(0,0,0,0.4), 0 0 0 0 rgba(255, 193, 7, 0.4); }
            50% { box-shadow: inset 0 0 8px rgba(0,0,0,0.4), 0 0 15px 3px rgba(255, 193, 7, 0.6); }
        }
        
        /* WARNA BADGE PER AREA */
        .area-keberangkatan { 
            background: linear-gradient(135deg, #ffc107, #d39e00); 
            color: white; 
        }

        /* ================= ROW STRIPING ================= */
        tbody tr:nth-child(even) { 
            background: rgba(255, 193, 7, 0.08); 
        }
        tbody tr:nth-child(odd) { 
            background: rgba(0,0,0,0.2); 
        }

        /* ================= EMPTY STATE ================= */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255,255,255,0.5);
        }
        .empty-state i {
            font-size: 48px;
            color: #ffc107;
            margin-bottom: 15px;
            opacity: 0.7;
            animation: float-icon 3s ease-in-out infinite;
        }
        .empty-state p {
            font-size: 18px;
            margin: 0;
            font-weight: 500;
        }

        /* ================= ANIMATIONS ================= */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .table tbody tr {
            animation: fadeIn 0.3s ease-out forwards;
        }
        .table tbody tr:nth-child(1) { animation-delay: 0.05s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.15s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.25s; }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .header h1 { font-size: 20px; }
            #tanggal { font-size: 14px; }
            #tanggal .time { font-size: 18px; }
            .table tbody td { font-size: 16px; padding: 8px 4px !important; }
            .plat-nomor { font-size: 18px !important; }
            .status-badge { font-size: 12px; padding: 4px 8px; }
        }

        /* ================= REFRESH INDICATOR ================= */
        .refresh-indicator {
            position: fixed;
            bottom: 60px;
            right: 20px;
            background: rgba(255, 193, 7, 0.9);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
            z-index: 100;
        }
        .refresh-indicator i {
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .refresh-indicator.hidden {
            display: none;
        }

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
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            overflow: hidden;
            z-index: 100;
        }
        .ticker-title {
            background: linear-gradient(135deg, #ffc107, #d39e00);
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
            animation: marquee 90s linear infinite;
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
            color: #ffc107;
            margin-right: 8px;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h1>
        <span class="header-icon">
            <i class="fas fa-bus"></i><i class="fas fa-arrow-up text-warning" style="font-size: 0.6em; margin-left: -8px; vertical-align: top;"></i>
        </span>
        AREA KEBERANGKATAN - TERMINAL PULO GEBANG
    </h1>
    <div id="tanggal">
        <div id="dateText"></div>
        <div class="time" id="timeText">--:--:--</div>
    </div>
</div>

<!-- TABLE -->
<div class="table-container">
    <div class="table-wrapper">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="20%">PLAT NOMOR</th>
                    <th width="35%">NAMA PERUSAHAAN (PO)</th>
                    <th width="30%">TUJUAN</th>
                    <th width="10%">JAM MASUK</th>
                </tr>
            </thead>
            <tbody id="tvTable" class="text-center">
                <!-- Data will be loaded here -->
            </tbody>
        </table>
    </div>
</div>

<!-- REFRESH INDICATOR -->
<div class="refresh-indicator" id="refreshIndicator">
    <i class="fas fa-sync-alt"></i>
    <span>Updating...</span>
</div>

<!-- DYNAMIC RUNNING TICKER -->
<div class="ticker-wrap">
    <div class="ticker-title"><i class="fas fa-bullhorn mr-2"></i> INFO TERMINAL</div>
    <div class="ticker">
        <div class="ticker-item"><i class="fas fa-check-circle"></i> Selamat Datang di Terminal Terpadu Pulo Gebang - Utamakan keselamatan dan kenyamanan bersama selama di perjalanan.</div>
        <div class="ticker-item"><i class="fas fa-info-circle"></i> INFO BARANG TEMUAN: Petugas rutin menginput barang tertinggal di menu Lost & Found. Bagi penumpang yang merasa kehilangan barang, silakan memeriksa ke Pusat Layanan Informasi Terminal.</div>
        <div class="ticker-item"><i class="fas fa-exclamation-triangle"></i> Hati-hati terhadap segala bentuk penipuan. Harap membeli tiket resmi hanya di loket Agen PO resmi atau aplikasi pemesanan tiket resmi.</div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
const BASE_URL = "<?= base_url(); ?>";
const AREA_CODE = "keberangkatan";
const BADGE_CLASS = "area-keberangkatan";
const STATUS_TEXT = "AREA KEBERANGKATAN";
const REFRESH_INTERVAL = 5000; // 5 detik

// ================= CLOCK & DATE =================
function updateClock() {
    let d = new Date();
    
    // Date
    let dateOptions = { 
        weekday: 'long', 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
    };
    document.getElementById('dateText').innerText = d.toLocaleDateString('id-ID', dateOptions).toUpperCase();
    
    // Time with seconds
    let timeString = d.toLocaleTimeString('id-ID');
    document.getElementById('timeText').innerText = timeString;
}
setInterval(updateClock, 1000);
updateClock();

// ================= FORMAT JAM =================
function formatTime(timestamp) {
    if (!timestamp) return '--:--';
    let dt = new Date(timestamp);
    return dt.getHours().toString().padStart(2, '0') + ":" + 
           dt.getMinutes().toString().padStart(2, '0');
}

// ================= LOAD DATA TV =================
function loadTV() {
    // Show refresh indicator
    $('#refreshIndicator').removeClass('hidden');
    
    $.get(BASE_URL + 'bus_monitor/get_tv_keberangkatan', function(res) {
        let html = '';
        let no = 1;

        if (!res || res.length === 0) {
            html = `<tr>
                <td colspan="5">
                    <div class="empty-state">
                        <i class="fas fa-bus"></i>
                        <p>BELUM ADA BUS SIAP BERANGKAT</p>
                        <small class="text-muted">Bus akan muncul otomatis saat dipindah ke area keberangkatan</small>
                    </div>
                </td>
            </tr>`;
        } else {
            res.forEach(b => {
                // Filter plat nomor kosong
                if (b.plat_nomor && b.plat_nomor.trim() !== "") {
                    // Gunakan area_updated_at jika ada (waktu pindah ke area keberangkatan), jika tidak gunakan created_at
                    let jam = formatTime(b.area_updated_at || b.created_at);
                    
                    html += `<tr>
                        <td class="text-muted" style="font-size:18px">${no++}</td>
                        <td class="plat-nomor">${b.plat_nomor}</td>
                        <td class="text-start ps-4 po-name">${b.nama_po || '-'}</td>
                        <td class="tujuan text-start ps-4">${b.tujuan || 'Belum ditentukan'}</td>
                        <td class="waktu">${jam}</td>
                    </tr>`;
                }
            });
        }
        
        $('#tvTable').html(html);
        
        // Hide refresh indicator after load
        setTimeout(() => {
            $('#refreshIndicator').addClass('hidden');
        }, 500);
        
    }, 'json')
    .fail(function(xhr, status, error) {
        console.error("❌ Gagal mengambil data:", error);
        $('#tvTable').html(`<tr><td colspan="5" class="text-danger p-4">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Gagal memuat data server
        </td></tr>`);
        $('#refreshIndicator').addClass('hidden');
    });
}

// ================= AUTO REFRESH =================
setInterval(loadTV, REFRESH_INTERVAL);

// Initial load
$(document).ready(function() {
    loadTV();
    
    // Optional: Force reload jika visibility change (tab aktif)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            loadTV();
        }
    });
});

// ================= KEYBOARD SHORTCUT (Opsional) =================
document.addEventListener('keydown', function(e) {
    // Tekan 'R' untuk refresh manual
    if (e.key.toLowerCase() === 'r' && !e.target.matches('input, textarea')) {
        e.preventDefault();
        loadTV();
    }
});
</script>

</body>
</html>