<?php $this->load->view('templates/header', ['title' => $title]); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content-wrapper p-3">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manajemen User</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button class="btn btn-primary" id="btnNewUser">Buat User Baru</button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Daftar User</h3>
                    </div>
                    <div class="card-body table-responsive p-0" style="max-height: 60vh;">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                        <td><?= htmlspecialchars($roles[$user['role']] ?? $user['role']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info btnEditUser" 
                                                data-id="<?= $user['id'] ?>"
                                                data-username="<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>"
                                                data-name="<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>"
                                                data-role="<?= $user['role'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if ($user['id'] !== 1): ?>
                                                <a href="<?= site_url('users/delete/' . $user['id']) ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Hapus user <?= htmlspecialchars($user['username']) ?>?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title" id="formTitle">Buat User Baru</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?= site_url('users/save') ?>" id="userForm">
                            <input type="hidden" name="id" id="userId" value="">

                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <?php foreach ($roles as $value => $label): ?>
                                        <option value="<?= $value ?>"><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                                <small class="form-text text-muted">Masukkan password baru untuk user baru atau kosongkan jika tidak ingin mengubah password user lama.</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success" id="submitButton">Simpan User</button>
                                <button type="button" class="btn btn-secondary" id="btnResetForm">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const formTitle = document.getElementById('formTitle');
    const userId = document.getElementById('userId');
    const usernameField = document.getElementById('username');
    const nameField = document.getElementById('name');
    const roleField = document.getElementById('role');
    const passwordField = document.getElementById('password');
    const submitButton = document.getElementById('submitButton');

    document.querySelectorAll('.btnEditUser').forEach(button => {
        button.addEventListener('click', function() {
            userId.value = this.dataset.id;
            usernameField.value = this.dataset.username;
            nameField.value = this.dataset.name;
            roleField.value = this.dataset.role;
            passwordField.value = '';
            formTitle.textContent = 'Edit User';
            submitButton.textContent = 'Update User';
        });
    });

    document.getElementById('btnNewUser').addEventListener('click', function() {
        resetForm();
    });

    document.getElementById('btnResetForm').addEventListener('click', function() {
        resetForm();
    });

    function resetForm() {
        userId.value = '';
        usernameField.value = '';
        nameField.value = '';
        roleField.value = 'admin';
        passwordField.value = '';
        formTitle.textContent = 'Buat User Baru';
        submitButton.textContent = 'Simpan User';
    }
</script>

<?php $this->load->view('templates/footer'); ?>
