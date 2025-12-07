<?php
//link
include 'config/auth.php';
include "config/kol.php";
//err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//validasi data diri
$id = $_SESSION['id_warga'];
$cekStatus = mysqli_query($conn, "select status_data_diri from warga where id_warga = $id");
$data = mysqli_fetch_assoc($cekStatus);

if(!empty($data['status_data_diri']) ){
  $warga = "warga/riwayat.php" ;
} else {
  $daftar = "warga/profil.php" ;
} 




?>


<!DOCTYPE html>
<html lang="id">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AJUK - Landing Page</title>

    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/dashboard.css" />
  </head>

  <body>

    <?= $warga ?? ""?>
    <?= $daftar ?? "" ?>
    <?=  $id ?> <!-- ========NAVIGASI=========== -->
    <nav class="navbar navbar-dark bg-dark  navbar-expand-lg bg-body-tertiary fixed-top" id="navigasi">
      <div class="container-fluid ">
        <a class="logo-link navbar-brand"><img src="./assets/logo.svg" alt="logo" /></a>
        <div class="wrap-icon ">
          <button class=" navbar-toggler btn1" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <img src="./assets/nav_menu.svg" class="material-icons navMenu" alt="" srcset="">
          </button>
          <div class="profile-btn">
            <a href="<?= $daftar ?? $warga ?>" class="profile-icon">
              <i class="bi bi-person-circle"></i>
            </a>
          </div>
        </div>

        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
          <ul class="navbar-nav  mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#layanan">Ajukan Dokumen</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="<?= $daftar ?? $warga ?>">Lacak Pengajuan</a>
            </li>
        </div>


        <div class="icon-hide profile-btn"> 
          <div class="d-flex text-danger fs-5 me-1 "><?php echo $_SESSION['nama'] ?>
            <?php echo $_SESSION['status']?> 


            <a href="warga/profil.php" class="profile-icon ">
              <i class="ml-3 bi bi-person-circle"></i>
            </a>
          </div>
        </div>
      </div>
      </div>
    </nav>

    <!-- KONTEN -->
    <main>
      <section class="dashboard-content" id="home">
        <div class="dashcont ">
          <div class="wrap-dash">
            <h1>Standar pelayanan publik dan SOP pelayanan AJUK</h1>
            <p class="">
              Sistem aplikasi pengajuan dokumen warga berbasis web ini memudahkan
              pengurusan dokumen di tingkat kelurahan, memungkinkan warga
              mengajukan secara online, petugas memverifikasi langsung, serta
              menghasilkan nomor surat otomatis, sementara dokumen RT/RW atau di
              atasnya tetap diurus manual.
            </p>

            <a href="#layanan" class="btn-primary fw-bold">Ajukan Dokumen</a>
          </div>
        </div>
      </section>

      <!-- jenis dari dokuemn -->
      <section class="layanan" id="layanan" >
        <div class="layanan-dashboard">
          <div class="wrap-layanan wrap-container">
            <h2>Layanan Yang Kami Sediakan</h2>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="surat/surat-SKTM.php">Surat Keterangan Tidak Mampu</a>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="surat/surat-domisili.php">Surat Domisili</a>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="surat/surat-SKK.php">Surat Keterangan Kematian</a>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="surat/surat-izin-usaha.php">Surat Izin Usaha</a>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="surat/surat-rumah.php">Surat Kepemilikan Rumah</a>
          </div>
        </div>
      </section>
    </main>


    <footer>
      <p>AJUK - Copyright © 2025. All rights reserved.</p>
    </footer>
  </body>
  <script src="./bootstrap/js/bootstrap.min.js"></script>

</html>
