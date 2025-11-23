<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Daftar Guru</h5>
                    <a href="<?= base_url('admin/tambah_guru') ?>" class="btn btn-success">Tambah Guru</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>NIP</th>
                            <th>Mapel yang Diampu</th>
                            <th>Kelas yang Diampu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($list_guru)): ?>
                            <?php $no = 1; foreach($list_guru as $guru): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $guru['nama']; ?></td>
                                    <td><?= $guru['username']; ?></td>
                                    <td><?= $guru['nip']; ?></td>
                                    <td><?= $guru['mapel_list']; // Will be improved later ?></td>
                                    <td><?= $guru['kelas_list']; // Will be improved later ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/edit_guru/'.$guru['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?= base_url('admin/hapus_guru/'.$guru['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus guru ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data guru.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
