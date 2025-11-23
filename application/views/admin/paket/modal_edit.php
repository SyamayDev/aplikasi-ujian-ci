<div class="modal fade" id="modalEditPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditPaket" method="post" action="<?= base_url('paket/edit/' . intval($paket['id'])) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" name="nama_paket" class="form-control" value="<?= htmlspecialchars($paket['nama_paket']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="mapel_id" class="form-select">
                            <?php foreach ($mapel as $m): ?>
                                <option value="<?= $m['id'] ?>" <?= $m['id'] == ($paket['mapel_id'] ?? '') ? 'selected' : '' ?>><?= htmlspecialchars($m['nama_mapel']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?= ($paket['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= ($paket['status'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= ($paket['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
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