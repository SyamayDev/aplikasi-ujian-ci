<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-success">Manajemen Room</h3>
        <div>
            <a href="<?= base_url('room/create') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Buat Room</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Room</th>
                            <th>Paket</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Aktif</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rooms)): foreach ($rooms as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['nama_room']) ?></td>
                                    <td><?= htmlspecialchars($r['nama_paket']) ?></td>
                                    <td><?= $r['mulai_datetime'] ?></td>
                                    <td><?= $r['selesai_datetime'] ?></td>
                                    <td><?= $r['aktif'] ? '<span class="badge bg-success">Ya</span>' : '<span class="badge bg-secondary">Tidak</span>' ?></td>
                                    <td class="text-end table-actions">
                                        <a href="<?= base_url('room/detail/' . $r['id']) ?>" class="btn btn-sm btn-outline-info" title="Detail Room"><i class="bi bi-info-circle"></i></a>
                                        <a href="<?= base_url('room/preview/' . $r['id']) ?>" class="btn btn-sm btn-outline-secondary" title="Preview Room"><i class="bi bi-eye"></i></a>
                                        <a href="<?= base_url('room/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-warning btn-edit-room" title="Edit Room"><i class="bi bi-pencil"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="6" class="text-center small text-muted">Belum ada room.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>