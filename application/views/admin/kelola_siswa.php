<?php
$unique_kelas = [];
if (!empty($list_siswa)) {
    $unique_kelas = array_unique(array_column($list_siswa, 'kelas'));
    sort($unique_kelas);
}
?>
<h1 class="mt-4">Kelola Akun Siswa</h1>
<p>Berikut adalah daftar akun siswa yang terdaftar dalam sistem.</p>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users me-1"></i>Daftar Akun Siswa Terdaftar</span>
        <div class="btn-group gap-2">
            <a href="<?= base_url('admin/import_siswa') ?>" class="btn btn-sm btn-info">
                <i class="fas fa-file-import me-1"></i> Import Siswa
            </a>
            <a href="<?= base_url('admin/tambah_siswa') ?>" class="btn btn-sm btn-success">
                <i class="fas fa-plus me-1"></i> Tambah Siswa
            </a>
            <button id="exportExcelBtn" class="btn btn-sm btn-primary" title="Export data siswa yang tampil ke Excel">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="kelasFilter" class="form-label">Filter Kelas:</label>
                <select id="kelasFilter" class="form-select form-select-sm">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($unique_kelas as $kelas): ?>
                        <option value="<?= htmlspecialchars($kelas) ?>"><?= htmlspecialchars($kelas) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="searchInput" class="form-label">Cari Siswa:</label>
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Ketik NIS atau Nama...">
            </div>
        </div>
        <div class="table-responsive">
            <table id="siswaTable" class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas Terakhir Login</th>
                        <th>No. HP Orang Tua</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list_siswa)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada siswa yang terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($list_siswa as $siswa): ?>
                            <tr>
                                <td><?= htmlspecialchars($siswa['nis']) ?></td>
                                <td><?= htmlspecialchars($siswa['nama']) ?></td>
                                <td><?= htmlspecialchars($siswa['kelas']) ?></td>
                                <td><?= htmlspecialchars($siswa['hp'] ?? '-') ?></td>
                                <td>
                                    <a href="<?= base_url('admin/absenkan_siswa/' . $siswa['id']) ?>" class="btn btn-sm btn-info btn-absenkan" title="Absenkan Siswa"><i class="fas fa-qrcode"></i></a>
                                    <a href="<?= base_url('admin/edit_siswa/' . $siswa['id']) ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="<?= base_url('admin/hapus_siswa/' . $siswa['id']) ?>" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#siswaTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[1, 'asc']],
        dom: 'rtip'
    });

    $('#kelasFilter').on('change', function() {
        let searchTerm = $(this).val();
        if (searchTerm) {
            table.column(2).search('^' + searchTerm + '$', true, false).draw();
        } else {
            table.column(2).search('').draw();
        }
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#exportExcelBtn').on('click', function() {
        let data = [];
        // Tambahkan header
        let headers = ['NIS', 'Nama', 'Kelas Terakhir Login', 'No. HP Orang Tua'];
        data.push(headers);

        // Ambil data dari baris yang sudah difilter
        table.rows({ search: 'applied' }).data().each(function(row) {
            data.push([
                row[0], // NIS
                row[1], // Nama
                row[2], // Kelas
                row[3]  // No. HP
            ]);
        });

        // Buat dan unduh file Excel
        let ws = XLSX.utils.aoa_to_sheet(data);
        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Data Siswa');
        let now = new Date();
        let filename = `Data_Siswa_${now.toLocaleDateString('id-ID').replace(/\//g, '-')}_${now.toLocaleTimeString('id-ID').replace(/:/g, '-')}.xlsx`;
        XLSX.writeFile(wb, filename);
    });

    $('.btn-absenkan').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        const namaSiswa = $(this).closest('tr').find('td:nth-child(2)').text();
        Swal.fire({
            title: 'Absenkan Siswa?',
            html: `Anda akan mengabsenkan <b>${namaSiswa}</b>.<br>Sistem akan menentukan status (Masuk/Pulang) secara otomatis berdasarkan jam saat ini.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Ya, Absenkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });

    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Akun siswa dan seluruh riwayat absensinya akan dihapus permanen!',
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
});
</script>