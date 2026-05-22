<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔊 Audio Queue Terminal - CP</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <base href="<?php echo base_url(); ?>">
    <?php if($this->security->get_csrf_token_name()): ?>
    <meta name="csrf_token_name" content="<?php echo $this->security->get_csrf_token_name(); ?>">
    <meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">
    <?php endif; ?>
      <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card { margin-bottom: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; }
        .card-header { font-weight: 600; }
        .queue-item {
            border-left: 4px solid #0d6efd;
            background: #fff;
            margin-bottom: 8px;
            padding: 12px 15px;
            border-radius: 0 8px 8px 0;
            transition: all 0.2s;
        }
        .queue-item:hover { transform: translateX(3px); box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .queue-item.playing { border-left-color: #ffc107; background: #fff3cd; }
        .queue-item.done { border-left-color: #198754; background: #d1e7dd; opacity: 0.85; }
        .queue-item.announcer { border-left-color: #6f42c1; background: #f8f2ff; }
        .queue-item.prayer { border-left-color: #198754; background: #d1e7dd; }
        .badge-type { font-size: 0.8em; padding: 0.35em 0.6em; }
        #currentAudio { font-size: 1.1em; font-weight: 500; min-height: 24px; }
        .template-preview {
            background: #e9ecef;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9em;
            border-left: 3px solid #6f42c1;
        }
        .form-control-sm, .form-select-sm { font-size: 0.9rem; }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.875rem; }
        .bg-purple { background: #6f42c1 !important; }
        .text-purple { color: #6f42c1 !important; }
        #ytPlayer { position: absolute; left: -9999px; top: -9999px; }
        .loading { opacity: 0.7; pointer-events: none; }
        .btn-custom { min-width: 100px; } 
    </style>
</head>
<body>
<div class="container py-3">
    <div class="text-center mb-3">
        <h3 class="text-white mb-0">🔊 Audio Queue System</h3>
        <small class="text-white-50">Terminal Pulo Gebang - Control Panel</small>
    </div>
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-speaker-fill me-1"></i> Status Audio Saat Ini</span>
            <span class="badge bg-light text-primary" id="queueBadge">0 pending</span>
        </div>
        <div class="card-body py-2">
            <div id="currentAudio" class="text-muted small">Tidak ada audio yang sedang diputar</div>
            <div class="mt-2 d-flex gap-2 flex-wrap">
                <button class="btn btn-success btn-sm" onclick="getNextAudio()">
                    <i class="bi bi-play-circle me-1"></i>Play Next
                </button>
                <button class="btn btn-secondary btn-sm" onclick="refreshQueue()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
                <button class="btn btn-danger btn-sm" onclick="stopAllAudio()">
                    <i class="bi bi-stop-circle me-1"></i>Stop All
                </button>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
    <!-- Header: Dibuat lebih modern dengan gradient -->
    <div class="card-header bg-success bg-gradient text-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0"><i class="bi bi-youtube me-2"></i>Background Music</h6>
        <span class="badge rounded-pill bg-white text-success" style="font-size: 0.7rem;">
            <i class="bi bi-play-fill me-1"></i>Putar Manual
        </span>
    </div>
    <div class="card-body p-4">
        <div class="row g-4 align-items-start">
            <!-- Kolom Kiri: Form Input -->
            <div class="col-lg-7">
                <form id="formYoutubeMusic">
                    <label class="form-label small fw-bold text-muted text-uppercase">Tambah Lagu Baru</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="title" placeholder="Judul (opsional)" style="flex: 1;">
                        <input type="url" class="form-control form-control-sm w-50" name="youtube_url"
                               placeholder="Paste link YouTube di sini..." required>
                        <button type="submit" class="btn btn-success btn-sm px-3">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                    <div class="form-text mt-2" style="font-size: 0.75rem;">
                        <i class="bi bi-info-circle me-1"></i> Klik tombol <strong>+</strong> lalu pilih lagu di playlist.

                    </div>

                </form>

            </div>



            <!-- Kolom Kanan: Playlist Queue -->

            <div class="col-lg-5">

                <label class="form-label small fw-bold text-muted text-uppercase">

                    <i class="bi bi-layers-half me-1"></i> Antrean Playlist

                </label>

                <div id="musicListContainer" class="border rounded bg-light p-2" style="height: 100px; overflow-y: auto;">

                    <div id="musicList" class="list-group list-group-flush shadow-sm">

                        <!-- Item Contoh (Akan diganti oleh JS) -->

                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 border-0 bg-transparent">

                            <span class="text-truncate small"><i class="bi bi-music-note-beamed me-2"></i>Memuat...</span>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- Hidden YouTube Player Container -->

        <div id="ytPlayer" class="d-none"></div>

    </div>

</div>



    <!-- Main Forms Row -->

    <div class="row g-2">

        <!-- Announcer Manual - Field Dipisah -->

        <div class="col-md-6">

            <div class="card h-100">

                <div class="card-header bg-purple text-white">

                    <i class="bi bi-mic-fill me-1"></i>🎤 Announcer Manual

                </div>

                <div class="card-body py-2">

                    <form id="formAnnouncer">

                        <div class="row g-2">

                            <div class="col-12">

                                <label class="form-label small mb-1">👤 Nama Penumpang</label>

                                <input type="text" class="form-control form-control-sm" name="penumpang"

                                       placeholder="Contoh: Bapak/Ibu. Poltak Hasugian" required>

                            </div>

                            <div class="col-6">

                                <label class="form-label small mb-1">🚌 Nama PO / Perusahaan</label>

                                <input type="text" class="form-control form-control-sm" name="po"

                                       placeholder="PO. Sinar Jaya" required>

                            </div>

                            <div class="col-6">

                                <label class="form-label small mb-1">📍 Jurusan / Rute</label>

                                <input type="text" class="form-control form-control-sm" name="jurusan"

                                       placeholder="Jakarta - Surabaya" required>

                            </div>

                            <div class="col-12">

                                <label class="form-label small mb-1">🚪 Nomor Pintu / Gate</label>

                                <input type="text" class="form-control form-control-sm" name="pintu"

                                       placeholder="Contoh: Pintu 3" required>

                            </div>

                        </div>

                       

                        <!-- Preview Template -->

                        <div class="mt-2 template-preview">

                            <small class="text-muted d-block mb-1"><i class="bi bi-eye me-1"></i>Preview:</small>

                            <span id="announcerPreview" class="small">

                                Mohon perhatian. Panggilan ditujukan kepada penumpang atas nama <strong>[Nama]</strong>.

                                Untuk penumpang bus <strong>[PO]</strong> tujuan <strong>[Jurusan]</strong>,

                                ditunggu kehadiran Anda di <strong>pintu [Gate]</strong>,

                                dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.

                            </span>

                        </div>

                       

                        <button type="submit" class="btn btn-purple btn-sm w-100 mt-2">

                            <i class="bi bi-broadcast me-1"></i>📢 Siarkan Pengumuman

                        </button>

                    </form>

                </div>

            </div>

        </div>



        <!-- Bus Entry + Prayer Button -->

        <div class="col-md-6">

            <!-- Form Bus -->

            <div class="card mb-2">

                <div class="card-header bg-info text-white">

                    <i class="bi bi-bus-front-fill me-1"></i>🚌 Bus Masuk Terminal

                </div>

                <div class="card-body py-2">

                    <form id="formBus">

                        <div class="row g-2">

                            <div class="col-6">

                                <label class="form-label small mb-1">Nomor Polisi</label>

                                <input type="text" class="form-control form-control-sm" name="nopol"

                                       placeholder="B 1234 XYZ" required maxlength="15">

                            </div>

                            <div class="col-6">

                                <label class="form-label small mb-1">Nama PO</label>

                                <input type="text" class="form-control form-control-sm" name="po"

                                       placeholder="PO. Sinar Jaya" required>

                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">

                            <i class="bi bi-plus-circle me-1"></i>Tambah ke Queue

                        </button>

                    </form>

                </div>

            </div>

           

            <!-- 🕌 Prayer Announcement Button (MANUAL) -->

            <div class="card p-3 text-center border-success">

                <div class="small text-muted mb-2">

                    <i class="bi bi-mosque-fill me-1"></i>Pengumuman Sholat - Masjid Lantai 1

                </div>

                <button class="btn btn-success btn-custom w-100" onclick="addPrayerAnnounce()">

                    🕌📢 Siarkan: Waktu Sholat Tiba

                </button>

                <small class="text-muted d-block mt-2">

                    Kepada Bapak/Ibu penumpang, bagi Anda yang ingin menunaikan ibadah salat,

                    tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal.

                    Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasih"

                </small>

            </div>

<!-- Modal Form Iklan Berjadwal -->

<div class="modal fade" id="modalAdsSchedule" tabindex="-1">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <form id="formAdsSchedule">

        <div class="modal-header bg-warning">

          <h5 class="modal-title">📅 Jadwal Himbawan / Iklan </h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

        </div>

        <div class="modal-body">

          <div class="row g-3">

            <div class="col-md-6">

              <label class="form-label small">Judul Iklan</label>

              <input type="text" class="form-control form-control-sm" name="ad_title" placeholder="Contoh: Himbauan Menjaga Kebersihan">

            </div>

            <div class="col-md-6">

              <label class="form-label small">Durasi (detik)</label>

              <select class="form-select form-select-sm" name="duration">

                <option value="15">15 detik</option>

                <option value="30" selected>30 detik</option>

                <option value="60">60 detik</option>

              </select>

            </div>

            <div class="col-md-6">

            <label class="form-label small fw-semibold">🔁 Interval Putar</label>

            <select class="form-select form-select-sm" name="interval_minutes" required>

                <option value="5">Setiap 5 menit</option>

                <option value="10">Setiap 10 menit</option>

                <option value="15">Setiap 15 menit</option>

                <option value="30" selected>Setiap 30 menit</option>

                <option value="60">Setiap 1 jam</option>

            </select>

            </div>

            <div class="col-12">

              <label class="form-label small">Pesan Iklan (akan dibacakan)</label>

              <textarea class="form-control form-control-sm" name="ad_text" rows="3" required placeholder="Ketik pesan yang ingin diumumkan..."></textarea>

            </div>

            <div class="col-md-6">

              <label class="form-label small">📅 Tanggal Mulai</label>

              <input type="date" class="form-control form-control-sm" name="start_date" required>

            </div>

            <div class="col-md-6">

              <label class="form-label small">📅 Tanggal Selesai</label>

              <input type="date" class="form-control form-control-sm" name="end_date" required>

            </div>

            <div class="col-md-6">

              <label class="form-label small">⏰ Jam Mulai</label>

              <input type="time" class="form-control form-control-sm" name="start_time" required>

            </div>

            <div class="col-md-6">

              <label class="form-label small">⏰ Jam Selesai</label>

              <input type="time" class="form-control form-control-sm" name="end_time" required>

            </div>

            <div class="col-12">

              <label class="form-label small">🔁 Ulangi Setiap Hari</label>

              <div class="d-flex gap-2 flex-wrap">

                <div class="form-check"><input class="form-check-input" type="checkbox" name="repeat_days[]" value="0" id="d0"><label class="form-check-label small" for="d0">Min</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="repeat_days[]" value="1" id="d1"><label class="form-check-label small" for="d1">Sen</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="repeat_days[]" value="2" id="d2"><label class="form-check-label small" for="d2">Sel</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="repeat_days[]" value="3" id="d3"><label class="form-check-label small" for="d3">Rab</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="repeat_days[]" value="4" id="d4"><label class="form-check-label small" for="d4">Kam</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="repeat_days[]" value="5" id="d5"><label class="form-check-label small" for="d5">Jum</label></div>

                <div class="form-check"><input class="form-check-input" type="checkbox" name="repeat_days[]" value="6" id="d6"><label class="form-check-label small" for="d6">Sab</label></div>

              </div>

            </div>

          </div>

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>

          <button type="submit" class="btn btn-warning btn-sm">💾 Simpan Jadwal</button>

        </div>

      </form>

    </div>

  </div>

</div>



<!-- Tombol buka modal (tambahkan di section Pengumuman Cepat) -->

<div class="card">

    <div class="card-header bg-warning text-dark d-flex justify-content-between">

        <span><i class="bi bi-megaphone-fill me-1"></i>📢 Pengumuman & Iklan</span>

        <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalAdsSchedule">

            <i class="bi bi-calendar-plus"></i> Jadwal Iklan

        </button>

    </div>

    <div class="card-body py-2">

        <form id="formAds" class="d-flex gap-2">

            <input type="text" class="form-control form-control-sm" name="text" placeholder="Pesan singkat..." required>

            <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-send"></i> Kirim</button>

        </form>

    </div>

</div>



        </div>

    </div>



    <!-- Quick Ads Form -->

    <div class="card">

        <div class="card-header bg-warning text-dark">

            <i class="bi bi-megaphone-fill me-1"></i>📢 Pengumuman Cepat

        </div>

        <div class="card-body py-2">

            <form id="formQuickAds" class="d-flex gap-2">

                <input type="text" class="form-control form-control-sm" name="text"

                       placeholder="Ketik pesan singkat untuk diumumkan..." required>

                <button type="submit" class="btn btn-warning btn-sm">

                    <i class="bi bi-send me-1"></i>Kirim

                </button>

            </form>

        </div>

    </div>



    <!-- Queue List with Replay Button -->

    <div class="card">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <span><i class="bi bi-list-ul me-1"></i>Daftar Queue Audio</span>

            <span class="badge bg-light text-dark" id="queueCount">0 item</span>

        </div>

        <div class="card-body py-2" style="max-height: 350px; overflow-y: auto;">

            <div id="queueList">

                <div class="text-center text-muted py-4 small">

                    <i class="bi bi-hourglass-split me-1"></i>Memuat queue...

                </div>

            </div>

        </div>

    </div>

   

    <!-- Footer -->

    <div class="text-center text-white-50 small py-2">

        <i class="bi bi-clock me-1"></i>Auto-refresh setiap 10 detik •

        <span id="lastUpdate">Last: -</span>

    </div>

</div>



<!-- Scripts -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://www.youtube.com/iframe_api"></script>



<script>

// ================= CONFIG =================

const BASE_URL = "<?php echo rtrim(base_url(), '/') . '/'; ?>";

const prayerSchedule = {

test:  "08:50",    

subuh:  "04:45",

    dzuhur: "12:05",

    ashar:  "15:20",

    maghrib:"18:00",

    isya:   "19:10"

};

let ytPlayer = null;

let currentSpeech = null;

let isProcessingQueue = false;  // 🔥 Hanya satu flag, lebih simpel

let audioMutex = false;          // 🔥 Mutex untuk race condition

let musicPlaylist = [];

let currentTrackIndex = -1;

let adsData = [];

let lastPrayerTrigger = {};

let adsInterval = null;



function getPriority(type) {

    switch(type) {

        case 'prayer': return 5;

        case 'bus': return 4;

        case 'announcer': return 3;

        case 'ads': return 2;

        default: return 1;

    }

}



// ================= PAUSE & RESUME YOUTUBE =================

function pauseYoutube() {

    if (ytPlayer && ytPlayer.pauseVideo) {

        ytPlayer.pauseVideo();

        console.log("⏸️ YouTube Paused");

    }

}



function resumeYoutube() {

    if (ytPlayer && ytPlayer.playVideo) {

        ytPlayer.playVideo();

        console.log("▶️ YouTube Resumed");

    }

}



// ================= CSRF HELPER =================

function getCsrfData() {

    let data = {};

    let name = $('meta[name="csrf_token_name"]').attr('content');

    let hash = $('meta[name="csrf_token"]').attr('content');

    if(name && hash) data[name] = hash;

    return data;

}



// ================= AJAX HELPER (CSRF-Safe) =================

function ciPost(url, formData, callback) {

    $.ajax({

        url: BASE_URL + url,

        type: 'POST',

        data: typeof formData === 'string' ? formData + '&' + $.param(getCsrfData()) : {...formData, ...getCsrfData()},

        dataType: 'json',

        timeout: 10000,

        success: function(res) { if(callback) callback(res); },

        error: function(xhr, status, err) {

            console.error('❌ POST Error:', {url: BASE_URL+url, status: xhr.status, response: xhr.responseText?.substring(0,300)});

            let msg = 'Koneksi gagal';

            if(xhr.status === 404) msg = 'URL tidak ditemukan';

            else if(xhr.status === 403) msg = 'CSRF Error - Refresh halaman (F5)';

            else if(xhr.status === 500) msg = 'Server Error';

            Swal.fire('Error!', msg, 'error');

        }

    });

}



function ciGet(url, callback) {

    $.ajax({

        url: BASE_URL + url,

        type: 'GET',

        data: getCsrfData(),

        dataType: 'json',

        timeout: 10000,

        success: function(res) { if(callback) callback(res); },

        error: function(xhr) {

            console.error('❌ GET Error:', BASE_URL+url, xhr.status);

        }

    });

}



function loadAdsSchedule() {

    ciGet('audio/get_ads_schedule', function(res) {



        if(res && res.length > 0) {

            adsData = res;



            // parse JSON repeat_days

            adsData.forEach(ad => {

                try {

                    ad.repeat_days = ad.repeat_days ? JSON.parse(ad.repeat_days) : [];

                } catch(e) {

                    ad.repeat_days = [];

                }

            });



            console.log("📅 Jadwal iklan loaded:", adsData);

        }

    });

}



function subtractMinutes(time, minutes) {

    let [h, m] = time.split(':').map(Number);

    let date = new Date();

    date.setHours(h);

    date.setMinutes(m - minutes);



    return date.toTimeString().slice(0,5);

}

function isSameMinute(now, targetTime) {

    let [h, m] = targetTime.split(':').map(Number);



    let target = new Date();

    target.setHours(h, m, 0, 0);



    let diff = Math.abs(now - target);



    return diff < 60000; // toleransi 1 menit

}



function checkPrayerSchedule() {

    let now = new Date();

     console.log("⏰ Cek waktu:", now.toLocaleTimeString());



    Object.entries(prayerSchedule).forEach(([name, time]) => {



        let reminderTime = subtractMinutes(time, 10);



        // 🔔 REMINDER 10 MENIT

        if (isSameMinute(now, reminderTime)) {



            if (lastPrayerTrigger[name + '_reminder'] === reminderTime) return;

            lastPrayerTrigger[name + '_reminder'] = reminderTime;



            let text = `Perhatian, waktu sholat ${name} akan segera tiba dalam 10 menit. Silakan bersiap menuju masjid terminal.`;



            ciPost('audio/add_prayer_announce', { text: text }, function(res){

                console.log("🕌 REMINDER RESPONSE:", res);

                if(res?.status === 'ok') refreshQueue();

            });

        }



        // 🕌 WAKTU SHOLAT

        if (isSameMinute(now, time)) {



            if (lastPrayerTrigger[name] === time) return;

            lastPrayerTrigger[name] = time;



            let text = `Kepada seluruh penumpang, waktu sholat ${name} telah tiba. , bagi Anda yang ingin menunaikan ibadah salat, tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal. Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasih.`;



            ciPost('audio/add_prayer_announce', { text: text }, function(res){

                console.log("🕌 SHOLAT RESPONSE:", res);

                if(res?.status === 'ok') refreshQueue();

            });

        }



    });

}

// ================= INIT =================

$(document).ready(function() {

    refreshQueue();

    loadMusicList();

    initAnnouncerPreview();

    updateLastUpdate();

   

   

    // Auto refresh queue

    //(() => { refreshQueue(); updateLastUpdate(); }, 10000);



    loadAdsSchedule();



// 🔁 CEK QUEUE CEPAT (3 DETIK)

setInterval(() => {

    // Hanya fetch jika tidak sedang memproses

    if (!isProcessingQueue && !audioMutex) {

        fetchAndPlayPending();

    }

    // ✅ YouTube resume otomatis di dalam fetchAndPlayPending() jika queue kosong

}, 4000);



// 🔥 JALANKAN IKLAN OTOMATIS

setInterval(() => {

    checkAndPlayAds();

}, 60000); // cek tiap 1 menit



// 🔁 Refresh UI hanya untuk update status (tidak mempengaruhi audio)

setInterval(() => {

    refreshQueue();

    updateLastUpdate();

      checkPrayerSchedule();

}, 20000);  // 20 detik cukup untuk UI



    // Form: YouTube Music (FIX: encode URL)

 

$('#formYoutubeMusic').on('submit', function(e) {
    e.preventDefault();
    Swal.fire({title: 'Menambahkan...', didOpen: () => Swal.showLoading()});
    
    // ✅ BENAR: Kirim apa adanya, jQuery $.param() handle encoding otomatis
    ciPost('audio/add_youtube_music', $(this).serialize(), function(res) {
        if(res?.status === 'ok') {
            Swal.fire('✅ Berhasil!', 'Musik YouTube ditambahkan', 'success');
            $('#formYoutubeMusic')[0].reset();
            loadMusicList();
        } else Swal.fire('❌ Gagal!', res?.message || 'Link tidak valid', 'error');
    });
});

$('#formQuickAds').on('submit', function(e) {
    e.preventDefault();

    let text = $(this).find('input[name="text"]').val();

    if(!text) {
        Swal.fire('⚠️ Kosong!', 'Isi dulu', 'warning');
        return;
    }

    ciPost('audio/add_ads', Object.assign({
        text: text
    }, getCsrfData()), function(res) {

        if(res?.status === 'ok') {
            Swal.fire('✅ Berhasil!', 'Masuk queue', 'success');
            $('#formQuickAds')[0].reset();
            refreshQueue();
        } else {
            Swal.fire('❌ Gagal!', res?.message || 'Error', 'error');
        }
    });
});

$('#formAdsSchedule').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);

    let startDate = form.find('input[name="start_date"]').val();
    let endDate   = form.find('input[name="end_date"]').val();
    let startTime = form.find('input[name="start_time"]').val();
    let endTime   = form.find('input[name="end_time"]').val();

    if (startDate > endDate) {
        Swal.fire('Error', 'Tanggal selesai harus setelah tanggal mulai!', 'error');
        return;
    }

    if (startTime >= endTime) {
        Swal.fire('Error', 'Jam selesai harus setelah jam mulai!', 'error');
        return;
    }

    // 🔥 ambil repeat_days
    let repeatDays = [];
    form.find('input[name="repeat_days[]"]:checked').each(function() {
        repeatDays.push($(this).val());
    });

    // 🔥 ambil semua data
    let data = {
        ad_title: form.find('[name="ad_title"]').val(),
        ad_text: form.find('[name="ad_text"]').val(),
        duration: form.find('[name="duration"]').val(),
        interval_minutes: form.find('[name="interval_minutes"]').val(),
        start_date: startDate,
        end_date: endDate,
        start_time: startTime,
        end_time: endTime,
        repeat_days: JSON.stringify(repeatDays)
    };

    Swal.fire({
        title: 'Menyimpan...',
        didOpen: () => Swal.showLoading()
    });

    // ✅ KIRIM KE CI3
    ciPost('audio/save_ads_schedule', data, function(res) {

        if(res?.status === 'ok') {

            Swal.fire('✅ Berhasil!', 'Jadwal disimpan', 'success');

            $('#modalAdsSchedule').modal('hide');
            form[0].reset();

            // reload data schedule
            loadAdsSchedule();

        } else {
            Swal.fire('❌ Gagal!', res?.message || 'Error server', 'error');
        }

    });

});
function playAd(ad) {

    console.log("📢 Trigger iklan:", ad.ad_title);

    ciPost('audio/add_ads', {
        text: ad.ad_text
    }, function(res) {
        if(res?.status === 'ok') {
            console.log("✅ Iklan masuk queue");
        }
    });

}

function checkAndPlayAds() {
    let now = new Date();

    adsData.forEach(ad => {

        if (ad.is_active != 1) return;

        let today = now.toISOString().split('T')[0];
        let currentDay = now.getDay();

        if (today < ad.start_date || today > ad.end_date) return;

        if (ad.repeat_days.length > 0 && !ad.repeat_days.includes(currentDay.toString())) return;

        let currentTime = now.toTimeString().slice(0,8);

        if (currentTime < ad.start_time || currentTime > ad.end_time) return;

        if (ad.last_played) {
            let last = new Date(ad.last_played.replace(' ', 'T'));
            let diffMinutes = (now - last) / 60000;

            if (diffMinutes < ad.interval_minutes) return;
        }

        console.log("✅ IKLAN MASUK:", ad.ad_title);

        playAd(ad);

        ciPost('audio/update_last_played', { id: ad.id });

        ad.last_played = now.toISOString();
    });
}
    
    // Form: Announcer
    $('#formAnnouncer').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({title: 'Mengirim...', didOpen: () => Swal.showLoading()});
        ciPost('audio/add_announcer', $(this).serialize(), function(res) {
            if(res?.status === 'ok') {
                Swal.fire('✅ Berhasil!', 'Pengumuman masuk queue', 'success');
                $('#formAnnouncer')[0].reset();
                updateAnnouncerPreview();
                refreshQueue();
            } else Swal.fire('❌ Gagal!', 'Cek koneksi', 'error');
        });
    });
    
    // Form: Bus
    $('#formBus').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({title: 'Menambahkan Bus...', didOpen: () => Swal.showLoading()});
        ciPost('audio/add_bus', $(this).serialize(), function(res) {
            if(res?.status === 'ok') {
                Swal.fire('✅ Berhasil!', 'Bus ditambahkan ke queue', 'success');
                $('#formBus')[0].reset();
                refreshQueue();
            } else Swal.fire('❌ Gagal!', 'Cek koneksi', 'error');
        });
    });
    
    // Form: Ads
    $('#formAds').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({title: 'Mengirim...', didOpen: () => Swal.showLoading()});
        ciPost('audio/add_ads', $(this).serialize(), function(res) {
            if(res?.status === 'ok') {
                Swal.fire('✅ Berhasil!', 'Pengumuman ditambahkan', 'success');
                $('#formAds')[0].reset();
                refreshQueue();
            } else Swal.fire('❌ Gagal!', 'Cek koneksi', 'error');
        });
    });
});

