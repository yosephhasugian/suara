<?php $this->load->view('templates/header', ['title' => 'Daftar Perangkat']); ?>
<?php $this->load->view('templates/sidebar'); ?>

<style>
.signage-card {
    height: 360px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.signage-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.signage-card .card-body {
    overflow: hidden;
    flex: 1;
}
.signage-preview img,
.signage-preview video {
    width: 100%;
    height: 160px;
    object-fit: cover;
    border-radius: 6px;
}
.signage-title {
    font-size: 1rem;
    font-weight: bold;
}
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="m-0 text-dark">Daftar Perangkat</h1>
            <a href="<?= site_url('signage/create') ?>" class="btn btn-primary">+ Tambah Perangkat</a>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php foreach ($signages as $s): 
                    $content = json_decode($s['content'], true) ?? [];
                    $video = isset($content['video']) ? base_url($content['video']) : '';
                    $image = isset($content['image']) ? base_url($content['image']) : '';
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                    <div class="card signage-card card-outline card-<?= $s['status'] == 'active' ? 'success' : 'secondary' ?>">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="signage-title"><?= htmlspecialchars($s['name']) ?></span>
                            <div class="card-tools">
                                <a href="<?= site_url('signage/edit/'.$s['id']) ?>" class="btn btn-tool">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= site_url('signage/delete/'.$s['id']) ?>" 
                                   class="btn btn-tool text-danger"
                                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="signage-preview mb-2">
                                <?php if ($video): ?>
                                    <video src="<?= $video ?>" controls muted></video>
                                <?php elseif ($image): ?>
                                    <img src="<?= $image ?>" alt="Preview">
                                <?php else: ?>
                                    <div class="text-center text-muted">Tidak ada media</div>
                                <?php endif; ?>
                            </div>
                            <p><strong>Layout:</strong> <?= ucfirst($s['layout']) ?></p>
                            <p><strong>Status:</strong>
                                <span class="badge badge-<?= $s['status'] == 'active' ? 'success' : 'secondary' ?>">
                                    <?= strtoupper($s['status']) ?>
                                </span>
                            </p>
                        </div>

                        <div class="card-footer text-center">
                            <a href="<?= site_url('player/view/'.$s['id']) ?>" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-play"></i> Preview
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('templates/footer'); ?>
