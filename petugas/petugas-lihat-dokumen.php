<?php
// --- 1. KONFIGURASI & AUTH ---
include "../config/kol.php";
include('../config/auth.php');

// Matikan error reporting saat production
// error_reporting(0); 

// Validasi login
if (!isset($_SESSION['id_petugas'])) {
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location='../login.php';</script>";
    exit();
}

// --- 2. AMBIL DATA DARI URL ---
$id_dok = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$dok    = isset($_GET['dok']) ? $_GET['dok'] : '';

// --- 3. LOGIKA PEMILIHAN TABEL & DATA ---
$namaDokumen = "";
$data = [];
$query = "";

switch ($dok) {
    case 'SKTM':
        $namaDokumen = "Surat Keterangan Tidak Mampu";
        $query = "SELECT * FROM dokumen_sktm WHERE id_surat = $id_dok LIMIT 1";
        break;
    case 'SKK':
        $namaDokumen = "Surat Keterangan Kematian";
        // Format tanggal langsung di query
        $query = "SELECT *, date_format(tanggal_kematian, '%d %M %Y') as tanggal FROM dokumen_skk WHERE id_surat = $id_dok LIMIT 1";
        break;
    case 'SRM':
        $namaDokumen = "Surat Rumah";
        $query = "SELECT * FROM dokumen_rumah WHERE id_surat = $id_dok LIMIT 1";
        break;
    case 'SIU':
        $namaDokumen = "Surat Izin Usaha";
        $query = "SELECT * FROM dokumen_izin_usaha WHERE id_surat = $id_dok LIMIT 1";
        break;
    default:
        // Default ke Surat Domisili jika dok tidak dikenali atau kosong
        $namaDokumen = "Surat Domisili";
        $dok = "SDM"; // Standardisasi kode
        $query = "SELECT * FROM dokumen_domisili WHERE id_surat = $id_dok LIMIT 1";
        break;
}

// Eksekusi Query
$ambil = mysqli_query($conn, $query);
$data  = mysqli_fetch_assoc($ambil);

// Cek jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data dokumen tidak ditemukan!'); window.location='petugas-pending.php';</script>";
    exit();
}

