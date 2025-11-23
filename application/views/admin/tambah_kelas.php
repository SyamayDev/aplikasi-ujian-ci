<h1 class="mb-4">Tambah Kelas Baru</h1>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="fas fa-plus me-1"></i>
        Form Tambah Kelas
    </div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/proses_tambah_kelas_jurusan') ?>">
            <div class="mb-3">
                <label for="inputTingkat" class="form-label">Tingkat Kelas</label>
                <select class="form-select" id="inputTingkat" name="tingkat" required>
                    <option value="" disabled selected>-- Pilih Tingkat --</option>
                    <option value="X">X</option>
                    <option value="XI">XI</option>
                    <option value="XII">XII</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="inputNamaJurusan" class="form-label">Nama Jurusan</label>
                <input type="text" class="form-control" id="inputNamaJurusan" name="nama_jurusan" required placeholder="Contoh: RPL 1, TKJ, DKV EXECUTIVE">
                <div class="form-text">Nama kelas akan digabung menjadi: (Tingkat)-(Nama Jurusan)</div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Kelas</button>
                <a href="<?= base_url('admin/kelola_kelas') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>