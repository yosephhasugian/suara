<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper">
<section class="content pt-3">
<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="font-weight-bold">
            <i class="fas fa-box-open text-warning"></i> Lost & Found
        </h4>

        <a href="<?= site_url('lost/tambah') ?>" class="btn btn-primary shadow">
            <i class="fas fa-plus"></i> Input Barang
        </a>
    </div>

    <!-- STAT -->
    <div class="row">
        <?php 
        $total = count($data);
        $ditemukan = 0;
        $diambil = 0;

        foreach($data as $d){
            if($d->status == 'ditemukan') $ditemukan++;
            if($d->status == 'diambil') $diambil++;
        }
        ?>

        <div class="col-md-4">
            <div class="small-box bg-info shadow">
                <div class="inner">
                    <h3><?= $total ?></h3>
                    <p>Total Barang</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-warning shadow">
                <div class="inner">
                    <h3><?= $ditemukan ?></h3>
                    <p>Belum Diambil</p>
                </div>
                <div class="icon"><i class="fas fa-search"></i></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-success shadow">
                <div class="inner">
                    <h3><?= $diambil ?></h3>
                    <p>Sudah Diambil</p>
                </div>
                <div class="icon"><i class="fas fa-check"></i></div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-lg">
        <div class="card-header bg-dark">
            <h3 class="card-title">
                <i class="fas fa-list"></i> Data Barang
            </h3>
        </div>

        <div class="card-body">

            <!-- SEARCH -->
            <input type="text" id="search" class="form-control mb-3" placeholder="🔍 Cari barang...">

            <div class="table-responsive">
                <table class="table table-hover table-striped" id="tableData">
                    <thead class="bg-gradient-dark text-white">
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($data as $d): ?>
                        <tr class="row-hover">

                            <td>
                                <?php if($d->bukti_foto): ?>
                                    <img src="<?= base_url('assets/uploads/images/'.$d->bukti_foto) ?>"
                                        width="55"
                                        class="img-thumbnail foto-click"
                                        style="cursor:pointer"
                                        data-img="<?= base_url('assets/uploads/images/'.$d->bukti_foto) ?>">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <strong><?= $d->nama_barang ?></strong><br>
                                <small class="text-muted"><?= $d->kategori ?></small>
                            </td>

                            <td><?= $d->lokasi_ditemukan ?></td>

                            <td><?= date('d M Y', strtotime($d->tanggal_ditemukan)) ?></td>

                            <td>
                                <?php if($d->status=='ditemukan'): ?>
                                    <span class="badge badge-warning">Menunggu</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Diambil</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($d->status=='ditemukan'): ?>
                                <a href="<?= site_url('lost/ambil/'.$d->id) ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-hand-holding"></i>
                                </a>
                                <?php endif; ?>

                                <a href="<?= site_url('lost/hapus/'.$d->id) ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Hapus data?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
</section>
</div>

<!-- ================= CUSTOM MODAL ================= -->
<div id="customModal" class="custom-modal">
    <span class="close-modal">&times;</span>
    <img class="modal-content-img" id="modalImg">
</div>

<!-- ================= STYLE ================= -->
<style>
.row-hover:hover {
    transform: scale(1.01);
    transition: 0.2s;
}

.small-box, .card {
    border-radius: 12px;
}

#search {
    border-radius: 30px;
    padding-left: 20px;
}

.foto-click {
    transition: 0.2s;
}

.foto-click:hover {
    transform: scale(1.1);
}

.custom-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    padding-top: 60px;
    left: 0; top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.9);
}

.modal-content-img {
    margin: auto;
    display: block;
    max-width: 80%;
    max-height: 80%;
    border-radius: 10px;
}

.close-modal {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    cursor: pointer;
}
</style>

<!-- ================= JS ================= -->
<script>
document.addEventListener("DOMContentLoaded", function(){

    // SEARCH
    document.getElementById("search").addEventListener("keyup", function(){
        let value = this.value.toLowerCase();
        document.querySelectorAll("#tableData tbody tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });

    // MODAL FOTO
    const modal = document.getElementById("customModal");
    const modalImg = document.getElementById("modalImg");
    const closeBtn = document.querySelector(".close-modal");

    document.querySelectorAll(".foto-click").forEach(img => {
        img.addEventListener("click", function(){
            modal.style.display = "block";
            modalImg.src = this.getAttribute("data-img");
        });
    });

    closeBtn.onclick = () => modal.style.display = "none";

    modal.onclick = function(e){
        if(e.target === modal){
            modal.style.display = "none";
        }
    };

});
</script>

<?php $this->load->view('templates/footer'); ?>