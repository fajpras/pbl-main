<?php
//link
include "../config/kol.php";
include "../config/auth.php";

//err log
error_reporting(E_ALL);
ini_set('display_errors', 1);

//notif ok
if (isset($_GET['note'])) {
  echo "<script>alert('Data Berhasil di simpan')</script>";
}



?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ajukan Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dashboard-admin.css" />
  </head>

  <body>

    <div class="container-fluid ">
      <div class="row flex-nowrap ">
        <!-- sidebar  -->
        <div class="col-auto px-0 sidebar ">
          <div id="sidebar" class="collapse collapse-horizontal show  ">
            <div class="d-flex container-fluid ">

              <a href="#" class="text-black text-decoration-none">
                <img class="logo p-3" src="../assets/logo.svg" alt="logo" />
              </a>
              <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
                class="border fs-1  p-1 text-decoration-none text-light align-items-center border1"><i
                  class="bi bi-x bi-lg py-2 p-1 "></i>
              </a>
            </div>

            <hr />
            <div id="sidebar-nav" class="list-group border-0 rounded-0 text-sm-start min-vh-100">
              <ul class="nav menu  fw-medium nav-pills flex-column mb-sm-auto mb-0 align-items-sm-start px-3" id="menu">
                <li class="">
                  <a href="#" class="nav-link align-middle ">
                    <i class="bi  bi-person"></i>Dashboard
                  </a>
                </li>

                <li class="">
                  <a href="tambah-petugas.php" class="nav-link align-middle ">
                    <i class="bi  bi-person"></i>Tambah Petugas
                  </a>
                </li>

              </ul>
            </div>

          </div>
        </div>


        <!-- main content -->
        <main class="col p-0 main-cons">

          <header class="topbar">
            <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
              class="border fs-1  p-1 text-decoration-none text-light border1"><i class="bi bi-list bi-lg py-2 p-1 "></i>
            </a>

            <div class="flex-nowrap d-flex">
              <div class="profile ">
                <a class="me-4 d-flex align-items-center text-decoration-none text-light " href="#"> Halo
                  <?php echo $_SESSION['nama_admin'] ?><i class="bi bi-person-circle text-light ms-2"></i></a>

              </div>
              <a class=" fs-5 fw-medium align-items-center btn btn-danger" href="../config/_close.php"
                onclick="return confirm('Anda Yakin Ingin Keluar')"><i class="bi  bi-box-arrow-right p-1"></i>Keluar</a>
            </div>
          </header>

          <section class="riwayat-section">

            <div class="row">
              <h2 class="col-md-12">Banyak Petugas Yang Ada</h2>
              <p class="fs-6 "></p>
            </div>


            <div class="row">
              <div class="col-md-6">

                <div class="card text-light fs-4 bg-success mb-3">


                  <?php

                  $id = $_SESSION['id_admin'];

                  //ambil total surat
                  $Q_totalSurat = "select count(nama_dokumen) as banyakDOK from dokumens ";
                  $F_totalSurat = mysqli_query($conn, $Q_totalSurat);
                  $totalSurat = mysqli_fetch_assoc($F_totalSurat);

                  //ambil total Petugas
                  $Q_petuggas = "select count(nama_petugas) as banyakPET from petugas";
                  $F_petugas = mysqli_query($conn, $Q_petuggas);
                  $totalPET = mysqli_fetch_assoc($F_petugas);


                  ?>


                  <div class="card-body">

                    <h5 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i>Petugas</h5>

                    <p class="card-text fs-5">Total: <?= $totalPET['banyakPET'] ?></p>

                  </div>

                </div>

              </div>

              <div class="col-md-6">

                <div class="card bg-warning mb-3">

                  <div class="card-body">

                    <h5 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i>Surat Yang Diajukan</h5>

                    <p class="card-text fs-5">Total: <?= $totalSurat['banyakDOK'] ?></p>

                  </div>

                </div>

              </div>


            </div>


            <?php

            //ambil date 
            $query = "select date_format(tanggal, '%d %M %Y') as date, nama_dokumen,  status, ids_warga from dokumens where ids_warga = $id";
            $validasi = mysqli_query($conn, $query);


            //tampilkan data dari table dokumens
            if (mysqli_num_rows($validasi) > 0) {

            while ($row = mysqli_fetch_assoc($validasi)) {

            //ambil statu & dok 
            $get_status = $row['status'];
            $nama_dokumen = $row['nama_dokumen'];

            //rename
            if ($nama_dokumen == 'SKTM') {
            $dok_title = "Surat Keterangan Tidak Mampu";
            } elseif ($nama_dokumen == 'SKK') {
            $dok_title = "Surat Keterangan Kematian";
            } elseif ($nama_dokumen == 'SRM') {
            $dok_title = "Surat Rumah";
            } elseif ($nama_dokumen == 'SIU') {
            $dok_title = "Surat Izin Usaha";
            } else {
            $dok_title = "Surat Domisili";
            }

            //bagian aksi tombol & label
            if ($get_status == 'PENDING') {
            $status = "Menunggu Verifikasi";
            $aksi = "";
            $aksi_bs = "";
            $warna = "bg-warning text-black";
            $btn = "";

            } elseif ($get_status == "DISETUJUI") {
            $status = "Disetujui";
            $aksi = "Download";
            $aksi_bs = "bi-download";
            $warna = "bg-success";
            $btn = "btn btn-outline-light";


            } else {
            $status = "Ditolak";
            $aksi = "Lihat";
            $aksi_bs = "bi-eye";
            $warna = "bg-danger";
            $btn = "btn btn-outline-light";

            }
            echo '
            <div class="riwayat-list">


            <div class="riwayat-card mt-3">
            <div class="riwayat-info">
            <h5>' . $dok_title . '</h5>
            <p>Tanggal Pengajuan : ' . $row['date'] . '</p>
            <p>
            Status:
            <span class="status ' . $warna . ' ">' . $status . '</span>
            </p>
            </div>
            <div class="riwayat-actions">
            <button class="' . $btn . '">
            <i class="bi ' . $aksi_bs . '"></i> ' . $aksi . '
            </button>
            </div>
            </div>
            </div>

            ';
            }
            }
            ?>

          </section>
          <footer class="container-fluid">
            <p>AJUK - Copyright © 2025. All rights reserved.</p>
          </footer>
        </main>
      </div>

    </div>

  </body>
  <script src="../bootstrap/js/bootstrap.min.js"></script>

</html>
