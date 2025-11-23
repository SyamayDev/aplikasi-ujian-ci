<div class="mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="text-success">Hasil Ujian</h3>
            <?php if (!empty($results)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Siswa</th>
                                <th>Paket</th>
                                <th>Room</th>
                                <th>Nilai</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $i => $r): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($r['nama']) ?></td>
                                    <td><?= htmlspecialchars($r['nama_paket']) ?></td>
                                    <td><?= htmlspecialchars($r['nama_room']) ?></td>
                                    <td><?= htmlspecialchars($r['nilai']) ?></td>
                                    <td><?= htmlspecialchars($r['finished_at'] ?? $r['started_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="small text-muted">Belum ada hasil ujian.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container py-4">
    <h3 class="text-success">Nilai Siswa (Untuk Guru)</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NISN</th>
                    <th>Kelas</th>
                    <th>Room</th>
                    <th>Paket</th>
                    <th>Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['nama']) ?></td>
                        <td><?= htmlspecialchars($r['nisn']) ?></td>
                        <td><?= htmlspecialchars($r['kode_kelas']) ?></td>
                        <td><?= htmlspecialchars($r['nama_room']) ?></td>
                        <td><?= htmlspecialchars($r['nama_paket']) ?></td>
                        <td><?= $r['nilai'] ?></td>
                        <td><?= $r['status'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>