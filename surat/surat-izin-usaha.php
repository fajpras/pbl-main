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
    <title>Pengajuan Surat Izin Usaha</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/surat-izin-usaha.css" />
  </head>

  <body>
    <nav class="navbar navbar-dark bg-dark fixed-top" id="navigasi">
      <div class="container-fluid justify-content-between">
        <div class="logo d-flex align-items-center ">
          <a href="../warga/riwayat.php" class=" text-black">
            <i class="fs-2 px-3 fw-bold text-light bi bi-arrow-left-circle"></i>
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
            <h2 class="mb-3 mt-3 fw-bold text-light">SURAT IZIN USAHA</h2>
          </div>

          <div class="card">
            <div class="card-body p-4"> 

              <?php
              //ambil nik dan nama dari table data_diri
              $id = $_SESSION['id_warga'];
              $dari_data_diri = mysqli_query($conn, "SELECT nik, nama_lengkap from data_diri where id_warga = $id");
              $data_diri = mysqli_fetch_assoc($dari_data_diri);

              ?>
              <form action="../surat-config/_proses-izin-usaha.php" enctype="multipart/form-data" method="POST">

                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="nik" class="form-label">Nomor Induk Kependudukan (NIK)</label>
                    <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" value="<?= $data_diri['nik']?>" readonly required>
                  </div>
                  <div class="col-md-6">
                    <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" id="namaLengkap" placeholder="Masukkan nama lengkap" required value="<?= $data_diri['nama_lengkap']?>" readonly >
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="namakbli" class="form-label">Nama KBLI</label>
                    <input type="text" class="form-control" id="namakbli" name="nama_kbli" placeholder="Masukkan Nama KBLI" required>
                  </div>
                  <div class="col-md-6">
                    <label for="nomorkbli" class="form-label">Nomor KBLI</label>
                    <input type="text" class="form-control" name="nomor_kbli" id="nomorkbli" placeholder="Masukkan Nomor KBLI" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="kecamatan" class="form-label">Kecamatan</label>
                    <input placeholder="Masukkan Kecamatan" type="text" class="form-control" name="kecamatan">
                  </div>
                  <div class="col-md-6">
                    <label for="desa" class="form-label">Desa / Kelurahan</label>
                    <input placeholder="Masukkan Desa / Kelurahan" type="text" class="form-control" name="desa">

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
                    <label for="suratPernyataan" class="form-label">Nomor Pokok Wajib Pajak (NPWP),
                    </label>
                    <input class="form-control" name="foto_npwp" type="file" id="suratPernyataan" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                  <div class="col-md-6 form-file-group">
                    <label for="suratPengantar"  class="form-label">Surat pengantar dari RT/RW</label>
                    <input class="form-control" name="foto_pengantar" type="file" id="suratPengantar" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 form-file-group">
                    <label for="kk" class="form-label">Kartu Keluarga (KK)</label>
                    <input class="form-control" name="foto_kk" type="file" id="kk" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                  <div class="col-md-6 form-file-group">
                    <label for="ktp" class="form-label">Kartu Tanda Penduduk</label>
                    <input class="form-control" type="file" name="foto_ktp" id="ktp" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 form-file-group">
                    <label for="pembayaranpajak" class="form-label">Bukti Pembayaran Pajak Bumi dan Bangunan</label>
                    <input class="form-control" name="foto_bukti" type="file" id="pembayaranpajak" accept=".jpg,.jpeg,.png,.pdf" required>
                  </div>
                  <div class="col-md-6 form-file-group">
                    <label for="suratdomisili" class="form-label">Surat Keterangan Domisili</label>
                    <input class="form-control" name="foto_surat_domisili" type="file" id="suratdomisili" accept=".jpg,.jpeg,.png,.pdf" required>
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