// ================= YOUTUBE PLAYER INIT (FIXED) =================
function onYouTubeIframeAPIReady() {
    console.log('🎬 YouTube iframe API ready!');
    
    ytPlayer = new YT.Player('ytPlayer', {
        height: '0', 
        width: '0',
        videoId: '', // Jangan set videoId di sini
        playerVars: { 
            'autoplay': 0, 
            'controls': 0, 
            'modestbranding': 1,
            'rel': 0,
            'iv_load_policy': 3
        },
        events: {
            'onReady': function(e) {
    console.log('✅ YouTube Player Ready');
    // ❌ JANGAN auto-play langsung
    // ✅ Biarkan user klik play manual, atau tunggu queue kosong
    // Jika ingin auto-start musik: hanya jika queue benar-benar kosong
    setTimeout(() => {
        ciGet('audio/get_next_audio', function(res) {
            if (!res || !res.id) {
                // Queue kosong, boleh mulai musik
                if (musicPlaylist.length > 0) {
                    playYoutubeByIndex(0);
                }
            }
        });
    }, 3000);
},
            'onStateChange': function(e) {
    console.log('🎬 YouTube state:', e.data);
    if(e.data === YT.PlayerState.ENDED) {
        // Jika lagu selesai, putar index berikutnya
        let nextIndex = currentTrackIndex + 1;
        if (nextIndex < musicPlaylist.length) {
            playYoutubeByIndex(nextIndex);
        } else {
            // Opsional: Kembali ke lagu pertama (loop playlist)
            playYoutubeByIndex(0);
        }
    }
},
            'onError': function(e) {
                console.error('❌ YouTube error:', e.data);
                Swal.fire('Error!', 'YouTube error code: ' + e.data, 'error');
            }
        }
    });
}

