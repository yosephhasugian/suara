<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0">Edit Perangkat</h1>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <form action="<?= site_url('signage/update/'.$signage['id']) ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="name" value="<?= htmlspecialchars($signage['name']) ?>" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Layout</label>
        <select name="layout" class="form-control">
            <option value="single" <?= $signage['layout']=='single'?'selected':'' ?>>Single</option>
            <option value="dual" <?= $signage['layout']=='dual'?'selected':'' ?>>Dual</option>
        </select>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="active" <?= $signage['status']=='active'?'selected':'' ?>>Active</option>
            <option value="inactive" <?= $signage['status']=='inactive'?'selected':'' ?>>Inactive</option>
        </select>
    </div>

    <div class="form-group">
        <label>Teks</label>
        <input type="text" name="text" value="<?= htmlspecialchars($content['text'] ?? '') ?>" class="form-control">
    </div>

    <div class="form-group">
        <label>Gambar</label><br>
        <?php if (!empty($content['image'])): ?>
            <img src="<?= base_url($content['image']) ?>" alt="" width="150"><br><br>
        <?php endif; ?>
        <input type="file" name="image" class="form-control">
    </div>

    <div class="form-group">
        <label>Video</label><br>
        <?php if (!empty($content['video'])): ?>
            <video src="<?= base_url($content['video']) ?>" width="200" controls></video><br><br>
        <?php endif; ?>
        <input type="file" name="video" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="<?= site_url('signage') ?>" class="btn btn-secondary">Batal</a>
</form>
    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>
