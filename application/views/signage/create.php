<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0">Tambah Perangkat</h1>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <form action="<?= site_url('signage/store') ?>" method="post" enctype="multipart/form-data">
        <div class="card card-primary">
          <div class="card-body">
            <div class="form-group">
              <label>Nama Perangkat</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Lokasi</label>
              <input type="text" name="location" class="form-control">
            </div>
            <div class="form-group">
              <label>Layout</label>
              <select name="layout" class="form-control">
                <option value="single">Single (Teks + Gambar)</option>
                <option value="dual">Dual (Gambar + Teks)</option>
                <option value="video_text">Video + Teks</option>
                <option value="carousel">Carousel</option>
              </select>
            </div>
            <div class="form-group">
              <label>Upload Gambar</label>
              <input type="file" name="image" class="form-control">
            </div>
            <div class="form-group">
              <label>Upload Video</label>
              <input type="file" name="video" class="form-control">
            </div>
            <div class="form-group">
              <label>Teks</label>
              <textarea name="text" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
              </select>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('signage') ?>" class="btn btn-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>
