<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- FontAwesome (just in case) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php if($this->security->get_csrf_token_name()): ?>
<meta name="csrf_token_name" content="<?php echo $this->security->get_csrf_token_name(); ?>">
<meta name="csrf_token" content="<?php echo $this->security->get_csrf_hash(); ?>">
<?php endif; ?>

<style>
    /* Premium Glassmorphism styling for AdminLTE wrapper */
    .content-wrapper.announcement-bg {
        background: radial-gradient(circle at 10% 20%, rgba(15, 23, 42, 1) 0%, rgba(30, 41, 59, 1) 90%) !important;
        color: #f8fafc;
        min-height: 100vh;
    }
    
    .announcement-header {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    
    .announcement-header h1 {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #38bdf8;
        text-shadow: 0 2px 10px rgba(56, 189, 248, 0.2);
    }
    
    .glass-card {
        background: rgba(30, 41, 59, 0.7) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
        transition: all 0.3s ease;
        margin-bottom: 24px;
        color: #f8fafc;
    }
    
    .glass-card:hover {
        transform: translateY(-2px);
        border-color: rgba(56, 189, 248, 0.3) !important;
        box-shadow: 0 15px 35px rgba(56, 189, 248, 0.1) !important;
    }
    
    .glass-card .card-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        background: transparent !important;
    }
    
    .glass-card .card-header.bg-ads {
        color: #f59e0b; /* Amber */
    }
    
    .glass-card .card-header.bg-announcer {
        color: #a855f7; /* Purple */
    }
    
    .form-label {
        font-weight: 600;
        color: #94a3b8;
        font-size: 0.85rem;
        margin-bottom: 6px;
    }
    
    .form-control, .form-select {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #f8fafc !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2) !important;
        outline: none;
    }
    
    .form-control::placeholder {
        color: #64748b !important;
    }
    
    .btn-premium {
        border-radius: 10px !important;
        padding: 10px 20px !important;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        border: none;
        transition: all 0.2s ease;
    }
    
    .btn-premium-ads {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }
    
    .btn-premium-ads:hover {
        background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        transform: translateY(-1px);
        color: #fff;
    }
    
    .btn-premium-announcer {
        background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3);
    }
    
    .btn-premium-announcer:hover {
        background: linear-gradient(135deg, #c084fc 0%, #7e22ce 100%);
        box-shadow: 0 6px 20px rgba(168, 85, 247, 0.4);
        transform: translateY(-1px);
        color: #fff;
    }
    
    .template-preview {
        background: rgba(15, 23, 42, 0.4);
        border: 1px dashed rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 12px;
        font-size: 0.85rem;
        color: #cbd5e1;
        margin-top: 15px;
        line-height: 1.5;
    }
</style>

<div class="content-wrapper announcement-bg">
    <div class="announcement-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1><i class="fas fa-bullhorn me-2"></i> Pengumuman Cepat & Announcer</h1>
                </div>
            </div>
        </div>
    </div>
    
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <!-- FORM PENGUMUMAN CEPAT -->
                <div class="col-md-6">
                    <div class="card glass-card">
                        <div class="card-header bg-ads">
                            <i class="fas fa-bolt me-2"></i> Form Pengumuman Cepat
                        </div>
                        <div class="card-body">
                            <form id="formQuickAnnouncement">
                                <div class="mb-3">
                                    <label class="form-label">Pesan Pengumuman</label>
                                    <textarea name="text" class="form-control" rows="4" placeholder="Ketik pesan singkat untuk diumumkan..." required></textarea>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label class="form-label">🔁 Jumlah Putar</label>
                                        <select name="repeat" class="form-select" required>
                                            <option value="1">1 Kali</option>
                                            <option value="2" selected>2 Kali</option>
                                            <option value="3">3 Kali</option>
                                            <option value="4">4 Kali</option>
                                            <option value="5">5 Kali</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">⏱️ Jeda (Detik)</label>
                                        <select name="delay" class="form-select" required>
                                            <option value="1">1 Detik</option>
                                            <option value="1.5" selected>1.5 Detik</option>
                                            <option value="2">2 Detik</option>
                                            <option value="3">3 Detik</option>
                                            <option value="4">4 Detik</option>
                                            <option value="5">5 Detik</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-premium btn-premium-ads w-100">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pengumuman
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- FORM ANNOUNCER MANUAL (PANGGILAN PENUMPANG) -->
                <div class="col-md-6">
                    <div class="card glass-card">
                        <div class="card-header bg-announcer">
                            <i class="fas fa-microphone me-2"></i> Announcer Manual (Panggilan Penumpang)
                        </div>
                        <div class="card-body">
                            <form id="formManualAnnouncer">
                                <div class="row mb-3">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">🔀 Kategori Panggilan</label>
                                        <select name="kategori" class="form-select" id="kategoriPanggilan" required>
                                            <option value="perorangan" selected>Perorangan (Nama Penumpang)</option>
                                            <option value="po">PO (Plat / Body / Seri Bus)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" id="labelPenumpang">👤 Nama Penumpang</label>
                                        <input type="text" class="form-control" name="penumpang" id="inputPenumpang" placeholder="Contoh: Bapak/Ibu. Poltak Hasugian" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">🚌 Nama PO / Perusahaan</label>
                                        <input type="text" class="form-control" name="po" placeholder="PO. Sinar Jaya" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">📍 Jurusan / Rute</label>
                                        <input type="text" class="form-control" name="jurusan" placeholder="Jakarta - Surabaya" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">🚪 Nomor Pintu / Gate</label>
                                        <input type="text" class="form-control" name="pintu" placeholder="Contoh: Pintu 3" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">🔁 Jumlah Putar</label>
                                        <select name="repeat" class="form-select" required>
                                            <option value="1">1 Kali</option>
                                            <option value="2" selected>2 Kali</option>
                                            <option value="3">3 Kali</option>
                                            <option value="4">4 Kali</option>
                                            <option value="5">5 Kali</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">⏱️ Jeda (Detik)</label>
                                        <select name="delay" class="form-select" required>
                                            <option value="1">1 Detik</option>
                                            <option value="1.5" selected>1.5 Detik</option>
                                            <option value="2">2 Detik</option>
                                            <option value="3">3 Detik</option>
                                            <option value="4">4 Detik</option>
                                            <option value="5">5 Detik</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="template-preview">
                                    <div class="font-weight-bold mb-1"><i class="fas fa-eye me-1"></i> Preview Teks Panggilan:</div>
                                    <span id="announcerPreview">Mohon perhatian. Panggilan ditujukan kepada penumpang atas nama [Nama Penumpang]. Untuk penumpang bus [PO] tujuan [Jurusan], ditunggu kehadiran Anda di pintu [Pintu], dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.</span>
                                </div>
                                
                                <button type="submit" class="btn btn-premium btn-premium-announcer w-100 mt-4">
                                    <i class="fas fa-bullhorn me-2"></i> Panggil Penumpang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Helper to get CodeIgniter CSRF Token
    function getCsrfData() {
        let csrfName = $('meta[name="csrf_token_name"]').attr('content');
        let csrfHash = $('meta[name="csrf_token"]').attr('content');
        if (csrfName && csrfHash) {
            return { [csrfName]: csrfHash };
        }
        return {};
    }

    // Dynamic Live Preview for Announcer Manual
    function updatePreview() {
        let kategori = $('#kategoriPanggilan').val();
        let penumpangVal = $('input[name="penumpang"]').val();
        let po = $('input[name="po"]').val() || '[PO]';
        let jurusan = $('input[name="jurusan"]').val() || '[Jurusan]';
        let pintu = $('input[name="pintu"]').val() || '[Pintu]';
        
        let previewText = '';
        if (kategori === 'po') {
            let plat = penumpangVal || '[Plat / Body / Seri Bus]';
            previewText = `Mohon perhatian. Kepada seluruh penumpang bus <strong class="text-warning">${po}</strong> dengan plat nomor atau bodi <strong class="text-warning">${plat}</strong>, tujuan <strong class="text-warning">${jurusan}</strong>, Mohon agar segera menaiki bus Anda di pintu <strong class="text-warning">${pintu}</strong>, dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.`;
        } else {
            let penumpang = penumpangVal || '[Nama Penumpang]';
            previewText = `Mohon perhatian. Panggilan ditujukan kepada penumpang atas nama <strong class="text-warning">${penumpang}</strong>. Untuk penumpang bus <strong class="text-warning">${po}</strong> tujuan <strong class="text-warning">${jurusan}</strong>, ditunggu kehadiran Anda di pintu <strong class="text-warning">${pintu}</strong>, dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.`;
        }
        $('#announcerPreview').html(previewText);
    }

    // Toggle labels & placeholders on category change
    $('#kategoriPanggilan').on('change', function() {
        let val = $(this).val();
        if (val === 'po') {
            $('#labelPenumpang').html('🔢 Plat Nomor / No. Body / Seri Bus');
            $('#inputPenumpang').attr('placeholder', 'Masukkan Plat Nomor, No. Body, atau Seri/Tipe Bus (Contoh: B 1234 XYZ / Body 88 / Jetbus 5)');
        } else {
            $('#labelPenumpang').html('👤 Nama Penumpang');
            $('#inputPenumpang').attr('placeholder', 'Contoh: Bapak/Ibu. Poltak Hasugian');
        }
        updatePreview();
    });

    $('input[name="penumpang"], input[name="po"], input[name="jurusan"], input[name="pintu"]').on('input', updatePreview);

    // Ajax Submit for Form Pengumuman Cepat
    $('#formQuickAnnouncement').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Mengirim Pengumuman...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        let postData = $(this).serializeArray();
        let csrf = getCsrfData();
        for (let key in csrf) {
            postData.push({ name: key, value: csrf[key] });
        }

        $.ajax({
            url: '<?= site_url("audio/add_ads") ?>',
            type: 'POST',
            data: $.param(postData),
            dataType: 'json',
            success: function(res) {
                if(res && res.status === 'ok') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pengumuman Cepat telah dimasukkan ke dalam antrian.',
                        background: '#1e293b',
                        color: '#f8fafc',
                        confirmButtonColor: '#f59e0b'
                    });
                    $('#formQuickAnnouncement')[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: res.message || 'Terjadi kesalahan saat menyimpan pengumuman.',
                        background: '#1e293b',
                        color: '#f8fafc'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal terhubung ke server.',
                    background: '#1e293b',
                    color: '#f8fafc'
                });
            }
        });
    });

    // Ajax Submit for Form Announcer Manual
    $('#formManualAnnouncer').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Mengirim Panggilan...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        let postData = $(this).serializeArray();
        let csrf = getCsrfData();
        for (let key in csrf) {
            postData.push({ name: key, value: csrf[key] });
        }

        $.ajax({
            url: '<?= site_url("audio/add_announcer") ?>',
            type: 'POST',
            data: $.param(postData),
            dataType: 'json',
            success: function(res) {
                if(res && res.status === 'ok') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Panggilan announcer telah dimasukkan ke dalam antrian.',
                        background: '#1e293b',
                        color: '#f8fafc',
                        confirmButtonColor: '#a855f7'
                    });
                    $('#formManualAnnouncer')[0].reset();
                    $('#kategoriPanggilan').trigger('change');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: res.message || 'Terjadi kesalahan saat memproses panggilan.',
                        background: '#1e293b',
                        color: '#f8fafc'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal terhubung ke server.',
                    background: '#1e293b',
                    color: '#f8fafc'
                });
            }
        });
    });
});
</script>