// Fungsi bantu untuk membuat tombol lihat file (agar kodingan HTML lebih bersih)
function tombolLihat($id, $dok, $kode, $icon = 'bi-image') {
    return '<a class="btn-link-file" href="detail.php?id='.$id.'&dok='.$dok.'&a='.$kode.'" target="_blank">
                <i class="bi '.$icon.'"></i> Lihat File
            </a>';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AJUK | Detail <?= $namaDokumen ?></title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/petugas-lihat-dokumen.css" />
    <style>
        .table th { width: 35%; background-color: #f8f9fa; }
        .btn-link-file { text-decoration: none; font-weight: 600; color: #0d6efd; transition: 0.3s; }
        .btn-link-file:hover { text-decoration: underline; color: #0a58ca; }
        .card-header-custom { background: linear-gradient(45deg, #0d6efd, #0043a8); color: white; }
    </style>
</head>

<body class="   bg-light">

<div class="kol">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <img src="../assets/logo.svg" alt="logo" height="50" class="me-2"> 
            </a>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow rounded-4 border-0 overflow-hidden">
                    
                    <div class="card-header card-header-custom p-4 position-relative text-center">
                        <button class="btn btn-light btn-sm text-danger fw-bold position-absolute top-0 end-0 m-3 shadow-sm"
                            onclick="window.location.href='petugas-pending.php'">
                            <i class="bi bi-x-lg"></i> Kembali
                        </button>
                        <h3 class="fw-bold mb-0">Detail <?= $namaDokumen ?></h3>
                        <p class="mb-0 small opacity-75">Periksa kelengkapan data dan berkas pemohon di bawah ini.</p>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <tbody>
                                    
                                    <?php if ($dok == "SKTM") : ?>
                                        <tr><th>NIK</th> <td><?= htmlspecialchars($data['nik']) ?></td></tr>
                                        <tr><th>Nama Lengkap</th> <td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                                        <tr><th>Berkas Utama</th> <td><?= tombolLihat($id_dok, $dok, 'fsp', 'bi-file-earmark-text') ?></td></tr>
                                        <tr><th>Foto Rumah</th> <td><?= tombolLihat($id_dok, $dok, 'fr') ?></td></tr>
                                        <tr><th>Foto KK</th> <td><?= tombolLihat($id_dok, $dok, 'fkk') ?></td></tr>
                                        <tr><th>Foto Slip Gaji</th> <td><?= tombolLihat($id_dok, $dok, 'fsg') ?></td></tr>
                                        <tr><th>Foto Tagihan</th> <td><?= tombolLihat($id_dok, $dok, 'ft') ?></td></tr>

                                    <?php elseif ($dok == "SKK") : ?>
                                        <tr><th>NIK</th> <td><?= htmlspecialchars($data['nik']) ?></td></tr>
                                        <tr><th>Nama Lengkap</th> <td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                                        <tr><th>Jenis Kelamin</th> <td><?= htmlspecialchars($data['jenis_kelamin']) ?></td></tr>
                                        <tr><th>Pekerjaan</th> <td><?= htmlspecialchars($data['pekerjaan']) ?></td></tr>
                                        <tr><th>Alamat</th> <td><?= htmlspecialchars($data['alamat']) ?></td></tr>
                                        <tr><th>Penyebab</th> <td><?= htmlspecialchars($data['penyebab']) ?></td></tr>
                                        <tr><th>Tanggal Kematian</th> <td><?= htmlspecialchars($data['tanggal']) ?></td></tr>
                                        <tr><th>Surat RS / Dokter</th> <td><?= tombolLihat($id_dok, $dok, 'fsrs', 'bi-file-earmark-medical') ?></td></tr>
                                        <tr><th>Foto KTP Pelapor</th> <td><?= tombolLihat($id_dok, $dok, 'fkp') ?></td></tr>
                                        <tr><th>Surat Pengantar RT</th> <td><?= tombolLihat($id_dok, $dok, 'fsp') ?></td></tr>
                                        <tr><th>Foto Akta Nikah</th> <td><?= tombolLihat($id_dok, $dok, 'fan') ?></td></tr>

                                    <?php elseif ($dok == "SRM") : ?>
                                        <tr><th>NIK</th> <td><?= htmlspecialchars($data['nik']) ?></td></tr>
                                        <tr><th>Nama Lengkap</th> <td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                                        <tr><th>Kecamatan</th> <td><?= htmlspecialchars($data['kecamatan']) ?></td></tr>
                                        <tr><th>Desa</th> <td><?= htmlspecialchars($data['desa']) ?></td></tr>
                                        <tr><th>Alamat</th> <td><?= htmlspecialchars($data['alamat']) ?></td></tr>
                                        <tr><th>Foto Sertifikat</th> <td><?= tombolLihat($id_dok, $dok, 'fs') ?></td></tr>
                                        <tr><th>Foto Akta Rumah</th> <td><?= tombolLihat($id_dok, $dok, 'fam') ?></td></tr>
                                        <tr><th>Foto KK</th> <td><?= tombolLihat($id_dok, $dok, 'fkk') ?></td></tr>
                                        <tr><th>Foto KTP</th> <td><?= tombolLihat($id_dok, $dok, 'fktp') ?></td></tr>
                                        <tr><th>Foto BPPBB</th> <td><?= tombolLihat($id_dok, $dok, 'fbp') ?></td></tr>
                                        <tr><th>Surat Tidak Sengketa</th> <td><?= tombolLihat($id_dok, $dok, 'fsts') ?></td></tr>

                                    <?php elseif ($dok == "SIU") : ?>
                                        <tr><th>NIK</th> <td><?= htmlspecialchars($data['nik']) ?></td></tr>
                                        <tr><th>Nama Lengkap</th> <td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                                        <tr><th>Nama KBLI</th> <td><?= htmlspecialchars($data['nama_kbli']) ?></td></tr>
                                        <tr><th>Nomor KBLI</th> <td><?= htmlspecialchars($data['nomor_kbli']) ?></td></tr>
                                        <tr><th>Kecamatan</th> <td><?= htmlspecialchars($data['kecamatan']) ?></td></tr>
                                        <tr><th>Desa</th> <td><?= htmlspecialchars($data['desa']) ?></td></tr>
                                        <tr><th>Alamat</th> <td><?= htmlspecialchars($data['alamat']) ?></td></tr>
                                        <tr><th>Foto NPWP</th> <td><?= tombolLihat($id_dok, $dok, 'fnpwp') ?></td></tr>
                                        <tr><th>Surat Pengantar RT</th> <td><?= tombolLihat($id_dok, $dok, 'fsp') ?></td></tr>
                                        <tr><th>Foto KK</th> <td><?= tombolLihat($id_dok, $dok, 'fkk') ?></td></tr>
                                        <tr><th>Foto KTP</th> <td><?= tombolLihat($id_dok, $dok, 'fktp') ?></td></tr>
                                        <tr><th>Surat Domisili</th> <td><?= tombolLihat($id_dok, $dok, 'fsd') ?></td></tr>
                                        <tr>
                                            <th>Foto Pajak Bumi (PBB)</th> 
                                            <td><?= tombolLihat($id_dok, $dok, 'fpbb') ?></td>
                                        </tr>

                                    <?php else : ?>
                                        <tr><th>NIK</th> <td><?= htmlspecialchars($data['nik']) ?></td></tr>
                                        <tr><th>Nama Lengkap</th> <td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                                        <tr><th>Agama</th> <td>
                                            <?php 
                                            // Mapping Kode Agama (Opsional, sesuaikan dgn DB)
                                            $agamaArr = [1=>'Islam', 2=>'Kristen', 3=>'Katholik', 4=>'Buddha', 5=>'Hindu', 6=>'Konghucu'];
                                            echo isset($agamaArr[$data['agama']]) ? $agamaArr[$data['agama']] : $data['agama']; 
                                            ?>
                                        </td></tr>
                                        <tr><th>Pekerjaan</th> <td><?= htmlspecialchars($data['pekerjaan']) ?></td></tr>
                                        <tr><th>Alamat</th> <td><?= htmlspecialchars($data['alamat']) ?></td></tr>
                                        <tr><th>Surat Pengantar RT</th> <td><?= tombolLihat($id_dok, $dok, 'fsp') ?></td></tr>
                                        <tr><th>Foto KK</th> <td><?= tombolLihat($id_dok, $dok, 'fkk') ?></td></tr>
                                        <tr>
                                            <th>Pas Foto</th> 
                                            <td><?= tombolLihat($id_dok, $dok, 'fp', 'bi-person-bounding-box') ?></td>
                                        </tr>

                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        

                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center py-4 text-muted small">
        <p class="mb-0">AJUK Sistem - Copyright © 2025. All rights reserved.</p>
    </footer>

</div>

</body>
</html>