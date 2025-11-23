<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Paket extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Paket_model');
        $this->load->model('Soal_model');
        $this->load->helper(['url', 'file']);
        // allow guru to upload and admin to approve; methods will check roles
    }

    public function index()
    {
        is_admin();
        $data['title'] = 'Manajemen Paket';
        $data['paket'] = $this->Paket_model->get_all_paket();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/paket/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function upload()
    {
        is_guru();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data = [];

        if ($this->input->method() === 'post') {
            // validate upload
            if (empty($_FILES['file_excel']['name'])) {
                $this->session->set_flashdata('error', 'Pilih file Excel.');
                redirect('paket/upload');
            }

            $allowed_types = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'];
            $max_size = 5 * 1024 * 1024; // 5MB
            $file = $_FILES['file_excel'];
            if ($file['size'] > $max_size) {
                $this->session->set_flashdata('error', 'Ukuran file terlalu besar (max 5MB).');
                redirect('paket/upload');
            }
            // basic mime check
            if (!in_array($file['type'], $allowed_types)) {
                // try extension check
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (!in_array(strtolower($ext), ['xlsx', 'xls', 'csv'])) {
                    $this->session->set_flashdata('error', 'Tipe file tidak didukung. Gunakan XLSX/XLS/CSV.');
                    redirect('paket/upload');
                }
            }

            // if images zip provided, extract it first (so gambar tersedia for validation)
            if (!empty($_FILES['file_images_zip']['name'])) {
                $zip = $_FILES['file_images_zip'];
                $zext = pathinfo($zip['name'], PATHINFO_EXTENSION);
                if (strtolower($zext) !== 'zip') {
                    $this->session->set_flashdata('error', 'File gambar harus berupa ZIP.');
                    redirect('paket/upload');
                }
                $tmpzip = sys_get_temp_dir() . DIRECTORY_SEPARATOR . time() . '_imgs.zip';
                if (!move_uploaded_file($zip['tmp_name'], $tmpzip)) {
                    $this->session->set_flashdata('error', 'Gagal menyimpan file ZIP gambar.');
                    redirect('paket/upload');
                }
                $zipObj = new ZipArchive();
                if ($zipObj->open($tmpzip) === TRUE) {
                    $images_dir = FCPATH . 'assets/uploads/paket/images/';
                    if (!is_dir($images_dir)) mkdir($images_dir, 0755, true);
                    $zipObj->extractTo($images_dir);
                    $zipObj->close();
                    @unlink($tmpzip);
                } else {
                    @unlink($tmpzip);
                    $this->session->set_flashdata('error', 'Gagal mengekstrak ZIP gambar.');
                    redirect('paket/upload');
                }
            }

            // move uploaded Excel/CSV file
            $upload_dir = FCPATH . 'assets/uploads/paket/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $file['name']);
            $target = $upload_dir . $filename;
            if (!move_uploaded_file($file['tmp_name'], $target)) {
                $this->session->set_flashdata('error', 'Gagal menyimpan file.');
                redirect('paket/upload');
            }

            // parse file with PhpSpreadsheet for validation
            try {
                $spreadsheet = IOFactory::load($target);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray(null, true, true, true);
                // Expect header in first row with keys like pertanyaan,a,b,c,d,e,jawaban,gambar_filename
                $header = array_map('trim', array_map('strtolower', array_values($rows[1])));
                $expected = ['pertanyaan', 'a', 'b', 'c', 'd', 'e', 'jawaban', 'gambar_filename'];
                $missing = array_diff($expected, $header);
                if (!empty($missing)) {
                    unlink($target);
                    $this->session->set_flashdata('error', 'Format Excel tidak sesuai. Kolom yang diperlukan: ' . implode(',', $expected));
                    redirect('paket/upload');
                }

                // normalize rows starting from row 2
                $parsed = [];
                $errors = [];
                for ($r = 2; $r <= count($rows); $r++) {
                    $row = $rows[$r];
                    // map by header position
                    $map = [];
                    $i = 0;
                    foreach ($row as $cell) {
                        $map[$header[$i]] = $cell;
                        $i++;
                    }
                    if (empty(trim($map['pertanyaan']))) continue; // skip blank
                    $entry = [
                        'pertanyaan' => $map['pertanyaan'],
                        'a' => $map['a'] ?? '',
                        'b' => $map['b'] ?? '',
                        'c' => $map['c'] ?? '',
                        'd' => $map['d'] ?? '',
                        'e' => $map['e'] ?? null,
                        'kunci' => strtoupper(trim($map['jawaban'] ?? '')),
                        'gambar' => trim($map['gambar_filename'] ?? '')
                    ];
                    // validate kunci
                    if (!in_array($entry['kunci'], ['A', 'B', 'C', 'D', 'E'])) {
                        $errors[] = "Baris $r: jawaban tidak valid ({$entry['kunci']}).";
                    }
                    // validate gambar if provided
                    if (!empty($entry['gambar'])) {
                        $imgpath = FCPATH . 'assets/uploads/paket/images/' . $entry['gambar'];
                        if (!file_exists($imgpath)) {
                            $errors[] = "Baris $r: gambar '{$entry['gambar']}' tidak ditemukan di assets/uploads/paket/images/.";
                        }
                    }
                    $parsed[] = $entry;
                }

                if (!empty($errors)) {
                    // remove uploaded file
                    unlink($target);
                    $this->session->set_flashdata('error', implode(' ', $errors));
                    redirect('paket/upload');
                }

                // save paket record with status pending
                $post = $this->input->post();
                $paket_id = $this->Paket_model->insert_paket([
                    'nama_paket' => $post['nama_paket'] ?? 'Paket ' . date('YmdHis'),
                    'guru_id' => current_user_id(),
                    'mapel_id' => $post['mapel_id'] ?? 1,
                    'file_excel' => 'assets/uploads/paket/' . $filename,
                    'status' => 'pending'
                ]);

                // Save parsed preview as JSON for admin approval
                $preview_dir = $upload_dir . 'previews/';
                if (!is_dir($preview_dir)) mkdir($preview_dir, 0755, true);
                file_put_contents($preview_dir . $paket_id . '.json', json_encode($parsed, JSON_UNESCAPED_UNICODE));

                $this->session->set_flashdata('success', 'File berhasil diunggah. Paket dikirim untuk approval admin.');
                redirect('guru');
            } catch (Exception $e) {
                @unlink($target);
                $this->session->set_flashdata('error', 'Gagal memproses file Excel: ' . $e->getMessage());
                redirect('paket/upload');
            }
        }

        // show upload form
        $this->load->model('Mapel_model');
        $data['mapel'] = $this->Mapel_model->get_all_mapel();
        $data['title'] = 'Upload Paket Soal';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('guru/paket_upload', $data);
        $this->load->view('templates/admin_footer');
    }

    public function view($id)
    {
        is_admin();
        $p = $this->Paket_model->get_paket_by_id($id);
        if (!$p) show_404();
        $preview_file = FCPATH . 'assets/uploads/paket/previews/' . $id . '.json';
        $data['paket'] = $p;
        $data['title'] = 'Preview Paket';
        $data['preview'] = file_exists($preview_file) ? json_decode(file_get_contents($preview_file), true) : [];
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/paket/view', $data);
        $this->load->view('templates/admin_footer');
    }

    public function edit($id)
    {
        is_admin();
        $this->load->model('Mapel_model');
        if ($this->input->method() === 'post') {
            $post = $this->input->post();
            $update = [
                'nama_paket' => $post['nama_paket'] ?? null,
                'mapel_id' => $post['mapel_id'] ?? null,
                'status' => $post['status'] ?? 'pending'
            ];
            $this->Paket_model->update_paket($id, $update);
            echo json_encode(['status' => 'ok', 'message' => 'Paket diperbarui']);
            return;
        }

        $p = $this->Paket_model->get_paket_by_id($id);
        if (!$p) {
            show_404();
        }
        $data['paket'] = $p;
        $data['mapel'] = $this->Mapel_model->get_all_mapel();
        $this->load->view('admin/paket/modal_edit', $data);
    }

    public function approve($id)
    {
        is_admin();
        $p = $this->Paket_model->get_paket_by_id($id);
        if (!$p) show_404();
        $preview_file = FCPATH . 'assets/uploads/paket/previews/' . $id . '.json';
        if (!file_exists($preview_file)) {
            if ($this->input->is_ajax_request() || $this->input->method() === 'post') {
                echo json_encode(['status' => 'error', 'message' => 'Preview paket tidak ditemukan.']);
                return;
            }
            $this->session->set_flashdata('error', 'Preview paket tidak ditemukan.');
            redirect('paket');
        }
        $rows = json_decode(file_get_contents($preview_file), true);
        $insert = [];
        foreach ($rows as $r) {
            $insert[] = [
                'paket_id' => $id,
                'pertanyaan' => $r['pertanyaan'],
                'a' => $r['a'],
                'b' => $r['b'],
                'c' => $r['c'],
                'd' => $r['d'],
                'e' => $r['e'],
                'kunci' => $r['kunci'],
                'gambar' => $r['gambar'],
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        if (!empty($insert)) {
            $this->Soal_model->insert_batch_soal($insert);
        }
        $this->Paket_model->update_paket($id, ['status' => 'approved']);
        if ($this->input->is_ajax_request() || $this->input->method() === 'post') {
            echo json_encode(['status' => 'ok', 'message' => 'Paket disetujui dan soal tersimpan.']);
            return;
        }
        $this->session->set_flashdata('success', 'Paket disetujui dan soal tersimpan.');
        redirect('paket');
    }

    public function reject($id)
    {
        is_admin();
        $this->Paket_model->update_paket($id, ['status' => 'rejected']);
        if ($this->input->is_ajax_request() || $this->input->method() === 'post') {
            echo json_encode(['status' => 'ok', 'message' => 'Paket ditolak.']);
            return;
        }
        $this->session->set_flashdata('success', 'Paket ditolak.');
        redirect('paket');
    }
}
