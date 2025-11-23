<h1 class="mb-4">Tambah Siswa Baru</h1>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="fas fa-user-plus me-1"></i>
        Form Tambah Siswa
    </div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/proses_tambah_siswa') ?>">
            <div class="mb-3">
                <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
                <input type="text" name="nis" id="nis" class="form-control" placeholder="Masukkan NIS" required>
                <div class="form-text">Pastikan NIS unik dan belum pernah terdaftar.</div>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Lengkap Siswa" required>
            </div>
            <div class="mb-3">
                <label for="kelas" class="form-label">Kelas Awal</label>
                <select name="kelas" id="kelas" class="form-select" required>
                    <option value="" selected disabled>-- Pilih Kelas --</option>
                    <?php foreach ($kelas_list as $kelas): ?>
                        <option value="<?= htmlspecialchars($kelas['nama_kelas']) ?>"><?= htmlspecialchars($kelas['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">
                    Ini adalah kelas awal siswa saat mendaftar. Siswa dapat mengubahnya saat login.
                </div>
            </div>
            <div class="mb-3">
                <label for="hp" class="form-label">No. HP Orang Tua (WhatsApp)</label>
                <input type="text" name="hp" id="hp" class="form-control" placeholder="Contoh: 081234567890" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Siswa</button>
                <a href="<?= base_url('admin/kelola_siswa') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>