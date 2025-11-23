<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Daftar Siswa</h5>
                    <a href="<?= base_url('admin/tambah_siswa') ?>" class="btn btn-success">Tambah Siswa</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Password (Plain)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($list_siswa)): ?>
                            <?php $no = 1; foreach($list_siswa as $siswa): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $siswa['nama']; ?></td>
                                    <td><?= $siswa['nisn']; ?></td>
                                    <td><?= $siswa['kode_kelas']; ?></td>
                                    <td>
                                        <!-- Insecure as per request, with a note -->
                                        <code><?= $siswa['password_plain']; ?></code> 
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/edit_siswa/'.$siswa['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?= base_url('admin/hapus_siswa/'.$siswa['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus siswa ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data siswa.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
