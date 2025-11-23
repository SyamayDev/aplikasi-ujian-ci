<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Daftar Mata Pelajaran</h5>
                    <a href="<?= base_url('admin/tambah_mapel') ?>" class="btn btn-success">Tambah Mapel</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($list_mapel)): ?>
                            <?php $no = 1; foreach($list_mapel as $mapel): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $mapel['nama_mapel']; ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/edit_mapel/'.$mapel['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="<?= base_url('admin/hapus_mapel/'.$mapel['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus mata pelajaran ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">Belum ada data mata pelajaran.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