// ================= PLAY YOUTUBE MANUAL (FIXED) =================
function playYoutube(videoId, title, showPopup = true) {
    if(!ytPlayer || !ytPlayer.loadVideoById) return;

    stopAllAudio();
    ytPlayer.loadVideoById(videoId);
    ytPlayer.unMute();
    ytPlayer.playVideo();

    if(showPopup) {
        Swal.fire({
            icon: 'info',
            title: '🎵 Memutar Musik',
            text: title,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    }
}


// ================= ANNOUNCER PREVIEW =================
function initAnnouncerPreview() {
    $('input[name="penumpang"], input[name="po"], input[name="jurusan"], input[name="pintu"]')
        .on('input', updateAnnouncerPreview);
}
function updateAnnouncerPreview() {
    let nama = $('input[name="penumpang"]').val() || '[Nama]';
    let po = $('input[name="po"]').val() || '[PO]';
    let jurusan = $('input[name="jurusan"]').val() || '[Jurusan]';
    let pintu = $('input[name="pintu"]').val() || '[Gate]';
    
    let preview = `Mohon perhatian. Panggilan ditujukan kepada penumpang atas nama <strong>${nama}</strong>. ` +
                  `Untuk penumpang bus <strong>${po}</strong> tujuan <strong>${jurusan}</strong>, ` +
                  `ditunggu kehadiran Anda di <strong>pintu ${pintu}</strong>, ` +
                  `dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.`;
    
    $('#announcerPreview').html(preview);
}

// ================= PRAYER ANNOUNCE (MANUAL BUTTON) =================
function addPrayerAnnounce() {
    Swal.fire({
        title: '🕌 Siarkan Pengumuman Sholat?',
        html: 'Pesan:<br><em>"Kepada Bapak/Ibu penumpang, bagi Anda yang ingin menunaikan ibadah salat, tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal. Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasi."</em>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Ya, Siarkan',
        cancelButtonText: 'Batal'
    }).then(r => {
        if(r.isConfirmed) {
            Swal.fire({title: 'Mengirim...', didOpen: () => Swal.showLoading()});
            
            let text = "Kepada seluruh penumpang, waktu sholat telah tiba. " +
                      "Silakan melaksanakan sholat  di masjid lantai 1 area terminal. " +
                      "Terima kasih.";
            
            ciPost('audio/add_prayer_announce', {text: text, ...getCsrfData()}, function(res) {
                if(res?.status === 'ok') {
                    Swal.fire('✅ Berhasil!', 'Pengumuman sholat masuk queue', 'success');
                    refreshQueue();
                } else Swal.fire('❌ Gagal!', 'Cek koneksi', 'error');
            });
        }
    });
}

// ================= MUSIC LIST =================
function loadMusicList() {
    ciGet('audio/get_music_list', function(res) {
        musicPlaylist = res || []; // Simpan ke variabel global
        let html = '';
        
        if(musicPlaylist.length > 0) {
            musicPlaylist.forEach((m, index) => {
                let vId = extractVideoId(m.youtube_url);
                html += `
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 border-0" 
                     id="track-${index}" onclick="playYoutubeByIndex(${index})">
                    <div class="text-truncate small">
                        <i class="bi bi-music-note-beamed me-2"></i>${m.title || 'Untitled'}
                    </div>
                    <i class="bi bi-play-fill text-success"></i>
                </div>`;
            });
        } else {
            html = '<div class="p-2 text-center text-muted small">Playlist Kosong</div>';
        }
        $('#musicList').html(html);
    });
}
function playYoutubeByIndex(index) {
    if (index >= musicPlaylist.length) {
        console.log("Sudah di akhir playlist.");
        currentTrackIndex = -1; 
        return;
    }

    currentTrackIndex = index;
    let track = musicPlaylist[index];
    let vId = extractVideoId(track.youtube_url);
    
    // Highlight di UI (opsional: agar tahu lagu mana yang aktif)
    $('.list-group-item').removeClass('bg-success text-white');
    $(`#track-${index}`).addClass('bg-success text-white');

    // Panggil fungsi play utama (tanpa popup penutup otomatis)
    playYoutube(vId, track.title, false); 
}

// Helper untuk ambil ID dari link YouTube
function extractVideoId(url) {
    let match = url.match(/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([^& \n]+)/);
    return (match && match[1]) ? match[1] : null;
}
function deleteMusic(id) {
    Swal.fire({
        title: 'Hapus musik ini?', icon: 'warning',
        showCancelButton: true, confirmButtonText: 'Ya, Hapus'
    }).then(r => {
        if(r.isConfirmed) {
            ciGet('audio/delete_music/'+id, function() { loadMusicList(); Swal.fire('Terhapus','','success'); });
        }
    });
}

// ================= STOP ALL AUDIO =================
function stopAllAudio() {
    if (ytPlayer?.stopVideo) ytPlayer.stopVideo();

    if (ytPlayer?.mute) ytPlayer.mute();

    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
    }
}

// ================= FETCH AND PLAY PENDING =================
function fetchAndPlayPending() {
    // 🔒 Jangan jalankan jika sedang memproses atau mutex aktif
    if (isProcessingQueue || audioMutex) {
        console.log('⏳ Queue sedang diproses, tunggu...');
        return;
    }

    // 🔐 Kunci mutex
    audioMutex = true;

    ciGet('audio/get_next_audio', function(res) {
        // 🔓 Buka mutex setelah response
        audioMutex = false;

        // Validasi: hanya ambil yang pending
        if (!res || !res.id || res.status !== 'pending') {
            // ✅ Queue kosong? Resume YouTube
            if (!isProcessingQueue) {
                resumeYoutube();
            }
            return;
        }

        console.log("🎯 PLAY QUEUE:", res.id, res.type);
        
        // 🔥 Set flag processing
        isProcessingQueue = true;
        
        // 🛑 Stop YouTube TOTAL sebelum mulai
        stopAllAudio();
        hardPauseYoutube();
        
        // Delay kecil: pastikan YouTube benar-benar mati
        setTimeout(() => {
            playQueueItemSequential(res);
        }, 800);
    });
}

// ================= YOUTUBE CONTROL (HARD STOP) =================
function hardPauseYoutube() {
    if (!ytPlayer) return;

    try {
        ytPlayer.pauseVideo();   // ⏸️ tetap di posisi sekarang
        ytPlayer.mute();         // 🔇 mute saja
        console.log('⏸️ YouTube paused (safe)');
    } catch(e) {}
}

function resumeYoutube() {
    // 🔒 Jangan resume jika queue masih aktif
    if (isProcessingQueue || audioMutex) {
        console.log('⏳ Queue aktif, YouTube tetap pause');
        return;
    }
    
    if (!ytPlayer) return;
    try {
        if (typeof ytPlayer.unMute === 'function') {
            ytPlayer.unMute();
        }
        if (typeof ytPlayer.setVolume === 'function') {
            ytPlayer.setVolume(100);
        }
        if (typeof ytPlayer.playVideo === 'function') {
            ytPlayer.playVideo();
        }
        console.log('🔊 YouTube resumed');
    } catch(e) {
        console.warn('⚠️ Resume YouTube error:', e);
    }
}

// ================= STOP ALL AUDIO (UNIFIED) =================
function stopAllAudio() {
    console.log('🛑 STOP ALL AUDIO');
    
    // 1. Stop YouTube
    hardPauseYoutube();
    
    // 2. Cancel TTS
    if ('speechSynthesis' in window) {
        try {
            window.speechSynthesis.cancel();
            currentSpeech = null;
        } catch(e) {}
    }
}

// ================= PLAY QUEUE ITEM (SEQUENTIAL) =================
function playQueueItemSequential(item) {
    console.log("🔊 MEMULAI:", item.type, "ID:", item.id);
    
    // Bersihkan teks dari HTML tag
    let cleanText = item.text.replace(/<[^>]*>/g, '').trim();
    
    // 🔥 Cancel semua speech yang mungkin masih berjalan
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
    }
    
    // Mulai TTS
    speakTextSequential(cleanText, function() {
        console.log("✅ TTS Selesai, mark as done:", item.id);
        
        // Update status di database
        markAsDone(item.id);
        
        // 🔓 Reset flag processing
        isProcessingQueue = false;
        
        // Delay sebelum cek queue berikutnya (anti rapid-fire)
        setTimeout(() => {
            if (!isProcessingQueue) {
                fetchAndPlayPending();  // 🔁 Lanjut ke item berikutnya
            }
        }, 1500);
    });
}

