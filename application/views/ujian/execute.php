<div class="container py-3">
    <div class="card">
        <div class="card-body">
            <div class="ujian-header mb-3">
                <div>
                    <h4 class="text-success mb-1"><?= htmlspecialchars($room['nama_room']) ?></h4>
                    <div class="small text-muted">Durasi: <?= intval($room['durasi_menit']) ?> menit</div>
                </div>
                <div class="text-end">
                    <img src="<?= base_url('assets/img/logotritech.png') ?>" alt="logo" style="height:42px;" class="mb-2 d-block ms-auto">
                    <div class="ujian-timer" id="ujianTimer">--:--</div>
                </div>
            </div>

            <div id="soal-wrapper">
                <?php foreach ($soal as $i => $s): ?>
                    <div class="soal-container <?= $i == 0 ? 'active' : '' ?> p-3" data-soal-id="<?= $s['id'] ?>" style="display: <?= $i == 0 ? 'block' : 'none' ?>;">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Soal <?= $i + 1 ?></h5>
                                <p><?= nl2br(htmlspecialchars($s['pertanyaan'])) ?></p>
                                <div>
                                    <?php foreach (['a', 'b', 'c', 'd', 'e'] as $opt): if (empty($s[$opt])) continue; ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jawaban_<?= $s['id'] ?>" id="jaw_<?= $s['id'] ?>_<?= $opt ?>" value="<?= strtoupper($opt) ?>">
                                            <label class="form-check-label" for="jaw_<?= $s['id'] ?>_<?= $opt ?>"><?= nl2br(htmlspecialchars($s[$opt])) ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php if (!empty($s['gambar'])): ?>
                                <div style="width:260px;">
                                    <img src="<?= base_url('assets/uploads/paket/images/' . $s['gambar']) ?>" class="img-fluid soal-image rounded" alt="gambar soal">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-3 d-flex justify-content-between">
                            <div>
                                <button class="btn btn-secondary btn-prev" <?= $i == 0 ? 'disabled' : '' ?>>&laquo; Sebelumnya</button>
                                <button class="btn btn-primary btn-next" <?= $i == count($soal) - 1 ? 'disabled' : '' ?>>Berikutnya &raquo;</button>
                            </div>
                            <div>
                                <small class="text-muted">Soal <?= $i + 1 ?> dari <?= count($soal) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button id="finish-btn" class="btn btn-danger">Selesaikan Ujian</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var hasil_id = <?= intval($hasil_id) ?>;
        // Start ujian behaviors
        if (window.Ujian) window.Ujian.start(hasil_id, '<?= htmlspecialchars($hasil['started_at'] ?? date('Y-m-d H:i:s')) ?>', <?= intval($room['durasi_menit'] * 60) ?>, function(remaining) {
            // remaining seconds
            var mm = String(Math.floor(remaining / 60)).padStart(2, '0');
            var ss = String(remaining % 60).padStart(2, '0');
            $('#ujianTimer').text(mm + ':' + ss);
        });

        // Next/Prev
        $('.btn-next').click(function() {
            var cur = $(this).closest('.soal-container');
            var next = cur.nextAll('.soal-container').first();
            if (next.length) {
                cur.hide().removeClass('active');
                next.show().addClass('active');
            }
            window.Ujian && window.Ujian.saveNow();
        });
        $('.btn-prev').click(function() {
            var cur = $(this).closest('.soal-container');
            var prev = cur.prevAll('.soal-container').first();
            if (prev.length) {
                cur.hide().removeClass('active');
                prev.show().addClass('active');
            }
            window.Ujian && window.Ujian.saveNow();
        });

        $('#finish-btn').click(function() {
            Swal.fire({
                title: 'Selesaikan ujian?',
                text: 'Anda tidak dapat mengubah jawaban setelah selesai.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Selesaikan'
            }).then(function(res) {
                if (!res.isConfirmed) return;
                $.post(base_url + 'ujian/end', {
                    hasil_id: hasil_id
                }, function(resp) {
                    Swal.fire('Selesai', resp.message || 'Ujian selesai', 'success').then(function() {
                        location.href = base_url + 'siswa';
                    });
                }, 'json');
            });
        });
    });
</script>