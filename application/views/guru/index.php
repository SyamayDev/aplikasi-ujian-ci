<div class="mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="text-success">Dashboard Guru</h3>
            <p>Halo, <?= htmlspecialchars($this->session->userdata('nama')) ?>. Ini halaman ringkasan untuk Guru.</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Total Hasil</h5>
                            <p class="mb-0 small text-muted">Lihat hasil ujian yang diampu melalui menu "Hasil".</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Buat Paket &amp; Cek Approval</h5>
                            <p class="mb-0 small text-muted">Guru dapat mengunggah paket soal via menu Paket.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>