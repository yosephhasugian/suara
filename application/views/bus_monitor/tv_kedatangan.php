<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DISPLAY KEDATANGAN - PULO GEBANG' ?></title>
    
    <!-- Bootstrap & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@500;900&display=swap" rel="stylesheet">
    
    <style>
        /* ================= BASE STYLES ================= */
        body {
            background: #000;
            color: white;
            font-family: 'Roboto', sans-serif;
            text-transform: uppercase;
            overflow: hidden;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* ================= HEADER ================= */
        .header {
            background: #111;
            border-bottom: 4px solid #17a2b8;  /* Cyan */
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(23, 162, 184, 0.3);
        }
        .header h1 {
            font-size: 28px;
            margin: 0;
            color: #17a2b8;
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
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            color: #fff;
            text-align: right;
            line-height: 1.4;
        }
        #tanggal .time {
            font-size: 24px;
            color: #17a2b8;
            font-weight: 700;
        }

        /* ================= TABLE CONTAINER ================= */
        .table-container {
            padding: 15px;
            height: calc(100vh - 70px);
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

        /* ================= TABLE STYLES ================= */
        .table {
            color: white;
            margin-bottom: 0;
            border: 1px solid #333;
            background: rgba(0,0,0,0.3);
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
        .table tbody td {
            padding: 10px 8px !important;
            font-size: 20px;
            font-weight: 700;
            border: 1px solid #222;
            vertical-align: middle;
            transition: all 0.2s ease;
        }
        .table tbody tr {
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .table tbody tr:hover {
            transform: scale(1.02);
            background: rgba(23, 162, 184, 0.15) !important;
            box-shadow: inset 0 0 20px rgba(23, 162, 184, 0.2);
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
            color: #00d4ff; 
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
            0%, 100% { box-shadow: inset 0 0 8px rgba(0,0,0,0.4), 0 0 0 0 rgba(23, 162, 184, 0.4); }
            50% { box-shadow: inset 0 0 8px rgba(0,0,0,0.4), 0 0 15px 3px rgba(23, 162, 184, 0.6); }
        }
        
        /* WARNA BADGE PER AREA */
        .area-kedatangan { 
            background: linear-gradient(135deg, #17a2b8, #138496); 
            color: white; 
        }

        /* ================= ROW STRIPING ================= */
        tbody tr:nth-child(even) { 
            background: rgba(23, 162, 184, 0.08); 
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
            color: #17a2b8;
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
            bottom: 15px;
            right: 20px;
            background: rgba(23, 162, 184, 0.9);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);
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
    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <h1>
        <span class="header-icon"><?= $icon ?? '🛬' ?></span>
        <?= $area_label ?? 'AREA KEDATANGAN' ?> - TERMINAL PULO GEBANG
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
                    <th width="4%">NO</th>
                    <th width="18%">PLAT NOMOR</th>
                    <th width="32%">NAMA PERUSAHAAN (PO)</th>
                    <th width="26%">ASAL / TUJUAN</th>
                    <th width="10%">JAM DATANG</th>
                    <th width="10%">STATUS</th>
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

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
const BASE_URL = "<?= base_url(); ?>";
const AREA_CODE = "kedatangan";
const BADGE_CLASS = "area-kedatangan";
const STATUS_TEXT = "BARU TIBA";
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
    
    $.get(BASE_URL + 'bus_monitor/get_tv_kedatangan', function(res) {
        let html = '';
        let no = 1;

        if (!res || res.length === 0) {
            html = `<tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fas fa-bus"></i>
                        <p>TIDAK ADA BUS YANG BARU TIBA</p>
                        <small class="text-muted">Data akan muncul otomatis saat bus masuk</small>
                    </div>
                </td>
            </tr>`;
        } else {
            res.forEach(b => {
                // Filter plat nomor kosong
                if (b.plat_nomor && b.plat_nomor.trim() !== "") {
                    let jam = formatTime(b.created_at);
                    
                    html += `<tr>
                        <td class="text-muted" style="font-size:18px">${no++}</td>
                        <td class="plat-nomor">${b.plat_nomor}</td>
                        <td class="text-start ps-4 po-name">${b.nama_po || '-'}</td>
                        <td class="tujuan text-start ps-4">${b.tujuan || 'Belum ditentukan'}</td>
                        <td class="waktu">${jam}</td>
                        <td><div class="status-badge ${BADGE_CLASS}">${STATUS_TEXT}</div></td>
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
        $('#tvTable').html(`<tr><td colspan="6" class="text-danger p-4">
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