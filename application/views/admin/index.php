<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Selamat Datang, <?= htmlspecialchars($this->session->userdata('nama')) ?></h4>
                <small class="text-muted">Kelola ujian dan pantau hasil secara cepat</small>
            </div>
            <div>
                <img src="<?= base_url('assets/img/logotritech.png') ?>" alt="logo" style="height:48px;">
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm card-stat p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="small text-muted">Total Guru</div>
                    <h3 class="mb-0"><?= intval($total_guru) ?></h3>
                </div>
                <div class="align-self-center text-success"><i class="bi bi-people fs-2"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm card-stat p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="small text-muted">Total Siswa</div>
                    <h3 class="mb-0"><?= intval($total_siswa) ?></h3>
                </div>
                <div class="align-self-center text-success"><i class="bi bi-mortarboard fs-2"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm card-stat p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="small text-muted">Paket Pending</div>
                    <h3 class="mb-0"><?= intval($paket_pending) ?></h3>
                </div>
                <div class="align-self-center text-success"><i class="bi bi-file-earmark-text fs-2"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm card-stat p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="small text-muted">Active Rooms</div>
                    <h3 class="mb-0"><?= intval($active_rooms) ?></h3>
                </div>
                <div class="align-self-center text-success"><i class="bi bi-door-open fs-2"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted">Rata-rata Nilai</h6>
            <div class="d-flex align-items-center">
                <h2 class="me-3 mb-0"><?= $avg_score !== null ? $avg_score : '-' ?></h2>
                <small class="text-muted">Nilai rata-rata dari semua ujian</small>
            </div>
            <hr>
            <h6 class="mb-2">Hasil Terbaru</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Siswa</th>
                            <th>Room</th>
                            <th>Paket</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_results as $i => $r): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($r['siswa_nama'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($r['nama_room'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($r['nama_paket'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($r['nilai'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-muted">Quick Actions</h6>
            <div class="list-group list-group-flush mt-2">
                <a href="<?= base_url('paket') ?>" class="list-group-item list-group-item-action">Kelola Paket Soal</a>
                <a href="<?= base_url('room') ?>" class="list-group-item list-group-item-action">Kelola Room Ujian</a>
                <a href="<?= base_url('admin/guru') ?>" class="list-group-item list-group-item-action">Kelola Guru</a>
                <a href="<?= base_url('admin/siswa') ?>" class="list-group-item list-group-item-action">Kelola Siswa</a>
            </div>
        </div>
    </div>
</div>