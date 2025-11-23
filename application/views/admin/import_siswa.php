<h1 class="mb-4">Import Data Siswa</h1>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="fas fa-file-import me-1"></i>
        Form Import Siswa dari Excel
    </div>
    <div class="card-body">
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading">Petunjuk Import</h5>
                <p>Gunakan file Excel (.xls atau .xlsx) dengan format yang telah ditentukan. Pastikan urutan kolom sesuai dengan template: <strong>NIS, Nama, Kelas, No. HP Orang Tua</strong>.</p>
                <p class="mb-0">Siswa dengan <strong>NIS yang sudah ada</strong> di sistem akan dilewati (tidak di-import ulang).</p>
                <hr>
                <a href="<?= base_url('admin/download_template_siswa') ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-download me-2"></i>Download Template Excel
                </a>
            </div>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/proses_import_siswa') ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="file_excel" class="form-label fw-bold">Pilih File Excel</label>
                <input type="file" class="form-control" name="file_excel" id="file_excel" accept=".xls,.xlsx" required>
                <div class="form-text">Hanya file .xls dan .xlsx yang diizinkan. Ukuran maksimal 10MB.</div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-2"></i>Upload & Import</button>
                <a href="<?= base_url('admin/kelola_siswa') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>