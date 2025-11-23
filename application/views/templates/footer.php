<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    var base_url = '<?= base_url() ?>';
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded. Please ensure jQuery is included.');
    } else {
        jQuery(document).ready(function() {
            console.log('jQuery loaded:', typeof jQuery !== 'undefined');
            <?php if ($this->session->flashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '<?= $this->session->flashdata('success') ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '<?= $this->session->flashdata('error') ?>',
                    showConfirmButton: false,
                    timer: 1500
                });
            <?php endif; ?>

            function toggleSidebar(e) {
                e.preventDefault();
                jQuery("#wrapper").toggleClass("toggled");
            }
            jQuery("#sidebarToggle").click(toggleSidebar);
            jQuery("#sidebarClose, #sidebar-overlay").click(function(e) {
                e.preventDefault();
                jQuery("#wrapper").removeClass("toggled");
            });
        });
    }
</script>
<script src="<?= base_url('assets/js/ujian.js') ?>"></script>
</body>

</html>