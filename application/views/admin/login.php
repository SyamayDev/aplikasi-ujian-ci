<div class="auth-wrapper">
    <div class="auth-content text-center">
        <img src="<?= base_url('assets/img/logotritech.png') ?>" alt="Logo" class="login-logo mb-4">

        <div class="card">
            <div class="card-body p-4">
                <h3 class="card-title mb-4 text-success fw-bold">Login Admin</h3>

                <?php if ($this->session->flashdata('warning')): ?>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <?= $this->session->flashdata('warning') ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/do_login') ?>" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg fw-bold">Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>