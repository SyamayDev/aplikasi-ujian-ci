<h1 class="mt-4">Kelola Admin</h1>
<p>Berikut adalah daftar admin yang dapat mengakses panel ini.</p>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-user-shield me-1"></i>Daftar Admin</span>
        <a href="<?= base_url('admin/tambah_admin') ?>" class="btn btn-sm btn-success">
            <i class="fas fa-plus me-1"></i> Tambah Admin
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="adminTable" class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Username</th>
                        <th>Tanggal Dibuat</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list_admin)): ?>
                        <tr>
                            <td colspan="3" class="text-center">Belum ada admin lain.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($list_admin as $admin): ?>
                            <tr>
                                <td><?= htmlspecialchars($admin['username']) ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($admin['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/edit_admin/' . $admin['id']) ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <?php if ($admin['id'] != $this->session->userdata('admin_id')): ?>
                                        <a href="<?= base_url('admin/hapus_admin/' . $admin['id']) ?>" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#adminTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[1, 'desc']]
    });

    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        const username = $(this).closest('tr').find('td:first').text();
        Swal.fire({
            title: 'Anda yakin?',
            html: `Akun admin "<b>${username}</b>" akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
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
