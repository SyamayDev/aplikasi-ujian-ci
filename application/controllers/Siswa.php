<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Siswa_model');
    }

    public function login()
    {
        if ($this->session->userdata('nis')) {
            redirect('siswa/beranda');
        }
        $this->load->helper('form');
        $data['error'] = $this->session->flashdata('error');
        $data['kelas_list'] = $this->Siswa_model->get_all_kelas_jurusan();
        $this->load->view('templates/header');
        $this->load->view('siswa/login', $data);
        $this->load->view('templates/footer');
    }

    public function do_login()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('nis', 'NIS', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('kelas', 'Kelas Saat Ini', 'required');
        $this->form_validation->set_message('required', '{field} wajib diisi.');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal (ada field kosong), tampilkan kembali form login
            // Data input akan terisi otomatis oleh fungsi set_value() di view
            $data['kelas_list'] = $this->Siswa_model->get_all_kelas_jurusan();
            $this->load->view('templates/header');
            $this->load->view('siswa/login', $data);
            $this->load->view('templates/footer');
        } else {
            // Validasi form sukses, sekarang cek kredensial
            $nis = strtoupper(trim($this->input->post('nis')));
            $nama = trim($this->input->post('nama'));
            $kelas = $this->input->post('kelas');

            $user = $this->Siswa_model->get_siswa_by_nis($nis);

            if ($user && strtolower($user['nama']) === strtolower($nama)) {
                // Kredensial benar, login berhasil
                $this->session->set_userdata(['nis' => $user['nis'], 'nama' => $user['nama'], 'kelas' => $kelas]);
                redirect('siswa/beranda');
            } else {
                // Kredensial salah, tampilkan kembali form dengan pesan error
                $data['error'] = 'NIS atau Nama tidak cocok. Silakan coba lagi.';
                $data['kelas_list'] = $this->Siswa_model->get_all_kelas_jurusan();
                $this->load->view('templates/header');
                $this->load->view('siswa/login', $data);
                $this->load->view('templates/footer');
            }
        }
    }

    public function beranda()
    {
        if (!$this->session->userdata('nis')) {
            redirect('siswa/login');
        }
        $this->load->view('templates/header');
        $this->load->view('siswa/beranda');
        $this->load->view('templates/footer');
    }

    public function quran()
    {
        if (!$this->session->userdata('nis')) {
            redirect('siswa/login');
        }
        $this->load->view('templates/header', ['title' => 'Al-Qur\'an Digital']);
        $this->load->view('siswa/quran');
    }

    public function jadwal_sholat()
    {
        if (!$this->session->userdata('nis')) {
            redirect('siswa/login');
        }
        $this->load->view('templates/header', ['title' => 'Jadwal Sholat']);
        $this->load->view('siswa/jadwal_sholat');
    }

    public function kalkulator()
    {
        if (!$this->session->userdata('nis')) {
            redirect('siswa/login');
        }
        $this->load->view('templates/header', ['title' => 'Kalkulator']);
        $this->load->view('siswa/kalkulator');
    }

    public function submit_absen()
    {
        header('Content-Type: application/json');
        if (!$this->session->userdata('nis')) {
            echo json_encode(['status' => 'error', 'message' => 'Belum login']);
            exit;
        }

        $qr = trim($this->input->post('qr'));
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $device_id = $this->input->post('device_id');

        if (empty($qr)) {
            echo json_encode(['status' => 'error', 'message' => 'QR code tidak terdeteksi.']);
            exit;
        }

        // --- VALIDASI PERANGKAT UNIK ---
        if (empty($device_id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID Perangkat tidak terdeteksi. Mohon muat ulang halaman dan izinkan cookie/local storage.']);
            exit;
        }

        $this->load->driver('cache', ['adapter' => 'file']);
        $today = date('Y-m-d');
        $nis = $this->session->userdata('nis');

        $device_key = 'device_lock_' . $device_id . '_' . $today;
        $student_key = 'student_lock_' . $nis . '_' . $today;

        $nis_on_device = $this->cache->get($device_key);
        $device_for_student = $this->cache->get($student_key);

        // Cek apakah perangkat ini sudah dipakai siswa lain
        if ($nis_on_device && $nis_on_device !== $nis) {
            echo json_encode(['status' => 'error', 'message' => 'Peringatan! Perangkat ini terdeteksi sudah digunakan untuk absensi untuk hari ini.']);
            exit;
        }
        // Cek apakah siswa ini sudah absen di perangkat lain
        if ($device_for_student && $device_for_student !== $device_id) {
            echo json_encode(['status' => 'error', 'message' => 'Anda sudah melakukan absensi di perangkat lain hari ini.']);
            exit;
        }
        // --- AKHIR VALIDASI PERANGKAT ---

        // Ambil kode QR yang benar dari database
        $correct_qr = $this->Siswa_model->get_setting('qr_code_string');

        if (empty($correct_qr)) {
            echo json_encode(['status' => 'error', 'message' => 'Pengaturan QR code di sistem belum ada. Silakan atur di panel admin.']);
            exit;
        }

        if ($qr !== $correct_qr) {
            echo json_encode(['status' => 'error', 'message' => 'QR code tidak valid atau sudah usang.']);
            exit;
        }

        if (empty($latitude) || empty($longitude)) {
            echo json_encode(['status' => 'error', 'message' => 'Lokasi tidak tersedia.']);
            exit;
        }

        $lokasi = $this->Siswa_model->get_lokasi();
        if (!$lokasi) {
            echo json_encode(['status' => 'error', 'message' => 'Lokasi sekolah belum diatur.']);
            exit;
        }

        $jarak = $this->hitung_jarak($latitude, $longitude, $lokasi['latitude'], $lokasi['longitude']);
        if ($jarak > $lokasi['radius']) {
            echo json_encode(['status' => 'error', 'message' => 'Diluar jangkauan (Jarak: ' . round($jarak) . ' meter).']);
            exit;
        }

        date_default_timezone_set('Asia/Jakarta');
        $now = new DateTime();
        $waktu = $now->format('Y-m-d H:i:s');

        // Ambil pengaturan waktu dinamis dari database
        $nama_hari_ini = strtolower($now->format('l'));
        $nama_hari_indonesia = ['monday' => 'senin', 'tuesday' => 'selasa', 'wednesday' => 'rabu', 'thursday' => 'kamis', 'friday' => 'jumat', 'saturday' => 'sabtu', 'sunday' => 'minggu'];
        $key_hari_ini = $nama_hari_indonesia[$nama_hari_ini] ?? 'senin';

        $jam_masuk_str = $this->Siswa_model->get_setting('jam_masuk_' . $key_hari_ini) ?: '07:15';
        $jam_pulang_str = $this->Siswa_model->get_setting('jam_pulang_' . $key_hari_ini) ?: '16:00';

        // Definisikan jam batas absensi
        $jam_mulai_absen_masuk_str = '04:00';
        $jam_akhir_absen_masuk_str = '14:00';

        // Buat objek DateTime untuk perbandingan
        $jam_mulai_absen_masuk = new DateTime($jam_mulai_absen_masuk_str);
        $jam_akhir_absen_masuk = new DateTime($jam_akhir_absen_masuk_str);
        $jam_masuk = new DateTime($jam_masuk_str);
        $jam_pulang = new DateTime($jam_pulang_str);

        // --- VALIDASI ABSENSI GANDA ---
        $riwayat_absen_hari_ini = $this->Siswa_model->cek_absensi_hari_ini($this->session->userdata('nis'));
        $sudah_absen_masuk = false;
        $sudah_absen_pulang = false;
        foreach ($riwayat_absen_hari_ini as $riwayat) {
            if (in_array($riwayat['keterangan'], ['Tepat Waktu', 'Terlambat'])) {
                $sudah_absen_masuk = true;
            }
            if ($riwayat['keterangan'] === 'Sudah Pulang') {
                $sudah_absen_pulang = true;
            }
        }

        // --- LOGIKA BARU UNTUK MENENTUKAN JENIS DAN WAKTU ABSENSI ---
        if ($sudah_absen_pulang) {
            echo json_encode(['status' => 'error', 'message' => 'Anda sudah melakukan absensi pulang hari ini.']);
            exit;
        }

        $keterangan = '';
        if ($now >= $jam_pulang) {
            if (!$sudah_absen_masuk) {
                echo json_encode(['status' => 'error', 'message' => 'Anda belum melakukan absensi masuk hari ini, tidak bisa absen pulang.']);
                exit;
            }
            $keterangan = 'Sudah Pulang';
        } elseif ($now >= $jam_mulai_absen_masuk && $now <= $jam_akhir_absen_masuk) {
            if ($sudah_absen_masuk) {
                echo json_encode(['status' => 'error', 'message' => 'Anda sudah melakukan absensi masuk hari ini.']);
                exit;
            }
            $keterangan = ($now <= $jam_masuk) ? 'Tepat Waktu' : 'Terlambat';
        } else {
            if ($now < $jam_mulai_absen_masuk) {
                echo json_encode(['status' => 'error', 'message' => 'Absensi masuk belum dibuka. Absensi dibuka mulai pukul ' . $jam_mulai_absen_masuk_str . '.']);
            } elseif ($now > $jam_akhir_absen_masuk && $now < $jam_pulang) {
                echo json_encode(['status' => 'error', 'message' => 'Waktu absensi masuk sudah berakhir. Silakan absen pulang mulai pukul ' . $jam_pulang_str . '.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Saat ini di luar jam absensi yang ditentukan.']);
            }
            exit;
        }

        $data = [
            'nis' => $this->session->userdata('nis'),
            'nama' => $this->session->userdata('nama'),
            'kelas' => $this->session->userdata('kelas'),
            'waktu' => $waktu,
            'keterangan' => $keterangan,
            'lokasi' => $lokasi['alamat'],
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        if ($this->Siswa_model->insert_absensi($data)) {
            // Simpan lock ke cache setelah absensi berhasil
            // TTL (Time To Live) diatur agar cache hilang pada tengah malam
            $seconds_until_midnight = strtotime('tomorrow') - time();
            $this->cache->save($device_key, $nis, $seconds_until_midnight);
            $this->cache->save($student_key, $device_id, $seconds_until_midnight);

            // Ambil data siswa untuk mendapatkan nomor HP
            $siswa = $this->Siswa_model->get_siswa_by_nis($this->session->userdata('nis'));
            $pesan_tambahan = '';

            if ($siswa && !empty($siswa['hp'])) {
                // Kirim notifikasi WhatsApp
                $kirim_sukses = $this->_kirim_notifikasi_whatsapp($siswa, $data);
                $pesan_tambahan = $kirim_sukses ? 'Notifikasi akan dikirimkan ke orang tua.' : 'Gagal mengirim notifikasi, cek log server.';
            } else {
                log_message('debug', 'Notifikasi tidak dikirim untuk NIS ' . $data['nis'] . ' karena tidak ada No. HP.');
                $pesan_tambahan = 'Notifikasi tidak dikirim (No. HP orang tua tidak ada).';
            }
            echo json_encode(['status' => 'success', 'message' => 'Absensi berhasil! (' . $keterangan . '). ' . $pesan_tambahan]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan.']);
        }
    }

    private function _kirim_notifikasi_whatsapp($siswa, $data_absensi)
    {
        $token = trim($this->Siswa_model->get_setting('fonnte_token'));
        $template = trim($this->Siswa_model->get_setting('fonnte_message_template'));

        if (empty($token)) {
            log_message('error', 'Gagal kirim WA: fonnte_token belum diatur di database settings.');
            return false;
        }
        if (empty($template)) {
            log_message('error', 'Gagal kirim WA: fonnte_message_template belum diatur di database settings.');
            return false;
        }
        if (empty($siswa['hp'])) {
            // Ini seharusnya sudah dicek sebelumnya, tapi sebagai pengaman tambahan
            log_message('debug', 'Gagal kirim WA: No. HP kosong untuk NIS ' . $siswa['nis']);
            return false;
        }

        // --- Normalisasi Nomor HP ---
        // 1. Hapus semua karakter non-digit (spasi, tanda hubung, plus, dll.)
        $nomor_bersih = preg_replace('/[^0-9]/', '', $siswa['hp']);

        // 2. Cek jika nomor diawali '0' (format lokal Indonesia), ganti dengan '62'
        if (substr($nomor_bersih, 0, 1) === '0') {
            $nomor_target = '62' . substr($nomor_bersih, 1);
        } else {
            // Jika sudah pakai kode negara (62, 60, dll.), gunakan apa adanya.
            $nomor_target = $nomor_bersih;
        }

        $pesan = str_replace(
            ['{nama_siswa}', '{nis}', '{kelas}', '{waktu}', '{keterangan}'],
            [$siswa['nama'], $siswa['nis'], $data_absensi['kelas'], $data_absensi['waktu'], $data_absensi['keterangan']],
            $template
        );

        log_message('debug', 'Mencoba kirim WA ke: ' . $nomor_target . ' (dari input: ' . $siswa['hp'] . ') | Pesan: ' . $pesan);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query(['target' => $nomor_target, 'message' => $pesan]),
            CURLOPT_HTTPHEADER => array('Authorization: ' . $token),
            // Tambahan untuk mengatasi error SSL di lingkungan lokal (seperti Laragon/XAMPP)
            // Opsi ini mungkin tidak diperlukan di server hosting production.
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            log_message('error', 'Fonnte cURL Error: ' . curl_error($curl));
        }

        curl_close($curl);

        // Log response dari Fonnte
        log_message('debug', 'Fonnte API Response: ' . $response);

        // Cek jika response adalah JSON dan ada status 'success'
        $json_response = json_decode($response, true);
        if (isset($json_response['status']) && $json_response['status'] == true) { // Gunakan '==' untuk lebih fleksibel
            return true;
        }
        return false;
    }

    private function hitung_jarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('siswa/login');
    }
}
