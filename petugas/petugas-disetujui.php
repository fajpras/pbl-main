 <?php

//PAGE UNUTK MENAMPILKAN SURAT YANG DISETUJUI

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
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>halaman petugas - AJUK</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/petugas-disetujui.css" />
</head>

<body>
  <div class="container-fluid">
    <div class="row flex-nowrap">
      <!-- sidebar -->
      <div class="col-auto px-0 sidebar ">
        <div id="sidebar" class="collapse collapse-horizontal show  ">
          <!-- logo -->
          <div class="d-flex container-fluid">

            <a class="text-black text-decoration-none">
              <img class="logo p-3" src="../assets/logo.svg" alt="logo" />
            </a>
            <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
              class=" fs-1  p-1 text-decoration-none text-light align-items-center border1"><i
                class="bi bi-x bi-lg py-2 p-1 "></i>
            </a>
          </div>
          <hr />
          <!-- sidebar -->
          <div id="sidebar-nav" class="list-group border-0 rounded-0 text-sm-start min-vh-100">
            <ul class="menu fs-6 fw-medium ">
              <li>
                <a href="petugas-pending.php"><i class="bi bi-hourglass "></i> Dokumen Pending</a>
              </li>
              <li>
                <a href="petugas-disetujui.php"><i class="bi bi-clipboard2-check "></i> Dokumen Disetujui</a>
              </li>
              <li>
                <a href="petugas-riwayat.php"><i class="bi bi-clock-history"></i> Riwayat Pemeriksaan</a>
              </li>
              <li>
                <a href="petugas-profil.php"><i class="bi bi-person"></i> Profil</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <main class="col p-0">
        <!-- main -->
        <main class="main-content">
          <!-- header -->
          <header class="topbar">
            <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
              class=" fs-1  p-1 text-decoration-none text-light "><i class="bi bi-list bi-lg py-2 p-1 "></i>
            </a>
            <a class="fs-7  fw-medium align-items-center btn btn-danger" href="../config/_close.php" onclick="return confirm('anda yakin ingin keluar')"> <i
                class="bi bi-box-arrow-right p-1"></i>Keluar</a>
          </header>
          <!-- main content -->
          <div class="pengajuan-section">
            <div class="content">
              <div class="content-overlay">
                <h2 class="fw-medium mb-4 text-start">Validasi Dokumen Pengajuan Warga</h2>
                <div class="table-responsive">
                  <table class="table table-hover align-middle text-white">
                    <thead>
                      <tr>
                        <td class="bg-light text-black" width="5%">No</td>
                        <th width="">Nama Warga</th>
                        <th width="" class="align-middle d-flex">Jenis Dokumen</th>
                        <th width="">Tanggal Diajukan</th>
                        <th width="5% ">Status</th>
                        <th width="" class="">Kirim Hasil File Doc</th>
                      </tr>
                    </thead>
                    <!-- isi content -->
                    <tbody>

<?php
$n=1;

//ambil data dokumen yang di setujui
$query = "select date_format(tanggal, '%d %M %Y') as date, nama_dokumen,  status, ids_warga, nama_warga, id_surat from dokumens where status = 'DISETUJUI' order by date DESC";
$validasi = mysqli_query($conn, $query);


//tampilkan data yang disetujiu sesuai dengan nama
if(mysqli_num_rows($validasi) > 0){

  while ($row = mysqli_fetch_assoc($validasi)  ) {

    //validasu per dokumen
    $nama_dokumen = $row['nama_dokumen'];
    if($nama_dokumen == 'SKTM'){
      $dok_title = "Surat Keterangan Tidak Mampu";
    }elseif ($nama_dokumen == 'SKK') {
      $dok_title = "Surat Keterangan Kematian";
    }elseif ($nama_dokumen == 'SRM') {
      $dok_title = "Surat Rumah";
    }elseif ($nama_dokumen == 'SIU') {
      $dok_title = "Surat Izin Usaha";
    }else{
      $dok_title = "Surat Domisili";
    }


echo'
<tr>
<td class="bg-light text-black">'.$n++.'</td>
<td>'.$row['nama_warga'].'</td>
<td>'.$dok_title.'</td>
<td>'.$row['date'].'</td>
<td><div class="rounded-2 py-1 px-1 bg-success">'.$row['status'].'</div></td>
<td>


<a class ="me-1" onclick="return confirm(`Konfirmasi Kirim Surat`); " href="../petugas-config/_petugas-disetujui.php?id='.$row['id_surat'].'&dok='.$nama_dokumen.'" class=""><button class="btn-lihat bg-danger"> <i class="bi bi-send-fill me-1"></i> Kirim
</button></a>


<a class="" href="../kol.php?id='.$row['id_surat'].'&dok='.$nama_dokumen.'&idw='.$row['ids_warga'].'"><button class="btn-lihat btn btn-info"><i class="bi bi-eye me-2"></i>Lihat</button></a></td>

</tr>

';
  }
}
?>


</tbody>
                  </table>
                </div>
              </div>

              <footer>
                <p>AJUK - Copyright © 2025. All rights reserved.</p>
              </footer>
            </div>
          </div>

        </main>

      </main>
    </div>
  </div>

</body>
<script src="../bootstrap/js/bootstrap.min.js"></script>


</html>
