<!DOCTYPE html>
<html lang="en">
  <head>


    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>AJUK - Ajukan Dokumen</title>

    <!-- ============LINK============= -->
    <link rel="stylesheet" href="bootstrap\css\bootstrap.min.css" />
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/styles.css" />


  </head>

  <body>

    <!-- bagian navigasi -->
    <nav class="navbar   navbar-expand-lg  fixed-top" id="navigasi">
      <div class="container-fluid  ">
        <a class="logo navbar-brand"><img src="./assets/logo.svg" alt="logo" /></a>

        <button class=" navbar-toggler btn1" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
          aria-label="Toggle navigation">
          <img src="./assets/nav_menu.svg" class=" navMenu" alt="" srcset="">
        </button>

        <div class=" collapse kol navbar-collapse justify-content-end" id="navbarSupportedContent">
          <ul class="navbar-nav  mb-lg-0">
            <li class="nav-item">
              <a class="nav-link  text-light" aria-current="page" href="#home">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link  text-light" href="login.php">Ajukan Dokumen</a>
            </li>
            <li class="nav-item">
              <a class="nav-link  text-light" href="login.php">Lacak Pengajuan</a>
            </li>
            <li class="nav-item " style="padding-right:12px;">
              <a class="nav-link  text-light " href="login.php">Masuk</a>
            </li>
            <li class="nav-item  ">
              <a class="nav-link btn btn-light tombol active fw-bold ml-3 p-2 " href="daftar.php">Daftar</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- ===========CONTENT=========== -->
    <main>
      <section class="dashboard-content" id="home">
        <div class="dashcont ">
          <div class="wrap-dash">
            <h1>Standar pelayanan publik dan SOP pelayanan AJUK</h1>
            <p class="mt-">
              Sistem aplikasi pengajuan dokumen warga berbasis web ini memudahkan
              pengurusan dokumen di tingkat kelurahan, memungkinkan warga
              mengajukan secara online, petugas memverifikasi langsung, serta
              menghasilkan nomor surat otomatis, sementara dokumen RT/RW atau di
              atasnya tetap diurus manual.
            </p>

            <a href="login.php" class="btn btn-light fw-bold tombolMasuk ">Ajukan Dokumen</a>
          </div>
        </div>

        <?php?>
      </section>

      <!-- jenis dari dokuemn -->
      <section class="layanan" >
        <div class="layanan-dashboard">
          <div class="wrap-layanan wrap-container">
            <h2>Layanan Yang Kami Sediakan</h2>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="login.php">Surat Keterangan Tidak Mampu</a>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="login.php">Surat Domisili</a>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="login.php">Surat Keterangan Kematian</a>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="login.php">Surat Izin Usaha</a>
            <a class="fw-bold fs-6 btn btn-light mt-4 p-1" href="login.php">Surat Kepemilikan Rumah</a>
          </div>
        </div>
      </section>
    </main>

    <footer>
      <p class="fw-bold ">AJUK - Copyright © 2025. All rights reserved.</p>
    </footer>
  </body>
  <script src="./bootstrap/js/bootstrap.min.js"></script>

</html>