// ================= TEXT TO SPEECH (SEQUENTIAL SAFE) =================
function speakTextSequential(text, onComplete) {
    if (!('speechSynthesis' in window)) {
        console.warn('⚠️ TTS not supported');
        if (onComplete) onComplete();
        return;
    }
    
    // Pastikan bersih dulu
    window.speechSynthesis.cancel();
    
    // Load voices (Chrome quirk)
    let voices = window.speechSynthesis.getVoices();
    if (voices.length === 0) {
        window.speechSynthesis.onvoiceschanged = function() {
            startSpeaking();
        };
        return;
    }
    startSpeaking();
    
    function startSpeaking() {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = 0.9;   // Sedikit lebih lambat = lebih jelas
        utterance.pitch = 1.0;
        
        // Cari voice Indonesia
        const idVoice = voices.find(v => 
            v.lang.includes('id') || v.name.toLowerCase().includes('indonesian')
        );
        if (idVoice) utterance.voice = idVoice;
        
        // 🔥 Flag untuk hindari double-callback
        let isFinished = false;
        
        const finish = function() {
            if (isFinished) return;
            isFinished = true;
            console.log('🔊 TTS finished callback');
            if (onComplete) onComplete();
        };
        
        utterance.onend = finish;
        utterance.onerror = function(e) {
            console.error('❌ TTS Error:', e.error);
            finish(); // Tetap lanjut walau error
        };
        
        // 🔥 Fallback timeout: estimasi durasi berdasarkan panjang teks
        // Rate 0.9 = ~10 char/detik, tambah buffer 3 detik
        let estimatedMs = Math.max(3000, (text.length / 10) * 1000 + 3000);
        setTimeout(() => {
            if (!isFinished) {
                console.warn('⚠️ TTS timeout fallback');
                finish();
            }
        }, estimatedMs);
        
        // Mulai bicara
        window.speechSynthesis.speak(utterance);
        currentSpeech = utterance;
        console.log('🗣️ Speaking:', text.substring(0, 40) + '...');
    }
}
function startAdsScheduler(intervalMinutes = 30) {
    if (adsInterval) clearInterval(adsInterval);

    adsInterval = setInterval(() => {
        console.log("📢 Waktu iklan!");

        ciPost('audio/add_ads', {
            text: "Perhatian! Nikmati promo spesial hari ini di area terminal. Terima kasih."
        }, function(res) {
            if(res?.status === 'ok') {
                console.log("✅ Iklan masuk queue");
            }
        });

    }, intervalMinutes * 60 * 1000);
}

