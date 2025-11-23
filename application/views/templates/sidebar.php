<div class="border-end d-flex flex-column" id="sidebar-wrapper">
    <div class="sidebar-heading border-bottom d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-user-shield me-2"></i>Absensi Siswa
        </span>
        <button class="btn btn-sm btn-close-sidebar d-md-none" id="sidebarClose"><i class="fas fa-times"></i></button>
    </div>
    <div class="list-group list-group-flush flex-grow-1">
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('admin/dashboard') ?>">
            <i class="fas fa-tachometer-alt fa-fw me-2"></i>Dashboard
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('admin/qr_code') ?>">
            <i class="fas fa-qrcode fa-fw me-2"></i>QR Code
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('admin/data_absensi') ?>">
            <i class="fas fa-table fa-fw me-2"></i>Data Absensi
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('admin/lokasi') ?>">
            <i class="fas fa-map-marker-alt fa-fw me-2"></i>Lokasi
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('admin/kelola_kelas') ?>">
            <i class="fas fa-school fa-fw me-2"></i>Kelola Kelas
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('admin/kelola_siswa') ?>">
            <i class="fas fa-users-cog fa-fw me-2"></i>Kelola Siswa
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('paket') ?>">
            <i class="fas fa-file-upload fa-fw me-2"></i>Manajemen Paket Soal
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('room') ?>">
            <i class="fas fa-door-open fa-fw me-2"></i>Manajemen Room
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('admin/kelola_admin') ?>">
            <i class="fas fa-users fa-fw me-2"></i>Kelola Admin
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('admin/notifikasi') ?>">
            <i class="fas fa-bell fa-fw me-2"></i>Notifikasi WA
        </a>
        <a class="list-group-item list-group-item-action p-3" href="<?= base_url('admin/logout') ?>">
            <i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout
        </a>
    </div>
    <div class="sidebar-footer p-3 text-center small">
        &copy; <?= date('Y') ?> SMK Tritech
    </div>
</div>