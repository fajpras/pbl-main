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
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Petugas - AJUK</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/petugas-riwayat.css" />

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
      background: #f6f6f6ef;
      color: #333;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      z-index: 1060;
      transition: all 0.3s ease;
    }

    /* Efek Aktif (Muncul) */
    .popup-overlay.active, .popup-box.active {
      visibility: visible;
      opacity: 1;
    }
    .popup-box.active {
      transform: translate(-50%, -50%) scale(1);
    }

    /* Header Pop-up */
    .popup-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #070707ff;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    .popup-title {
      font-size: 18px;
      font-weight: bold;
      margin: 0;
      color: #000;
    }
    .btn-close-popup {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #999;
    }
    .btn-close-popup:hover { color: #d9534f; }

    /* Isi Detail */
    .detail-row {
      display: flex;
      margin-bottom: 10px;
      font-size: 14px;
      text-align: left;
    }
    .label {
      width: 100px;
      font-weight: bold;
      color: #555;
      flex-shrink: 0;
    }
    .value {
      flex-grow: 1;
      color: #000;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row flex-nowrap">
      <div class="col-auto px-0 sidebar ">
        <div id="sidebar" class="collapse collapse-horizontal show  ">
          <div class="d-flex container-fluid">
            <a class="text-black text-decoration-none">
              <img class="logo p-3" src="../assets/logo.svg" alt="logo" />
            </a>
            <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
              class="border fs-1  p-1 text-decoration-none text-light align-items-center border1"><i
                class="bi bi-x bi-lg py-2 p-1 "></i>
            </a>
          </div>
          <hr />
          <div id="sidebar-nav" class="list-group border-0 rounded-0 text-sm-start min-vh-100">
            <ul class="menu fs-6 fw-medium ">
              <li><a href="petugas-pending.php"><i class="bi bi-hourglass "></i> Dokumen Pending</a></li>
              <li><a href="petugas-disetujui.php"><i class="bi bi-clipboard2-check "></i> Dokumen Disetujui</a></li>
              <li><a href="petugas-riwayat.php"><i class="bi bi-clock-history"></i> Riwayat Pemeriksaan</a></li>
              <li><a href="petugas-profil.php"><i class="bi bi-person"></i> Profil</a></li>
            </ul>
          </div>
        </div>
      </div>

      <main class="col p-0">
        <section class="main-content">
          <header class="topbar">
            <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
              class="border fs-1  p-1 text-decoration-none text-light border1"><i
                class="bi bi-list bi-lg py-2 p-1 "></i>
            </a>
            <a class="fs-7  fw-medium align-items-center btn btn-danger" href="../config/_close.php" onclick="return confirm('anda yakin ingin keluar')"> 
              <i class="bi bi-box-arrow-right p-1"></i>Keluar
            </a>
          </header>

          <div class="pengajuan-section">
            <div class="content">
              <div class="content-overlay">
                <h2 class="fw-medium mb-4 text-start">Riwayat Pengajuan Dokumen Warga</h2>
                <div class="table-responsive">
                  <table class="table table-hover align-middle text-white">
                    <thead>
                      <tr>
                        <th class="bg-light text-black">No</th>
                        <th>Nama Warga</th>
                        <th>Jenis Dokumen</th>
                        <th>Tanggal Diajukan</th>
                        <th>Status</th>
                        <th>Pada</th>
                        <th>Alasan</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $n = 1;
                      $id = $_SESSION['id_petugas'];
                      //tampilkan data dari table dokumens dengan status dan label warna
                      $Q_riwayat = "select *, date_format(tanggal, '%d %M %Y ') as tgl, date_format(pada, '%d-%m-%Y <br>%h:%i:%s') as kpn from dokumens where id_petugas = $id and status='SELESAI' or status='DITOLAK'  " ;
                      $F_riwatat = mysqli_query($conn, $Q_riwayat);

                      //tampilkan data list
                      if(mysqli_num_rows($F_riwatat) > 0){
                        while ($row = mysqli_fetch_assoc($F_riwatat)) {
                          
                          if ($row['status'] == "PENDING" ){
                            $bg = "bg-warning text-black rounded-4 px-2";
                            $dis ="disabled";

                            // selesai
                          } elseif($row['status'] == "SELESAI" ){
                            $bg = "bg-success rounded-4 px-2";
                            $dis ="";

                            //disetujui
                          } elseif ($row['status'] == "DISETUJUI" ) {
                            $bg = "bg-success text-black rounded-4 px-2";
                            $dis ="";
                          //ditolak
                          }else{
                            $bg = "bg-danger rounded-4 px-2";
                            $dis ="";
                          }

                          $alasan = isset($row['komentar']) ? $row['komentar'] : '-'; 

                          echo '
                          <tr>
                            <td class="bg-light text-black">'.$n++.'</td>
                            <td>'.$row['nama_warga'].'</td>
                            <td>'.$row['nama_dokumen'].'</td>
                            <td>'.$row['tgl'].'</td>
                            <td class=""><label class="'.$bg.'">'.$row['status'].'</label></td>
                            <td>'.$row['kpn'].'</td>
                            <td>
                              <button type="button" '.$dis.'
                                      class="btn btn-sm btn-warning  text-black fw-bold"
                                      onclick="openPopup(this)"
                                      data-nama="'.$row['nama_petugas'].'"
                                      data-kapan="'.$row['kpn'].'"
                                      data-alasan="'.$alasan.'">
                                <i class="bi bi-eye"></i> Cek
                              </button>
                            </td>
                          </tr>';
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
        </section>
      </main>
    </div>
  </div>


  <!-- pop up alasan penolakan -->
  <div class="popup-overlay" id="overlay" onclick="closePopup()"></div>
  <div class="popup-box" id="popup">
    <div class="popup-header">
      <h3 class="popup-title">Detail Validasi</h3>
      <hr>
      <button class="btn-close-popup" onclick="closePopup()">&times;</button>
    </div>
    
    <div class="popup-content">
      <div class="detail-row">
        <span class="label">Nama Petugas</span>
        <span class="value">: <span id="modal-nama"></span></span>
      </div>
      <div class="detail-row">
        <span class="label">Waktu</span>
        <span class="value">: <span id="modal-kapan"></span></span>
      </div>
      <div class="detail-row">
        <span class="label">Alasan</span>
        <span class="value">: <span id="modal-alasan"></span></span>
      </div>
    </div>
  </div>

  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <script>
    // Ambil elemen
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('popup');
    
    // Element text dalam popup
    const txtNama = document.getElementById('modal-nama');
    const txtKapan = document.getElementById('modal-kapan');
    const txtAlasan = document.getElementById('modal-alasan');

    // Fungsi Buka Popup
    function openPopup(btn) {
      const nama = btn.getAttribute('data-nama');
      const kapan = btn.getAttribute('data-kapan'); 
      const alasan = btn.getAttribute('data-alasan');

      // Masukkan ke dalam popup
      txtNama.textContent = nama;
      txtKapan.innerHTML = kapan; 
      txtAlasan.textContent = alasan;

      // Munculkan
      overlay.classList.add('active');
      popup.classList.add('active');
    }

    // Fungsi Tutup Popup
    function closePopup() {
      overlay.classList.remove('active');
      popup.classList.remove('active');
    }
  </script>

</body>
</html>