// ================= PLAY QUEUE ITEM =================
function playQueueItem(item, priority) {

    audioLock = true;
    isPlayingQueue = true;
    currentPriority = priority;

    console.log("🔊 PLAY:", item.type, "priority:", priority);

    pauseYoutube();
    killYoutubeAudio();

    let ttsText = item.text.replace(/<[^>]*>/g, '');

    window.speechSynthesis.cancel();

    speakText(ttsText, function() {

        console.log("✅ SELESAI:", item.type);

        markAsDone(item.id);

        isPlayingQueue = false;
        audioLock = false;
        currentPriority = 0;

        setTimeout(() => {
            resumeYoutube();
        }, 2000);

    });
}
function killYoutubeAudio() {
    try {
        if (ytPlayer) {
            ytPlayer.pauseVideo();   // ⏸️ pause saja
            ytPlayer.mute();         // 🔇 biar tidak bocor suara
        }
    } catch(e) {}
}

// ================= GET NEXT AUDIO =================
function getNextAudio() {
    fetchAndPlayPending();
}
function playSpecific(id) {
    ciGet('audio/get_next_audio', function(res) {
        if(res && res.id == id) { playQueueItem(res); refreshQueue(); }
    });
}

// ================= MARK DONE =================
function markAsDone(id) {
    ciGet('audio/done_audio/' + id, function(res) {
        if(res?.status === 'done') { 
            refreshQueue(); 
            Swal.fire({icon: 'success', title: 'Selesai!', timer: 1000, showConfirmButton: false}); 
        }
    });
}

