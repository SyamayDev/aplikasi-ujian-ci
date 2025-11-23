<div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h3 class="text-success">Preview Paket: <?= htmlspecialchars($paket['nama_paket']) ?></h3>
            <p class="mb-0">Guru: <?= htmlspecialchars($paket['nama_guru'] ?? '') ?></p>
            <p class="small text-muted">Status: <?= htmlspecialchars($paket['status']) ?></p>
        </div>
        <div class="btn-group">
            <?php if ($paket['status'] == 'pending'): ?>
                <a href="<?= base_url('paket/approve/' . $paket['id']) ?>" class="btn btn-success btn-approve">Approve</a>
                <a href="<?= base_url('paket/reject/' . $paket['id']) ?>" class="btn btn-danger btn-reject">Reject</a>
            <?php endif; ?>
            <a href="<?= base_url('paket') ?>" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php if (empty($preview)): ?>
                <p>Tidak ada preview tersedia.</p>
            <?php else: ?>
                <?php foreach ($preview as $i => $row): ?>
                    <div class="mb-3">
                        <h6>Soal <?= $i + 1 ?></h6>
                        <p><?= nl2br(htmlspecialchars($row['pertanyaan'])) ?></p>
                        <ul>
                            <li>A: <?= htmlspecialchars($row['a']) ?></li>
                            <li>B: <?= htmlspecialchars($row['b']) ?></li>
                            <li>C: <?= htmlspecialchars($row['c']) ?></li>
                            <li>D: <?= htmlspecialchars($row['d']) ?></li>
                            <?php if (!empty($row['e'])): ?><li>E: <?= htmlspecialchars($row['e']) ?></li><?php endif; ?>
                        </ul>
                        <p>Kunci: <?= htmlspecialchars($row['kunci']) ?> | Gambar: <?= htmlspecialchars($row['gambar']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>