<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Sistem Ujian'; ?> - Sistem Ujian Online</title>
    <?php if (isset($this->security) && method_exists($this->security, 'get_csrf_token_name')): ?>
        <meta name="csrf-name" content="<?= $this->security->get_csrf_token_name() ?>">
        <meta name="csrf-token" content="<?= $this->security->get_csrf_hash() ?>">
    <?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #f4f6f9;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        /* Sidebar styles moved to custom.css for clarity */
        #content {
            width: 100%;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <!-- Offcanvas Sidebar for mobile, normal sidebar for desktop -->
        <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
            <div class="offcanvas-header bg-success text-white">
                <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <nav class="nav flex-column bg-dark text-white vh-100 p-3">
                    <a class="nav-link text-white mb-2" href="<?= base_url('admin') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <div class="mt-3">
                        <div class="text-uppercase text-muted small px-2">Master Data</div>
                        <a class="nav-link text-white px-2" href="<?= base_url('admin/guru') ?>">Guru</a>
                        <a class="nav-link text-white px-2" href="<?= base_url('admin/siswa') ?>">Siswa</a>
                        <a class="nav-link text-white px-2" href="<?= base_url('admin/kelas') ?>">Kelas</a>
                        <a class="nav-link text-white px-2" href="<?= base_url('admin/mapel') ?>">Mapel</a>
                    </div>
                    <div class="mt-3">
                        <div class="text-uppercase text-muted small px-2">Ujian</div>
                        <a class="nav-link text-white px-2" href="<?= base_url('paket') ?>">Paket Soal</a>
                        <a class="nav-link text-white px-2" href="<?= base_url('room') ?>">Room Ujian</a>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Sidebar for large screens -->
        <nav id="sidebar" class="d-none d-lg-block bg-dark text-white">
            <div class="sidebar-header p-3 bg-success text-white">
                <div class="d-flex align-items-center">
                    <img src="<?= base_url('assets/img/logotritech.png') ?>" alt="Logo" style="height:40px;" class="me-2">
                    <div>
                        <h5 class="mb-0">Admin Panel</h5>
                        <small>SD Al Washliyah</small>
                    </div>
                </div>
            </div>
            <ul class="nav flex-column p-3">
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin') ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li class="nav-item mt-2">
                    <div class="text-uppercase text-muted small px-2">Master Data</div>
                </li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/guru') ?>">Guru</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/siswa') ?>">Siswa</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/kelas') ?>">Kelas</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/mapel') ?>">Mapel</a></li>
                <li class="nav-item mt-3">
                    <div class="text-uppercase text-muted small px-2">Ujian</div>
                </li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('paket') ?>">Paket Soal</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('room') ?>">Room Ujian</a></li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content" class="w-100">
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-outline-success d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                            <i class="bi bi-list"></i>
                        </button>
                        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('admin') ?>">
                            <img src="<?= base_url('assets/img/logotritech.png') ?>" alt="Logo" style="height:34px;" class="me-2">
                            <span class="fw-bold text-success">Sistem Ujian</span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ms-auto align-items-center">
                            <li class="nav-item me-3">
                                <span class="small text-muted">Halo, <?= htmlspecialchars($this->session->userdata('nama')) ?></span>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle fs-4 text-success"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                    <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h4 mb-0 text-success"><?= isset($title) ? $title : 'Dashboard'; ?></h1>
                </div>
                <hr>