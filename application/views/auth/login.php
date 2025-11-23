<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Ujian Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e8f5e9;
            /* Light green background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('<?= base_url('assets/img/gedungtritech.jpg') ?>');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .card {
            z-index: 2;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #28a745;
            /* Green header */
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .alert-container {
            position: absolute;
            top: 20px;
            width: 100%;
            display: flex;
            justify-content: center;
            z-index: 10;
        }

        .alert {
            max-width: 400px;
        }

        .logo {
            max-width: 100px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="alert-container">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <div class="card" style="width: 24rem;">
        <div class="card-header py-3">
            <h4>Sistem Ujian Online</h4>
        </div>
        <div class="card-body p-4">
            <div class="text-center">
                <img src="<?= base_url('assets/img/logotritech.png') ?>" alt="Logo" class="logo">
            </div>
            <p class="text-center">SD Al Washliyah Tembung</p>
            <form action="<?= base_url('auth/process') ?>" method="post">
                <?php if (
                    isset($this->security) &&
                    method_exists($this->security, 'get_csrf_token_name')
                ): ?>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <?php endif; ?>
                <input type="hidden" name="target_role" value="<?= isset($target_role) ? $target_role : 'siswa'; ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Username / NISN</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Admin/Guru: username — Siswa: gunakan NISN" required>
                    <?= form_error('username', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>