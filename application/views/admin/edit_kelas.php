<h1 class="mb-4">Edit Kelas</h1>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="fas fa-edit me-1"></i>
        Form Edit Kelas
    </div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/proses_update_kelas_jurusan/' . $kelas['id']) ?>">
            <div class="mb-3">
                <label for="inputTingkat" class="form-label">Tingkat Kelas</label>
                <select class="form-select" id="inputTingkat" name="tingkat" required>
                    <option value="X" <?= ($tingkat_selected === 'X') ? 'selected' : '' ?>>X</option>
                    <option value="XI" <?= ($tingkat_selected === 'XI') ? 'selected' : '' ?>>XI</option>
                    <option value="XII" <?= ($tingkat_selected === 'XII') ? 'selected' : '' ?>>XII</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="inputNamaJurusan" class="form-label">Nama Jurusan</label>
                <input type="text" class="form-control" id="inputNamaJurusan" name="nama_jurusan" required 
                       value="<?= htmlspecialchars($jurusan_value) ?>" 
                       placeholder="Contoh: RPL 1, TKJ, DKV EXECUTIVE">
                <div class="form-text">Nama kelas akan digabung menjadi: (Tingkat)-(Nama Jurusan)</div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                <a href="<?= base_url('admin/kelola_kelas') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>