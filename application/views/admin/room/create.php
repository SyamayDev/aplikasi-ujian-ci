<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-success">Buat Room Ujian</h3>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger"><?= validation_errors() ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label>Nama Room</label>
                    <input name="nama_room" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Paket Soal</label>
                    <select name="paket_id" class="form-control select2">
                        <?php foreach ($paket as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama_paket']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Kelas Target</label>
                    <select name="kelas_target[]" multiple class="form-control select2">
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['kode_kelas'] ?? $k['nama_kelas'] ?? $k['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Durasi (menit)</label>
                        <input name="durasi_menit" type="number" class="form-control" value="60">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Mulai</label>
                        <input name="mulai_datetime" class="form-control" value="<?= date('Y-m-d H:i:s') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Selesai</label>
                        <input name="selesai_datetime" class="form-control" value="<?= date('Y-m-d H:i:s', strtotime('+1 hour')) ?>">
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="aktif" class="form-check-input" id="aktif"><label for="aktif" class="form-check-label">Aktif</label>
                </div>
                <button class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>