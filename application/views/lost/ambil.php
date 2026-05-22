<?php $this->load->view('templates/header', ['title' => 'Pengambilan Barang']); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper">
<section class="content pt-3">
<div class="container-fluid">

<div class="card shadow-lg">
<div class="card-header bg-success">
    <h5><i class="fas fa-hand-holding"></i> Form Pengambilan Barang</h5>
</div>

<div class="card-body">

<form method="post" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6">
<label>Nama Pengambil</label>
<input name="nama" class="form-control mb-3" required>
</div>

<div class="col-md-6">
<label>No HP</label>
<input name="hp" class="form-control mb-3" required>
</div>

<div class="col-md-6">
<label>No Identitas (KTP/SIM)</label>
<input name="identitas" class="form-control mb-3" required>
</div>

<div class="col-md-6">
<label>Nama Petugas</label>
<input name="petugas" class="form-control mb-3" required>
</div>

<div class="col-md-12">
<label>Alamat Pengambil</label>
<textarea name="alamat" class="form-control mb-3"></textarea>
</div>

<div class="col-md-6">
<label>Foto Serah Terima (WAJIB)</label>
<input type="file" name="foto_pengambilan" class="form-control mb-3" required>
</div>

<div class="col-md-6">
<label>Foto Identitas</label>
<input type="file" name="foto_identitas" class="form-control mb-3">
</div>

</div>

<button class="btn btn-success">
    <i class="fas fa-check"></i> Konfirmasi Pengambilan
</button>

</form>

</div>
</div>

</div>
</section>
</div>

<?php $this->load->view('templates/footer'); ?>