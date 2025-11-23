<!-- Sidebar Offcanvas -->
<div class="offcanvas offcanvas-start w-75" tabindex="-1" id="islamiSidebar" aria-labelledby="islamiSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-success" id="islamiSidebarLabel"><i class="fas fa-puzzle-piece me-2"></i>Fitur Tambahan</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link fs-5" href="<?= base_url('siswa/quran') ?>"><i class="fas fa-book-quran me-2"></i> Al-Qur'an Digital</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-5" href="<?= base_url('siswa/jadwal_sholat') ?>"><i class="fas fa-calendar-alt me-2"></i> Jadwal Sholat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-5" href="<?= base_url('siswa/kalkulator') ?>"><i class="fas fa-calculator me-2"></i> Kalkulator</a>
            </li>
        </ul>
    </div>
</div>

<div class="container-fluid min-vh-100 d-flex flex-column p-0">
    <!-- Header Profil dengan Tombol Sidebar -->
    <header class="bg-white shadow-sm d-flex justify-content-between align-items-center px-3 py-2 sticky-top">
        <button class="btn btn-outline-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#islamiSidebar" aria-controls="islamiSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="text-center">
            <div class="h5 mb-0 fw-bold text-success">📚 Absensi QR Siswa</div>
            <div class="text-end">
                <small class="text-muted"><?= htmlspecialchars($this->session->userdata('nama')) ?> (<?= $this->session->userdata('kelas') ?>)</small>
            </div>
        </div>
        <a href="<?= base_url('siswa/logout') ?>" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt"></i></a>
    </header>

    <!-- Main Scanner -->
    <main class="flex-grow-1 d-flex justify-content-center align-items-center px-3 py-3" role="main">
        <div class="bg-white shadow-lg rounded-3 p-4 w-100 text-center" style="max-width: 500px;">
            <h1 class="h4 fw-bold text-success mb-3">📸 Scanner QR Absensi</h1>
            <div id="clock" class="text-muted fw-semibold mb-3"></div>

            <p id="status" class="text-muted d-flex align-items-center justify-content-center gap-2 small mb-3">
                <i class="fas fa-camera"></i> Menunggu scan QR code...
            </p>

            <div id="reader" class="w-100 border-4 border-dashed rounded-3 mx-auto" style="border-color: #a7e0a7; max-width: 300px; aspect-ratio: 1/1;"></div>

            <div class="mt-3 d-flex flex-column gap-2">
                <button id="submit-btn" class="btn btn-primary fw-semibold"><i class="fas fa-paper-plane me-2"></i> Submit Scan</button>
            </div>
        </div>
    </main>
</div>

<!-- Tambahkan library yang dibutuhkan -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
// Pastikan skrip dijalankan setelah semua elemen HTML dimuat

