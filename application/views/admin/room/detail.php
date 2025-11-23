<div class="container py-4">
    <h3 class="text-success">Detail Room: <?= htmlspecialchars($room['nama_room']) ?></h3>
    <p>Paket: <?= htmlspecialchars($room['nama_paket'] ?? '') ?></p>
    <h5>Peserta & Hasil</h5>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Nilai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hasil as $h): ?>
                    <tr>
                        <td><?= htmlspecialchars($h['nisn']) ?></td>
                        <td><?= htmlspecialchars($h['nama']) ?></td>
                        <td><?= htmlspecialchars($h['kode_kelas']) ?></td>
                        <td><?= $h['nilai'] ?></td>
                        <td><?= $h['status'] ?></td>
                        <td>
                            <a href="<?= base_url('room/reset_hasil/' . $h['id']) ?>" class="btn btn-sm btn-warning btn-reset" data-hasil-id="<?= $h['id'] ?>">Reset</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>