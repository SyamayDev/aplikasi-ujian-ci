<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-success">Manajemen Paket Soal</h3>
        <div>
            <a href="<?= base_url('paket/upload') ?>" class="btn btn-success"><i class="bi bi-upload me-1"></i> Upload Paket</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Paket</th>
                            <th>Guru</th>
                            <th>Mapel</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($paket)): foreach ($paket as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['nama_paket']) ?></td>
                                    <td><?= htmlspecialchars($p['nama_guru']) ?></td>
                                    <td><?= htmlspecialchars($p['nama_mapel']) ?></td>
                                    <td><span class="badge bg-<?= $p['status'] == 'approved' ? 'success' : ($p['status'] == 'pending' ? 'warning' : 'danger') ?>"><?= ucfirst($p['status']) ?></span></td>
                                    <td class="text-end table-actions">
                                        <a href="<?= base_url('paket/view/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="bi bi-eye"></i></a>
                                        <a href="<?= base_url('paket/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-warning btn-edit-paket" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <?php if ($p['status'] == 'pending'): ?>
                                            <a href="<?= base_url('paket/approve/' . $p['id']) ?>" class="btn btn-sm btn-success btn-approve" title="Approve"><i class="bi bi-check2"></i></a>
                                            <a href="<?= base_url('paket/reject/' . $p['id']) ?>" class="btn btn-sm btn-danger btn-reject" title="Reject"><i class="bi bi-x-lg"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="5" class="text-center small text-muted">Belum ada paket soal.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>