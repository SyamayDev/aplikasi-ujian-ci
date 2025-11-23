<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Daftar Kelas</h5>
                    <a href="<?= base_url('admin/tambah_kelas') ?>" class="btn btn-success">Tambah Kelas</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($list_kelas)): ?>
                            <?php $no = 1; foreach($list_kelas as $kelas): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $kelas['kode_kelas']; ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/edit_kelas/'.$kelas['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?= base_url('admin/hapus_kelas/'.$kelas['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kelas ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">Belum ada data kelas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
