<h1 class="mb-4">Edit Data Siswa</h1>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="fas fa-edit me-1"></i>
        Form Edit Siswa
    </div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/update_siswa/' . $siswa['id']) ?>">
            <div class="mb-3">
                <label for="nis" class="form-label">NIS</label>
                <input type="text" name="nis" id="nis" class="form-control" value="<?= htmlspecialchars($siswa['nis']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($siswa['nama']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <select name="kelas" id="kelas" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelas_list as $kelas): ?>
                        <?php
                            $nama_kelas = htmlspecialchars($kelas['nama_kelas']);
                            $selected = ($nama_kelas === $siswa['kelas']) ? 'selected' : '';
                        ?>
                        <option value="<?= $nama_kelas ?>" <?= $selected ?>><?= $nama_kelas ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">
                    Ini adalah kelas yang dipilih siswa saat terakhir login/daftar.
                </div>
            </div>
            <div class="mb-3">
                <label for="hp">No. HP Orang Tua (WhatsApp)</label>
                <input type="text" class="form-control" id="hp" name="hp" value="<?= htmlspecialchars($siswa['hp'] ?? '') ?>" placeholder="Contoh: 081234567890">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                <a href="<?= base_url('admin/kelola_siswa') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>