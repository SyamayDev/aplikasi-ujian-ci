<?php
// Buat URL lengkap dari data yang dikirim controller
$full_qr_url = base_url($qr_target_url . '?code=' . $qr_code_string);
?>
<h1 class="mb-4">Pengaturan QR Code Absensi</h1>

<div class="row">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                Edit Pengaturan
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/update_qr_code') ?>">
                    <div class="mb-3">
                        <label for="qr_target_url" class="form-label">URL Target Absensi</label>
                        <div class="input-group">
                            <span class="input-group-text"><?= base_url() ?></span>
                            <input type="text" class="form-control" id="qr_target_url" name="qr_target_url" value="<?= htmlspecialchars($qr_target_url) ?>" required>
                        </div>
                        <div class="form-text">Halaman yang akan terbuka saat QR di-scan.</div>
                    </div>
                    <div class="mb-3">
                        <label for="qr_code_string" class="form-label">Kode Unik QR</label>
                        <input type="text" class="form-control" id="qr_code_string" name="qr_code_string" value="<?= htmlspecialchars($qr_code_string) ?>" required>
                        <div class="form-text">Kode ini yang akan dikirim oleh aplikasi siswa. Jangan gunakan spasi atau karakter aneh.</div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Pengaturan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5 mt-4 mt-md-0">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-qrcode me-1"></i>
                Hasil QR Code
            </div>
            <div class="card-body text-center">
                <p>Scan QR Code ini dengan kamera biasa atau Google Lens.</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode($full_qr_url) ?>&size=200x200&margin=10" alt="QR Code" class="border rounded shadow-sm mb-3">
                <p class="small">URL Target: <br><a href="<?= $full_qr_url ?>" class="text-success" target="_blank"><?= $full_qr_url ?></a></p>
                <a href="<?= base_url('admin/download_qr') ?>" class="btn btn-success"><i class="fas fa-download me-2"></i>Download QR Code</a>
            </div>
        </div>
    </div>
</div>