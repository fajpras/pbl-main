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
  <title>Dashboard Admin | AJUK</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/dashboard-admin.css" />

  <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fc; /* Background halaman lebih soft */
    }

    /* --- STYLE BARU UNTUK CARD STATISTIK --- */
    .stat-card {
        border: none;
        border-radius: 15px;
        padding: 25px;
        color: white;
        position: relative;
        overflow: hidden; /* Agar icon background tidak keluar */
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }

    .bg-gradient-success {
        background: linear-gradient(45deg, #1cc88a, #13855c);
    }

    .stat-card h5 {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .stat-card h2 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0;
    }

    /* Icon besar di background */
    .stat-icon-bg {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.15;
        color: white;
    }

    /* --- STYLE BARU UNTUK CARD RIWAYAT --- */
    .doc-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 5px solid #ddd; /* Border default */
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .doc-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        background-color: #fafafa;
    }

    /* Warna border kiri berdasarkan status */
    .border-pending { border-left-color: #f6c23e !important; }
    .border-success { border-left-color: #1cc88a !important; }
    .border-danger { border-left-color: #e74a3b !important; }

    .doc-info h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .doc-meta {
        font-size: 0.85rem;
        color: #888;
    }

    .doc-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-block;
    }
    
    .status-badge-pending { background-color: #fff3cd; color: #856404; }
    .status-badge-success { background-color: #d4edda; color: #155724; }
    .status-badge-danger { background-color: #f8d7da; color: #721c24; }

    .btn-action {
        border-radius: 50px;
        padding: 6px 15px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

  </style>
</head>

<body>

  <div class="container-fluid ">
    <div class="row flex-nowrap ">
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

        <section class="riwayat-section p-4">

          <div class="row mb-4">
            <h3 class="col-md-12 font-weight-bold" style="color: #fff; font-weight:bold;">Dashboard Overview</h3>
          </div>

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

          <div class="row mb-5">
            <div class="col-md-6 mb-3">
              <div class="stat-card bg-gradient-primary">
                <div class="stat-content">
                    <h5>Total Petugas</h5>
                    <h2><?= $totalPET['banyakPET'] ?></h2>
                </div>
                <div class="stat-icon-bg">
                    <i class="fas fa-users"></i>
                </div>
              </div>
            </div>

            <div class="col-md-6 mb-3">
               <div class="stat-card bg-gradient-success">
                <div class="stat-content">
                    <h5>Surat Diajukan</h5>
                    <h2><?= $totalSurat['banyakDOK'] ?></h2>
                </div>
                <div class="stat-icon-bg">
                    <i class="fas fa-file-alt"></i>
                </div>
              </div>
            </div>
          </div>
        

          <div class="row">
            <div class="col-md-12">
            <?php

            //ambil date 
       
            ?>
            </div>
          </div>
          </section>
        <footer class="container-fluid mt-4">
          <p class="text-center text-muted small">AJUK - Copyright © 2025. All rights reserved.</p>
        </footer>
      </main>
    </div>

  </div>

</body>
<script src="../bootstrap/js/bootstrap.min.js"></script>

</html>
