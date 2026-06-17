<?php $this->load->view('templates/header', ['title' => 'Tambah Barang']); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper">
<section class="content pt-3">

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="font-weight-bold">
            <i class="fas fa-plus-circle text-primary"></i> Input Barang Tertinggal
        </h4>

        <a href="<?= site_url('lost') ?>" class="btn btn-secondary shadow">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary">
                    <h5 class="mb-0">
                        <i class="fas fa-box"></i> Form Input Barang
                    </h5>
                </div>

                <div class="card-body">

                    <form method="post" enctype="multipart/form-data">

                        <div class="row">

                            <!-- NAMA -->
                            <div class="col-md-6">
                                <label>Nama Barang</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                    </div>
                                    <input name="nama_barang" class="form-control" required>
                                </div>
                            </div>

                            <!-- KATEGORI -->
                            <div class="col-md-6">
                                <label>Kategori</label>
                                <select name="kategori" class="form-control mb-3">
                                    <option>Elektronik</option>
                                    <option>Dokumen</option>
                                    <option>Tas</option>
                                    <option>Pakaian</option>
                                    <option>Lainnya</option>
                                </select>
                            </div>

                            <!-- LOKASI -->
                            <div class="col-md-6">
                                <label>Lokasi Ditemukan</label>
                                <input name="lokasi" class="form-control mb-3">
                            </div>

                            <!-- TANGGAL -->
                            <div class="col-md-6">
                                <label>Tanggal Ditemukan</label>
                                <input type="date" name="tanggal" class="form-control mb-3">
                            </div>

                            <!-- PENEMU -->
                            <div class="col-md-6">
                                <label>Nama Petugas Penerima</label>
                                <input name="penemu" class="form-control mb-3">
                            </div>

                            <!-- KONTAK -->
                            <div class="col-md-6">
                                <label>Kontak Petugas Penerima</label>
                                <input name="kontak" class="form-control mb-3">
                            </div>

                            <!-- DESKRIPSI -->
                            <div class="col-md-12">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control mb-3" rows="3"></textarea>
                            </div>

                            <!-- FOTO -->
                            <div class="col-md-12">
                                <label>Upload Foto</label>
                                <div class="custom-file mb-3">
                                    <input type="file" name="foto" class="custom-file-input" id="fotoInput">
                                    <label class="custom-file-label">Pilih gambar...</label>
                                </div>

                                <!-- PREVIEW -->
                                <div class="text-center">
                                    <img id="preview" src="" class="img-thumbnail d-none shadow" width="200">
                                </div>
                            </div>

                            <!-- SIARKAN AUTOMATIS CHECKBOX -->
                            <div class="col-md-12 mt-3">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success p-3 border rounded bg-light">
                                    <input type="checkbox" name="siarkan_audio" class="custom-control-input" id="siarkanAudio" value="1" checked>
                                    <label class="custom-control-label font-weight-bold text-dark" for="siarkanAudio" style="cursor: pointer;">
                                        📢 Siarkan Pengumuman Barang Temuan Otomatis ke Terminal
                                    </label>
                                    <small class="form-text text-muted text-xs mt-1">
                                        Jika diaktifkan, sistem akan otomatis merancang teks pengumuman suara dan menambahkannya ke antrean siaran terminal secara real-time.
                                    </small>
                                </div>
                            </div>

                        </div>

                        <!-- BUTTON -->
                        <div class="text-right mt-4">
                            <button class="btn btn-primary shadow px-4">
                                <i class="fas fa-save mr-1"></i>Simpan Data
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
</section>
</div>

<!-- STYLE -->
<style>
.card {
    border-radius: 12px;
}

input, select, textarea {
    border-radius: 8px !important;
}

.custom-file-label {
    border-radius: 8px;
}

.btn {
    border-radius: 8px;
}
</style>

<!-- JS PREVIEW GAMBAR -->
<script>
document.getElementById("fotoInput").addEventListener("change", function(e) {
    let file = e.target.files[0];
    let preview = document.getElementById("preview");

    if(file){
        preview.src = URL.createObjectURL(file);
        preview.classList.remove("d-none");
    }
});
</script>

<?php $this->load->view('templates/footer'); ?>