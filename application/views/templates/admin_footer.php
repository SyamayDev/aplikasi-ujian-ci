    </div>
    </div>
    </div>

    <!-- Modal container for AJAX-loaded modals -->
    <div id="modalContainer"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('assets/js/admin-modals.js') ?>"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
            });

            // keep dropdowns and offcanvas working smoothly
            var offcanvasElList = [].slice.call(document.querySelectorAll('.offcanvas'))
            var offcanvasList = offcanvasElList.map(function(offcanvasEl) {
                return new bootstrap.Offcanvas(offcanvasEl)
            });

            // Approve / Reject confirmation for paket (use POST with CSRF)
            function getCsrf() {
                var name = $('meta[name="csrf-name"]').attr('content');
                var token = $('meta[name="csrf-token"]').attr('content');
                var obj = {};
                if (name && token) obj[name] = token;
                return obj;
            }

            $(document).on('click', '.btn-approve', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Approve paket?',
                    text: 'Paket akan di-convert menjadi soal dan disimpan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Approve',
                    reverseButtons: true
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    var data = getCsrf();
                    $.post(href, data, function(resp) {
                        if (resp && resp.status == 'ok') {
                            Swal.fire('Berhasil', 'Paket disetujui', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', resp && resp.message ? resp.message : 'Terjadi kesalahan', 'error');
                        }
                    }, 'json').fail(function() {
                        Swal.fire('Gagal', 'Tidak dapat menghubungi server', 'error');
                    });
                });
            });

            $(document).on('click', '.btn-reject', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Tolak paket?',
                    text: 'Paket akan ditandai sebagai ditolak.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak',
                    reverseButtons: true
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    var data = getCsrf();
                    $.post(href, data, function(resp) {
                        if (resp && resp.status == 'ok') {
                            Swal.fire('Berhasil', 'Paket ditolak', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', resp && resp.message ? resp.message : 'Terjadi kesalahan', 'error');
                        }
                    }, 'json').fail(function() {
                        Swal.fire('Gagal', 'Tidak dapat menghubungi server', 'error');
                    });
                });
            });

            // Reset hasil confirmation (use POST)
            $(document).on('click', '.btn-reset', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Reset hasil siswa?',
                    text: 'Reset akan menghapus hasil dan jawaban siswa untuk room ini.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Reset',
                    reverseButtons: true
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    var data = getCsrf();
                    $.post(href, data, function(resp) {
                        if (resp && resp.status == 'ok') {
                            Swal.fire('Berhasil', 'Reset berhasil', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', resp && resp.message ? resp.message : 'Terjadi kesalahan', 'error');
                        }
                    }, 'json').fail(function() {
                        Swal.fire('Gagal', 'Tidak dapat menghubungi server', 'error');
                    });
                });
            });
        });
    </script>

    </body>

    </html>