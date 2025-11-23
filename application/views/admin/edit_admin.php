<h1 class="mb-4">Edit Admin</h1>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="fas fa-user-edit me-1"></i>
        Form Edit Admin
    </div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/update_admin/' . $admin['id']) ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($admin['username']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password Baru (Opsional)</label>
                <input type="password" name="password" id="password" class="form-control">
                <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
            </div>
            <div class="mb-3">
                <label for="passconf" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="passconf" id="passconf" class="form-control">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                <a href="<?= base_url('admin/kelola_admin') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>