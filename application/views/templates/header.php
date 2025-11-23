<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Absensi Siswa' ?></title>
    <!-- Library untuk QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <!-- jQuery (dipindahkan dari footer agar bisa digunakan di view) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" integrity="sha256-2m+0H7O8eN0ZrdIdI8i0hXgqH0H1+mc2W4xQ64QfOSQ=" crossorigin="anonymous">
    <!-- CSS untuk Fitur Islami -->
    <link rel="stylesheet" href="<?= base_url('assets/css/fitur_islami.css') ?>">
    <!-- DataTables CSS (opsional, hanya jika digunakan di halaman tertentu) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f0;
        }

        .navbar {
            background-color: #2e7d32;
        }

        .sidebar {
            background-color: #1b5e20;
            transition: width 0.3s;
        }

        .sidebar .nav-link {
            color: #ffffff;
        }

        .sidebar .nav-link:hover {
            background-color: #388e3c;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .btn-primary {
            background-color: #388e3c;
            border-color: #388e3c;
        }

        .btn-primary:hover {
            background-color: #2e7d32;
            border-color: #2e7d32;
        }

        .card {
            border-color: #4caf50;
        }

        .text-success {
            color: #2e7d32 !important;
        }

        /* Styles for Login/Register Page */
        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .auth-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('<?= base_url("assets/img/gedungtritech.jpg") ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(4px);
            transform: scale(1.05);
            /* To avoid blurred edges */
            z-index: -2;
        }

        .auth-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(46, 125, 50, 0.4);
            /* Green overlay */
            z-index: -1;
        }

        .auth-content {
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }

        .auth-content .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .login-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.8),
                0 0 25px rgba(46, 125, 50, 0.6);
        }
    </style>
</head>

<body></body>