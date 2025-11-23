<div class="auth-wrapper">
    <div class="auth-content text-center">
        <!-- Pastikan logo ada di assets/img/logo_tritech.png -->
        <img src="<?= base_url('assets/img/logotritech.png') ?>" alt="Logo" class="login-logo mb-4">

        <div class="card">
            <div class="card-body p-4">
                <h3 class="card-title mb-4 text-success fw-bold">Login Siswa</h3>

                <?php
                // Menampilkan error validasi form (jika ada field kosong)
                if (validation_errors()) {
                    echo '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>';
                }
                // Menampilkan error custom dari controller (jika kredensial salah)
                if (!empty($error)) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                }
                ?>

                <form action="<?= base_url('siswa/do_login') ?>" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nis" name="nis" placeholder="NIS" value="<?= set_value('nis') ?>" required>
                        <label for="nis">NIS</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" value="<?= set_value('nama') ?>" required>
                        <label for="nama">Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select name="kelas" id="kelas" class="form-select" required>
                            <option value="" disabled <?= set_select('kelas', '', TRUE) ?>>-- Pilih Kelas Saat Ini --</option>
                            <?php foreach ($kelas_list as $kelas) : ?>
                                <option value="<?= htmlspecialchars($kelas['nama_kelas']) ?>" <?= set_select('kelas', $kelas['nama_kelas']) ?>><?= htmlspecialchars($kelas['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="kelas">Kelas Saat Ini</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg fw-bold">Masuk</button>
                    </div>
                </form>
                <p class="text-center mt-3">
                    Butuh Bantuan? <a href="https://wa.me/6288261991512" target="_blank">Klik Disini</a>
                </p>
            </div>
        </div>
    </div>
</div>