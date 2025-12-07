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
  <title>Pengajuan Surat Rumah</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
  <link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/surat-rumah.css" />
</head>

<body>
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
          <h2 class="mb-3 mt-3 fw-bold text-light">SURAT KEPEMILIKAN RUMAH</h2>
        </div>
        <div class="card">
        <div class="card-body p-4"> 


<?php

//ambil nik dan nama dari table data_diri
$id = $_SESSION['id_warga'];
$dari_data_diri = mysqli_query($conn, "SELECT nik, nama_lengkap from data_diri where id_warga = $id");
  $data_diri = mysqli_fetch_assoc($dari_data_diri);

?>
            <form action="../surat-config/_proses-rumah.php" enctype="multipart/form-data" method="POST">
        
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="nik" class="form-label">Nomor Induk Kependudukan (NIK)</label>
                  <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required value="<?= $data_diri['nik']?>" readonly>
                </div>
                <div class="col-md-6">
                  <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" id="namaLengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required value="<?= $data_diri['nama_lengkap']?>" readonly>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="kecamatan" class="form-label">Kecamatan</label>
                  <select name="kecamatan" class="form-select" id="kecamatan">
                    <option selected aria-readonly="apa">Pilih Kecamatan</option>
                    <option value="1">Kecamatan 1</option>
                    <option value="2">Kecamatan 2</option>
                    <option value="3">Kecamatan 3</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="desa" class="form-label">Desa / Kelurahan</label>
                  <select name="desa" class="form-select" id="desa">
                    <option selected aria-readonly="apa">Pilih Desa/Kelurahan</option>
                    <option value="1">Desa 1</option>
                    <option value="2">Desa 2</option>
                    <option value="3">Desa 3</option>
                  </select>
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

              <div class="row">
                <div class="col-md-6 form-file-group">
                  <label for="suratPernyataan" class="form-label">Sertifikat Tanah</label>
                  <input class="form-control" type="file" id="suratPernyataan" name="foto_sertifikat" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
                <div class="col-md-6 form-file-group">
                  <label for="suratPengantar" class="form-label">Akta Jual-Beli/Ijin Mendirikan Bangunan</label>
                  <input class="form-control" type="file" id="suratPengantar" name="foto_akta_mendirikan" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-file-group">
                  <label for="kk" class="form-label">Kartu Keluarga (KK)</label>
                  <input name="foto_kk" class="form-control" type="file" id="kk" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
                <div class="col-md-6 form-file-group">
                  <label for="ktp" class="form-label">Kartu Tanda Penduduk</label>
                  <input class="form-control" type="file" id="ktp" name="foto_ktp" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-file-group">
                  <label for="pembayaranpajak" class="form-label">Bukti Pembayaran Pajak Bumi dan Bangunan</label>
                  <input class="form-control" type="file" id="pembayaranpajak" name="foto_BPPBB" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
                <div class="col-md-6 form-file-group">
                  <label for="surattidaksengketa" class="form-label">Surat Pernyataan Tidak Sengketa</label>
                  <input class="form-control" type="file" id="surattidaksengketa" name="foto_surat_tidak_sengketa" accept=".jpg,.jpeg,.png,.pdf" required>
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

<script src="../bootstrap/js/bootstrap.min.js"></script>


</html>