// 🆔 Fungsi untuk mendapatkan atau membuat ID unik untuk perangkat
function getDeviceId() {
    let deviceId = localStorage.getItem('deviceId');
    if (!deviceId) {
        // Buat ID unik sederhana menggunakan crypto API jika tersedia untuk keacakan yang lebih baik
        const random_array = new Uint32Array(1);
        const crypto = window.crypto || window.msCrypto;
        const random_val = crypto ? crypto.getRandomValues(random_array)[0] : Math.random() * 999999;
        deviceId = 'device-' + Date.now() + '-' + random_val;
        localStorage.setItem('deviceId', deviceId);
    }
    return deviceId;
}
document.addEventListener("DOMContentLoaded", function() {
    let scannedCode = null;
    let currentCamera = 0;
    let cameras = [];
    const html5QrCode = new Html5Qrcode("reader");

    // 🎥 Fungsi untuk mendapatkan list kamera dan memulai scanner (INI YANG MEMICU IZIN KAMERA)
    function initializeScanner() {
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                cameras = devices;
                // Coba prioritaskan kamera belakang (environment)
                const backCam = devices.find(c => c.label.toLowerCase().includes("back")) || devices[0];
                currentCamera = devices.indexOf(backCam);
                html5QrCode.start(
                    backCam.id,
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText, decodedResult) => { // <-- INI BAGIAN YANG DIPERBAIKI
                        let finalCode = decodedText;
                        try {
                            // Coba parse sebagai URL dan ambil parameter 'code'
                            const url = new URL(decodedText);
                            if (url.searchParams.has('code')) {
                                finalCode = url.searchParams.get('code');
                            }
                        } catch (e) {
                            // Jika bukan URL, biarkan finalCode apa adanya.
                        }
                        scannedCode = finalCode;
                        document.getElementById("status").innerHTML = `<i class="fas fa-check-circle text-success"></i> Scan berhasil: <strong>${scannedCode}</strong>`;
                        html5QrCode.stop().catch(err => console.log("Gagal menghentikan scanner.", err));
                    },
                    (errorMessage) => {
                        // error parsing, abaikan saja
                    })
                    .then(() => {
                        document.getElementById("status").innerHTML = `<i class="fas fa-camera"></i> Kamera aktif. Arahkan ke QR.`;
                    })
                    .catch((err) => {
                        document.getElementById("status").innerHTML = `<i class="fas fa-exclamation-triangle text-danger"></i> Gagal memulai kamera. Pastikan Anda memberikan izin.`;
                    });
            } else {
                document.getElementById("status").innerHTML = `<i class="fas fa-exclamation-triangle text-danger"></i> Kamera tidak ditemukan.`;
            }
        }).catch(err => {
            document.getElementById("status").innerHTML = `<i class="fas fa-exclamation-triangle text-danger"></i> Gagal mendeteksi kamera: ${err}`;
        });
    }

    // 🔗 Cek kode dari URL saat halaman dimuat
    const urlParams = new URLSearchParams(window.location.search);
    const codeFromUrl = urlParams.get('code');
    
    // Panggil scanner tanpa syarat agar kamera selalu mencoba untuk terbuka.
    initializeScanner();
    
    if (codeFromUrl) {
        scannedCode = codeFromUrl;
        document.getElementById("status").innerHTML = `<i class="fas fa-link text-success"></i> Kode <strong>${codeFromUrl}</strong> terdeteksi dari URL. Arahkan kamera untuk scan ulang atau langsung submit.`;
    }

    // 🔄 Ganti Kamera
    const switchCamBtn = document.getElementById("switch-camera");
    if (switchCamBtn) {
        switchCamBtn.addEventListener("click", () => {
            if (cameras.length <= 1) {
                Swal.fire('Info', 'Kamera lain tidak tersedia.', 'info');
                return;
            }
            currentCamera = (currentCamera + 1) % cameras.length;
            html5QrCode.stop().then(() => {
                initializeScanner(); // Panggil ulang fungsi utama untuk memulai kamera yang baru
            }).catch(err => {
                console.error("Gagal stop kamera untuk ganti:", err);
                initializeScanner(); // Coba paksa start
            });
        });
    }

    // Jam Digital
    setInterval(() => {
        document.getElementById("clock").textContent = new Date().toLocaleTimeString('id-ID');
    }, 1000);

    // Submit Data
    document.getElementById("submit-btn").addEventListener("click", () => {
        if (!scannedCode) {
            Swal.fire('Error', 'QR belum discan atau kode tidak valid.', 'error');
            return;
        }
        if (!navigator.geolocation) {
            Swal.fire('Error', 'Browser tidak mendukung geolokasi.', 'error');
            return;
        }

        Swal.fire({
            title: 'Mengirim data...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        const deviceId = getDeviceId(); // Dapatkan ID perangkat

        // INI YANG MEMICU IZIN LOKASI
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                $.post('<?= base_url('siswa/submit_absen') ?>', {
                    qr: scannedCode,
                    device_id: deviceId, // Kirim ID perangkat
                    latitude: pos.coords.latitude,
                    longitude: pos.coords.longitude
                }, function(data) {
                    Swal.fire({
                        icon: data.status,
                        title: data.status === 'success' ? 'Berhasil' : 'Gagal',
                        text: data.message
                    }).then(() => {
                        if (data.status === 'success') location.reload();
                    });
                }, 'json').fail((xhr, status, error) => {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    Swal.fire('Error Server', 'Gagal terhubung ke server. Cek console (F12) untuk detail.', 'error');
                });
            },
            (err) => {
                Swal.fire('Error Lokasi', 'Gagal mengakses lokasi: ' + err.message, 'error');
            },
            { enableHighAccuracy: true, timeout: 20000, maximumAge: 0 }
        );
    });
});
</script>
