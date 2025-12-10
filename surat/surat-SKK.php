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
  <title>Pengajuan Surat SKK</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/surat-SKK.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-dark bg-dark fixed-top" id="navigasi">
    <div class="container-fluid justify-content-between">
      <div class="logo d-flex align-items-center ">
        <a href="../warga/riwayat.php" class="text-black">
          <i class="fs-2 px-3 fw-bold text-light bi bi-arrow-left-circle"></i>
        </a>
        <img src="../assets/logo.svg" alt="logo">
      </div>

      <div class="profile">
        <a class="d-flex align-items-center text-decoration-none text-light fw-bold" href="../warga/profil.php">
        Halo, <?php echo $_SESSION['nama']?><i class="bi bi-person-circle text-light ms-2"></i>
        </a>
      </div>
    </div>
  </nav>

  <!-- MAIN FORM -->
  <main class="dokumen1">
    <div class="container py-5">
      <div class="container form-container">
        <div class="text-center py-5">
          <h2 class="mb-3 mt-3 fw-bold text-light">SURAT KETERANGAN KEMATIAN</h2>
        </div>

        <div class="card">
          <div class="card-body p-4">
          <?php

            //ambil nik dan nama dari table data_diri
            $id = $_SESSION['id_warga'];
            $dari_data_diri = mysqli_query($conn, "SELECT nik, 
            nama_lengkap from data_diri where id_warga = $id");
              $data_diri = mysqli_fetch_assoc($dari_data_diri);?>
            <form action="../surat-config/_proses-skk.php" method="post" enctype="multipart/form-data">

              <!-- DATA IDENTITAS JENAZAH -->
              <div class="row mb-3">

                <div class="col-md-6">
                  <label for="nik" class="form-label mt-3">Nomor Induk Kependudukan (NIK)</label>
                  <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required >
                </div>

                <div class="col-md-6">
                  <label for="namaLengkap" class="form-label mt-3">Nama Lengkap</label>
                  <input type="text" class="form-control" id="namaLengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required >
                </div>

              </div>

              <div class="row mb-3">

                <div class="col-md-6">
                  <label for="kelamin" class="form-label mt-3">Jenis Kelamin</label>
                  <select class="form-select" id="kelamin" name="jenis_kelamin" required>
                    <option aria-readonly="apa" selected>Jenis Kelamin</option>
                    <option value="Laki-Laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                  </select>
                </div>

                <div class="col-md-6">
                    <label for="pekerjaan" class="form-label mt-3">Pekerjaan</label>
                    <input type="text" class="form-control" name="pekerjaan">
                  </div>

                <div class="col-md-12 mt-5">
                  <label for="alamat" class="form-label fw-bold">Alamat</label>
                  <textarea class="form-control" id="alamat" name="alamat" rows="5" placeholder="Masukkan alamat"
                    required></textarea>
                </div>

              </div>

              <!-- DATA KEMATIAN -->
              <div class="row mb-3">

                <div class="col-md-6">
                  <label for="penyebab" class="form-label mt-3">Penyebab Kematian</label>
                  <input type="text" class="form-control" id="penyebab" name="penyebab"
                    placeholder="Masukkan penyebab kematian" required>
                </div>

                <div class="col-md-6">
                  <label for="tanggal_kematian" class="form-label mt-3">Tanggal Kematian</label>
                  <input type="date" class="form-control" id="tanggal_kematian" name="tanggal_kematian" required>
                </div>

              </div>

              <!-- BERKAS ADMIN -->
              <h6 class="mb-3 fw-bold mt-3">BERKAS ADMINISTRASI</h6>
              <p class="form-text mb-3 text-light">
                Jenis file harus JPG/JPEG/PNG maksimal 1MB
              </p>

              <div class="row">

                <div class="col-md-6 form-file-group mt-3">
                  <label for="skr" class="form-label">Surat Keterangan Rumah Sakit</label>
                  <input class="form-control" type="file" id="skr" name="surat_rumah_sakit"
                    accept=".jpg,.jpeg,.png,.pdf" required>
                </div>

                <div class="col-md-6 form-file-group mt-3">
                  <label for="ktp_pelapor" class="form-label">KTP Pelapor</label>
                  <input class="form-control" type="file" id="ktp_pelapor" name="ktp_pelapor"
                    accept=".jpg,.jpeg,.png,.pdf" required>
                </div>

              </div>

              <div class="row">

                <div class="col-md-6 form-file-group mt-3">
                  <label for="pengantar" class="form-label">Surat Pengantar RT/RW</label>
                  <input class="form-control" type="file" id="pengantar" name="surat_pengantar"
                    accept=".jpg,.jpeg,.png,.pdf" required>
                </div>

                <div class="col-md-6 form-file-group mt-3">
                  <label for="akte_nikah" class="form-label">Akte Nikah Alm (Jika Ada)</label>
                  <input class="form-control" type="file" id="akte_nikah" name="akte_nikah"
                    accept=".jpg,.jpeg,.png,.pdf">
                </div>

              </div>

              <!-- SUBMIT -->
              <div class="text-end">
                <button type="submit" class="btn btn-success mt-5 px-4">
                  Kirim Permohonan
                </button>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../bootstrap/js/bootstrap.min.js"></script>

</body>
</html>