// ================= REPLAY QUEUE ITEM (TOMBOL 🔄) =================
function replayItem(id) {
    Swal.fire({
        title: '🔄 Ulangi pengumuman ini?',
        text: 'Item akan dikembalikan ke antrian untuk diputar ulang',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Ya, Ulangi',
        cancelButtonText: 'Batal'
    }).then(r => {
        if(r.isConfirmed) {
            ciGet('audio/replay_queue_item/' + id, function(res) {
                if(res?.status === 'ok') {
                    Swal.fire('Berhasil!', 'Pengumuman siap diputar ulang', 'success');
                    refreshQueue();
                }
            });
        }
    });
}

// ================= REFRESH QUEUE (dengan tombol REPLAY) =================
function refreshQueue() {
    ciGet('audio/get_all_queue', function(data) {
        let html = '', count = 0, pending = 0;
        
        if(!data || !data.length) {
            html = '<div class="text-center text-muted py-4 small"><i class="bi bi-check-circle me-1"></i>Queue kosong 🎉</div>';
        } else {
            $.each(data, function(i, item) {
                if(item.status === 'pending') pending++;
                
                // Badge & icon per type
              let badge = '';
                let icon = '';
                let cls = ''; // ✅ TAMBAHAN INI (PENTING)

                if(item.type === 'bus') {
                    badge = 'bg-info';
                    icon = '🚌 BUS';
                    cls = 'bus';
                }
                else if(item.type === 'announcer') {
                    badge = 'bg-primary';
                    icon = '🎤 ANNOUNCER';
                    cls = 'announcer';
                }
                else if(item.type === 'prayer') {
                    badge = 'bg-success';
                    icon = '🕌 SHOLAT';
                    cls = 'prayer';
                }
                else if(item.type === 'ads') {
                    badge = 'bg-warning text-dark';
                    icon = '📢 IKLAN';
                    cls = 'ads';
                }
                
                // ✅ Tombol: Play / Done / Replay
                let btn = '';
                if(item.status === 'pending') {
                    btn = `<button class="btn btn-sm btn-success me-1" onclick="playSpecific(${item.id})" title="Putar"><i class="bi bi-play-fill"></i></button>`;
                } else if(item.status === 'playing') {
                    btn = `<button class="btn btn-sm btn-primary me-1" onclick="markAsDone(${item.id})" title="Selesai"><i class="bi bi-check-lg"></i></button>`;
                } else {
                    // ✅ Tombol REPLAY untuk item selesai (khusus announcer/prayer)
                    if(item.type === 'announcer' || item.type === 'prayer') {
                        btn = `<button class="btn btn-sm btn-outline-secondary me-1" onclick="replayItem(${item.id})" title="Ulangi Panggilan"><i class="bi bi-arrow-clockwise"></i></button>`;
                    } else {
                        btn = `<span class="text-muted small me-1"><i class="bi bi-check-circle-fill"></i></span>`;
                    }
                }
                
                let displayText = item.text.length > 80 ? item.text.substring(0,80)+'...' : item.text;
                
                html += `<div class="queue-item ${cls} rounded">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1 me-2">
                            <span class="badge ${badge} badge-type">${icon} ${item.type}</span>
                            <strong class="ms-2 small">#${item.id}</strong>
                            ${item.priority <= 2 ? '<span class="badge bg-danger ms-1" style="font-size:0.7em">PRIORITAS</span>' : ''}
                            <p class="mb-1 mt-2 small text-dark">${displayText}</p>
                            <small class="text-muted" style="font-size:0.75em">Status: ${item.status}</small>
                        </div>
                        <div class="flex-shrink-0">${btn}</div>
                    </div>
                </div>`;
                count++;
            });
        }
        
        $('#queueList').html(html);
        $('#queueCount').text(count + ' item');
        $('#queueBadge').text(pending + ' pending');
        
        // Update current audio display
        if(data && data.length > 0) {
            let playing = data.find(x => x.status === 'playing');
            if(playing) {
                $('#currentAudio').html(`<span class="text-primary">${playing.text.replace(/<[^>]*>/g, '').substring(0, 70)}${playing.text.length > 70 ? '...' : ''}</span>`);
            }
        }
    });
}

