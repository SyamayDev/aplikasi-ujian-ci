<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-success">Upload Paket Soal (Excel)</h3>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Paket</label>
                    <input name="nama_paket" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilih Mapel</label>
                    <select name="mapel_id" class="form-control select2">
                        <?php foreach ($mapel as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nama_mapel']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">File Excel (XLSX/XLS/CSV) - max 5MB</label>
                    <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">(Opsional) ZIP Gambar Soal - Unggah semua gambar dalam satu ZIP</label>
                    <input type="file" name="file_images_zip" accept=".zip" class="form-control">
                    <small class="text-muted">Jika disertakan, ZIP akan diekstrak ke <code>assets/uploads/paket/images/</code>.</small>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Jika soal menyertakan gambar, upload gambar ke <code>assets/uploads/paket/images/</code> terlebih dahulu atau sertakan ZIP.</small>
                </div>
                <button class="btn btn-success">Kirim Soal</button>
            </form>
        </div>
    </div>
</div>