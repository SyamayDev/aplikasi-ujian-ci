<div class="modal fade" id="modalEditRoom" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditRoom" method="post" action="<?= base_url('room/edit/' . intval($room['id'])) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Room</label>
                        <input type="text" name="nama_room" class="form-control" value="<?= htmlspecialchars($room['nama_room']) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Paket Soal</label>
                            <select name="paket_id" class="form-select">
                                <?php foreach ($paket as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= $p['id'] == ($room['paket_id'] ?? '') ? 'selected' : '' ?>><?= htmlspecialchars($p['nama_paket']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Durasi (menit)</label>
                            <input type="number" name="durasi_menit" class="form-control" value="<?= intval($room['durasi_menit'] ?? 60) ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mulai (YYYY-MM-DD HH:MM)</label>
                            <input type="datetime-local" name="mulai_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($room['mulai_datetime'])) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Selesai (YYYY-MM-DD HH:MM)</label>
                            <input type="datetime-local" name="selesai_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($room['selesai_datetime'])) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas Target</label>
                        <select name="kelas_target[]" class="form-select select2" multiple>
                            <?php foreach ($kelas as $k): $sel = in_array($k['id'], explode(',', $room['kelas_target'] ?? '')) ? 'selected' : ''; ?>
                                <option value="<?= $k['id'] ?>" <?= $sel ?>><?= htmlspecialchars($k['nama_kelas'] ?? $k['kode_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="aktif" id="aktifRoom" class="form-check-input" <?= (isset($room['aktif']) && $room['aktif']) ? 'checked' : '' ?>>
                        <label for="aktifRoom" class="form-check-label">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>