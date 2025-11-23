# README - Modifikasi: Sistem Ujian Online (CI3)

Ringkasan perubahan

- Menambahkan helper role: `application/helpers/role_helper.php` (is_admin/is_guru/is_siswa)
- Mengubah `application/config/autoload.php` untuk mem-autoload helper `role`.
- Menambah routes ujian/room/paket di `application/config/routes.php`.
- Menambah controller `Ujian.php` (student endpoints) dan `Room.php` (admin room management).
- Menambah view list room dan halaman eksekusi ujian untuk siswa (`application/views/room/*`, `application/views/ujian/*`).
- Menambah admin views untuk mengelola room (`application/views/admin/room/*`).
- Menambah frontend JS: `assets/js/ujian.js` (fullscreen, anti-cheat, autosave).
- Menambahkan root SQL `db_siswa.sql` (schema + seed minimal).

Cara migrasi / import SQL

1. Buat database baru (contoh `db_ujian`).
2. Import file `db_siswa.sql` yang ada di root. Anda bisa pakai phpMyAdmin atau CLI.

   - phpMyAdmin: buka phpMyAdmin → pilih database atau buat database baru → tab "Import" → pilih file `db_siswa.sql` dari project root → klik Go.
   - CLI (MySQL/MariaDB):

     ```powershell
     mysql -u root -p < db_siswa.sql
     ```

3. Perbarui `application/config/database.php` sesuai kredensial lokal.

Folder uploads

- Buat folder `assets/uploads/paket/` dan `assets/uploads/paket/images/` dengan permission yang dapat ditulis oleh webserver. Jika guru ingin mengunggah gambar bersama Excel, mereka dapat mengunggah satu file ZIP gambar di form upload; sistem otomatis akan mengekstrak ZIP ke `assets/uploads/paket/images/`.

Login (seed)

- Admin: username `admin`, password `admin` (MD5: 21232f...)
- Guru: `guru_ipa` / password `1234` (MD5 sesuai seed), `guru_mtk`.
- Siswa: NISN `001`..`006` dengan password `siswa123` (MD5 in seed). Admin dapat melihat `password_plain` di halaman manajemen siswa.

Manual steps / catatan

- Excel & gambar upload: Implementasi parser Excel (PhpSpreadsheet) sudah terintegrasi pada endpoint `paket/upload`. Guru dapat mengunggah file Excel (XLSX/XLS/CSV) dan secara opsional satu file ZIP yang berisi semua gambar soal; ZIP akan diekstrak otomatis ke `assets/uploads/paket/images/`.
- Pastikan nama file pada kolom `gambar_filename` di Excel identik dengan nama file di ZIP (case-sensitive di server Windows biasanya case-insensitive, tapi usahakan konsisten).

Testing ringan

1. Import `db_siswa.sql` (lihat langkah di atas).
2. Perbarui `application/config/database.php` jika perlu.
3. Pastikan `composer install` sudah dijalankan agar `phpoffice/phpspreadsheet` tersedia:

   ```powershell
   composer install
   ```

4. Pastikan folder `assets/uploads/paket/` dan `assets/uploads/paket/images/` ada dan dapat ditulis.

5. Buka `http://localhost/<project>/` (mis. jika Laragon, `http://localhost/aplikasi-ujian-ci/`) → login sebagai `admin`.

6. Sebagai guru: buka `paket/upload` → isi nama paket, pilih mapel, pilih Excel → (opsional) pilih ZIP gambar → submit.

7. Sebagai admin: buka `Manajemen Paket Soal` → klik `Lihat` pada paket pending → jika tampak baik, klik `Approve` → soal akan disimpan ke tabel `soal`.

8. Buat `Room` dengan paket yang sudah di-approve, pilih kelas target, aktifkan.

9. Login sebagai siswa (`001` / `siswa123`) → buka daftar ujian → `Mulai Ujian` → ikuti alur.

Perhatian keamanan

- Sistem menyimpan password siswa dalam bentuk MD5 dan `password_plain` sesuai permintaan — ini sangat tidak aman. Migrasi ke `password_hash()` dan hapus `password_plain` sangat disarankan.
- Batasi ukuran & tipe file upload pada controller Paket sebelum menerima file dari pengguna.

Berikut daftar file yang diubah/ditambah (ringkasan):

- application/helpers/role_helper.php (baru)
- application/config/autoload.php (ubah)
- application/config/routes.php (ubah)
- application/controllers/Ujian.php (baru)
- application/controllers/Room.php (baru)
- application/views/room/list.php (baru)
- application/views/ujian/execute.php (baru)
- application/views/admin/room/\* (baru)
- assets/js/ujian.js (baru)
- db_siswa.sql (root, baru)

Jika Anda ingin, saya bisa:

- Lengkapi controller `Paket.php` untuk upload Excel dan parsing dengan PhpSpreadsheet (sudah terdapat dependency di project: phpoffice/phpspreadsheet).
- Tambah validasi upload, page approve paket di admin UI, dan koneksi gambar Excel.

Preview Room (Admin)

- Di daftar Room (Admin) terdapat tombol 'Preview' (ikon mata). Klik untuk masuk ke mode preview: Admin dapat menavigasi soal (Next/Prev), melihat soal & gambar, dan melihat sisa waktu ujian berdasarkan `mulai_datetime`/`selesai_datetime`. Preview bersifat read-only: tidak membuat hasil ujian, tidak merekam jawaban, dan tidak memicu anti-cheat.

Peran & kemampuan (ringkas)

- Admin:
  - Login: `admin` / `admin` (seed).
  - Akses: manajemen admin/guru/siswa/kelas, manajemen paket soal (lihat/approve/reject), manajemen Room (buat/edit/delete), preview Room (lihat soal seperti siswa), lihat hasil ujian, reset hasil siswa.
- Guru:
  - Login: contoh `guru_ipa` / `1234`.
  - Akses: upload paket soal (Excel) + optional ZIP gambar, melihat hasil untuk kelas yang diaampu (menu Hasil Guru).
  - Tidak dapat approve paket (hanya submit untuk admin approval).
- Siswa:
  - Login: menggunakan NISN (mis. `001`) + password (`siswa123`).
  - Akses: melihat daftar Room yang menargetkan kelasnya, mulai ujian saat Room aktif, mengerjakan soal (autosave), ujian akan otomatis berakhir bila siswa keluar fullscreen/pindah tab/blur.
