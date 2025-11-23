<div class="container py-4">
    <h3 class="text-success">Daftar Ujian</h3>
    <div class="list-group">
        <?php if (empty($rooms)): ?>
            <div class="alert alert-info">Tidak ada room aktif untuk kelas Anda.</div>
        <?php else: ?>
            <?php foreach ($rooms as $r): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><?= htmlspecialchars($r['nama_room']) ?></h5>
                        <small><?= htmlspecialchars($r['nama_paket']) ?> — <?= $r['mulai_datetime'] ?> s/d <?= $r['selesai_datetime'] ?></small>
                    </div>
                    <div>
                        <a href="<?= base_url('ujian/start/' . $r['id']) ?>" class="btn btn-success">Mulai Ujian</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>