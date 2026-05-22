<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Dashboard Operasional — TT Pulo Gebang</title>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Tailwind (CDN) -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />

<style>
  :root{
    --bg:#f8fafc; --card:#ffffff; --muted:#6b7280; --accent:#2563eb;
  }
  body{ background:var(--bg); font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto; }
  .card{ background:var(--card); border-radius:12px; padding:16px; box-shadow:0 6px 20px rgba(16,24,40,0.06); }
  .stat-card{ text-align:center; padding:1rem; border-radius:12px; }
  .stat-value{ font-size:1.6rem; font-weight:700; margin-top:.4rem; color:#111827; }
  .stat-label{ font-size:.85rem; color:var(--muted); }
  .chart-wrap{ height:300px; position:relative; }
  .enter{ opacity:0; transform:translateY(8px); animation:enter .45s ease forwards; }
  @keyframes enter{ to{ opacity:1; transform:none; } }
</style>
</head>
<body class="p-6">

<div class="max-w-7xl mx-auto space-y-6">

  <!-- Header -->
  <header class="mb-2">
    <h1 class="text-2xl font-semibold text-gray-700">📊 Dashboard Operasional — Terminal Terpadu Pulo Gebang</h1>
    <p class="text-sm text-gray-500 mt-1">
      Periode: <strong>
        <?= htmlspecialchars($start_date ?? date('Y-m-01')) ?> 
        s/d 
        <?= htmlspecialchars($end_date ?? date('Y-m-d')) ?>
      </strong>
    </p>
  </header>

  <!-- Filter Form -->
  <form id="filterForm" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
    <div class="card enter">
      <label class="text-xs text-gray-600">Tahun</label>
      <select name="tahun" class="mt-2 w-full border rounded px-2 py-1 text-sm" onchange="this.form.submit()">
        <?php for($y = date('Y'); $y >= 2020; $y--): ?>
          <option value="<?= $y ?>" <?= ($tahun ?? date('Y')) == $y ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
      </select>
    </div>

    <div class="card enter">
      <label class="text-xs text-gray-600">Bulan</label>
      <select name="bulan" class="mt-2 w-full border rounded px-2 py-1 text-sm" onchange="this.form.submit()">
        <?php
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        foreach($months as $k => $v):
        ?>
          <option value="<?= $k ?>" <?= ($bulan ?? date('m')) == $k ? 'selected' : '' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="card enter flex items-end">
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm font-medium transition">
        🔄 Terapkan
      </button>
    </div>

    <div class="card enter flex items-end">
      <a href="<?= site_url('dashboard2') ?>" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded text-sm font-medium text-center transition">
        🗙 Reset
      </a>
    </div>
  </form>

  <!-- Summary Cards -->
  <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
    <div class="card stat-card enter bg-blue-50">
      <div class="stat-label">🚌 Bus Berangkat</div>
      <div class="stat-value"><?= number_format($busberangkat ?? 0) ?></div>
    </div>
    <div class="card stat-card enter bg-green-50">
      <div class="stat-label">🚌 Bus Datang</div>
      <div class="stat-value"><?= number_format($busdatang ?? 0) ?></div>
    </div>
    <div class="card stat-card enter bg-amber-50">
      <div class="stat-label">🧍 Pnp Berangkat</div>
      <div class="stat-value"><?= number_format($pnpberangkat ?? 0) ?></div>
    </div>
    <div class="card stat-card enter bg-red-50">
      <div class="stat-label">🧍 Pnp Datang</div>
      <div class="stat-value"><?= number_format($pnpdatang ?? 0) ?></div>
    </div>
    <div class="card stat-card enter bg-purple-50">
      <div class="stat-label">🧍 Total Penumpang</div>
      <div class="stat-value"><?= number_format($totalpnp ?? 0) ?></div>
    </div>
  </div>

  <!-- 🔁 2 Grafik Tahunan -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card enter">
      <h3 class="font-semibold text-gray-700 mb-2">📈 Perbandingan Bus (<?= $tahun ?? date('Y') ?>)</h3>
      <p class="text-xs text-gray-500 mb-2">Datang vs Berangkat per bulan</p>
      <div class="chart-wrap">
        <canvas id="chartYearlyBus"></canvas>
      </div>
    </div>

    <div class="card enter">
      <h3 class="font-semibold text-gray-700 mb-2">📈 Perbandingan Penumpang (<?= $tahun ?? date('Y') ?>)</h3>
      <p class="text-xs text-gray-500 mb-2">Datang vs Berangkat per bulan</p>
      <div class="chart-wrap">
        <canvas id="chartYearlyPnp"></canvas>
      </div>
    </div>
  </div>

  <!-- 4 Main Charts (Harian) -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card enter">
      <h3 class="font-semibold text-gray-700 mb-2">Grafik Harian — Keberangkatan Penumpang</h3>
      <div class="chart-wrap">
        <canvas id="chartDepartPassenger"></canvas>
      </div>
    </div>

    <div class="card enter">
      <h3 class="font-semibold text-gray-700 mb-2">Grafik Harian — Kedatangan Penumpang</h3>
      <div class="chart-wrap">
        <canvas id="chartArrivePassenger"></canvas>
      </div>
    </div>

    <div class="card enter">
      <h3 class="font-semibold text-gray-700 mb-2">Grafik Harian — Keberangkatan Bus</h3>
      <div class="chart-wrap">
        <canvas id="chartDepartBus"></canvas>
      </div>
    </div>

    <div class="card enter">
      <h3 class="font-semibold text-gray-700 mb-2">Grafik Harian — Kedatangan Bus</h3>
      <div class="chart-wrap">
        <canvas id="chartArriveBus"></canvas>
      </div>
    </div>
  </div>

  <footer class="mt-6 text-center text-xs text-gray-500">
    © <?= date('Y') ?> Terminal Terpadu Pulo Gebang — Realtime & historis operasional
  </footer>

</div>

<!-- ======================
     Data dari PHP ke JS
     ====================== -->
<script>
// --- Data Harian ---
const dataGrafik = <?= $penumpang_data ?? '[]' ?>;

// --- Data Tahunan (Jan–Des atau Jan–bulan ini) ---
const yearlyData = <?= $yearly_data_json ?? '[]' ?>;

// Helper
const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
const labelsHarian = dataGrafik.map(d => {
  const dt = new Date(d.tanggal);
  return isNaN(dt) ? d.tanggal : dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
});

// Data harian
const keberangkatanPnp  = dataGrafik.map(d => d.keberangkatan_pnp || 0);
const kedatanganPnp     = dataGrafik.map(d => d.kedatangan_pnp || 0);
const keberangkatanBus  = dataGrafik.map(d => d.keberangkatan_bus || 0);
const kedatanganBus     = dataGrafik.map(d => d.kedatangan_bus || 0);

// Data tahunan — lengkapi 12 bulan
const yearlyLabels = [];
const datangBus = Array(12).fill(0);
const berangkatBus = Array(12).fill(0);
const datangPnp = Array(12).fill(0);
const berangkatPnp = Array(12).fill(0);

yearlyData.forEach(row => {
  const idx = parseInt(row.bulan) - 1;
  if (idx >= 0 && idx < 12) {
    datangBus[idx] = parseInt(row.kedatangan_bus) || 0;
    berangkatBus[idx] = parseInt(row.keberangkatan_bus) || 0;
    datangPnp[idx] = parseInt(row.kedatangan_pnp) || 0;
    berangkatPnp[idx] = parseInt(row.keberangkatan_pnp) || 0;
  }
});

// Isi label sesuai bulan yang ada (misal: 2025 → Jan–Nov)
const currentMonth = new Date().getMonth(); // 0-based
const selectedYear = <?= json_encode($tahun ?? date('Y')) ?>;
const isCurrentYear = selectedYear == new Date().getFullYear();
const maxMonth = isCurrentYear ? currentMonth : 11;

for (let i = 0; i <= maxMonth; i++) {
  yearlyLabels.push(monthNames[i]);
}

// Potong data sesuai bulan aktif
const datangBusShow = datangBus.slice(0, maxMonth + 1);
const berangkatBusShow = berangkatBus.slice(0, maxMonth + 1);
const datangPnpShow = datangPnp.slice(0, maxMonth + 1);
const berangkatPnpShow = berangkatPnp.slice(0, maxMonth + 1);

// Utility: Line + Bar Chart (gabungan)
function createComparisonChart(canvasId, label1, data1, label2, data2, color1, color2) {
  const ctx = document.getElementById(canvasId);
  if (!ctx) return;
  if (Chart.getChart(ctx)) Chart.getChart(ctx).destroy();

  return new Chart(ctx, {
    type: 'bar',
    data: {
      labels: yearlyLabels,
      datasets: [
        {
          label: label1,
          data: data1,
          backgroundColor: color1 + '80', // 50% opacity
          borderColor: color1,
          borderWidth: 1,
          borderRadius: 4,
        },
        {
          label: label2,
          data: data2,
          backgroundColor: color2 + '80',
          borderColor: color2,
          borderWidth: 1,
          borderRadius: 4,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            font: { size: 12 },
            padding: 10
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { color: '#4b5563' }
        },
        y: {
          beginAtZero: true,
          grid: { color: 'rgba(0,0,0,0.05)' },
          ticks: { color: '#4b5563' }
        }
      },
      animation: { duration: 700 }
    }
  });
}

// Utility: Line Chart (harian)
function createLineChart(canvasId, label, data, borderColor) {
  const ctx = document.getElementById(canvasId);
  if (!ctx) return;
  if (Chart.getChart(ctx)) Chart.getChart(ctx).destroy();
  
  return new Chart(ctx, {
    type: 'line',
    data: {
      labels: labelsHarian.length ? labelsHarian : ['-'],
      datasets: [{
        label: label,
        data: data.length ? data : [0],
        borderColor: borderColor,
        backgroundColor: borderColor + '20',
        borderWidth: 2,
        tension: 0.3,
        pointRadius: 2,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: '#4b5563' }, grid: { color: 'rgba(0,0,0,0.02)' } },
        y: { beginAtZero: true, ticks: { color: '#4b5563' }, grid: { color: 'rgba(0,0,0,0.02)' } }
      },
      animation: { duration: 600 }
    }
  });
}

// 🚀 Inisialisasi Chart
document.addEventListener('DOMContentLoaded', () => {
  // 2 Grafik Tahunan
  createComparisonChart(
    'chartYearlyBus',
    'Bus Datang', datangBusShow,
    'Bus Berangkat', berangkatBusShow,
    '#10b981', '#3b82f6'
  );
  createComparisonChart(
    'chartYearlyPnp',
    'Pnp Datang', datangPnpShow,
    'Pnp Berangkat', berangkatPnpShow,
    '#ef4444', '#f59e0b'
  );

  // 4 Grafik Harian
  createLineChart('chartDepartPassenger', 'Keberangkatan Penumpang', keberangkatanPnp, '#f59e0b');
  createLineChart('chartArrivePassenger', 'Kedatangan Penumpang', kedatanganPnp, '#ef4444');
  createLineChart('chartDepartBus', 'Keberangkatan Bus', keberangkatanBus, '#3b82f6');
  createLineChart('chartArriveBus', 'Kedatangan Bus', kedatanganBus, '#10b981');
});
</script>

</body>
</html>