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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <base href="<?php echo base_url(); ?>">
    <?php if($this->security->get_csrf_token_name()): ?>
    <meta name="csrf_token_name" content="<?php echo $this->security->get_csrf_token_name(); ?>">
    <meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">
    <?php endif; ?>
    
    <style>
        :root {
            --primary-start: #667eea;
            --primary-end: #764ba2;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
            --card-hover: 0 8px 30px rgba(0,0,0,0.12);
            --transition: all 0.25s ease;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, var(--primary-start) 0%, var(--primary-end) 100%);
            background-attachment: fixed;
            min-height: 100vh;
            color: #2d3748;
            padding-bottom: 2rem;
        }

        /* Container */
        .container { max-width: 1200px; }

        /* Header */
        .header-title {
            text-shadow: 0 2px 10px rgba(0,0,0,0.15);
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header-subtitle {
            opacity: 0.95;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        /* Cards - Enhanced but compatible */
        .card {
            margin-bottom: 16px;
            box-shadow: var(--card-shadow);
            border: none;
            border-radius: 16px;
            transition: var(--transition);
            background: rgba(255,255,255,0.98);
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-hover);
        }
        .card-header {
            font-weight: 600;
            border-radius: 16px 16px 0 0 !important;
            padding: 0.875rem 1.25rem;
            font-size: 0.95rem;
        }
        .card-body { padding: 1rem 1.25rem; }
        .card-body.py-2 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }

        /* Queue Items - Same structure, better visuals */
        .queue-item {
            border-left: 4px solid #0d6efd;
            background: #fff;
            margin-bottom: 8px;
            padding: 12px 15px;
            border-radius: 0 10px 10px 0;
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .queue-item:hover {
            transform: translateX(4px);
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }
        .queue-item.playing { border-left-color: #ffc107; background: #fff9e6; }
        .queue-item.done { border-left-color: #198754; background: #e8f5e9; opacity: 0.9; }
        .queue-item.announcer { border-left-color: #6f42c1; background: #f3e5f5; }
        .queue-item.prayer { border-left-color: #198754; background: #e8f5e9; }
        .queue-item.ads { border-left-color: #fd7e14; background: #fff3e0; }
        .queue-item.bus { border-left-color: #0dcaf0; background: #e0f7fa; }

        .badge-type { font-size: 0.75em; padding: 0.3em 0.55em; font-weight: 500; }
        
        #currentAudio {
            font-size: 1.05rem;
            font-weight: 500;
            min-height: 24px;
            padding: 0.5rem 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid var(--primary-start);
        }

        /* Template Preview */
        .template-preview {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 12px;
            border-radius: 10px;
            font-size: 0.9em;
            border-left: 3px solid #6f42c1;
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* Form Controls */
        .form-control-sm, .form-select-sm {
            font-size: 0.9rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        .form-control-sm:focus, .form-select-sm:focus {
            border-color: var(--primary-start);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        }
        .form-label.small { font-weight: 500; color: #4a5568; }

        /* Buttons */
        .btn-sm {
            padding: 0.4rem 0.85rem;
            font-size: 0.875rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }
        .btn-sm:hover { transform: translateY(-1px); }
        
        .btn-purple {
            background: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }
        .btn-purple:hover {
            background: #5a32a3;
            border-color: #5a32a3;
            color: white;
        }
        
        .btn-custom { min-width: 100px; }

        /* Badge */
        #queueBadge {
            padding: 0.4em 0.8em;
            border-radius: 50px;
            font-weight: 500;
        }

        /* Music List */
        #musicListContainer {
            border-radius: 12px;
            background: #f8f9fa;
        }
        .list-group-item {
            padding: 0.6rem 0.85rem;
            border: none;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: var(--transition);
            cursor: pointer;
        }
        .list-group-item:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }
        .list-group-item.bg-success { color: white; }

        /* YouTube Player */
        #ytPlayer { position: absolute; left: -9999px; top: -9999px; }

        /* Loading State */
        .loading { opacity: 0.7; pointer-events: none; }

        /* Modal */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .modal-header { border-radius: 16px 16px 0 0 !important; }

        /* Footer */
        .footer-text {
            opacity: 0.9;
            font-size: 0.85rem;
        }

        /* Responsive Enhancements */
        @media (max-width: 768px) {
            .container { padding: 0 12px; }
            .card-body { padding: 0.875rem 1rem; }
            .btn-sm { width: 100%; margin-bottom: 4px; }
            .btn-group-actions { flex-direction: column; }
            .queue-item { padding: 10px 12px; }
            .header-title { font-size: 1.4rem; }
            .input-group { flex-wrap: wrap; }
            .input-group .form-control { min-width: 100%; margin-bottom: 0.5rem; }
            .input-group .btn { width: 100%; }
        }

        @media (max-width: 480px) {
            .queue-meta { flex-wrap: wrap; gap: 4px; }
            .badge-type { font-size: 0.7em; }
            .header-title { font-size: 1.25rem; }
            .card-header { font-size: 0.9rem; padding: 0.75rem 1rem; }
        }

        /* Utility */
        .text-gradient {
            background: linear-gradient(135deg, #fff, rgba(255,255,255,0.9));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-purple { background: #6f42c1 !important; }
        .text-purple { color: #6f42c1 !important; }
        
        /* Animation for new items */
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .queue-item { animation: slideIn 0.2s ease-out; }
    </style>
</head>
<body>

<div class="container py-3">
    <!-- Header -->
    <div class="text-center mb-3">
        <h3 class="text-white mb-0 header-title">🔊 Audio Queue System</h3>
        <small class="text-white-50 header-subtitle">Terminal Pulo Gebang - Control Panel</small>
    </div>

    <!-- Alert Audio Blocked -->
    <div id="audioBlockedAlert" class="alert alert-warning border-0 shadow-sm mb-3 animate__animated animate__fadeIn">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span style="font-size: 1.5rem;">🚨</span>
                <div>
                    <h6 class="alert-heading fw-bold mb-0 text-dark">Sistem Suara Terkunci / Muted</h6>
                    <small class="text-dark opacity-75">Browser membatasi pemutaran audio otomatis. Silakan klik tombol di samping atau klik mana saja di halaman ini untuk mengaktifkan suara pengumuman.</small>
                </div>
            </div>
            <button class="btn btn-warning btn-sm fw-bold border-dark px-3" onclick="unlockAudio(event)">
                🔓 Aktifkan Suara
            </button>
        </div>
    </div>

    <!-- Status Card -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-speaker-fill me-1"></i> Status Audio Saat Ini</span>
            <span class="badge bg-light text-primary" id="queueBadge">0 pending</span>
        </div>
        <div class="card-body py-2">
            <div id="currentAudio" class="text-muted small mb-2">Tidak ada audio yang sedang diputar</div>
            <div class="mt-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#ttsSettingsCollapse">
                    <i class="bi bi-gear-fill me-1"></i> Pengaturan Suara
                </button>
            </div>

            <!-- COLLAPSIBLE TTS SETTINGS -->
            <div class="collapse mt-3 border-top pt-3" id="ttsSettingsCollapse">
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-sliders me-1"></i> Pengaturan Pengeras Suara (Text-to-Speech)</h6>
                <div class="row g-4">
                    <!-- ID VOICE SETTINGS -->
                    <div class="col-md-6 border-md-end">
                        <span class="fw-bold text-dark d-block mb-2 small"><i class="bi bi-flag-fill text-danger me-1"></i> Pengaturan Bahasa Indonesia</span>
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1" style="font-size: 0.75rem;">🗣️ Pilihan Suara (Voice)</label>
                            <select id="ttsVoiceSelect" class="form-select form-select-sm">
                                <option value="">Memindai suara perangkat...</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1" style="font-size: 0.75rem;">⏩ Kecepatan Bicara (Rate)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" id="ttsRateRange" class="form-range form-range-sm" min="0.5" max="1.5" step="0.05" value="0.9">
                                <span id="ttsRateVal" class="badge bg-secondary">0.90x</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1" style="font-size: 0.75rem;">🎼 Nada Suara (Pitch)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" id="ttsPitchRange" class="form-range form-range-sm" min="0.5" max="1.5" step="0.05" value="1.0">
                                <span id="ttsPitchVal" class="badge bg-secondary">1.0</span>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="testTtsVoice('id')">
                                <i class="bi bi-soundwave me-1"></i> Tes Suara ID
                            </button>
                        </div>
                    </div>

                    <!-- EN VOICE SETTINGS -->
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark small"><i class="bi bi-flag-fill text-danger me-1"></i> Pengaturan Bahasa Indonesia (Suara 2)</span>
                            <div class="form-check form-switch small">
                                <input class="form-check-input" type="checkbox" id="ttsEnVoiceToggle" checked>
                                <label class="form-check-label text-muted" for="ttsEnVoiceToggle" id="ttsEnVoiceToggleLabel">Aktif</label>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1" style="font-size: 0.75rem;">🗣️ Pilihan Suara (Voice)</label>
                            <select id="ttsEnVoiceSelect" class="form-select form-select-sm">
                                <option value="">Memindai suara perangkat...</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1" style="font-size: 0.75rem;">⏩ Kecepatan Bicara (Rate)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" id="ttsEnRateRange" class="form-range form-range-sm" min="0.5" max="1.5" step="0.05" value="0.9">
                                <span id="ttsEnRateVal" class="badge bg-secondary">0.90x</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1" style="font-size: 0.75rem;">🎼 Nada Suara (Pitch)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" id="ttsEnPitchRange" class="form-range form-range-sm" min="0.5" max="1.5" step="0.05" value="1.0">
                                <span id="ttsEnPitchVal" class="badge bg-secondary">1.0</span>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="testTtsVoice('en')">
                                <i class="bi bi-soundwave me-1"></i> Tes Suara ID 2
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mt-1 border-top pt-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 0.75rem;">🔁 Jumlah Putar Global (Global Repeat)</label>
                        <select id="ttsGlobalRepeatSelect" class="form-select form-select-sm">
                            <option value="1">1 Kali</option>
                            <option value="2">2 Kali</option>
                            <option value="3">3 Kali</option>
                            <option value="4">4 Kali</option>
                            <option value="5">5 Kali</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 0.75rem;">⏱️ Jeda Antar Putaran Global (Global Delay)</label>
                        <select id="ttsGlobalDelaySelect" class="form-select form-select-sm">
                            <option value="1">1 Detik</option>
                            <option value="1.5">1.5 Detik</option>
                            <option value="2">2 Detik</option>
                            <option value="3">3 Detik</option>
                            <option value="4">4 Detik</option>
                            <option value="5">5 Detik</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- YouTube Music Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-success bg-gradient text-white d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0"><i class="bi bi-youtube me-2"></i>Background Music</h6>
            <div class="d-flex align-items-center gap-2">
                <!-- Pause/Play Controls -->
                <button type="button" class="btn btn-light btn-sm text-success fw-bold py-1 px-3 shadow-sm" onclick="togglePlayPauseMusic()" id="btnMusicPlayPause" style="font-size: 0.8rem; border-radius: 20px;">
                    <i class="bi bi-pause-fill me-1"></i>Pause
                </button>
                <button type="button" class="btn btn-light btn-sm text-danger fw-bold py-1 px-3 shadow-sm" onclick="stopMusicPlayback()" title="Stop musik" style="font-size: 0.8rem; border-radius: 20px;">
                    <i class="bi bi-stop-fill me-1"></i>Stop
                </button>
                <button type="button" class="btn btn-light btn-sm text-primary fw-bold py-1 px-3 shadow-sm" onclick="restartPlaylist()" title="Putar dari awal" style="font-size: 0.8rem; border-radius: 20px;">
                    <i class="bi bi-arrow-clockwise me-1"></i>Restart
                </button>
                <span class="badge rounded-pill bg-white text-success" style="font-size: 0.7rem;">
                    <i class="bi bi-play-fill me-1"></i>Putar Manual
                </span>
            </div>
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
                            </button>
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
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 border-0 bg-transparent">
                                <span class="text-truncate small"><i class="bi bi-music-note-beamed me-2"></i>Memuat...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="ytPlayer" class="d-none"></div>
        </div>
    </div>

    <!-- Main Forms Row -->
    <div class="row g-2">
        <!-- Announcer Manual -->
        <div class="col-md-6 d-flex flex-column">
            <div class="card flex-grow-1 mb-0 d-flex flex-column">
                <div class="card-header bg-purple text-white">
                    <i class="bi bi-mic-fill me-1"></i>🎤 Announcer Manual
                </div>
                <div class="card-body py-2 d-flex flex-column">
                    <form id="formAnnouncer" class="d-flex flex-column flex-grow-1 justify-content-between">
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
                            <div class="col-4">
                                <label class="form-label small mb-1">🚪 Nomor Pintu / Gate</label>
                                <input type="text" class="form-control form-control-sm" name="pintu"
                                       placeholder="Contoh: Pintu 3" required>
                            </div>
                            <div class="col-4">
                                <label class="form-label small mb-1">🔁 Jumlah Putar</label>
                                <select class="form-select form-select-sm" name="repeat" required>
                                    <option value="1">1 Kali</option>
                                    <option value="2" selected>2 Kali</option>
                                    <option value="3">3 Kali</option>
                                    <option value="4">4 Kali</option>
                                    <option value="5">5 Kali</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label small mb-1">⏱️ Jeda (Detik)</label>
                                <select class="form-select form-select-sm" name="delay" required>
                                    <option value="1">1 Detik</option>
                                    <option value="1.5" selected>1.5 Detik</option>
                                    <option value="2">2 Detik</option>
                                    <option value="3">3 Detik</option>
                                    <option value="4">4 Detik</option>
                                    <option value="5">5 Detik</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 flex-grow-1 d-flex flex-column justify-content-end">
                            <div class="template-preview mb-2">
                                <small class="text-muted d-block mb-1"><i class="bi bi-eye me-1"></i>Preview:</small>
                                <span id="announcerPreview" class="small">
                                    Mohon perhatian. Panggilan ditujukan kepada penumpang atas nama <strong>[Nama]</strong>.
                                    Untuk penumpang bus <strong>[PO]</strong> tujuan <strong>[Jurusan]</strong>,
                                    ditunggu kehadiran Anda di <strong>pintu [Gate]</strong>,
                                    dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.
                                </span>
                            </div>
                            <button type="submit" class="btn btn-purple btn-sm w-100">
                                <i class="bi bi-broadcast me-1"></i>📢 Siarkan Pengumuman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bus Entry + Prayer Button -->
        <div class="col-md-6 d-flex flex-column">
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
                            <div class="col-12 mt-1">
                                <label class="form-label small mb-1">Tujuan Akhir</label>
                                <input type="text" class="form-control form-control-sm" name="tujuan"
                                       placeholder="Contoh: Surabaya / Solo">
                            </div>
                            <div class="col-12 mt-1">
                                <label class="form-label small mb-1">Status Lanjutan (Area Pelayanan)</label>
                                <select name="target_area" class="form-control form-control-sm">
                                    <option value="">-- Hanya Masuk --</option>
                                    <option value="kedatangan">Kedatangan</option>
                                    <option value="pengendapan">Pengendapan</option>
                                    <option value="keberangkatan">Keberangkatan</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">
                            <i class="bi bi-plus-circle me-1"></i>Tambah ke Queue
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Prayer Announcement Button -->
            <div class="card flex-grow-1 d-flex flex-column mb-0">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span><i class="bi bi-mosque-fill me-1"></i> Pengumuman & Jadwal Sholat</span>
                    <span id="prayerDateText" class="badge bg-white text-success fw-bold" style="font-size: 0.8rem;">-</span>
                </div>
                <div class="card-body py-2 d-flex flex-column justify-content-between flex-grow-1">
                    <div class="d-flex justify-content-between gap-1 flex-wrap mb-3 text-center" id="prayerTimeContainer">
                        <div class="flex-grow-1 p-1 bg-light border rounded" style="font-size: 0.75rem;">
                            <div class="text-muted" style="font-size: 0.7rem;">Subuh</div>
                            <strong id="time-subuh" class="text-success">04:45</strong>
                        </div>
                        <div class="flex-grow-1 p-1 bg-light border rounded" style="font-size: 0.75rem;">
                            <div class="text-muted" style="font-size: 0.7rem;">Dzuhur</div>
                            <strong id="time-dzuhur" class="text-success">12:05</strong>
                        </div>
                        <div class="flex-grow-1 p-1 bg-light border rounded" style="font-size: 0.75rem;">
                            <div class="text-muted" style="font-size: 0.7rem;">Ashar</div>
                            <strong id="time-ashar" class="text-success">15:20</strong>
                        </div>
                        <div class="flex-grow-1 p-1 bg-light border rounded" style="font-size: 0.75rem;">
                            <div class="text-muted" style="font-size: 0.7rem;">Maghrib</div>
                            <strong id="time-maghrib" class="text-success">18:00</strong>
                        </div>
                        <div class="flex-grow-1 p-1 bg-light border rounded" style="font-size: 0.75rem;">
                            <div class="text-muted" style="font-size: 0.7rem;">Isya</div>
                            <strong id="time-isya" class="text-success">19:10</strong>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-success btn-custom w-100" onclick="addPrayerAnnounce()">
                            <i class="bi bi-broadcast me-1"></i>🕌📢 Siarkan: Waktu Sholat Tiba
                        </button>
                        <div class="mt-2 text-center" style="font-size: 0.7rem; color: #198754; font-weight: 500;">
                            <i class="bi bi-cloud-check-fill me-1"></i> Sinkronisasi Jadwal Sholat API Otomatis Aktif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengumuman & Iklan Card -->
    <div class="card mt-3 mb-3">
        <div class="card-header bg-warning text-dark">
            <span><i class="bi bi-megaphone-fill me-1"></i>📢 Pengumuman & Iklan</span>
        </div>
        <div class="card-body py-2">
            <form id="formAds" class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" name="text" placeholder="Pesan singkat..." required>
                <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-send"></i> Kirim</button>
            </form>
        </div>
    </div>

    <!-- Quick Ads Form -->
    <div class="card mb-3">
        <div class="card-header bg-warning text-dark">
            <i class="bi bi-megaphone-fill me-1"></i>📢 Pengumuman Cepat
        </div>
        <div class="card-body py-2">
            <form id="formQuickAds" class="d-flex flex-column gap-2">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" name="text"
                           placeholder="Ketik pesan singkat untuk diumumkan..." required>
                    <button type="submit" class="btn btn-warning btn-sm" style="white-space: nowrap;">
                        <i class="bi bi-send me-1"></i>Kirim
                    </button>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small mb-0" style="font-size: 0.75rem;">🔁 Jumlah Putar</label>
                        <select class="form-select form-select-sm" name="repeat" required>
                            <option value="1" selected>1 Kali</option>
                            <option value="2">2 Kali</option>
                            <option value="3">3 Kali</option>
                            <option value="4">4 Kali</option>
                            <option value="5">5 Kali</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-0" style="font-size: 0.75rem;">⏱️ Jeda (Detik)</label>
                        <select class="form-select form-select-sm" name="delay" required>
                            <option value="1">1 Detik</option>
                            <option value="1.5" selected>1.5 Detik</option>
                            <option value="2">2 Detik</option>
                            <option value="3">3 Detik</option>
                            <option value="4">4 Detik</option>
                            <option value="5">5 Detik</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Queue List -->
    <div class="card mb-3">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">
            <span><i class="bi bi-list-ul me-1"></i>Daftar Queue Audio</span>
            <div class="d-flex gap-2 align-items-center">
                <button class="btn btn-sm btn-warning text-dark fw-bold" onclick="stopQueue()" id="btnStopQueue" title="Stop/Hentikan antrian otomatis">
                    <i class="bi bi-stop-fill me-1"></i>Stop
                </button>
                <button class="btn btn-sm btn-success fw-bold" onclick="resumeQueue()" id="btnResumeQueue" title="Lanjutkan antrian otomatis">
                    <i class="bi bi-play-fill me-1"></i>Lanjutkan
                </button>
                <button class="btn btn-sm btn-danger" onclick="clearQueue()" title="Hapus semua antrian">
                    <i class="bi bi-trash-fill me-1"></i>Bersihkan
                </button>
                <span class="badge bg-light text-dark" id="queueCount">0 item</span>
            </div>
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
    <div class="text-center text-white-50 small py-2 footer-text">
        <i class="bi bi-clock me-1"></i>Auto-refresh setiap 10 detik •
        <span id="lastUpdate">Last: -</span>
    </div>
</div>

<!-- ⚠️ SCRIPT ASLI TETAP UTUH - JANGAN DIUBAH ⚠️ -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://www.youtube.com/iframe_api"></script>

<script>
// ================= CONFIG =================
const BASE_URL = "<?php echo rtrim(base_url(), '/') . '/'; ?>";
let prayerSchedule = {
    subuh:  "04:45",
    dzuhur: "12:05",
    ashar:  "15:20",
    maghrib:"18:00",
    isya:   "19:10"
};

function getIndonesianDate() {
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const now = new Date();
    const dayName = days[now.getDay()];
    const date = now.getDate();
    const monthName = months[now.getMonth()];
    const year = now.getFullYear();
    return `${dayName}, ${date} ${monthName} ${year}`;
}

function updatePrayerUiList(hijriInfo = null) {
    $('#time-subuh').text(prayerSchedule.subuh);
    $('#time-dzuhur').text(prayerSchedule.dzuhur);
    $('#time-ashar').text(prayerSchedule.ashar);
    $('#time-maghrib').text(prayerSchedule.maghrib);
    $('#time-isya').text(prayerSchedule.isya);

    let dateStr = getIndonesianDate();
    if (hijriInfo) {
        dateStr += ` / ${hijriInfo.day} ${hijriInfo.month.en} ${hijriInfo.year} H`;
    }
    $('#prayerDateText').text(dateStr);
}

function fetchPrayerTimes() {
    $.ajax({
        url: 'https://api.aladhan.com/v1/timingsByCity?city=Jakarta&country=Indonesia',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res && res.data && res.data.timings) {
                let t = res.data.timings;
                prayerSchedule.subuh = t.Fajr;
                prayerSchedule.dzuhur = t.Dhuhr;
                prayerSchedule.ashar = t.Asr;
                prayerSchedule.maghrib = t.Maghrib;
                prayerSchedule.isya = t.Isha;
                console.log("🕌 Jadwal sholat hari ini tersinkronisasi otomatis (API):", prayerSchedule);
                
                let hj = (res.data.date && res.data.date.hijri) ? res.data.date.hijri : null;
                updatePrayerUiList(hj);
            }
        },
        error: function() {
            console.warn("⚠️ Gagal sinkronisasi API jadwal sholat, menggunakan waktu cadangan (hardcoded).");
            updatePrayerUiList();
        }
    });
}
let ytPlayer = null;
let currentSpeech = null;
let isProcessingQueue = false;
let audioMutex = false;
let isQueueManuallyStopped = false;
let musicPlaylist = [];
let currentTrackIndex = -1;
let currentPlayingAudioId = null;
let adsData = [];
let lastPrayerTrigger = {};
let adsInterval = null;
let isMusicPausedByQueue = false;

// ================= TTS CONFIG =================
let selectedVoiceName = localStorage.getItem('tts_voice_name') || '';
let selectedEnVoiceName = localStorage.getItem('tts_en_voice_name') || '';
let ttsRate = parseFloat(localStorage.getItem('tts_rate') || '0.9');
let ttsPitch = parseFloat(localStorage.getItem('tts_pitch') || '1.0');
let ttsEnRate = parseFloat(localStorage.getItem('tts_en_rate') || '0.9');
let ttsEnPitch = parseFloat(localStorage.getItem('tts_en_pitch') || '1.0');
let ttsGlobalRepeat = parseInt(localStorage.getItem('tts_global_repeat') || '1');
let ttsGlobalDelay = parseFloat(localStorage.getItem('tts_global_delay') || '1.5');
let isMusicManuallyPaused = localStorage.getItem('music_manually_paused') === 'true';

function populateTtsVoices() {
    if (!('speechSynthesis' in window)) return;
    let voices = window.speechSynthesis.getVoices();
    let selectId = $('#ttsVoiceSelect');
    let selectEn = $('#ttsEnVoiceSelect');
    selectId.empty();
    selectEn.empty();

    if (voices.length === 0) {
        selectId.append('<option value="">Tidak ada suara terdeteksi</option>');
        selectEn.append('<option value="">Tidak ada suara terdeteksi</option>');
        return;
    }

    // Urutkan suara Indonesia duluan untuk selectId
    let idVoices = [...voices].sort((a, b) => {
        let aIndo = a.lang.includes('id') || a.name.toLowerCase().includes('indonesian');
        let bIndo = b.lang.includes('id') || b.name.toLowerCase().includes('indonesian');
        if (aIndo && !bIndo) return -1;
        if (!aIndo && bIndo) return 1;
        return a.name.localeCompare(b.name);
    });

    // Urutkan suara Indonesia duluan juga untuk selectEn (Bahasa Indonesia Suara 2)
    let enVoices = [...voices].sort((a, b) => {
        let aIndo = a.lang.includes('id') || a.name.toLowerCase().includes('indonesian');
        let bIndo = b.lang.includes('id') || b.name.toLowerCase().includes('indonesian');
        if (aIndo && !bIndo) return -1;
        if (!aIndo && bIndo) return 1;
        return a.name.localeCompare(b.name);
    });

    let hasSelectedId = false;
    idVoices.forEach(voice => {
        let isIndo = voice.lang.includes('id') || voice.name.toLowerCase().includes('indonesian');
        let label = `${voice.name} (${voice.lang})${isIndo ? ' 🇮🇩 [Rekomendasi]' : ''}`;
        let option = $('<option></option>').val(voice.name).text(label);
        
        if (selectedVoiceName === voice.name) {
            option.prop('selected', true);
            hasSelectedId = true;
        } else if (!selectedVoiceName && isIndo && !hasSelectedId) {
            option.prop('selected', true);
            selectedVoiceName = voice.name;
            hasSelectedId = true;
        }
        selectId.append(option);
    });

    let hasSelectedEn = false;
    enVoices.forEach(voice => {
        let isIndo = voice.lang.includes('id') || voice.name.toLowerCase().includes('indonesian');
        let label = `${voice.name} (${voice.lang})${isIndo ? ' 🇮🇩 [Rekomendasi]' : ''}`;
        let option = $('<option></option>').val(voice.name).text(label);
        
        if (selectedEnVoiceName === voice.name) {
            option.prop('selected', true);
            hasSelectedEn = true;
        } else if (!selectedEnVoiceName && isIndo && !hasSelectedEn) {
            option.prop('selected', true);
            selectedEnVoiceName = voice.name;
            hasSelectedEn = true;
        }
        selectEn.append(option);
    });
}

function testTtsVoice(lang) {
    let sampleText = "";
    let langCode = 'id-ID';
    let voices = [];
    if ('speechSynthesis' in window) {
        voices = window.speechSynthesis.getVoices();
    }

    if (lang === 'en') {
        langCode = 'en-US';
        let targetVoice = voices.find(v => v.name === selectedEnVoiceName);
        let voiceLang = targetVoice ? targetVoice.lang.toLowerCase() : 'id';
        if (voiceLang.startsWith('id')) {
            sampleText = "Perhatian. Menguji pengaturan suara announcer Bahasa Indonesia dua. Terima kasih.";
        } else {
            sampleText = "Attention. Testing voice announcer configuration two. Thank you.";
        }
    } else {
        langCode = 'id-ID';
        let targetVoice = voices.find(v => v.name === selectedVoiceName);
        let voiceLang = targetVoice ? targetVoice.lang.toLowerCase() : 'id';
        if (voiceLang.startsWith('id')) {
            sampleText = "Perhatian. Menguji pengaturan suara announcer Bahasa Indonesia satu. Terima kasih.";
        } else {
            sampleText = "Attention. Testing voice announcer configuration one. Thank you.";
        }
    }

    stopAllAudio();
    speakTextSequential(sampleText, langCode, function() {
        Swal.fire({
            icon: 'success',
            title: 'Tes Selesai',
            text: 'Suara berhasil diuji!',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
}

function unlockAudio(event) {
    if (event) event.stopPropagation();
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        let utterance = new SpeechSynthesisUtterance(" ");
        window.speechSynthesis.speak(utterance);
        
        Swal.fire({
            icon: 'success',
            title: 'Suara Aktif',
            text: 'Izin suara berhasil didapatkan dari browser!',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
        
        $('#audioBlockedAlert').addClass('d-none');
    }
}

function getPriority(type) {
    switch(type) {
        case 'prayer': return 5;
        case 'bus': return 4;
        case 'announcer': return 3;
        case 'ads': return 2;
        default: return 1;
    }
}

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

function getCsrfData() {
    let data = {};
    let name = $('meta[name="csrf_token_name"]').attr('content');
    let hash = $('meta[name="csrf_token"]').attr('content');
    if(name && hash) data[name] = hash;
    return data;
}

function ciPost(url, formData, callback) {
    return $.ajax({
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
    return $.ajax({
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
    return diff < 60000;
}

function checkPrayerSchedule() {
    let now = new Date();
    console.log("⏰ Cek waktu:", now.toLocaleTimeString());
    Object.entries(prayerSchedule).forEach(([name, time]) => {
        let reminderTime = subtractMinutes(time, 10);
        let nameCap = name.charAt(0).toUpperCase() + name.slice(1);
        if (isSameMinute(now, reminderTime)) {
            if (lastPrayerTrigger[name + '_reminder'] === reminderTime) return;
            lastPrayerTrigger[name + '_reminder'] = reminderTime;
            let text_id = `Perhatian, waktu sholat ${name} akan segera tiba dalam 10 menit. Silakan bersiap menuju masjid terminal.`;
            let text_en = `Attention, the prayer time for ${nameCap} will arrive in 10 minutes. Please prepare to proceed to the terminal mosque.`;
            let text = text_id + " | " + text_en;
            ciPost('audio/add_prayer_announce', { text: text }, function(res){
                console.log("🕌 REMINDER RESPONSE:", res);
                if(res?.status === 'ok') refreshQueue();
            });
        }
        if (isSameMinute(now, time)) {
            if (lastPrayerTrigger[name] === time) return;
            lastPrayerTrigger[name] = time;
            let text_id = `Kepada seluruh penumpang, waktu sholat ${name} telah tiba. Bagi Anda yang ingin menunaikan ibadah salat, tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal. Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasih.`;
            let text_en = `To all passengers, the prayer time for ${nameCap} has arrived. For those who wish to perform the prayer, a comfortable mosque facility is available on the 1st floor of the terminal. Let us remain diligent in our prayers during your journey. Thank you.`;
            let text = text_id + " | " + text_en;
            ciPost('audio/add_prayer_announce', { text: text }, function(res){
                console.log("🕌 SHOLAT RESPONSE:", res);
                if(res?.status === 'ok') refreshQueue();
            });
        }
    });
}

$(document).ready(function() {
    // ================= INITIALIZE TTS CONTROLS =================
    if ('speechSynthesis' in window) {
        window.speechSynthesis.onvoiceschanged = function() {
            populateTtsVoices();
        };
        populateTtsVoices();
    }

    // Auto-unlock audio on first body click
    $(document).on('click', function() {
        if ('speechSynthesis' in window && !window.speechSynthesis.speaking) {
            try {
                let u = new SpeechSynthesisUtterance("");
                window.speechSynthesis.speak(u);
            } catch(e){}
        }
        $('#audioBlockedAlert').addClass('d-none');

        // Auto-play/resume YouTube music if not playing and not manually paused
        if (ytPlayer && typeof ytPlayer.getPlayerState === 'function') {
            let state = ytPlayer.getPlayerState();
            if (state !== YT.PlayerState.PLAYING && !isMusicManuallyPaused) {
                if (currentTrackIndex === -1 && musicPlaylist.length > 0) {
                    playYoutubeByIndex(0);
                } else {
                    resumeYoutube();
                }
            }
        }
    });
    
    // Set initial values from localStorage
    $('#ttsRateRange').val(ttsRate);
    $('#ttsRateVal').text(ttsRate.toFixed(2) + 'x');
    $('#ttsPitchRange').val(ttsPitch);
    $('#ttsPitchVal').text(ttsPitch.toFixed(1));

    $('#ttsEnRateRange').val(ttsEnRate);
    $('#ttsEnRateVal').text(ttsEnRate.toFixed(2) + 'x');
    $('#ttsEnPitchRange').val(ttsEnPitch);
    $('#ttsEnPitchVal').text(ttsEnPitch.toFixed(1));

    $('#ttsGlobalRepeatSelect').val(ttsGlobalRepeat);
    $('#ttsGlobalDelaySelect').val(ttsGlobalDelay);

    // Initial state for Voice 2 Toggle
    let initialVoice2Enabled = localStorage.getItem('tts_en_voice_enabled') !== 'false';
    $('#ttsEnVoiceToggle').prop('checked', initialVoice2Enabled);
    if (initialVoice2Enabled) {
        $('#ttsEnVoiceToggleLabel').text('Aktif');
        $('#ttsEnVoiceSelect, #ttsEnRateRange, #ttsEnPitchRange').prop('disabled', false);
    } else {
        $('#ttsEnVoiceToggleLabel').text('Nonaktif');
        $('#ttsEnVoiceSelect, #ttsEnRateRange, #ttsEnPitchRange').prop('disabled', true);
    }

    // Handle range/select changes
    $('#ttsVoiceSelect').on('change', function() {
        selectedVoiceName = $(this).val();
        localStorage.setItem('tts_voice_name', selectedVoiceName);
    });

    $('#ttsEnVoiceSelect').on('change', function() {
        selectedEnVoiceName = $(this).val();
        localStorage.setItem('tts_en_voice_name', selectedEnVoiceName);
    });

    $('#ttsRateRange').on('input', function() {
        ttsRate = parseFloat($(this).val());
        $('#ttsRateVal').text(ttsRate.toFixed(2) + 'x');
        localStorage.setItem('tts_rate', ttsRate);
    });

    $('#ttsPitchRange').on('input', function() {
        ttsPitch = parseFloat($(this).val());
        $('#ttsPitchVal').text(ttsPitch.toFixed(1));
        localStorage.setItem('tts_pitch', ttsPitch);
    });

    $('#ttsEnRateRange').on('input', function() {
        ttsEnRate = parseFloat($(this).val());
        $('#ttsEnRateVal').text(ttsEnRate.toFixed(2) + 'x');
        localStorage.setItem('tts_en_rate', ttsEnRate);
    });

    $('#ttsEnPitchRange').on('input', function() {
        ttsEnPitch = parseFloat($(this).val());
        $('#ttsEnPitchVal').text(ttsEnPitch.toFixed(1));
        localStorage.setItem('tts_en_pitch', ttsEnPitch);
    });

    $('#ttsGlobalRepeatSelect').on('change', function() {
        ttsGlobalRepeat = parseInt($(this).val());
        localStorage.setItem('tts_global_repeat', ttsGlobalRepeat);
    });

    $('#ttsGlobalDelaySelect').on('change', function() {
        ttsGlobalDelay = parseFloat($(this).val());
        localStorage.setItem('tts_global_delay', ttsGlobalDelay);
    });

    $('#ttsEnVoiceToggle').on('change', function() {
        let isEnabled = $(this).is(':checked');
        localStorage.setItem('tts_en_voice_enabled', isEnabled);
        if (isEnabled) {
            $('#ttsEnVoiceToggleLabel').text('Aktif');
            $('#ttsEnVoiceSelect, #ttsEnRateRange, #ttsEnPitchRange').prop('disabled', false);
        } else {
            $('#ttsEnVoiceToggleLabel').text('Nonaktif');
            $('#ttsEnVoiceSelect, #ttsEnRateRange, #ttsEnPitchRange').prop('disabled', true);
        }
    });

    refreshQueue();
    updatePrayerUiList();
    fetchPrayerTimes();
    loadMusicList();
    initAnnouncerPreview();
    updateLastUpdate();
    loadAdsSchedule();

    // Set Play/Pause button initial state based on saved preference
    let btn = $('#btnMusicPlayPause');
    if (isMusicManuallyPaused) {
        btn.html('<i class="bi bi-play-fill me-1"></i>Play').removeClass('text-success').addClass('text-danger');
    } else {
        btn.html('<i class="bi bi-pause-fill me-1"></i>Pause').removeClass('text-danger').addClass('text-success');
    }

    setInterval(() => {
        if (!isProcessingQueue && !audioMutex) {
            fetchAndPlayPending();
        }
    }, 4000);

    setInterval(() => {
        checkAndPlayAds();
    }, 60000);

    setInterval(() => {
        refreshQueue();
        updateLastUpdate();
        checkPrayerSchedule();
    }, 20000);

    $('#formYoutubeMusic').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({title: 'Menambahkan...', didOpen: () => Swal.showLoading()});
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
        let repeat = $(this).find('select[name="repeat"]').val();
        let delay = $(this).find('select[name="delay"]').val();
        if(!text) {
            Swal.fire('⚠️ Kosong!', 'Isi dulu', 'warning');
            return;
        }
        ciPost('audio/add_ads', Object.assign({ text: text, repeat: repeat, delay: delay }, getCsrfData()), function(res) {
            if(res?.status === 'ok') {
                Swal.fire('✅ Berhasil!', 'Masuk queue', 'success');
                $('#formQuickAds')[0].reset();
                refreshQueue();
            } else {
                Swal.fire('❌ Gagal!', res?.message || 'Error', 'error');
            }
        });
    });

    function playAd(ad) {
        console.log("📢 Trigger iklan:", ad.ad_title);
        ciPost('audio/add_ads', { text: ad.ad_text }, function(res) {
            if(res?.status === 'ok') { console.log("✅ Iklan masuk queue"); }
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

function onYouTubeIframeAPIReady() {
    console.log('🎬 YouTube iframe API ready!');
    ytPlayer = new YT.Player('ytPlayer', {
        height: '0', width: '0', videoId: '',
        playerVars: { 'autoplay': 0, 'controls': 0, 'modestbranding': 1, 'rel': 0, 'iv_load_policy': 3 },
        events: {
            'onReady': function(e) {
                console.log('✅ YouTube Player Ready');
                setTimeout(() => {
                    ciGet('audio/get_next_audio', function(res) {
                        if (!res || !res.id) {
                            if (musicPlaylist.length > 0) { playYoutubeByIndex(0); }
                        }
                    });
                }, 3000);
            },
            'onStateChange': function(e) {
                console.log('🎬 YouTube state:', e.data);
                let btn = $('#btnMusicPlayPause');
                if (e.data === YT.PlayerState.PLAYING) {
                    btn.html('<i class="bi bi-pause-fill me-1"></i>Pause').removeClass('text-danger').addClass('text-success');
                } else if (e.data === YT.PlayerState.PAUSED) {
                    btn.html('<i class="bi bi-play-fill me-1"></i>Play').removeClass('text-success').addClass('text-danger');
                }
                if(e.data === YT.PlayerState.ENDED) {
                    let nextIndex = currentTrackIndex + 1;
                    if (nextIndex < musicPlaylist.length) {
                        playYoutubeByIndex(nextIndex);
                    } else {
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

function playYoutube(videoId, title, showPopup = true) {
    if(!ytPlayer || !ytPlayer.loadVideoById) return;
    stopAllAudio();
    ytPlayer.loadVideoById(videoId);
    ytPlayer.unMute();
    ytPlayer.playVideo();
    isMusicManuallyPaused = false;
    localStorage.setItem('music_manually_paused', 'false');
    $('#btnMusicPlayPause').html('<i class="bi bi-pause-fill me-1"></i>Pause').removeClass('text-danger').addClass('text-success');
    if(showPopup) {
        Swal.fire({ icon: 'info', title: '🎵 Memutar Musik', text: title, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
    }
}

function togglePlayPauseMusic() {
    if (!ytPlayer) return;
    try {
        let state = ytPlayer.getPlayerState();
        let btn = $('#btnMusicPlayPause');
        if (state === YT.PlayerState.PLAYING) {
            ytPlayer.pauseVideo();
            isMusicManuallyPaused = true;
            localStorage.setItem('music_manually_paused', 'true');
            btn.html('<i class="bi bi-play-fill me-1"></i>Play').removeClass('text-success').addClass('text-danger');
            console.log("⏸️ Background music paused manually");
        } else {
            ytPlayer.playVideo();
            ytPlayer.unMute();
            isMusicManuallyPaused = false;
            localStorage.setItem('music_manually_paused', 'false');
            btn.html('<i class="bi bi-pause-fill me-1"></i>Pause').removeClass('text-danger').addClass('text-success');
            console.log("▶️ Background music played manually");
        }
    } catch(e) {
        console.warn("⚠️ Toggle play/pause error:", e);
    }
}

function stopMusicPlayback() {
    if (!ytPlayer) return;
    try {
        ytPlayer.stopVideo();
        isMusicManuallyPaused = true;
        localStorage.setItem('music_manually_paused', 'true');
        $('#btnMusicPlayPause').html('<i class="bi bi-play-fill me-1"></i>Play').removeClass('text-success').addClass('text-danger');
        console.log("⏹️ Background music stopped");
    } catch(e) {
        console.warn("⚠️ Stop music error:", e);
    }
}

function restartPlaylist() {
    if (!ytPlayer) return;
    try {
        currentTrackIndex = -1;
        ytPlayer.stopVideo();
        if (musicPlaylist.length > 0) {
            playYoutubeByIndex(0);
        }
        console.log("🔄 Playlist restarted from beginning");
    } catch(e) {
        console.warn("⚠️ Restart playlist error:", e);
    }
}

function initAnnouncerPreview() {
    $('input[name="penumpang"], input[name="po"], input[name="jurusan"], input[name="pintu"]').on('input', updateAnnouncerPreview);
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

function addPrayerAnnounce() {
    Swal.fire({
        title: '🕌 Siarkan Pengumuman Sholat?',
        html: 'Pesan:<br><em>"Kepada Bapak/Ibu penumpang, bagi Anda yang ingin menunaikan ibadah salat, tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal. Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasih."</em>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✅ Ya, Siarkan',
        cancelButtonText: 'Batal'
    }).then(r => {
        if(r.isConfirmed) {
            Swal.fire({title: 'Mengirim...', didOpen: () => Swal.showLoading()});
            let text_id = "Kepada Bapak/Ibu penumpang, bagi Anda yang ingin menunaikan ibadah salat, tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal. Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasih.";
            let text_en = "To all passengers, for those who wish to perform the prayer, a comfortable mosque facility is available on the 1st floor of the terminal. Let us remain diligent in our prayers during your journey. Thank you.";
            let text = text_id + " | " + text_en;
            ciPost('audio/add_prayer_announce', {text: text, ...getCsrfData()}, function(res) {
                if(res?.status === 'ok') {
                    Swal.fire('✅ Berhasil!', 'Pengumuman sholat masuk queue', 'success');
                    refreshQueue();
                } else Swal.fire('❌ Gagal!', 'Cek koneksi', 'error');
            });
        }
    });
}

function loadMusicList() {
    ciGet('audio/get_music_list', function(res) {
        musicPlaylist = res || [];
        let html = '';
        if(musicPlaylist.length > 0) {
            musicPlaylist.forEach((m, index) => {
                let vId = extractVideoId(m.youtube_url);
                html += `<div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 border-0" id="track-${index}" onclick="playYoutubeByIndex(${index})" style="border-radius: 8px; margin-bottom: 4px;">
                    <div class="text-truncate small" style="max-width: 80%; cursor: pointer;">
                        <i class="bi bi-music-note-beamed me-2"></i>${m.title || 'Untitled'}
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-play-fill text-success" style="font-size: 1.1rem; cursor: pointer;"></i>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0 p-0 px-1" onclick="event.stopPropagation(); deleteMusic(${m.id})" title="Hapus dari Playlist" style="line-height: 1;">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </div>`;
            });
        } else {
            html = '<div class="p-2 text-center text-muted small">Playlist Kosong</div>';
        }
        $('#musicList').html(html);
    });
}

function playYoutubeByIndex(index) {
    if (index >= musicPlaylist.length) { console.log("Sudah di akhir playlist."); currentTrackIndex = -1; return; }
    currentTrackIndex = index;
    let track = musicPlaylist[index];
    let vId = extractVideoId(track.youtube_url);
    $('.list-group-item').removeClass('bg-success text-white');
    $(`#track-${index}`).addClass('bg-success text-white');
    playYoutube(vId, track.title, false); 
}

function extractVideoId(url) {
    if (!url) return null;
    let match = url.trim().match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/ ]{11})/i);
    return (match && match[1]) ? match[1] : null;
}

function deleteMusic(id) {
    Swal.fire({ title: 'Hapus musik ini?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus' }).then(r => {
        if(r.isConfirmed) {
            ciGet('audio/delete_music/'+id, function() { loadMusicList(); Swal.fire('Terhapus','','success'); });
        }
    });
}

function stopAllAudio(showNotice = false, manual = false) {
    if (ytPlayer?.pauseVideo) ytPlayer.pauseVideo();
    if (ytPlayer?.mute) ytPlayer.mute();
    if ('speechSynthesis' in window) { window.speechSynthesis.cancel(); }

    if (manual) {
        isMusicManuallyPaused = true;
        isMusicPausedByQueue = false;
        localStorage.setItem('music_manually_paused', 'true');
    }
    const musicBtn = $('#btnMusicPlayPause');
    musicBtn.html('<i class="bi bi-play-fill me-1"></i>Play').removeClass('text-success').addClass('text-danger');

    // Setelah menghentikan audio, izinkan aksi baru pada queue
    isProcessingQueue = false;
    audioMutex = false;

    if (showNotice) {
        Swal.fire({
            icon: 'info',
            title: 'Suara dihentikan',
            text: 'Audio dihentikan. Tekan Play untuk menghidupkan kembali.',
            timer: 2200,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }
}

function fetchAndPlayPending() {
    if (isQueueManuallyStopped) {
        console.log('⏹️ Queue dihentikan manual, tidak ada processing');
        return;
    }

    if (audioMutex) {
        console.log('⏳ Queue sedang dimuat, tunggu...');
        return;
    }

    if (isProcessingQueue) {
        console.log('⏳ Audio masih diproses, tidak akan lanjut ke item berikutnya.');
        return;
    }

    if (typeof window !== 'undefined' && 'speechSynthesis' in window && window.speechSynthesis.speaking) {
        console.log('⏳ TTS sedang berbicara, tunggu sampai selesai.');
        return;
    }

    if (currentSpeech) {
        console.log('⏳ TTS aktif, tunggu sampai selesai.');
        return;
    }

    audioMutex = true;
    ciGet('audio/get_next_audio', function(res) {
        audioMutex = false;
        if (!res || !res.id || res.status !== 'pending') {
            if (!isProcessingQueue) { 
                isMusicPausedByQueue = false;
                resumeYoutube(); 
            }
            return;
        }
        console.log("🎯 PLAY QUEUE:", res.id, res.type);
        isProcessingQueue = true;
        currentPlayingAudioId = res.id;
        isMusicPausedByQueue = true;
        stopAllAudio(false, false);
        hardPauseYoutube();
        setTimeout(() => { playQueueItemSequential(res); }, 800);
    }).always(function() {
        audioMutex = false;
    });
}

function hardPauseYoutube() {
    if (!ytPlayer) return;
    try {
        ytPlayer.pauseVideo();
        ytPlayer.mute();
        console.log('⏸️ YouTube paused (safe) - lanjutkan dari sini saat queue selesai');
    } catch(e) {}
}

function resumeYoutube() {
    if (typeof isMusicManuallyPaused !== 'undefined' && isMusicManuallyPaused) {
        console.log('⏸️ YouTube tetap pause karena dimatikan manual oleh operator');
        return;
    }
    if (isMusicPausedByQueue) {
        console.log('⏳ Antrian masih diproses, YouTube tetap pause');
        return;
    }
    if (isProcessingQueue || audioMutex) {
        console.log('⏳ Queue aktif, YouTube tetap pause');
        return;
    }
    // Cek: jangan resume jika TTS masih berbicara
    if (typeof window !== 'undefined' && 'speechSynthesis' in window && window.speechSynthesis.speaking) {
        console.log('⏳ TTS masih berbicara, YouTube tetap pause');
        return;
    }
    if (!ytPlayer) return;
    try {
        if (typeof ytPlayer.unMute === 'function') { ytPlayer.unMute(); }
        if (typeof ytPlayer.setVolume === 'function') { ytPlayer.setVolume(100); }
        if (typeof ytPlayer.playVideo === 'function') { ytPlayer.playVideo(); }
        isMusicPausedByQueue = false;
        console.log('▶️ YouTube melanjutkan dari posisi sebelumnya');
    } catch(e) { console.warn('⚠️ Resume YouTube error:', e); }
}

function playQueueItemSequential(item) {
    console.log("🔊 MEMULAI:", item.type, "ID:", item.id);
    currentPlayingAudioId = item.id;
    let cleanText = item.text.replace(/<[^>]*>/g, '').trim();
    if ('speechSynthesis' in window) { window.speechSynthesis.cancel(); }

    const resetBlocked = function() {
        console.warn("⚠️ TTS Blocked by browser, resetting to pending:", item.id);
        ciGet('audio/replay_queue_item/' + item.id, function() {
            isProcessingQueue = false;
        });
    };

    const finishQueue = function() {
        console.log("✅ TTS Selesai, mark as done:", item.id);
        markAsDone(item.id, false);
        currentPlayingAudioId = null;
        isProcessingQueue = false;
        setTimeout(() => { if (!isProcessingQueue) { fetchAndPlayPending(); } }, 1500);
    };

    let parts = cleanText.split('|').map(p => p.trim());
    
    // Tentukan jumlah pengulangan global
    let repeatCount = ttsGlobalRepeat;
    if (item.type === 'announcer' || item.type === 'ads') {
        // Untuk announcer manual dan ads manual (pengumuman cepat), pengulangan sudah di-handle oleh controller/teks antrian
        repeatCount = 1;
    }

    let isVoice2Enabled = localStorage.getItem('tts_en_voice_enabled') !== 'false';

    // Bangun daftar final part dan kodenya sesuai pengulangan
    let finalParts = [];
    let partTypes = [];
    for (let r = 0; r < repeatCount; r++) {
        parts.forEach((part, index) => {
            if (isVoice2Enabled) {
                finalParts.push(part);
                if (item.type === 'bus') {
                    partTypes.push((index % 2 === 0) ? 'id-ID' : 'en-US');
                } else if (item.type === 'announcer' || item.type === 'ads') {
                    partTypes.push('id-ID');
                } else {
                    partTypes.push((index % 2 === 0) ? 'id-ID' : 'en-US');
                }
            } else {
                // Jika Suara 2 dinonaktifkan, cuman menggunakan parts pertama (index 0)
                // Kecuali jika tipenya adalah 'announcer' manual atau 'ads' manual
                if (index === 0 || item.type === 'announcer' || item.type === 'ads') {
                    finalParts.push(part);
                    partTypes.push('id-ID');
                }
            }
        });
    }

    // Tentukan waktu jeda antar putaran (delay)
    let delayMs = ttsGlobalDelay * 1000;
    if ((item.type === 'announcer' || item.type === 'ads') && item.title) {
        let parsedDelay = parseFloat(item.title);
        if (!isNaN(parsedDelay) && parsedDelay > 0) {
            delayMs = parsedDelay * 1000;
        }
    }

    const playPart = function(index) {
        if (index >= finalParts.length) {
            finishQueue();
            return;
        }

        let langCode = partTypes[index] || 'id-ID';
        let originalText = finalParts[index];
        let spokenText = originalText;

        // Cari target lang dari voice yang dipilih
        let voices = ('speechSynthesis' in window) ? window.speechSynthesis.getVoices() : [];
        let targetVoice = null;
        if (langCode === 'id-ID') {
            targetVoice = voices.find(v => v.name === selectedVoiceName);
        } else {
            targetVoice = voices.find(v => v.name === selectedEnVoiceName);
        }
        let targetLangPrefix = targetVoice ? targetVoice.lang.substring(0, 2).toLowerCase() : (langCode === 'id-ID' ? 'id' : 'en');
        
        spokenText = translateAnnouncement(originalText, targetLangPrefix);

        setTimeout(() => {
            speakTextSequential(spokenText, langCode, function(err) {
                if (err === 'not-allowed') { resetBlocked(); return; }
                playPart(index + 1);
            });
        }, index === 0 ? 1500 : delayMs);
    };

    playPart(0);
}

function translateAnnouncement(text, targetLangPrefix) {
    targetLangPrefix = targetLangPrefix.toLowerCase();
    if (targetLangPrefix === 'en') return text;

    const translations = {
        bus_entrance: {
            id: "Perhatian. Bus {po} dengan nomor polisi {plat} telah memasuki {area}. Terima kasih.",
            ar: "انتباه. دخلت الحافلة {po} ذات لوحة الترخيص {plat} إلى {area}. شكرًا لك.",
            ja: "ご案内いたします。{po}、ナンバープレート{plat}のバスが{area}に入りました。ありがとうございました。",
            zh: "请注意。车牌号为 {plat} 的 {po} 客车已进入 {area}。谢谢。",
            es: "Atención. El autobús {po} con número de matrícula {plat} ha entrado en {area}. Gracias.",
            fr: "Attention. L'autobus {po} avec le numéro de plaque d'immatriculation {plat} est entré dans {area}. Merci.",
            de: "Achtung. Der Bus {po} mit dem Kennzeichen {plat} ist in {area} eingefahren. Vielen Dank.",
            ko: "알려드립니다. 차량 번호 {plat}의 {po} 버스가 {area}에 진입했습니다. 감사합니다."
        },
        areas: {
            "the arrival area": {
                id: "area kedatangan",
                ar: "منطقة الوصول",
                ja: "到着エリア",
                zh: "到达区",
                es: "el área de llegada",
                fr: "la zone d'arrivée",
                de: "den Ankunftsbereich",
                ko: "도착 구역"
            },
            "the laying-over area": {
                id: "area pengendapan",
                ar: "منطقة الانتظار",
                ja: "待機エリア",
                zh: "停放区",
                es: "el área de estacionamiento",
                fr: "la zone de stationnement",
                de: "den Abstellbereich",
                ko: "계류 구역"
            },
            "the departure area": {
                id: "area keberangkatan",
                ar: "منطقة المغادرة",
                ja: "出発エリア",
                zh: "出发区",
                es: "el área de salida",
                fr: "la zone de départ",
                de: "den Abfahrtsbereich",
                ko: "출val 구역"
            },
            "the terminal area": {
                id: "area terminal",
                ar: "منطقة المحطة",
                ja: "ターミナルエリア",
                zh: "终点站区",
                es: "el área de la terminal",
                fr: "la zone du terminal",
                de: "den Terminalbereich",
                ko: "터미널 구역"
            }
        },
        prayer_reminder: {
            id: "Perhatian, waktu sholat {prayer} akan segera tiba dalam 10 menit. Silakan bersiap menuju masjid terminal.",
            ar: "انتباه، سيحين وقت صلاة {prayer} خلال 10 دقائق. يرجى الاستعداد للتوجه إلى مسجد المحطة.",
            ja: "ご案内いたします。あと10分で{prayer}の礼拝時間になります。ターミナル内のモスクへ移動する準備をしてください。",
            zh: "请注意，{prayer} 的礼拜时间将在10分钟后到来。请准备前往终点站清真寺。",
            es: "Atención, el tiempo de oración para {prayer} llegará en 10 minutos. Por favor, prepárese para dirigirse a la mezquita de la terminal.",
            fr: "Attention, l'heure de la prière de {prayer} arrivera dans 10 minutes. Veuillez vous préparer à vous rendre à la mosquée du terminal.",
            de: "Achtung, die Gebetszeit für {prayer} beginnt in 10 Minuten. Bitte bereiten Sie sich darauf vor, die Terminal-Moschee aufzusuchen.",
            ko: "알려드립니다. 10분 후에 {prayer} 예배 시간이 시작됩니다. 터미널 사원으로 이동할 준비를 해주시기 바랍니다."
        },
        prayer_arrived: {
            id: "Kepada seluruh penumpang, waktu sholat {prayer} telah tiba. Bagi Anda yang ingin menunaikan ibadah salat, tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal. Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasih.",
            ar: "إلى جميع الركاب، حان وقت صلاة {prayer}. للراغبين في أداء الصلاة، تتوفر مرافق masjid مريحة في الطابق الأول من المحطة. شكراً لكم.",
            ja: "乗客の皆様、{prayer}の礼拝時間になりました。礼拝を行いたい方は、ターミナルの1階に快適なモスクがございます。ありがとうございました。",
            zh: "各位旅客，{prayer} 的礼拜时间已到。需要礼拜的旅客，终点站一楼设有舒适的清真寺设施。谢谢。",
            es: "A todos los pasajeros, el tiempo de oración para {prayer} ha llegado. Para aquellos que deseen realizar la oración, hay una cómoda mezquita disponible en el primer piso de la terminal. Gracias.",
            fr: "À tous les passagers, l'heure de la prière de {prayer} est arrivée. Pour ceux qui souhaitent effectuer la prière, une mosquée confortable est disponible au 1er étage du terminal. Merci.",
            de: "An alle Fahrgäste, die Gebetszeit für {prayer} hat begonnen. Für diejenigen, die beten möchten, steht im 1. Stock des Terminals eine komfortable Moschee zur Verfügung. Vielen Dank.",
            ko: "승객 여러분께 알려드립니다. {prayer} 예배 시간이 되었습니다. 예배를 드리고자 하시는 분들을 위해 터미널 1층에 편안한 사원 시설이 마련되어 있습니다. 감사합니다."
        },
        prayer_general: {
            id: "Kepada seluruh penumpang, bagi Anda yang ingin menunaikan ibadah salat, tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal. Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasih.",
            ar: "إلى جميع الركاب، للراغبين في أداء الصلاة، تتوفر مرافق مسجد مريحة في الطابق الأول من المحطة. شكراً لكم.",
            ja: "乗客の皆様、礼拝を行いたい方は、ターミナルの1階に快適なモスクがございます。ありがとうございました。",
            zh: "各位旅客，需要礼拜的旅客，终点站一楼设有舒适的清真寺设施。谢谢。",
            es: "A todos los pasajeros, para aquellos que deseen realizar la oración, hay una cómoda mezquita disponible en el primer piso de la terminal. Gracias.",
            fr: "À tous les passagers, pour ceux qui souhaitent effectuer la prière, une mosquée confortable est disponible au 1er étage du terminal. Merci.",
            de: "An alle Fahrgäste, für diejenigen, die beten möchten, steht im 1. Stock des Terminals eine komfortable Moschee zur Verfügung. Vielen Dank.",
            ko: "승객 여러분께 알려드립니다. 예배를 드리고자 하시는 분들을 위해 터미널 1층에 편안한 사원 시설이 마련되어 있습니다. 감사합니다."
        }

    };

    let busRegex = /Attention\.\s+Bus\s+(.+?)\s+with\s+license\s+plate\s+number\s+(.+?)\s+has\s+entered\s+(the\s+arrival\s+area|the\s+laying-over\s+area|the\s+departure\s+area|the\s+terminal\s+area)\.\s+Thank\s+you\./i;
    let matchBus = text.match(busRegex);
    if (matchBus) {
        let po = matchBus[1];
        let plat = matchBus[2];
        let areaEn = matchBus[3].toLowerCase();
        let template = translations.bus_entrance[targetLangPrefix];
        if (template) {
            let areaTrans = (translations.areas[areaEn] && translations.areas[areaEn][targetLangPrefix]) ? translations.areas[areaEn][targetLangPrefix] : areaEn;
            return template.replace(/{po}/g, po).replace(/{plat}/g, plat).replace(/{area}/g, areaTrans);
        }
    }

    let reminderRegex = /Attention,\s+the\s+prayer\s+time\s+for\s+(.+?)\s+will\s+arrive\s+in\s+10\s+minutes\.\s+Please\s+prepare\s+to\s+proceed\s+to\s+the\s+terminal\s+mosque\./i;
    let matchReminder = text.match(reminderRegex);
    if (matchReminder) {
        let prayer = matchReminder[1];
        let template = translations.prayer_reminder[targetLangPrefix];
        if (template) {
            return template.replace(/{prayer}/g, prayer);
        }
    }

    let arrivedRegex = /To\s+all\s+passengers,\s+the\s+prayer\s+time\s+for\s+(.+?)\s+has\s+arrived\.\s+For\s+those\s+who\s+wish\s+to\s+perform\s+the\s+prayer,\s+a\s+comfortable\s+mosque\s+facility\s+is\s+available\s+on\s+the\s+1st\s+floor\s+of\s+the\s+terminal\.\s+Let\s+us\s+remain\s+diligent\s+in\s+our\s+prayers\s+during\s+your\s+journey\.\s+Thank\s+you\./i;
    let matchArrived = text.match(arrivedRegex);
    if (matchArrived) {
        let prayer = matchArrived[1];
        let template = translations.prayer_arrived[targetLangPrefix];
        if (template) {
            return template.replace(/{prayer}/g, prayer);
        }
    }

    let generalPrayerRegex = /To\s+all\s+passengers,\s+for\s+those\s+who\s+wish\s+to\s+perform\s+the\s+prayer,\s+a\s+comfortable\s+mosque\s+facility\s+is\s+available\s+on\s+the\s+1st\s+floor\s+of\s+the\s+terminal\.\s+Let\s+us\s+remain\s+diligent\s+in\s+our\s+prayers\s+during\s+your\s+journey\.\s+Thank\s+you\./i;
    if (generalPrayerRegex.test(text)) {
        let template = translations.prayer_general[targetLangPrefix];
        if (template) return template;
    }

    return text;
}

function speakTextSequential(text, langCode, onComplete) {
    if (typeof langCode === 'function') {
        onComplete = langCode;
        langCode = 'id-ID';
    }
    langCode = langCode || 'id-ID';

    if (!('speechSynthesis' in window)) { console.warn('⚠️ TTS not supported'); if (onComplete) onComplete(); return; }
    
    let isFinished = false;
    const finish = function(errorName = null) {
        if (isFinished) return;
        isFinished = true;
        console.log('🔊 TTS finished callback');
        if (onComplete) onComplete(errorName);
    };

    // Global fallback timeout: no matter what happens, finish must be called!
    let estimatedMs = Math.max(4000, (text.length / 10) * 1000 + 4000);
    let globalTimeout = setTimeout(() => {
        if (!isFinished) {
            console.warn('⚠️ TTS global timeout fallback triggered!');
            window.speechSynthesis.cancel();
            finish();
        }
    }, estimatedMs);

    window.speechSynthesis.cancel();
    let voices = window.speechSynthesis.getVoices();
    
    if (voices.length === 0) {
        let voiceTimeout = setTimeout(() => {
            console.warn('⚠️ Voice load timeout, speaking with default native...');
            startSpeaking(true);
        }, 1000);
        
        window.speechSynthesis.onvoiceschanged = function() {
            window.speechSynthesis.onvoiceschanged = null;
            clearTimeout(voiceTimeout);
            voices = window.speechSynthesis.getVoices();
            startSpeaking();
        };
        return;
    }
    
    startSpeaking();
    
    function startSpeaking(useFallbackVoice = false) {
        try {
            let spokenText = text;
            let targetVoice = null;
            let voiceLang = langCode;

            if (!useFallbackVoice && voices && voices.length > 0) {
                if (langCode === 'id-ID' && selectedVoiceName) {
                    targetVoice = voices.find(v => v.name === selectedVoiceName);
                } else if (langCode.substring(0, 2).toLowerCase() === 'en' && selectedEnVoiceName) {
                    targetVoice = voices.find(v => v.name === selectedEnVoiceName);
                }
                if (!targetVoice) {
                    targetVoice = voices.find(v => v.lang.toLowerCase().includes(langCode.substring(0, 2).toLowerCase()) || v.name.toLowerCase().includes(langCode === 'id-ID' ? 'indonesian' : 'english'));
                }
            }

            if (targetVoice) {
                voiceLang = targetVoice.lang;
                if (langCode.substring(0, 2).toLowerCase() === 'en') {
                    let voiceLangPrefix = targetVoice.lang.substring(0, 2).toLowerCase();
                    if (voiceLangPrefix !== 'en') {
                        spokenText = translateAnnouncement(text, voiceLangPrefix);
                    }
                }
            }

            const utterance = new SpeechSynthesisUtterance(spokenText);
            utterance.lang = voiceLang;
            if (langCode.substring(0, 2).toLowerCase() === 'en') {
                utterance.rate = ttsEnRate;
                utterance.pitch = ttsEnPitch;
            } else {
                utterance.rate = ttsRate;
                utterance.pitch = ttsPitch;
            }
            
            if (targetVoice) {
                utterance.voice = targetVoice;
            }
            
            utterance.onend = function() {
                clearTimeout(globalTimeout);
                currentSpeech = null;
                finish();
            };
            
            utterance.onerror = function(e) { 
                console.error('❌ TTS Error:', e.error); 
                currentSpeech = null;
                if (e.error === 'not-allowed') {
                    $('#audioBlockedAlert').removeClass('d-none');
                    clearTimeout(globalTimeout);
                    finish(e.error);
                } else if (!useFallbackVoice && targetVoice) {
                    console.warn('⚠️ Selected voice failed, retrying with default native voice...');
                    startSpeaking(true);
                } else {
                    clearTimeout(globalTimeout);
                    finish();
                }
            };
            
            window.speechSynthesis.speak(utterance);
            currentSpeech = utterance;
            console.log('🗣️ Speaking:', text.substring(0, 40) + '...');
        } catch (err) {
            console.error('❌ startSpeaking Exception:', err);
            currentSpeech = null;
            clearTimeout(globalTimeout);
            finish();
        }
    }
}

function startAdsScheduler(intervalMinutes = 30) {
    if (adsInterval) clearInterval(adsInterval);
    adsInterval = setInterval(() => {
        console.log("📢 Waktu iklan!");
        ciPost('audio/add_ads', { text: "Perhatian! Nikmati promo spesial hari ini di area terminal. Terima kasih." }, function(res) {
            if(res?.status === 'ok') { console.log("✅ Iklan masuk queue"); }
        });
    }, intervalMinutes * 60 * 1000);
}

function playQueueItem(item, priority) {
    audioLock = true;
    isPlayingQueue = true;
    currentPriority = priority;
    console.log("🔊 PLAY:", item.type, "priority:", priority);
    pauseYoutube();
    killYoutubeAudio();
    let ttsText = item.text.replace(/<[^>]*>/g, '');
    window.speechSynthesis.cancel();

    const finishPlay = function() {
        console.log("✅ SELESAI:", item.type);
        markAsDone(item.id);
        isPlayingQueue = false;
        audioLock = false;
        currentPriority = 0;
        setTimeout(() => { resumeYoutube(); }, 2000);
    };

    let parts = ttsText.split('|').map(p => p.trim());
    if (parts.length > 1) {
        speakText(parts[0], 'id-ID', function() {
            speakText(parts[1], 'en-US', function() {
                finishPlay();
            });
        });
    } else {
        speakText(ttsText, 'id-ID', function() {
            finishPlay();
        });
    }
}

function killYoutubeAudio() {
    try { if (ytPlayer) { ytPlayer.pauseVideo(); ytPlayer.mute(); } } catch(e) {}
}

function getNextAudio() {
    if (audioMutex) {
        Swal.fire({
            icon: 'info',
            title: 'Sedang memuat...',
            text: 'Tunggu dulu sebelum memutar item berikutnya.',
            timer: 1600,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
        return;
    }

    if (isProcessingQueue && currentPlayingAudioId) {
        Swal.fire({
            title: 'Ingin lanjut ke audio berikutnya?',
            text: 'Audio saat ini akan dihentikan dan ditandai selesai.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Tidak'
        }).then(result => {
            if (!result.isConfirmed) return;
            stopAllAudio(false);
            const activeId = currentPlayingAudioId;
            markAsDone(activeId, false, function() {
                fetchAndPlayPending();
            });
        });
        return;
    }

    fetchAndPlayPending();
}

function playSpecific(id) {
    ciGet('audio/get_next_audio', function(res) {
        if(res && res.id == id) { playQueueItem(res); refreshQueue(); }
    });
}

function markAsDone(id, showToast = true, callback = null) {
    ciGet('audio/done_audio/' + id, function(res) {
        if(res?.status === 'done') {
            refreshQueue();
            if (currentPlayingAudioId == id) {
                currentPlayingAudioId = null;
            }
            if (showToast) {
                Swal.fire({icon: 'success', title: 'Selesai!', timer: 1000, showConfirmButton: false});
            }
            if (typeof callback === 'function') {
                callback();
            }
        } else if (typeof callback === 'function') {
            callback();
        }
    });
}

function skipCurrentAudio() {
    if (!currentPlayingAudioId) {
        Swal.fire({
            icon: 'info',
            title: 'Tidak ada audio aktif',
            text: 'Belum ada item berstatus playing untuk dilewati.',
            timer: 1800,
            showConfirmButton: false
        });
        return;
    }

    Swal.fire({
        title: 'Lewati audio ini?',
        text: 'Suara akan dihentikan dan item yang sedang playing ditandai selesai.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Lewati',
        cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            stopAllAudio(false);
            isProcessingQueue = false;
            markAsDone(currentPlayingAudioId, true);
        }
    });
}

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

function refreshQueue() {
    ciGet('audio/get_all_queue', function(data) {
        let html = '', count = 0, pending = 0;
        if(!data || !data.length) {
            html = '<div class="text-center text-muted py-4 small"><i class="bi bi-check-circle me-1"></i>Queue kosong 🎉</div>';
        } else {
            $.each(data, function(i, item) {
                if(item.status === 'pending') pending++;
                let badge = '', icon = '', cls = '';
                if(item.type === 'bus') { badge = 'bg-info'; icon = '🚌 BUS'; cls = 'bus'; }
                else if(item.type === 'announcer') { badge = 'bg-primary'; icon = '🎤 ANNOUNCER'; cls = 'announcer'; }
                else if(item.type === 'prayer') { badge = 'bg-success'; icon = '🕌 SHOLAT'; cls = 'prayer'; }
                else if(item.type === 'ads') { badge = 'bg-warning text-dark'; icon = '📢 IKLAN'; cls = 'ads'; }
                let btn = '';
                if(item.status === 'pending') {
                    btn = `<button class="btn btn-sm btn-success me-1" onclick="playSpecific(${item.id})" title="Putar"><i class="bi bi-play-fill"></i></button>`;
                } else if(item.status === 'playing') {
                    btn = `<button class="btn btn-sm btn-primary me-1" onclick="markAsDone(${item.id})" title="Selesai"><i class="bi bi-check-lg"></i></button>`;
                } else {
                    if(item.type === 'announcer' || item.type === 'prayer') {
                        btn = `<button class="btn btn-sm btn-outline-secondary me-1" onclick="replayItem(${item.id})" title="Ulangi Panggilan"><i class="bi bi-arrow-clockwise"></i></button>`;
                    } else {
                        btn = `<span class="text-muted small me-1"><i class="bi bi-check-circle-fill"></i></span>`;
                    }
                }
                let displayText = '';
                if (item.text.includes('|')) {
                    let parts = item.text.split('|').map(p => p.trim());
                    displayText = `<div class="small"><span class="badge bg-secondary me-1 text-uppercase" style="font-size: 0.65rem;">ID</span> ${parts[0]}</div>
                                   <div class="small mt-1 text-muted"><span class="badge bg-light text-dark border me-1 text-uppercase" style="font-size: 0.65rem;">EN</span> ${parts[1]}</div>`;
                } else {
                    let txt = item.text.length > 80 ? item.text.substring(0,80)+'...' : item.text;
                    displayText = `<p class="mb-1 mt-2 small text-dark">${txt}</p>`;
                }
                html += `<div class="queue-item ${cls} rounded">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1 me-2">
                            <span class="badge ${badge} badge-type">${icon} ${item.type}</span>
                            <strong class="ms-2 small">#${item.id}</strong>
                            ${item.priority <= 2 ? '<span class="badge bg-danger ms-1" style="font-size:0.7em">PRIORITAS</span>' : ''}
                            <div class="mt-2 mb-1">${displayText}</div>
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
        if(data && data.length > 0) {
            let playing = data.find(x => x.status === 'playing');
            if(playing) {
                currentPlayingAudioId = playing.id;
                let cleanText = playing.text.replace(/<[^>]*>/g, '');
                if (cleanText.includes('|')) {
                    let parts = cleanText.split('|').map(p => p.trim());
                    $('#currentAudio').html(`<div class="text-primary font-monospace small"><span class="badge bg-primary text-white me-1">ID</span> ${parts[0]}</div>
                                             <div class="text-secondary font-monospace small mt-1"><span class="badge bg-secondary text-white me-1">EN</span> ${parts[1]}</div>`);
                } else {
                    $('#currentAudio').html(`<span class="text-primary">${cleanText.substring(0, 70)}${cleanText.length > 70 ? '...' : ''}</span>`);
                }
            } else {
                currentPlayingAudioId = null;
                $('#currentAudio').html('<span class="text-muted">Tidak ada audio yang sedang diputar</span>');
            }
        } else {
            currentPlayingAudioId = null;
            $('#currentAudio').html('<span class="text-muted">Tidak ada audio yang sedang diputar</span>');
        }
    });
}

function stopQueue() {
    isQueueManuallyStopped = true;
    Swal.fire({
        icon: 'warning',
        title: 'Antrian Dihentikan',
        text: 'Queue audio telah dihentikan. Audio tidak akan dimainkan secara otomatis sampai Anda tekan tombol Lanjutkan.',
        toast: true,
        position: 'top-end',
        timer: 3000,
        showConfirmButton: false
    });
    console.log('⏹️ Queue audio dihentikan manual');
}

function resumeQueue() {
    isQueueManuallyStopped = false;
    isMusicPausedByQueue = true;
    hardPauseYoutube();
    Swal.fire({
        icon: 'success',
        title: 'Antrian Dilanjutkan',
        text: 'Queue audio dilanjutkan. Musik akan resume setelah antrian selesai.',
        toast: true,
        position: 'top-end',
        timer: 3000,
        showConfirmButton: false
    });
    fetchAndPlayPending();
    console.log('▶️ Queue audio dilanjutkan - musik tetap pause');
}

function clearQueue() {
    Swal.fire({
        title: 'Bersihkan Semua Antrian?',
        text: 'Tindakan ini akan menghapus SEMUA item di queue (kecuali yang sedang playing)',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Bersihkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545'
    }).then(r => {
        if (r.isConfirmed) {
            Swal.fire({title: 'Mengirim...', didOpen: () => Swal.showLoading()});
            ciGet('audio/clear_queue', function(res) {
                if(res?.status === 'ok') {
                    Swal.fire('✅ Berhasil!', 'Queue telah dibersihkan', 'success');
                    refreshQueue();
                } else {
                    Swal.fire('❌ Gagal!', 'Gagal membersihkan queue', 'error');
                }
            });
        }
    });
}

function speakText(text, langCode, callback) {
    if (typeof langCode === 'function') {
        callback = langCode;
        langCode = 'id-ID';
    }
    langCode = langCode || 'id-ID';

    if (!('speechSynthesis' in window)) return;
    
    let voices = speechSynthesis.getVoices();
    let targetVoice = null;
    let voiceLang = langCode;
    let spokenText = text;

    if (langCode === 'id-ID' && selectedVoiceName) {
        targetVoice = voices.find(v => v.name === selectedVoiceName);
    } else if (langCode.substring(0, 2).toLowerCase() === 'en' && selectedEnVoiceName) {
        targetVoice = voices.find(v => v.name === selectedEnVoiceName);
    }
    if (!targetVoice) {
        targetVoice = voices.find(v => v.lang.toLowerCase().includes(langCode.substring(0, 2).toLowerCase()) || v.name.toLowerCase().includes(langCode === 'id-ID' ? 'indonesian' : 'english'));
    }

    if (targetVoice) {
        voiceLang = targetVoice.lang;
        if (langCode.substring(0, 2).toLowerCase() === 'en') {
            let voiceLangPrefix = targetVoice.lang.substring(0, 2).toLowerCase();
            if (voiceLangPrefix !== 'en') {
                spokenText = translateAnnouncement(text, voiceLangPrefix);
            }
        }
    }

    const utterance = new SpeechSynthesisUtterance(spokenText);
    utterance.lang = voiceLang;
    if (langCode.substring(0, 2).toLowerCase() === 'en') {
        utterance.rate = ttsEnRate;
        utterance.pitch = ttsEnPitch;
    } else {
        utterance.rate = ttsRate;
        utterance.pitch = ttsPitch;
    }
    if (targetVoice) utterance.voice = targetVoice;
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
    setTimeout(() => {
        if (!finished) {
            console.warn("⚠️ FORCE END (fallback)");
            finished = true;
            if (callback) callback();
        }
    }, text.length * 120);
    speechSynthesis.speak(utterance);
}

if('speechSynthesis' in window) {
    window.speechSynthesis.onvoiceschanged = function() { console.log('🔊 Voices loaded'); };
    window.speechSynthesis.getVoices();
}

function updateLastUpdate() {
    let now = new Date();
    $('#lastUpdate').text('Last: ' + now.toLocaleTimeString('id-ID'));
}
</script>
</body>
</html>