// ================= TEXT TO SPEECH =================
function speakText(text, callback) {
    if (!('speechSynthesis' in window)) return;

    const utterance = new SpeechSynthesisUtterance(text);

    utterance.lang = 'id-ID';
    utterance.rate = 0.85; // 🔥 lebih pelan = lebih stabil
    utterance.pitch = 1;

    let voices = speechSynthesis.getVoices();
    let voice = voices.find(v => v.lang === 'id-ID');

    if (voice) utterance.voice = voice;

    let finished = false;

    utterance.onend = function() {
        if (finished) return;
        finished = true;

        console.log("✅ TTS BENAR-BENAR SELESAI");
        if (callback) callback();
    };

    utterance.onerror = function(e) {
        console.error("❌ ERROR TTS:", e);
        if (!finished && callback) callback();
    };

    // 🔥 fallback kalau browser bug
    setTimeout(() => {
        if (!finished) {
            console.warn("⚠️ FORCE END (fallback)");
            finished = true;
            if (callback) callback();
        }
    }, text.length * 120); // estimasi durasi

    speechSynthesis.speak(utterance);
}
// Load voices early (Chrome quirk)
if('speechSynthesis' in window) {
    window.speechSynthesis.onvoiceschanged = function() {
        console.log('🔊 Voices loaded');
    };
    window.speechSynthesis.getVoices();
}

function updateLastUpdate() {
    let now = new Date();
    $('#lastUpdate').text('Last: ' + now.toLocaleTimeString('id-ID'));
}
</script>
</body>
</html>