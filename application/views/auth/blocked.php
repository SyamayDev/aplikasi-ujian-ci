<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8d7da;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            border-color: #dc3545;
        }
        .card-header {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="card text-center" style="width: 24rem;">
        <div class="card-header">
            <h3>Akses Ditolak</h3>
        </div>
        <div class="card-body">
            <h5 class="card-title">Anda Tidak Memiliki Hak Akses</h5>
            <p class="card-text">Anda tidak diizinkan untuk mengakses halaman ini. Silakan hubungi administrator jika Anda merasa ini adalah sebuah kesalahan.</p>
            <a href="<?= base_url('auth') ?>" class="btn btn-primary">Kembali ke Login</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
