<div class="container mt-4">
    <h2>Pengaturan Notifikasi WhatsApp (Fonnte)</h2>
    <hr>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-bell me-1"></i>
            Form Pengaturan Notifikasi
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/update_notifikasi') ?>" method="post">
                <div class="form-group mb-3">
                    <label for="fonnte_token" class="form-label">Fonnte API Token</label>
                    <input type="text" class="form-control" id="fonnte_token" name="fonnte_token" value="<?= htmlspecialchars($fonnte_token ?? '') ?>" required>
                    <small class="form-text text-muted">Dapatkan token dari dashboard Fonnte Anda.</small>
                </div>
                <div class="form-group mb-3">
                    <label for="fonnte_message_template" class="form-label">Template Pesan</label>
                    <textarea class="form-control" id="fonnte_message_template" name="fonnte_message_template" rows="5" required><?= htmlspecialchars($fonnte_message_template ?? '') ?></textarea>
                    <small class="form-text text-muted">
                        Gunakan placeholder berikut:
                        <code>{nama_siswa}</code>, <code>{nis}</code>, <code>{kelas}</code>, <code>{waktu}</code>, <code>{keterangan}</code>.
                    </small>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Pengaturan</button>
            </form>
        </div>
    </div>
</div>
