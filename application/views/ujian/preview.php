<div class="container py-3">
    <div class="card">
        <div class="card-body">
            <h4 class="text-success"><?= htmlspecialchars($room['nama_room']) ?> (Preview)</h4>
            <p>Waktu Mulai: <?= $room['mulai_datetime'] ?> — Selesai: <?= $room['selesai_datetime'] ?></p>
            <p>Sisa Waktu: <span id="time-remaining"></span></p>

            <div id="soal-wrapper">
                <?php foreach ($soal as $i => $s): ?>
                    <div class="soal-container <?= $i == 0 ? 'active' : '' ?> p-3" data-soal-id="<?= $s['id'] ?>" style="display: <?= $i == 0 ? 'block' : 'none' ?>;">
                        <h5>Soal <?= $i + 1 ?></h5>
                        <?php if (!empty($s['gambar'])): ?>
                            <img src="<?= base_url('assets/uploads/paket/images/' . $s['gambar']) ?>" class="img-fluid mb-2" alt="gambar soal">
                        <?php endif; ?>
                        <p><?= nl2br(htmlspecialchars($s['pertanyaan'])) ?></p>
                        <div>
                            <?php foreach (['a', 'b', 'c', 'd', 'e'] as $opt): if (empty($s[$opt])) continue; ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled name="jawaban_<?= $s['id'] ?>" id="jaw_<?= $s['id'] ?>_<?= $opt ?>" value="<?= strtoupper($opt) ?>">
                                    <label class="form-check-label" for="jaw_<?= $s['id'] ?>_<?= $opt ?>"><?= nl2br(htmlspecialchars($s[$opt])) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-secondary btn-prev" <?= $i == 0 ? 'disabled' : '' ?>>Sebelumnya</button>
                            <button class="btn btn-primary btn-next" <?= $i == count($soal) - 1 ? 'disabled' : '' ?>>Berikutnya</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        // timer
        var end = new Date('<?= $room['selesai_datetime'] ?>').getTime();

        function updateTimer() {
            var now = new Date().getTime();
            var diff = end - now;
            if (diff < 0) {
                $('#time-remaining').text('Selesai');
                return;
            }
            var hours = Math.floor(diff / (1000 * 60 * 60));
            var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((diff % (1000 * 60)) / 1000);
            $('#time-remaining').text(hours + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2));
        }
        setInterval(updateTimer, 1000);
        updateTimer();

        $('.btn-next').click(function() {
            var cur = $(this).closest('.soal-container');
            var next = cur.nextAll('.soal-container').first();
            if (next.length) {
                cur.hide().removeClass('active');
                next.show().addClass('active');
            }
        });
        $('.btn-prev').click(function() {
            var cur = $(this).closest('.soal-container');
            var prev = cur.prevAll('.soal-container').first();
            if (prev.length) {
                cur.hide().removeClass('active');
                prev.show().addClass('active');
            }
        });
    });
</script>