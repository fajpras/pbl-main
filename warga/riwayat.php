<?php 
//link
include ('../config/auth.php') ;
include "../config/kol.php";
//err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//alert
if(isset($_GET['note'])){
  echo "<script>alert('Data Berhasil di simpan')</script>";
}

$id = $_SESSION['id_warga'];
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ajukan Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/riwayat.css" />

    <style>
    /* Overlay Gelap */
    .popup-overlay {
      visibility: hidden;
      opacity: 0;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1050;
      transition: all 0.3s ease;
    }

    /* Kotak Pop-up */
    .popup-box {
      visibility: hidden;
      opacity: 0;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0.8);
      width: 400px;
      max-width: 90%;
      background: #fff;
      color: #333;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      z-index: 1060;
      transition: all 0.3s ease;
    }

    /* Efek Aktif */
    .popup-overlay.active, .popup-box.active {
      visibility: visible;
      opacity: 1;
    }
    .popup-box.active {
      transform: translate(-50%, -50%) scale(1);
    }

    .popup-header {
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }
    .btn-close-popup {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
    }
    .detail-row {
      margin-bottom: 8px;
    }
    .label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
    }
    .reason-text {
      background:#cdd1d3ff;
      padding: 10px;
      border-left: 4px solid #ffc107;
      border-radius: 4px;
    }
    .reason-text-nama {
      background: #cdd1d3ff;
      padding: 10px;
      width:100%;
      border-left: 4px solid #9e8025ff;
      border-radius: 4px;
    }
    </style>
  </head>

  <body>

    <div class="container-fluid ">
      <div class="row flex-nowrap ">
        <div class="col-auto px-0 sidebar ">
          <div id="sidebar" class="collapse collapse-horizontal show  ">
            <div class="d-flex container-fluid ">
              <a href="dashboard.html" class="text-black text-decoration-none">
                <img class="logo p-3" src="../assets/logo.svg" alt="logo" />
              </a>
              <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
                class="border fs-1  p-1 text-decoration-none text-light align-items-center border1"><i class="bi bi-x bi-lg py-2 p-1 "></i> 
              </a>
            </div>

            <hr />
            <div id="sidebar-nav" class="list-group border-0 rounded-0 text-sm-start min-vh-100">
              <ul class="nav menu  fw-medium nav-pills flex-column mb-sm-auto mb-0 align-items-sm-start px-3"
                id="menu">
                <li><a href="profil.php" class="nav-link align-middle "><i class="bi  bi-person"></i>Profil</a></li>
                <li><a href="../surat/surat-SKTM.php" class="nav-link "><i class="bi  bi-coin"></i>SKTM</a></li>
                <li><a href="../surat/surat-SKK.php" class="nav-link"><i class="bi  bi-file-person"></i>SKK</a></li>
                <li><a href="../surat/surat-rumah.php" class="nav-link"><i class="bi  bi-house"></i>Surat Rumah</a></li>
                <li><a href="../surat/surat-izin-usaha.php" class="nav-link"><i class="bi  bi-briefcase"></i>Izin Usaha</a></li>
                <li><a href="../surat/surat-domisili.php" class="nav-link"><i class="bi  bi-geo-alt"></i>Domisili</a></li>
              </ul>
            </div>
          </div>
        </div>


        <main class="col p-0 main-cons">

          <header class="topbar">
            <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
              class="border fs-1  p-1 text-decoration-none text-light border1"><i class="bi bi-list bi-lg py-2 p-1 "></i> 
            </a>
            <div class="profile">
              <a class="d-flex align-items-center text-decoration-none text-light " href="profil.php"> Halo, <?php echo $_SESSION['nama'] ?><i class="bi bi-person-circle text-light ms-2"></i></a>
            </div>
          </header>

          <section class="riwayat-section">
            <div class="row">
              <h2 class="col-md-12">Riwayat Pengajuan Anda</h2>
            </div>

            <?php 
            $Q_cekDok = mysqli_query($conn, "select * from dokumens where id_warga = $id");
            if(mysqli_num_rows($Q_cekDok) == 0 ){
            echo '<div class="riwayat-list">
            <div class="riwayat-card mt-3">
            <div class="riwayat-info">
            <h5>Anda Belum Ada Mengajukan Dokumen</h5>
            </div>
            </div>
            </div>';
            }
            ?>

            <?php

            //list dokumens 
            $query = "select *, date_format(tanggal, '%d %M %Y') as date, nama_dokumen, status, id_warga, komentar from dokumens where id_warga = $id";
            $validasi = mysqli_query($conn, $query);

            //tampil isi dokumens
            if(mysqli_num_rows($validasi) > 0){

            while ($row = mysqli_fetch_assoc($validasi)) {

            $get_status = $row['status'];
            $id_surat = $row['id_surat'];
            $nama_dokumen = $row['nama_dokumen'];
            $nama_petugas = $row['nama_petugas'];              
            $alasan = isset($row['komentar']) ? $row['komentar'] : 'Tidak ada keterangan.';

            // Judul Dokumen
            if($nama_dokumen == 'SKTM') $dok_title = "Surat Keterangan Tidak Mampu";
            elseif ($nama_dokumen == 'SKK') $dok_title = "Surat Keterangan Kematian";
            elseif ($nama_dokumen == 'SRM') $dok_title = "Surat Rumah";
            elseif ($nama_dokumen == 'SIU') $dok_title = "Surat Izin Usaha";
            else $dok_title = "Surat Domisili";

            // Tombol
            $tombol_aksi = ""; 


            //PENDING
            if ($get_status == 'PENDING' ){
            $status_label = "Menunggu Verifikasi";
            $warna = "bg-warning text-black";
            $tombol_aksi = '<button class="btn btn-secondary btn-sm" disabled>Diproses</button>';


            //DISETUJUI
            }elseif ($get_status == 'DISETUJUI' ){
            $status_label = "Menunggu Verifikasi";
            $warna = "bg-warning text-black";
            $tombol_aksi = '<button class="btn btn-secondary btn-sm" disabled>Diproses</button>';

            //SELESAI
            } elseif ($get_status == "SELESAI") {
            $status_label = "Disetujui";
            $warna = "bg-success";
            $link = "../kol.php?id=$id_surat&dok=$nama_dokumen&idw=$id";
            $tombol_aksi = '<a href="'.$link.'" class="btn btn-outline-light btn-sm"> 
            <i class="bi bi-download"></i> Download 
            </a>';

            } else {
            //DITOLAK
            $status_label = "Ditolak";
            $warna = "bg-danger";
            $tombol_aksi = '<button type="button" 
            class="btn btn-outline-light btn-sm"
            onclick="openReasonPopup(this)"
            data-judul="'.$dok_title.'"
            data-tgl="'.$row['date'].'"
            data-alasan="'.$alasan.'"
            data-nama="'.$nama_petugas.'">
            <i class="bi bi-eye"></i> Lihat Alasan
            </button>';
            }

            echo '
            <div class="riwayat-list">
            <div class="riwayat-card mt-3">
            <div class="riwayat-info">
            <h5>'.$dok_title.'</h5>
            <p>Tanggal Pengajuan : ' . $row['date'] . '</p>
            <p>
            Status: <span class="status '.$warna.' ">' . $status_label . '</span>
            </p>
            </div>
            <div class="riwayat-actions">
            '.$tombol_aksi.'
            </div>
            </div>
            </div>';
            } 
            }
            ?>

          </section>

          <footer class="container-fluid" >
            <p>AJUK - Copyright © 2025. All rights reserved.</p>
          </footer>
        </main>
      </div>
    </div>

    <div class="popup-overlay" id="reasonOverlay" onclick="closeReasonPopup()"></div>
    <div class="popup-box" id="reasonPopup">
      <div class="popup-header">
        <h5 style="margin:0">Detail Penolakan</h5>
        <button class="btn-close-popup" onclick="closeReasonPopup()">&times;</button>
      </div>
      <div class="popup-content">
        <div class="detail-row">
          <span class="label">Jenis Dokumen:</span>
          <span id="pop-judul" style="font-weight: normal;">-</span>
        </div>
        <div class="detail-row">
          <span class="label">Tanggal Ajuan:</span>
          <span id="pop-tgl" style="font-weight: normal;">-</span>
        </div>
        <div class="detail-row">
          <span class="label" style="color:#dc3545">Nama Petugas:</span>
          <div id="pop-nama" class="reason-text-nama">-</div>
        </div>
        <hr>
        <div class="detail-row">
          <span class="label" style="color:#dc3545">Alasan Penolakan:</span>
          <div id="pop-alasan" class="reason-text">
            -
          </div>
        </div>
      </div>
    </div>

    <script src="../bootstrap/js/bootstrap.min.js"></script>

    <script>
    //POP UP ALASAN
    const overlay = document.getElementById('reasonOverlay');
    const popup = document.getElementById('reasonPopup');


    const txtJudul = document.getElementById('pop-judul');
    const txtTgl = document.getElementById('pop-tgl');
    const txtAlasan = document.getElementById('pop-alasan');
    const namas = document.getElementById('pop-nama');

    function openReasonPopup(btn) {
      const judul = btn.getAttribute('data-judul');
      const tgl = btn.getAttribute('data-tgl');
      const alasan = btn.getAttribute('data-alasan');
      const nama = btn.getAttribute('data-nama');

      txtJudul.innerText = judul;
      txtTgl.innerText = tgl;
      txtAlasan.innerText = alasan;
      namas.innerText = nama;


      overlay.classList.add('active');
      popup.classList.add('active');
    }

    function closeReasonPopup() {
      overlay.classList.remove('active');
      popup.classList.remove('active');
    }
    </script>

  </body>
</html>
