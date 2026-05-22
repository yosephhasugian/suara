<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DISPLAY PINTU KELUAR - PULO GEBANG' ?></title>
    
    <!-- Bootstrap & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@500;900&display=swap" rel="stylesheet">
    
    <style>
        /* ================= BASE ================= */
        body { 
            background: #000; 
            color: white; 
            font-family: 'Roboto', sans-serif; 
            text-transform: uppercase; 
            overflow: hidden; 
            height: 100vh; 
            margin: 0; 
        }

        /* ================= HEADER ================= */
        .header { 
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            border-bottom: 4px solid #dc3545; 
            padding: 12px 25px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 4px 20px rgba(220, 53, 69, 0.3);
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header h1 { 
            font-size: 26px; 
            margin: 0; 
            color: #dc3545; 
            font-weight: 900; 
            letter-spacing: 1px; 
        }
        
        /* ✨ BUS COUNTER BADGE */
        .bus-counter {
            background: linear-gradient(135deg, #dc3545, #a71d2a);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.5);
            animation: pulse-counter 2s infinite;
        }
        @keyframes pulse-counter {
            0%, 100% { box-shadow: 0 4px 15px rgba(220, 53, 69, 0.5); }
            50% { box-shadow: 0 4px 25px rgba(220, 53, 69, 0.8); }
        }
        .bus-counter i { font-size: 20px; }
        .bus-counter .number { 
            font-size: 24px; 
            color: #fff; 
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* ================= CLOCK ================= */
        #tanggal { 
            font-family: 'Orbitron', sans-serif; 
            font-size: 16px; 
            color: #fff; 
            text-align: right;
            line-height: 1.4;
        }
        #tanggal .time {
            font-size: 22px;
            color: #dc3545;
            font-weight: 700;
        }

        /* ================= TABLE ================= */
        .table-container { 
            padding: 10px; 
            height: calc(100vh - 70px);
            overflow: hidden;
        }
        .table-wrapper {
            height: 100%;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #dc3545 #222;
        }
        .table-wrapper::-webkit-scrollbar { width: 6px; }
        .table-wrapper::-webkit-scrollbar-track { background: #222; }
        .table-wrapper::-webkit-scrollbar-thumb { background: #dc3545; border-radius: 3px; }

        .table { 
            color: white; 
            margin-bottom: 0; 
            border: 1px solid #333; 
            background: rgba(0,0,0,0.3);
        }
        .table thead th { 
            background: linear-gradient(135deg, #1a1a2e, #16213e) !important; 
            color: #dc3545 !important; 
            font-size: 17px; 
            padding: 12px 8px; 
            border: 1px solid #444; 
            text-align: center; 
            position: sticky;
            top: 0;
            z-index: 10;
            font-weight: 700;
        }
        .table tbody td { 
            padding: 10px 8px !important; 
            font-size: 19px; 
            font-weight: 700; 
            border: 1px solid #222; 
            vertical-align: middle; 
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            transform: scale(1.01);
            background: rgba(220, 53, 69, 0.15) !important;
            box-shadow: inset 0 0 20px rgba(220, 53, 69, 0.2);
        }

        /* ================= COLUMN STYLES ================= */
        .plat-nomor { 
            color: #ffc107; 
            font-family: 'Orbitron', sans-serif; 
            font-size: 22px !important; 
            font-weight: 900;
            letter-spacing: 1px;
        }
        .tujuan { color: #00ff88; font-weight: 600; }
        .waktu { font-family: 'Orbitron', sans-serif; color: #ff6b6b; font-weight: 700; }
        .po-name { color: #e0e0e0; font-weight: 500; }

        /* ✨ STATUS BADGE */
        .status-badge {
            font-size: 13px;
            padding: 5px 10px;
            border-radius: 6px;
            display: inline-block;
            width: 100%;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
        }
        .area-berangkat { 
            background: linear-gradient(135deg, #dc3545, #a71d2a); 
            color: white; 
            animation: pulse-departed 1.5s infinite;
        }
        @keyframes pulse-departed {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
            50% { opacity: 0.85; box-shadow: 0 0 0 8px rgba(220, 53, 69, 0); }
        }

        /* ================= ROW STRIPING ================= */
        tbody tr:nth-child(even) { background: rgba(220,53,69,0.08); }
        tbody tr:nth-child(odd) { background: rgba(0,0,0,0.2); }

        /* ================= EMPTY STATE ================= */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: rgba(255,255,255,0.5);
        }
        .empty-state i {
            font-size: 50px;
            color: #dc3545;
            margin-bottom: 15px;
            opacity: 0.7;
        }
        .empty-state p { font-size: 18px; margin: 5px 0; font-weight: 500; }
        .empty-state small { font-size: 14px; opacity: 0.7; }

        /* ================= ANIMATIONS ================= */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .table tbody tr { animation: fadeIn 0.3s ease-out forwards; }
        .table tbody tr:nth-child(1) { animation-delay: 0.05s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.15s; }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .header { flex-direction: column; gap: 10px; padding: 10px 15px; }
            .header h1 { font-size: 20px; text-align: center; }
            .bus-counter { padding: 6px 15px; font-size: 16px; }
            .bus-counter .number { font-size: 20px; }
            #tanggal { font-size: 14px; text-align: center; }
            #tanggal .time { font-size: 18px; }
            .table tbody td { font-size: 15px; padding: 8px 4px !important; }
            .plat-nomor { font-size: 18px !important; }
            .status-badge { font-size: 11px; padding: 4px 8px; }
        }

        /* ================= REFRESH INDICATOR ================= */
        .refresh-indicator {
            position: fixed;
            bottom: 15px;
            right: 20px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            z-index: 100;
        }
        .refresh-indicator i { animation: spin 2s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .refresh-indicator.hidden { display: none; }
    </style>
</head>
<body>

<!-- HEADER WITH COUNTER -->
<div class="header">
    <div class="header-left">
        <h1>🔴 BUS TELAH BERANGKAT - TERMINAL PULO GEBANG</h1>
        <!-- ✨ BUS COUNTER -->
        <div class="bus-counter">
            <i class="fas fa-flag-checkered"></i>
            <span class="number" id="busCount">0</span>
            <small style="font-size:12px; opacity:0.9">TELAH KELUAR</small>
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
        <table class="table table-sm">
            <thead>
                <tr>
                    <th width="4%">NO</th>
                    <th width="18%">PLAT NOMOR</th>
                    <th width="30%">NAMA PERUSAHAAN (PO)</th>
                    <th width="28%">TUJUAN AKHIR</th>
                    <th width="10%">JAM KELUAR</th>
                    <th width="10%">STATUS</th>
                </tr>
            </thead>
            <tbody id="tvTable" class="text-center">
                <!-- Data loaded via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<!-- REFRESH INDICATOR -->
<div class="refresh-indicator hidden" id="refreshIndicator">
    <i class="fas fa-sync-alt"></i>
    <span>Updating...</span>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
const BASE_URL = "<?= base_url(); ?>";
const STATUS_TEXT = "SUDAH BERANGKAT";
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

// ================= FORMAT JAM (Prioritize area_updated_at) =================
function formatTime(timestamp, areaUpdated) {
    let timeValue = areaUpdated || timestamp;
    if (!timeValue) return '--:--';
    let dt = new Date(timeValue);
    return dt.getHours().toString().padStart(2, '0') + ":" + dt.getMinutes().toString().padStart(2, '0');
}

// ================= LOAD DATA TV =================
function loadTV() {
    $('#refreshIndicator').removeClass('hidden');
    
    $.get(BASE_URL + 'bus_monitor/get_tv_keluar', function(res) {
        let html = '';
        let no = 1;
        let totalCount = 0;

        if (!res || res.length === 0) {
            html = `<tr><td colspan="6"><div class="empty-state">
                <i class="fas fa-flag-checkered"></i>
                <p>BELUM ADA BUS YANG BERANGKAT</p>
                <small>Bus akan muncul otomatis saat dikonfirmasi keluar</small>
            </div></td></tr>`;
        } else {
            // Filter plat nomor valid & hitung total
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
                    <td><div class="status-badge area-berangkat">${STATUS_TEXT}</div></td>
                </tr>`;
            });
        }
        
        $('#tvTable').html(html);
        
        // ✨ UPDATE COUNTER (SIMPLE & RELIABLE)
        $('#busCount').text(totalCount);
        
        // Pulse effect saat counter berubah
        if (totalCount > 0) {
            $('.bus-counter').css('animation', 'none');
            setTimeout(() => {
                $('.bus-counter').css('animation', 'pulse-counter 2s infinite');
            }, 10);
        }
        
    }, 'json').fail(function(xhr) {
        console.error("❌ Error:", xhr.status);
        $('#tvTable').html(`<tr><td colspan="6" class="text-danger p-4">
            <i class="fas fa-exclamation-triangle me-2"></i>Gagal memuat data
        </td></tr>`);
    });
}

// ================= AUTO REFRESH =================
setInterval(loadTV, REFRESH_INTERVAL);

// ================= INITIAL LOAD =================
$(document).ready(function() {
    loadTV();
    
    // Reload jika tab aktif kembali
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