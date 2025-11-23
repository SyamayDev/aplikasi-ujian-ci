<h1 class="mb-4">Pengaturan Lokasi Sekolah</h1>
<div class="card shadow-sm p-4">
    <form method="POST" action="<?= base_url('admin/update_lokasi') ?>">
        <div class="mb-3">
            <label class="form-label">Nama Lokasi</label>
            <input type="text" name="nama_lokasi" class="form-control" value="<?= htmlspecialchars($lokasi['nama_lokasi'] ?? 'SMK Tritech Informatika Medan') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($lokasi['alamat'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Latitude</label>
            <input type="number" step="any" name="latitude" class="form-control" value="<?= htmlspecialchars($lokasi['latitude'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Longitude</label>
            <input type="number" step="any" name="longitude" class="form-control" value="<?= htmlspecialchars($lokasi['longitude'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Radius (meter)</label>
            <input type="number" name="radius" class="form-control" value="<?= htmlspecialchars($lokasi['radius'] ?? 100) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
    </form>
</div>