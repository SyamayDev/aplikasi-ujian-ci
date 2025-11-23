<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');

        // Dapatkan metode yang sedang diakses
        $method = $this->router->fetch_method();

        // Jika metode bukan 'login' atau 'do_login' dan session admin_id tidak ada, redirect ke login
        if (!in_array($method, ['login', 'do_login']) && !$this->session->userdata('admin_id')) {
            $this->session->set_flashdata('error', 'Login admin diperlukan.');
            redirect('admin/login');
        }
    }

    public function index()
    {
        redirect('admin/dashboard');
    }

    public function login()
    {
        if ($this->session->userdata('admin_id')) {
            redirect('admin/dashboard');
        }
        $data['error'] = $this->session->flashdata('error');
        $this->load->view('templates/header');
        $this->load->view('admin/login', $data);
        $this->load->view('templates/footer');
    }

    public function do_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $admin = $this->Admin_model->get_admin_by_username($username);

        if ($admin && password_verify($password, $admin['password'])) {
            // Login berhasil, simpan data admin ke session
            $session_data = [
                'admin_id' => $admin['id'],
                'admin_username' => $admin['username']
            ];
            $this->session->set_userdata($session_data);
            redirect('admin/dashboard');
        } else {
            // Login gagal
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('admin/login');
        }
    }

    public function dashboard()
    {
        $data['lokasi'] = $this->Admin_model->get_lokasi();

        $hari_list = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $waktu_absensi = [];
        foreach ($hari_list as $hari) {
            $waktu_absensi[$hari]['masuk'] = $this->Admin_model->get_setting('jam_masuk_' . $hari);
            $waktu_absensi[$hari]['pulang'] = $this->Admin_model->get_setting('jam_pulang_' . $hari);
        }
        $data['waktu_absensi'] = $waktu_absensi;

        /* Baris di bawah ini tidak lagi relevan karena kita mengambil data per hari
        $data['lokasi'] = $this->Admin_model->get_lokasi();
        $data['jam_masuk'] = $this->Admin_model->get_setting('jam_masuk');
        $data['jam_pulang'] = $this->Admin_model->get_setting('jam_pulang');
        */
        $this->_render_page('admin/dashboard', $data, 'Dashboard');
    }

    public function qr_code()
    {
        $data['qr_code_string'] = $this->Admin_model->get_setting('qr_code_string');
        $data['qr_target_url'] = $this->Admin_model->get_setting('qr_target_url');
        $this->_render_page('admin/qr_code', $data, 'QR Code Absensi');
    }

    public function download_qr()
    {
        $code = $this->Admin_model->get_setting('qr_code_string');
        $target = $this->Admin_model->get_setting('qr_target_url');
        $qr_url = base_url($target . '?code=' . $code);

        $img_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($qr_url) . "&size=300x300&margin=10";
        $img = @file_get_contents($img_url);
        if ($img === FALSE) {
            show_error('Gagal mengunduh gambar QR Code dari server.', 500);
            return;
        }
        header('Content-Description: File Transfer');
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="qr_absensi.png"');
        header('Content-Length: ' . strlen($img));
        echo $img;
    }

    public function update_qr_code()
    {
        $code = $this->input->post('qr_code_string');
        $target = $this->input->post('qr_target_url');

        $this->Admin_model->update_setting('qr_code_string', $code);
        $this->Admin_model->update_setting('qr_target_url', $target);

        $this->session->set_flashdata('success', 'Pengaturan QR Code berhasil diperbarui.');
        redirect('admin/qr_code');
    }

    public function data_absensi()
    {
        $absensi_data = $this->Admin_model->get_all_absensi();
        $lokasi = $this->Admin_model->get_lokasi();

        foreach ($absensi_data as &$row) { // Gunakan reference (&) untuk modifikasi array
            $row['jarak'] = ($lokasi && !empty($row['latitude']) && !empty($row['longitude']))
                ? number_format($this->hitung_jarak($row['latitude'], $row['longitude'], $lokasi['latitude'], $lokasi['longitude']), 1)
                : 'N/A';
        }

        // Ambil data dari tabel kelas_jurusan dan siapkan untuk filter dropdown
        $all_kelas_jurusan = $this->Admin_model->get_all_kelas_jurusan();
        $tingkat_list = array();
        $jurusan_list = array();
        foreach ($all_kelas_jurusan as $kj) {
            $parts = explode('-', $kj['nama_kelas'], 2);
            if (count($parts) === 2) {
                $tingkat_list[] = $parts[0];
                $jurusan_list[] = $parts[1];
            }
        }
        $data['absensi'] = $absensi_data;
        $data['lokasi'] = $lokasi;
        $data['tingkat_list'] = array_unique($tingkat_list, SORT_STRING);
        $data['jurusan_list'] = array_unique($jurusan_list, SORT_STRING);

        $this->_render_page('admin/data_absensi', $data, 'Data Absensi');
    }

    public function edit_absensi($id)
    {
        $data['absen'] = $this->Admin_model->get_absensi_by_id($id);
        if (!$data['absen']) {
            $this->session->set_flashdata('error', 'Data absensi tidak ditemukan.');
            redirect('admin/data_absensi');
        }
        $this->_render_page('admin/edit_absensi', $data, 'Edit Absensi');
    }

    public function update_absensi($id)
    {
        $data = [
            'nis' => $this->input->post('nis', TRUE),
            'nama' => $this->input->post('nama', TRUE),
            'kelas' => $this->input->post('kelas', TRUE),
            'waktu' => $this->input->post('waktu', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
            'lokasi' => $this->input->post('lokasi', TRUE),
            'latitude' => $this->input->post('latitude', TRUE),
            'longitude' => $this->input->post('longitude', TRUE)
        ];

        if ($this->Admin_model->update_absensi($id, $data)) {
            $this->session->set_flashdata('success', 'Data absensi berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data absensi.');
        }
        redirect('admin/data_absensi');
    }

    public function hapus_absensi($id)
    {
        // Memastikan ID adalah angka untuk keamanan
        if (!is_numeric($id)) {
            show_404();
        }

        $this->Admin_model->hapus_absensi($id);
        $this->session->set_flashdata('success', 'Data absensi berhasil dihapus.');
        redirect('admin/data_absensi');
    }
    public function lokasi()
    {
        $data['lokasi'] = $this->Admin_model->get_lokasi();
        $this->_render_page('admin/lokasi', $data, 'Pengaturan Lokasi');
    }

    public function update_lokasi()
    {
        $data = [
            'latitude' => $this->input->post('latitude', TRUE),
            'longitude' => $this->input->post('longitude', TRUE),
            'radius' => $this->input->post('radius', TRUE),
            'nama_lokasi' => $this->input->post('nama_lokasi', TRUE),
            'alamat' => $this->input->post('alamat', TRUE)
        ];

        if ($this->Admin_model->update_lokasi($data)) {
            $this->session->set_flashdata('success', 'Lokasi berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui lokasi.');
        }
        redirect('admin/lokasi');
    }

    public function update_waktu_absensi()
    {
        $jam_masuk = $this->input->post('jam_masuk');
        $jam_pulang = $this->input->post('jam_pulang');
        $hari_list = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];

        // --- Validasi Manual ---
        $errors = [];
        // Aturan validasi yang menerima format HH:MM atau HH:MM:SS
        $time_regex = '/^([0-1]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/';

        if (!empty($jam_masuk) && is_array($jam_masuk)) {
            foreach ($jam_masuk as $hari => $waktu) {
                if (!preg_match($time_regex, $waktu)) {
                    $errors[] = 'Format Jam Masuk ' . ucfirst($hari) . ' tidak valid (HH:MM).';
                }
            }
        }
        if (!empty($jam_pulang) && is_array($jam_pulang)) {
            foreach ($jam_pulang as $hari => $waktu) {
                if (!preg_match($time_regex, $waktu)) {
                    $errors[] = 'Format Jam Pulang ' . ucfirst($hari) . ' tidak valid (HH:MM).';
                }
            }
        }

        if (!empty($errors)) {
            // Jika validasi gagal, kembali ke dashboard dengan pesan error
            $this->session->set_flashdata('error', implode('<br>', $errors));
            redirect('admin/dashboard');
        } else {
            // Jika validasi berhasil, simpan data
            foreach ($jam_masuk as $hari => $waktu) {
                if (in_array($hari, $hari_list)) {
                    $this->Admin_model->update_setting('jam_masuk_' . $hari, $waktu);
                }
            }
            foreach ($jam_pulang as $hari => $waktu) {
                if (in_array($hari, $hari_list)) {
                    $this->Admin_model->update_setting('jam_pulang_' . $hari, $waktu);
                }
            }

            $this->session->set_flashdata('success', 'Waktu absensi berhasil diperbarui.');
            redirect('admin/dashboard');
        }
    }

    public function kelola_kelas()
    {
        $data['list_kelas_jurusan'] = $this->Admin_model->get_all_kelas_jurusan();
        $this->_render_page('admin/kelola_kelas', $data, 'Kelola Kelas');
    }

    public function tambah_kelas_jurusan()
    {
        $this->_render_page('admin/tambah_kelas', [], 'Tambah Kelas');
    }

    public function proses_tambah_kelas_jurusan()
    {
        $tingkat = $this->input->post('tingkat', TRUE);
        $jurusan = $this->input->post('nama_jurusan', TRUE);

        if (empty($tingkat) || empty($jurusan)) {
            $this->session->set_flashdata('error', 'Tingkat dan Nama Jurusan harus diisi.');
            redirect('admin/tambah_kelas_jurusan');
        }

        $nama_kelas = $tingkat . '-' . trim($jurusan);

        // Cek apakah kelas sudah ada
        if ($this->Admin_model->cek_kelas_jurusan_exist($nama_kelas)) {
            $this->session->set_flashdata('error', 'Gagal menambahkan. Kelas "' . htmlspecialchars($nama_kelas) . '" sudah ada.');
            redirect('admin/tambah_kelas_jurusan');
        }

        $data = ['nama_kelas' => $nama_kelas];

        if ($this->Admin_model->insert_kelas_jurusan($data)) {
            $this->session->set_flashdata('success', 'Kelas "' . htmlspecialchars($nama_kelas) . '" berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan kelas karena kesalahan server.');
        }
        redirect('admin/kelola_kelas');
    }

    public function edit_kelas_jurusan($id)
    {
        $data['kelas'] = $this->Admin_model->get_kelas_jurusan_by_id($id);
        if (!$data['kelas']) {
            $this->session->set_flashdata('error', 'Data kelas tidak ditemukan.');
            redirect('admin/kelola_kelas');
        }

        // Pecah nama_kelas menjadi tingkat dan jurusan untuk form
        $parts = explode('-', $data['kelas']['nama_kelas'], 2);
        $data['tingkat_selected'] = $parts[0];
        $data['jurusan_value'] = $parts[1] ?? '';

        $this->_render_page('admin/edit_kelas', $data, 'Edit Kelas');
    }

    public function proses_update_kelas_jurusan($id)
    {
        $tingkat = $this->input->post('tingkat', TRUE);
        $jurusan = $this->input->post('nama_jurusan', TRUE);

        if (empty($tingkat) || empty($jurusan)) {
            $this->session->set_flashdata('error', 'Tingkat dan Nama Jurusan harus diisi.');
            redirect('admin/kelola_kelas');
        }

        $nama_kelas = $tingkat . '-' . trim($jurusan);

        // Cek apakah kelas sudah ada
        if ($this->Admin_model->cek_kelas_jurusan_exist($nama_kelas)) {
            $this->session->set_flashdata('error', 'Gagal menambahkan. Kelas "' . htmlspecialchars($nama_kelas) . '" sudah ada.');
            redirect('admin/edit_kelas_jurusan/' . $id);
        }

        if ($this->Admin_model->update_kelas_jurusan($id, ['nama_kelas' => $nama_kelas])) {
            $this->session->set_flashdata('success', 'Kelas berhasil diperbarui menjadi "' . htmlspecialchars($nama_kelas) . '".');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui kelas karena kesalahan server.');
        }
        redirect('admin/kelola_kelas');
    }

    public function hapus_kelas_jurusan($id)
    {
        if ($this->Admin_model->delete_kelas_jurusan($id)) {
            $this->session->set_flashdata('success', 'Data kelas berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data kelas.');
        }
        redirect('admin/kelola_kelas');
    }

    public function kelola_siswa()
    {
        $data['list_siswa'] = $this->Admin_model->get_all_siswa();
        $this->_render_page('admin/kelola_siswa', $data, 'Kelola Siswa');
    }

    public function tambah_siswa()
    {
        $data['kelas_list'] = $this->Admin_model->get_all_kelas_jurusan();
        $this->_render_page('admin/tambah_siswa', $data, 'Tambah Siswa');
    }

    public function proses_tambah_siswa()
    {
        $nis = $this->input->post('nis', TRUE);
        $nama = $this->input->post('nama', TRUE);
        $kelas = $this->input->post('kelas', TRUE);
        $hp_input = $this->input->post('hp', TRUE);
        $hp = $this->_normalize_phone_number($hp_input);

        // Validasi NIS tidak boleh duplikat (sudah ada)
        if ($this->Admin_model->cek_nis_exist($nis)) {
            $this->session->set_flashdata('error', 'Gagal menambahkan. NIS "' . htmlspecialchars($nis) . '" sudah terdaftar.');
            redirect('admin/tambah_siswa');
        }

        $data = ['nis' => $nis, 'nama' => $nama, 'kelas' => $kelas, 'hp' => $hp];

        if ($this->Admin_model->insert_siswa($data)) {
            $this->session->set_flashdata('success', 'Siswa "' . htmlspecialchars($nama) . '" berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan siswa karena kesalahan server.');
        }
        redirect('admin/kelola_siswa');
    }

    public function edit_siswa($id)
    {
        $data['siswa'] = $this->Admin_model->get_siswa_by_id($id);
        if (!$data['siswa']) {
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan.');
            redirect('admin/kelola_siswa');
        }
        $data['kelas_list'] = $this->Admin_model->get_all_kelas_jurusan();
        $this->_render_page('admin/edit_siswa', $data, 'Edit Siswa');
    }

    public function update_siswa($id)
    {
        $hp_input = $this->input->post('hp', TRUE);
        $hp = $this->_normalize_phone_number($hp_input);
        $data = [
            'nis' => $this->input->post('nis', TRUE),
            'nama' => $this->input->post('nama', TRUE),
            'kelas' => $this->input->post('kelas', TRUE),
            'hp' => $hp
        ];

        if ($this->Admin_model->update_siswa($id, $data)) {
            $this->session->set_flashdata('success', 'Data siswa berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data siswa.');
        }
        redirect('admin/kelola_siswa');
    }

    public function hapus_siswa($id)
    {
        $this->Admin_model->delete_siswa($id);
        $this->session->set_flashdata('success', 'Data siswa berhasil dihapus.');
        redirect('admin/kelola_siswa');
    }

    public function export_siswa_excel()
    {
        // 1. Ambil data siswa dari model
        $list_siswa = $this->Admin_model->get_all_siswa();

        // 2. Buat objek Spreadsheet baru
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 3. Atur Judul dan Header Tabel
        $sheet->setTitle('Data Siswa');
        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Kelas Terakhir Login');
        $sheet->setCellValue('D1', 'No. HP Orang Tua');

        // Beri style pada header
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9EAD3']] // Warna hijau muda
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        // 4. Isi data siswa ke dalam baris
        $row = 2;
        foreach ($list_siswa as $siswa) {
            $sheet->setCellValue('A' . $row, $siswa['nis']);
            $sheet->setCellValue('B' . $row, $siswa['nama']);
            $sheet->setCellValue('C' . $row, $siswa['kelas']);
            $sheet->setCellValue('D' . $row, $siswa['hp'] ?? '-');
            $row++;
        }

        // 5. Atur lebar kolom agar otomatis
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // 6. Buat writer dan kirim file ke browser
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'data_siswa_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function import_siswa()
    {
        $this->_render_page('admin/import_siswa', [], 'Import Siswa');
    }

    public function download_template_siswa()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Kelas');
        $sheet->setCellValue('D1', 'No. HP Orang Tua');

        // Atur lebar kolom agar lebih mudah dibaca
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $filename = 'template_import_siswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function proses_import_siswa()
    {
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 10240; // 10MB
        $config['file_name']     = 'import_siswa_' . time();

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_excel')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', 'Gagal mengupload file: ' . strip_tags($error));
            redirect('admin/import_siswa');
        }

        $file_data = $this->upload->data();
        $file_path = $file_data['full_path'];

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $imported_count = 0;
            $skipped_count = 0;
            $error_rows = [];

            foreach ($rows as $index => $row) {
                if ($index == 0) continue; // Lewati baris header

                $nis = trim($row[0] ?? '');
                $nama = trim($row[1] ?? '');
                $kelas = trim($row[2] ?? '');
                $hp_input = trim($row[3] ?? '');
                $hp = $this->_normalize_phone_number($hp_input);
                if (empty($nis) || empty($nama) || empty($kelas)) {
                    $error_rows[] = $index + 1;
                    continue;
                }

                if ($this->Admin_model->cek_nis_exist($nis)) {
                    $skipped_count++;
                    continue;
                }

                $data = ['nis' => $nis, 'nama' => $nama, 'kelas' => $kelas, 'hp' => $hp];
                if ($this->Admin_model->insert_siswa($data)) {
                    $imported_count++;
                } else {
                    $error_rows[] = $index + 1;
                }
            }

            unlink($file_path); // Hapus file setelah diproses

            $message = "Import selesai. Berhasil menambahkan <b>{$imported_count}</b> siswa. ";
            if ($skipped_count > 0) $message .= "Dilewati <b>{$skipped_count}</b> siswa karena NIS sudah ada. ";
            if (!empty($error_rows)) {
                $message .= "Gagal memproses baris: " . implode(', ', $error_rows) . ".";
                $this->session->set_flashdata('error', $message);
            } else {
                $this->session->set_flashdata('success', $message);
            }
        } catch (Exception $e) {
            if (file_exists($file_path)) unlink($file_path);
            $this->session->set_flashdata('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        redirect('admin/kelola_siswa');
    }

    public function absenkan_siswa($id)
    {
        $siswa = $this->Admin_model->get_siswa_by_id($id);
        if (!$siswa) {
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan.');
            redirect('admin/kelola_siswa');
        }

        $lokasi = $this->Admin_model->get_lokasi();
        if (!$lokasi) {
            $this->session->set_flashdata('error', 'Lokasi sekolah belum diatur. Silakan atur di menu Pengaturan Lokasi.');
            redirect('admin/kelola_siswa');
        }

        // Set zona waktu dan dapatkan waktu saat ini
        date_default_timezone_set('Asia/Jakarta');
        $now = new DateTime();
        $waktu = $now->format('Y-m-d H:i:s');

        // Ambil pengaturan waktu dinamis dari database
        $nama_hari_ini = strtolower($now->format('l')); // 'monday', 'tuesday', etc.
        $nama_hari_indonesia = ['monday' => 'senin', 'tuesday' => 'selasa', 'wednesday' => 'rabu', 'thursday' => 'kamis', 'friday' => 'jumat', 'saturday' => 'sabtu', 'sunday' => 'minggu'];
        $key_hari_ini = $nama_hari_indonesia[$nama_hari_ini] ?? 'senin'; // default ke senin jika hari libur
        $jam_masuk_str = $this->Admin_model->get_setting('jam_masuk_' . $key_hari_ini) ?: '07:15';
        $jam_pulang_str = $this->Admin_model->get_setting('jam_pulang_' . $key_hari_ini) ?: '16:00';

        // Buat objek DateTime untuk perbandingan
        $jam_masuk = new DateTime($jam_masuk_str);
        $jam_pulang = new DateTime($jam_pulang_str);

        $riwayat_absen_hari_ini = $this->Admin_model->cek_absensi_hari_ini($siswa['nis']);
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

        if ($sudah_absen_pulang) {
            $this->session->set_flashdata('error', 'Gagal: Siswa ini sudah melakukan absensi pulang hari ini.');
            redirect('admin/kelola_siswa');
        }

        $keterangan = '';
        if ($now >= $jam_pulang) {
            if (!$sudah_absen_masuk) {
                $this->session->set_flashdata('error', 'Gagal: Siswa ini belum diabsenkan masuk, tidak bisa absen pulang.');
                redirect('admin/kelola_siswa');
            }
            $keterangan = 'Sudah Pulang';
        } else {
            if ($sudah_absen_masuk) {
                $this->session->set_flashdata('error', 'Gagal: Siswa ini sudah melakukan absensi masuk hari ini.');
                redirect('admin/kelola_siswa');
            }
            $keterangan = ($now <= $jam_masuk) ? 'Tepat Waktu' : 'Terlambat';
        }

        $data = [
            'nis' => $siswa['nis'],
            'nama' => $siswa['nama'],
            'kelas' => $siswa['kelas'],
            'waktu' => $waktu,
            'keterangan' => $keterangan,
            'lokasi' => 'Diabsenkan oleh Admin', // Lokasi khusus untuk admin
            'latitude' => $lokasi['latitude'],
            'longitude' => $lokasi['longitude']
        ];

        if ($this->Admin_model->insert_absensi($data)) {
            $this->session->set_flashdata('success', 'Siswa "' . htmlspecialchars($siswa['nama']) . '" berhasil diabsenkan dengan status: ' . $keterangan);

            // Kirim notifikasi jika ada nomor HP
            if (!empty($siswa['hp'])) {
                $this->_kirim_notifikasi_whatsapp($siswa, $data);
            }
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data absensi ke database.');
        }
        redirect('admin/kelola_siswa');
    }

    private function _kirim_notifikasi_whatsapp($siswa, $data_absensi)
    {
        $token = trim($this->Admin_model->get_setting('fonnte_token'));
        $template = trim($this->Admin_model->get_setting('fonnte_message_template'));

        if (empty($token) || empty($template) || empty($siswa['hp'])) {
            log_message('error', 'Gagal kirim WA: Token/Template/No. HP tidak lengkap untuk NIS ' . $siswa['nis']);
            return false;
        }

        $nomor_target = $this->_normalize_phone_number($siswa['hp']);

        $pesan = str_replace(
            ['{nama_siswa}', '{nis}', '{kelas}', '{waktu}', '{keterangan}'],
            [$siswa['nama'], $siswa['nis'], $data_absensi['kelas'], $data_absensi['waktu'], $data_absensi['keterangan']],
            $template
        );

        log_message('debug', 'Mencoba kirim WA (dari Admin) ke: ' . $nomor_target . ' | Pesan: ' . $pesan);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query(['target' => $nomor_target, 'message' => $pesan]),
            CURLOPT_HTTPHEADER => array('Authorization: ' . $token),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        log_message('debug', 'Fonnte API Response (dari Admin): ' . $response);

        $json_response = json_decode($response, true);
        return (isset($json_response['status']) && $json_response['status'] == true);
    }

    // --- CRUD KELOLA ADMIN ---

    public function kelola_admin()
    {
        $data['list_admin'] = $this->Admin_model->get_all_admin();
        $this->_render_page('admin/kelola_admin', $data, 'Kelola Admin');
    }

    public function tambah_admin()
    {
        $this->_render_page('admin/tambah_admin', [], 'Tambah Admin');
    }

    public function proses_tambah_admin()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');
        $passconf = $this->input->post('passconf');

        if ($password !== $passconf) {
            $this->session->set_flashdata('error', 'Password dan konfirmasi password tidak cocok.');
            redirect('admin/tambah_admin');
        }

        if ($this->Admin_model->cek_username_exist($username)) {
            $this->session->set_flashdata('error', 'Username "' . htmlspecialchars($username) . '" sudah digunakan.');
            redirect('admin/tambah_admin');
        }

        $data = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        if ($this->Admin_model->insert_admin($data)) {
            $this->session->set_flashdata('success', 'Admin baru berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan admin.');
        }
        redirect('admin/kelola_admin');
    }

    public function edit_admin($id)
    {
        $data['admin'] = $this->Admin_model->get_admin_by_id($id);
        if (!$data['admin']) {
            $this->session->set_flashdata('error', 'Data admin tidak ditemukan.');
            redirect('admin/kelola_admin');
        }
        $this->_render_page('admin/edit_admin', $data, 'Edit Admin');
    }

    public function update_admin($id)
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');
        $passconf = $this->input->post('passconf');

        if ($this->Admin_model->cek_username_exist($username, $id)) {
            $this->session->set_flashdata('error', 'Username "' . htmlspecialchars($username) . '" sudah digunakan oleh admin lain.');
            redirect('admin/edit_admin/' . $id);
        }

        $data = ['username' => $username];

        if (!empty($password)) {
            if ($password !== $passconf) {
                $this->session->set_flashdata('error', 'Password dan konfirmasi password tidak cocok.');
                redirect('admin/edit_admin/' . $id);
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->Admin_model->update_admin($id, $data)) {
            $this->session->set_flashdata('success', 'Data admin berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data admin.');
        }
        redirect('admin/kelola_admin');
    }

    public function hapus_admin($id)
    {
        if ($id == $this->session->userdata('admin_id')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            redirect('admin/kelola_admin');
        }

        $admin_count = count($this->Admin_model->get_all_admin());
        if ($admin_count <= 1) {
            $this->session->set_flashdata('error', 'Gagal menghapus. Harus ada minimal satu admin di sistem.');
            redirect('admin/kelola_admin');
        }

        if ($this->Admin_model->delete_admin($id)) {
            $this->session->set_flashdata('success', 'Admin berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus admin.');
        }
        redirect('admin/kelola_admin');
    }

    public function notifikasi()
    {
        $data['fonnte_token'] = $this->Admin_model->get_setting('fonnte_token');
        $data['fonnte_message_template'] = $this->Admin_model->get_setting('fonnte_message_template');
        $this->_render_page('admin/notifikasi', $data, 'Pengaturan Notifikasi');
    }

    public function update_notifikasi()
    {
        $token = trim($this->input->post('fonnte_token'));
        $template = trim($this->input->post('fonnte_message_template'));

        $this->Admin_model->update_setting('fonnte_token', $token);
        $this->Admin_model->update_setting('fonnte_message_template', $template);

        $this->session->set_flashdata('success', 'Pengaturan notifikasi berhasil diperbarui.');
        redirect('admin/notifikasi');
    }

    private function _normalize_phone_number($phone)
    {
        if (empty($phone)) {
            return null;
        }
        // 1. Hapus semua karakter non-digit (spasi, tanda hubung, plus, dll.)
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // 2. Cek jika nomor diawali '0' (format lokal Indonesia), ganti dengan '62'
        if (substr($cleaned, 0, 1) === '0') {
            return '62' . substr($cleaned, 1);
        }

        // 3. Jika sudah pakai kode negara (62, 60, dll.), gunakan apa adanya.
        //    Ini juga menangani kasus jika input sudah benar (628...).
        return $cleaned;
    }

    private function hitung_jarak($lat1, $lon1, $lat2, $lon2)
    {
        if (!is_numeric($lat1) || !is_numeric($lon1) || !is_numeric($lat2) || !is_numeric($lon2)) {
            return null;
        }
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    public function logout()
    {
        // Hapus semua data session yang relevan dengan admin
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('admin_username');
        $this->session->unset_userdata('admin'); // Hapus juga key lama jika masih ada
        $this->session->set_flashdata('success', 'Anda telah berhasil logout!');
        redirect('admin/login');
    }

    private function _render_page($view, $data = [], $title = 'Admin')
    {
        $layout_data['page'] = $view;
        $layout_data['data_page'] = $data;
        $layout_data['title'] = $title;
        $this->load->view('templates/layout', $layout_data);
    }
}
