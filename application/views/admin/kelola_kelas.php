<?php
$unique_tingkat = [];
if (!empty($list_kelas_jurusan)) {
    foreach ($list_kelas_jurusan as $kj) {
        $parts = explode('-', $kj['nama_kelas'], 2);
        if (!empty($parts[0])) {
            $unique_tingkat[] = $parts[0];
        }
    }
    $unique_tingkat = array_unique($unique_tingkat);
    sort($unique_tingkat);
}
?>
<h1 class="mt-4">Kelola Kelas</h1>
<p>Berikut adalah daftar kelas yang terdaftar dalam sistem.</p>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-school me-1"></i>Daftar Kelas</span>
                <a href="<?= base_url('admin/tambah_kelas_jurusan') ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i> Tambah Kelas
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="kelasFilter" class="form-label">Filter Tingkat:</label>
                        <select id="kelasFilter" class="form-select form-select-sm">
                            <option value="">Semua Tingkat</option>
                            <?php foreach ($unique_tingkat as $tingkat): ?>
                                <option value="<?= htmlspecialchars($tingkat) ?>"><?= htmlspecialchars($tingkat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="searchInput" class="form-label">Cari Kelas:</label>
                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Ketik Nama Kelas...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="kelasTable" class="table table-bordered table-striped">
                        <thead class="table-success">
                            <tr>
                                <th>Nama Kelas</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($list_kelas_jurusan)): ?>
                                <tr>
                                    <td colspan="2" class="text-center">Belum ada data kelas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($list_kelas_jurusan as $kj): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($kj['nama_kelas']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/edit_kelas_jurusan/' . $kj['id']) ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <a href="<?= base_url('admin/hapus_kelas_jurusan/' . $kj['id']) ?>" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    console.log('jQuery loaded:', typeof $ !== 'undefined');
    console.log('DataTables loaded:', typeof $.fn.DataTable !== 'undefined');

    try {
        var table = $('#kelasTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']],
            dom: 'rtip'
        });

        $('#kelasFilter').on('change', function() {
            let tingkat = $(this).val();
            table.column(0).search(tingkat ? '^' + tingkat + '-' : '', true, false).draw();
        });

        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            Swal.fire({
                title: 'Anda yakin?',
                text: 'Data kelas akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    } catch (e) {
        console.error('DataTables initialization error:', e);
    }
});
</script>