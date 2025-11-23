<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Admin' ?> - Absensi Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_style.css') ?>">
    <link rel="icon" href="<?= base_url('assets/favicon.ico') ?>" type="image/x-icon">
    <?php
        $baseUrl = base_url();
    ?>
</head>
<body>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    <div class="d-flex" id="wrapper">
        <?php $this->load->view('templates/sidebar'); ?>
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top mb-5">
                <div class="container-fluid">
                    <button class="btn btn-success d-md-none" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                </div>
            </nav>
            <main class="content-wrapper">
                <div class="container-fluid p-3 p-md-4">
                    <?php $this->load->view($page, $data_page); ?>
                </div>
            </main>
        </div>
    </div>
    <?php $this->load->view('templates/footer'); ?>
</body>
</html>