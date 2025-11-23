<h1 class="mb-4">Dashboard</h1>
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm p-4 mb-4">
            <h3 class="text-success"><i class="fas fa-map-marker-alt me-2"></i>Informasi Lokasi Sekolah</h3>
            <?php if ($lokasi): ?>
                <p><strong>Alamat:</strong> <?= htmlspecialchars($lokasi['alamat']) ?></p>
                <p><strong>Latitude:</strong> <?= htmlspecialchars($lokasi['latitude']) ?></p>
                <p><strong>Longitude:</strong> <?= htmlspecialchars($lokasi['longitude']) ?></p>
                <p><strong>Radius:</strong> <?= htmlspecialchars($lokasi['radius']) ?> meter</p>
            <?php else: ?>
                <p class="text-danger">Lokasi sekolah belum diatur.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm p-4 mb-4">
            <h3 class="text-success"><i class="fas fa-clock me-2"></i>Pengaturan Waktu Absensi</h3>
            <p class="form-text">Atur jam masuk dan jam pulang untuk setiap hari kerja.</p>
            <form method="POST" action="<?= base_url('admin/update_waktu_absensi') ?>">
                <?php
                $hari_list = [
                    'senin' => 'Senin',
                    'selasa' => 'Selasa',
                    'rabu' => 'Rabu',
                    'kamis' => 'Kamis',
                    'jumat' => 'Jumat'
                ];
                foreach ($hari_list as $key => $nama_hari):
                ?>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-3"><strong><?= $nama_hari ?></strong></div>
                    <div class="col-md-4">
                        <label for="jam_masuk_<?= $key ?>" class="form-label small mb-0">Jam Masuk</label>
                        <input type="time" class="form-control form-control-sm" id="jam_masuk_<?= $key ?>" name="jam_masuk[<?= $key ?>]" value="<?= htmlspecialchars($waktu_absensi[$key]['masuk'] ?? '07:15') ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="jam_pulang_<?= $key ?>" class="form-label small mb-0">Jam Pulang</label>
                        <input type="time" class="form-control form-control-sm" id="jam_pulang_<?= $key ?>" name="jam_pulang[<?= $key ?>]" value="<?= htmlspecialchars($waktu_absensi[$key]['pulang'] ?? '16:00') ?>">
                    </div>
                </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-save me-2"></i>Simpan Waktu</button>
            </form>
        </div>
    </div>
</div>