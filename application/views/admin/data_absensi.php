<h1 class="mt-4">Data Absensi Siswa</h1>
<p>Berikut adalah seluruh data absensi yang terekam dalam sistem.</p>

<div class="card shadow-sm">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Tabel Data Absensi
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="kelasFilter" class="form-label">Filter Tingkat:</label>
                <select id="kelasFilter" class="form-select form-select-sm">
                    <option value="">Semua Tingkat</option>
                    <?php foreach ($tingkat_list as $tingkat): ?>
                        <option value="<?= htmlspecialchars($tingkat) ?>"><?= htmlspecialchars($tingkat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="jurusanFilter" class="form-label">Filter Jurusan:</label>
                <select id="jurusanFilter" class="form-select form-select-sm">
                    <option value="">Semua Jurusan</option>
                    <?php foreach ($jurusan_list as $jurusan): ?>
                        <option value="<?= htmlspecialchars($jurusan) ?>"><?= htmlspecialchars($jurusan) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="keteranganFilter" class="form-label">Filter Keterangan:</label>
                <select id="keteranganFilter" class="form-select form-select-sm">
                    <option value="">Semua Keterangan</option>
                    <option value="Tepat Waktu">Tepat Waktu</option>
                    <option value="Terlambat">Terlambat</option>
                    <option value="Sudah Pulang">Sudah Pulang</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="periodeFilter" class="form-label">Filter Periode:</label>
                <select id="periodeFilter" class="form-select form-select-sm">
                    <option value="">Semua Periode</option>
                    <option value="today">Hari Ini</option>
                    <option value="week">Seminggu Terakhir</option>
                    <option value="month">Sebulan Terakhir</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="searchInput" class="form-label">Cari Siswa:</label>
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Ketik NIS, Nama, Kelas, atau Keterangan...">
            </div>
            <div class="col-md-3 d-flex align-items-end mt-2">
                <button id="applyFilterBtn" class="btn btn-primary btn-sm me-2">Apply Filter</button>
                <button id="exportExcelBtn" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export to Excel</button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="absensiTable" class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Waktu</th>
                        <th>Keterangan</th>
                        <th>Lokasi</th>
                        <th style="width: 100px;">Jarak (m)</th>
                        <th style="width: 60px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($absensi)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada siswa yang melakukan absensi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($absensi as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nis']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['kelas']) ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($row['waktu'])) ?></td>
                                <td class="<?= $row['keterangan'] === 'Terlambat' ? 'text-danger fw-bold' : ($row['keterangan'] === 'Sudah Pulang' ? 'text-warning' : 'text-success') ?>">
                                    <?= htmlspecialchars($row['keterangan']) ?>
                                </td>
                                <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                <td><?= htmlspecialchars($row['jarak']) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/hapus_absensi/' . $row['id']) ?>" class="btn btn-sm btn-danger btn-delete-absen" title="Hapus Data"><i class="fas fa-trash"></i></a>
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
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#absensiTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [
                [3, 'desc']
            ],
            dom: 'rtip',
            columnDefs: [{
                targets: 3, // Waktu column
                render: function(data, type, row) {
                    if (type === 'sort') {
                        // Convert to timestamp for sorting
                        let parts = data.split(' ');
                        let dateParts = parts[0].split('-');
                        let dateStr = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]} ${parts[1]}`;
                        return new Date(dateStr).getTime();
                    }
                    // Return original data for display and filtering
                    return data;
                }
            }]
        });

        // Function to parse date from table format (d-m-Y H:i:s) to JavaScript Date
        function parseTableDate(dateStr) {
            let parts = dateStr.split(' ');
            if (parts.length !== 2) {
                console.error('Invalid date format:', dateStr);
                return null;
            }
            let dateParts = parts[0].split('-');
            if (dateParts.length !== 3) {
                console.error('Invalid date parts:', dateStr);
                return null;
            }
            let dateStrFormatted = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]} ${parts[1]}`;
            let parsedDate = new Date(dateStrFormatted);
            if (isNaN(parsedDate.getTime())) {
                console.error('Invalid date parsed:', dateStr);
                return null;
            }
            return parsedDate;
        }

        // Function to apply all filters
        function applyFilter() {
            let kelas = $('#kelasFilter').val();
            let jurusan = $('#jurusanFilter').val();
            let keterangan = $('#keteranganFilter').val();
            let periode = $('#periodeFilter').val();
            let searchTerm = '';

            // Build class search term
            if (kelas && jurusan) {
                searchTerm = kelas + '-' + jurusan;
            } else if (kelas) {
                searchTerm = kelas + '-';
            } else if (jurusan) {
                searchTerm = '-' + jurusan;
            }

            // Apply class and keterangan filters
            table.column(2).search(searchTerm, true, false);
            table.column(4).search(keterangan ? '^' + keterangan + '$' : '', true, false);

            // Clear any existing custom filters
            $.fn.dataTable.ext.search = [];

            // Apply time period filter
            if (periode) {
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        let dateStr = data[3]; // Waktu column
                        let date = parseTableDate(dateStr);
                        if (!date) {
                            console.warn('Skipping row due to invalid date:', dateStr);
                            return false;
                        }

                        let now = new Date();
                        // Set time to 00:00:00 for consistent date comparison
                        let today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                        let weekAgo = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7);
                        let monthAgo = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate());

                        console.log(`Filtering: ${dateStr} (parsed: ${date.toISOString()}) | Periode: ${periode}`);
                        console.log(`Today: ${today.toISOString()}, Week Ago: ${weekAgo.toISOString()}, Month Ago: ${monthAgo.toISOString()}`);

                        if (periode === 'today' && date >= today) {
                            return true;
                        } else if (periode === 'week' && date >= weekAgo) {
                            return true;
                        } else if (periode === 'month' && date >= monthAgo) {
                            return true;
                        }
                        return false;
                    }
                );
            }

            // Redraw table to apply filters
            table.draw();
        }

        // Apply filter on button click
        $('#applyFilterBtn').on('click', function() {
            applyFilter();
        });

        // Real-time search
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Export to Excel
        $('#exportExcelBtn').on('click', function() {
            let data = [];
            let headers = ['NIS', 'Nama', 'Kelas', 'Waktu', 'Keterangan', 'Lokasi', 'Jarak (m)'];
            data.push(headers);

            table.rows({
                search: 'applied'
            }).data().each(function(row) {
                data.push([
                    row[0],
                    row[1],
                    row[2],
                    row[3],
                    row[4].replace(/<[^>]+>/g, ''),
                    row[5],
                    row[6]
                ]);
            });

            let ws = XLSX.utils.aoa_to_sheet(data);
            let wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Absensi');
            XLSX.writeFile(wb, 'Data_Absensi_Siswa.xlsx');
        });

        // Event listener for delete button
        $('#absensiTable').on('click', '.btn-delete-absen', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const namaSiswa = $(this).closest('tr').find('td:nth-child(2)').text();
            const waktuAbsen = $(this).closest('tr').find('td:nth-child(4)').text();

            Swal.fire({
                title: 'Anda yakin?',
                html: `Data absensi untuk <b>${namaSiswa}</b> pada waktu <b>${waktuAbsen}</b> akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
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