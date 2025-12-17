
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengajuan Surat SKTM</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/surat-SKTM.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
      rel="stylesheet"
    />
  </head>
  <body>
    <nav class="navbar navbar-dark bg-dark fixed-top" id="navigasi">
      <div class="container-fluid justify-content-between">
        <div class="logo d-flex align-items-center ">
          <a onclick="history.back()" class=" text-black"><i
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
            <h2 class="mb-3 mt-3 fw-bold text-light">SURAT KETERANGAN TIDAK MAMPU</h2>
          </div>

          <div class="card">
            <div class="card-body p-4">

              <?php

              //ambil nik dan nama dari table data_diri
              $id = $_SESSION['id_warga'];
              $dari_data_diri = mysqli_query($conn, "SELECT nik, nama_lengkap from data_diri where id_warga = $id");
              $data_diri = mysqli_fetch_assoc($dari_data_diri);

              ?>
              <form action="../surat-config/_proses-sktm.php" method="POST" enctype="multipart/form-data">

                <div class="row mb-3 ">
                  <div class="col-md-6 ">
                    <label for="nik" class="form-label"
                    >Nomor Induk Kependudukan (NIK)</label
                    >
                    <input
                      value="<?= $data_diri['nik']?>" readonly
                      name="nik"
                      type="number"
                      class="form-control"
                      id="nik"
                      placeholder="Masukkan NIK"
                    >
                  </div>
                  <div class="col-md-6 ">
                    <label for="namaLengkap" class="form-label"
                    >Nama Lengkap</label
                    >
                    <input
                      value="<?= $data_diri['nama_lengkap']?>"
                      readonly
                      type="text"
                      name="nama_lengkap"
                      class="form-control"
                      id="namaLengkap"
                      placeholder="Masukkan nama lengkap"
                    >
                  </div>
                </div>

                <div class="row mb-3 ">
                  <div class="col-md-6">
                    <label for="agama" class="form-label">Agama</label>
                    <select class="form-select" id="agama" name="agama">
                      <option selected aria-readonly="agama">Agama</option>
                      <option value="Islam">Islam</option>
                      <option value="Kristen">Kristen</option>
                      <option value="Katholik">Katholik</option>
                      <option value="Buddha">Buddha</option>
                      <option value="Hindu">Hindu</option>
                      <option value="Konghucu">Konghucu</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="Pekerjaan" class="form-label">Pekerjaan</label>
                    <input type="text"  class="form-control" name="pekerjaan"></input>
                  </div>
                  <div class="col-md-12 mt-5 ">
                    <label for="alamat" class="form-label fw-bold">Alamat</label>
                    <textarea name="alamat" cols="5" rows="5" class="form-control" placeholder="Masukkan alamat" id="alamat"
                      required></textarea>
                  </div>

                </div>

                <h6 class="mb-3 fw-bold">BERKAS ADMINISTRASI</h6>
                <p class="form-text form-text-small mb-3 text-light">
                  Jenis Gambar yang dapat diunggah adalah JPG/JPEG/PNG dengan
                  kapasitas maksimum 1Mb
                </p>

                <div class="row mt-3">
                  <div class="col-md-6 form-file-group mt-3">
                    <label for="suratPernyataan" class="form-label fs-5 "
                    >Surat Pernyataan tidak mampu dari RT/RW</label
                    >
                    <input
                      class="form-control"
                      name="surat_tidak_mampu"

                      type="file"
                      id="suratPernyataan"
                      accept=".jpg,.jpeg,.png,.pdf"
                      required>
                  </div>
                  <div class="col-md-6 form-file-group mt-3">
                    <label for="fotorumah" class="form-label"
                    >Foto Rumah tempat tinggal</label
                    >
                    <input
                      class="form-control"
                      type="file"
                      name="fotorumah"
                      id="fotorumah"
                      accept=".jpg,.jpeg,.png,.pdf"
                      required>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-6 form-file-group mt-3">
                    <label for="kk" class="form-label"
                    >Kartu Keluarga (KK)</label
                    >
                    <input
                      class="form-control"
                      type="file"
                      id="kk"
                      name="fotokk"
                      accept=".jpg,.jpeg,.png,.pdf"
                      required>
                  </div>
                  <div class="col-md-6 form-file-group mt-3">
                    <label for="slip" class="form-label">Slip Gaji</label>
                    <input
                      class="form-control"
                      type="file"
                      name="fotoslip"            
                      id="slip"
                      accept=".jpg,.jpeg,.png"
                      required>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-6 form-file-group mt-3">
                    <label for="listrik" class="form-label"
                    >Tagihan listrik dan air</label
                    >
                    <input
                      class="form-control"
                      type="file"
                      name="fototagihan"              
                      id="listrik"
                      accept=".jpg,.jpeg,.png,.pdf"
                      required>
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
