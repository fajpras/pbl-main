<?php
// err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//link
include "../config/kol.php";
include "../config/auth.php";

?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Surat Domisili</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- Removed bootstrap-responsive.min.css: file does not exist -->
    <link rel="stylesheet" href="../css/surat-domisili.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  </head>

  <body>
    <!-- navbar -->
    <nav class="navbar navbar-dark bg-dark fixed-top" id="navigasi">
      <div class="container-fluid justify-content-between">
        <div class="logo d-flex align-items-center ">
          <a href="../warga/riwayat.php" class=" text-black"><i
            class="fs-2 px-3 fw-bold text-light bi bi-arrow-left-circle"></i>
          </a>
          <img src="../assets/logo.svg" alt="logo">

        </div>
        <div class="profile">
          <a class="d-flex align-items-center text-decoration-none text-light fw-bold " href="../warga/profil.php"> Halo, <?= $_SESSION['nama']?><i
            class="bi bi-person-circle text-light ms-2 "></i></a>
        </div>
      </div>
    </nav>
    <main class="dokumen1">
      <div class="container py-5">
        <div class="container form-container">
          <div class="text-center py-5">
            <h2 class="mb-3 mt-3 fw-bold text-light">SURAT PINDAH DOMISILI</h2>
          </div>
          <div class="card">
            <div class="card-body p-4">
              <?php

              //ambil nik dan nama dari table data_diri
              $id = $_SESSION['id_warga'];
              $dari_data_diri = mysqli_query($conn, "SELECT nik, nama_lengkap from data_diri where id_warga = $id");
              $data_diri = mysqli_fetch_assoc($dari_data_diri);

              ?>
              <form action="../surat-config/_proses-domisili.php" method="POST" enctype="multipart/form-data">

                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="nik" class="form-label fw-bold">Nomor Induk Kependudukan (NIK)</label>
                    <input type="text" class="form-control" name="nik" id="nik" placeholder="Masukkan NIK" value="<?= $data_diri['nik']?>" readonly required />
                  </div>
                  <div class="col-md-6">
                    <label for="namaLengkap"  class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" id="namaLengkap" placeholder="Masukkan nama lengkap" value="<?= $data_diri['nama_lengkap']?>" required readonly/>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="agama" class="form-label fw-bold">Agama</label>
                    <select class="form-select fw-medium" name="agama" id="agama" required>
                      <option selected aria-readonly="agama" >Agama</option>
                      <option value="1">Islam</option>
                      <option value="2">Kristen</option>
                      <option value="3">Katholik</option>
                      <option value="4">Buddha</option>
                      <option value="5">Hindu</option>
                      <option value="6">Konghucu</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="Pekerjaan" class="form-label fw-bold">Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="form-control" id="pekerjaan" placeholder="Pekerjaan" required>

                  </div>
                  <div class="col-md-12 mt-5 ">
                    <label for="alamat" class="form-label fw-bold">Alamat</label>
                    <textarea name="alamat" cols="5" rows="5" class="form-control" placeholder="Masukkan alamat" id="alamat"
                      required></textarea>
                  </div>

                </div>



                <h6 class="mb-3 fw-bold mt-3">BERKAS ADMINISTRASI</h6>
                <p class="form-text form-text-small mb-3 text-light fw-medium">
                  Jenis Gambar yang dapat diunggah adalah JPG/JPEG/PNG dengan kapasitas maksimum 1Mb
                </p>

                <div class="row">
                  <div class="col-md-6 form-file-group">
                    <label for="foto" class="form-label fw-bold">Pas Foto</label>
                    <input class="form-control" type="file" name="foto_pas" id="foto" accept=".jpg,.jpeg,.png,.pdf" required />
                  </div>
                  <div class="col-md-6 form-file-group">
                    <label for="surat" class="form-label fw-bold">Surat Pengantar Dari RT/RW</label>
                    <input class="form-control" type="file" name="foto_surat_pengantar" id="surat" accept=".jpg,.jpeg,.png,.pdf" required />
                  </div>
                  <div class="col-md-6 form-file-group ">
                    <label for="kk" class="form-label fw-bold">Kartu Keluarga (KK)</label>
                    <input class="form-control" type="file" name="foto_kk" id="kk" accept=".jpg,.jpeg,.png,.pdf" required />
                  </div>

                </div>



                <button type="submit" class="btn mt-5 px-4 btn-success float-end" >
                  Kirim Permohonan
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>




    </main>

  </body>
  <script>

  </script>
  <script src="../bootstrap/js/bootstrap.min.js"></script>

</html>
