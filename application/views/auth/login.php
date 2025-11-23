<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= html_escape($app_name); ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('<?= base_url('assets/img/' . html_escape($pengaturan['background_login'])); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem 3rem;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-header img {
            max-width: 80px;
            margin-bottom: 1rem;
        }
        .login-header h2 {
            margin-bottom: 0.5rem;
            color: #333;
        }
        .login-header p {
            color: #666;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #059669; /* Green theme */
        }
        .btn-login {
            background-color: #059669; /* Green theme */
            border-color: #059669;
            color: white;
            padding: 10px;
            font-size: 1.1rem;
        }
        .btn-login:hover {
            background-color: #047857;
            border-color: #047857;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <img src="<?= base_url('assets/img/' . html_escape($pengaturan['logo_sekolah'])); ?>" alt="Logo Sekolah">
            <h2><?= html_escape($app_name); ?></h2>
            <p>Silakan login untuk melanjutkan</p>
        </div>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <?php if(validation_errors()): ?>
            <div class="alert alert-danger" role="alert">
                <?= validation_errors(); ?>
            </div>
        <?php endif; ?>

        <?= form_open('auth/login'); ?>
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username / NIS</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username atau NIS" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-login">Login</button>
            </div>
        <?= form_close(); ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
