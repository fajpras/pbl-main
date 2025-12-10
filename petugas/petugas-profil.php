<?php

//link
include "../config/kol.php";
include ('../config/auth.php') ;

//err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//validasi login
if (!isset($_SESSION['id_petugas'])) {
  echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location='../login.php';</script>";
  exit();
}

$id = $_SESSION['id_petugas'];
$nama = $_SESSION['nama_petugas'];

//ambil unutuk tampilkan data diri petugas
$qry = "SELECT * FROM data_diri_petugas where  id_petugas = $id ";
$mysql = mysqli_query($conn, $qry);
$data = mysqli_fetch_array($mysql);

$read = "readonly";
$dis ="disabled";


?>

<!DOCTYPE html>
<html lang="id">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | AJUK</title>

    <!-- link -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/petugas-profile.css">
  </head>

  <body>
    <!-- navbar -->
    <nav class="navbar navbar-dark fixed-top" id="navigasi">
      <div class="logo d-flex align-items-center">
        <a href="petugas-riwayat.php" class=" text-black"><i class="fs-2 px-3 fw-bold text-light bi bi-arrow-left-circle"></i>
        </a>
        <img src="../assets/logo.svg" alt="logo">

      </div>
      <div class="nav-links">
        <!-- keluar -->
        <a 
          class="fs-7 fw-medium align-items-center btn btn-danger"
          href="../config/_close.php" onclick="return confirm('anda yakin ingin keluar')">
          <i class="bi bi-box-arrow-right p-1"></i>Keluar
        </a>      </div>
    </nav>

    <!-- main section -->
    <section class="profil-section">
      <div class="profil-container ">
        <h1>DATA DIRI</h1>
        <div class="profil-content">
          <div class="profil-form">

            <div class="profil-side">
              <div class="profil-avatar">
                <!-- <i class="bi bi-person-circle"></i> -->
                <img style="border-radius:50%;" src="../uploads-petugas/<?= $data['foto_profil'] ?> " height="100" width="100" >
              </div>
              <h3>Halo <?= $nama ?></h3>
            </div>

            <form method="POST" action="../petugas-config/_proses_profil_petugas.php" enctype="multipart/form-data">
              <div class="mb-2">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" value="<?= $data['nama_lengkap'] ?? ''?>" <?= $read ?>>
              </div>

              <div class="mb-2">
                <label>Foto Profil</label>
                <input type="file" placeholder="<?= $data['foto_profil'] ?? "" ?> " name="foto_profil_petugas" class="form-control" >
              </div>

              <div class="mb-2">
                <label>Nomor Petugas</label>
                <input type="text" class="form-control" value="<?= $data['nomor'] ?? ''?>" <?= $read ?>>
              </div>

              <div class="mb-2">
                <label>Email</label>
                <input type="email" class="form-control" value="<?= $data['email'] ?? '' ?>" <?= $read ?>>
              </div>

              <div class="row">
                <div class="col-md-6 mb-2">
                  <label>Tempat Lahir</label>
                  <input type="text" class="form-control" value="<?= $data['tempat_lahir'] ?? '' ?>" <?= $read ?>>
                </div>
                <div class="col-md-6 mb-2">
                  <label>Tanggal Lahir</label>
                  <input type="text" class="form-control" value="<?= $data['tanggal_lahir'] ?? '' ?>" <?= $read ?>>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-2">
                  <label>Jenis Kelamin</label>
                  <input type="text" class="form-control" value="<?= $data['jenis_kelamin'] ?? '' ?>" <?= $read?>>
                </div>

              </div>
              <button class="col-md-3 btn btn-light mt-3 float-end" onclick="konfirm()">Simpan</button>

            </form>
          </div>


        </div>
      </div>
    </section>

    <footer>
      <p>AJUK - Copyright © 2025. All rights reserved.</p>
    </footer>
  </body>

</html>
