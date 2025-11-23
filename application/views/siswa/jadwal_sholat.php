<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
  <title>Jadwal Sholat & Imsakiyah</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/fitur_islami.css') ?>">
</head>
<body>
  <div id="wrapper">
    <div id="content">
      <header class="main_haeder multi_item bg-success text-white shadow">
        <div class="em_side_right">
          <a class="btn btn__back rounded-circle bg-light text-success" href="<?= base_url('siswa/beranda') ?>">
            <i class="fas fa-arrow-left"></i>
          </a>
        </div>
        <div class="title_page">
          <span class="page_name">Jadwal Sholat</span>
        </div>
        <div class="em_side_right"></div>
      </header>

      <main class="container mt-4 animate__animated animate__fadeIn">
        <div class="view-mode text-center mb-3">
          <button class="view-option active" data-view="daily">HARI INI</button>
          <button class="view-option" data-view="monthly">BULAN INI</button>
        </div>
        <div class="mb-3">
            <label for="citySelect" class="form-label">Pilih Kota:</label>
            <select id="citySelect" class="form-select"></select>
        </div>
        <p id="selectedInfo" class="text-center fw-bold mt-3"></p>
        <div id="loadingIndicator" class="text-center" style="display: none;">
          <div class="spinner"></div>
        </div>
        <div class="table-responsive" id="monthlyTable" style="display: none;">
          <table class="table table-bordered text-center">
            <thead class="sticky-header">
              <tr>
                <th>Tanggal</th><th>Imsak</th><th>Subuh</th><th>Terbit</th><th>Dhuha</th><th>Dzuhur</th><th>Ashar</th><th>Maghrib</th><th>Isya</th>
              </tr>
            </thead>
            <tbody id="scheduleContainer"></tbody>
          </table>
        </div>
        <div class="daily-schedule" id="dailySchedule" style="display: block;">
          <!-- Konten jadwal harian akan dirender di sini -->
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    const base_url = '<?= base_url() ?>';
  </script>
  <script src="<?= base_url('assets/js/jadwal_sholat.js') ?>"></script>
</body>
</html>