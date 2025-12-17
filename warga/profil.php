<?php
//link
include("../config/kol.php");
include('../config/auth.php');
//ambil data diri
$id = $_SESSION['id_warga'];
$mysql = mysqli_query($conn,  "SELECT * FROM data_diri where  id_warga = $id ");

$data = mysqli_fetch_array($mysql);

//editable for input form 
if (mysqli_num_rows($mysql) === 1) {
  $read = "readonly";
  $dis = "disabled";
} else {
  $dis = "";
  $pilih = "Pilih";
}

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | AJUK</title>

    <!-- ========LINK====== -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/profil.css">

  </head>

  <body>

    <!-- navigasi -->
    <nav class="navbar navbar-dark fixed-top" id="navigasi">
      <div class="logo">
        <a onclick="history.back()" class=" text-black  "><i
          class="fs-2 px-3 fw-bold text-light bi bi-arrow-left-circle"></i></a>
        <a class="logo-link">
          <img src="../assets/logo.svg" alt="logo">
        </a>
      </div>
      <div class="nav-links d-flex justify-content-between">

        <a class="fs-7  fw-medium align-items-center btn btn-danger" href="../config/_close.php"
          onclick="return confirm('anda yakin ingin keluar')"> <i class="bi bi-box-arrow-right p-1"></i>Keluar</a>
      </div>
    </nav>


    <section class="profil-section ">
      <div class="profil-container  ">
        <h1 class="text-light">DATA DIRI</h1>
        <div class="profil-content">

          <!-- data diri form -->
          <div class="profil-form">
            <!-- icon profile -->
            <div class="profil-side">
              <div class="profil-avatar">

                <?php if(mysqli_num_rows($mysql) > 0){ ?>
                <img style="border-radius:50%;" src=" ../uploads/<?= $data['foto_profil'] ?? "../assets/logo.svg"?>" height="100" width="100">
                <?php }else{?>
                  <img style="border-radius:50%;" src=" ../assets/logo.svg" height="100" width="100">
                <?php };?>

              </div>
              <h3 class="text-light">Halo <?php echo $_SESSION['nama']??"" ?>

            </div>
            <form action="../config/_proses_profil.php" method="POST" enctype="multipart/form-data">
              <div class="mb-2">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" <?= $read ??"" ?> value="<?= $data['nama_lengkap'] ??"" ?>" name="nama"
                  required>
              </div>

              <div class="mb-2">
                <label>Foto Profil</label>
                <input type="file" class="form-control" value="<?= $data['foto_profil']??"" ?>" <?= $dis??"" ?> name="profil">
              </div>

              <div class="mb-2">
                <label>NIK</label>
                <input type="number" class="form-control" name="nik" value="<?= $data['nik']??"" ?>" <?= $read??"" ?> required minlength="16" >
              </div>
              <div class="row">
                <div class="col-md-6">

                  <label>Email</label>
                  <input type="email" class="form-control" name="email" value="<?= $_SESSION['email']??"" ?>" readonly required>
                </div>
                <div class="col-md-6">
                  <label for="agama" class="form-label fw-bold">Agama</label>
                  <select class="form-select fw-medium" name="agama" id="agama" required>
                    <option><?= $pilih ?? $data['agama'] ?? '' ?></option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katholik">Katholik</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Konghucu">Konghucu</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <label>Tempat Lahir</label>
                  <input type="text" class="form-control" value="<?= $data['tempat_lahir']??"" ?>" <?= $read??"" ?>
                    name="tempat_lahir" required>
                </div>
                <div class="col-md-6 mb-2">
                  <label>Tanggal Lahir</label>
                  <input type="date" class="form-control" value="<?= $data['tanggal_lahir']??"" ?>" <?= $read??"" ?> name="ttl"
                    required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-2">
                  <label>Jenis Kelamin </label>
                  <select class="form-control" name="jk" value="" required>
                    <option><?= $pilih ?? $data['jenis_kelamin'] ?? '' ?> <i class="fas fa-plus-circle"></i></option>
                    <option>Laki-laki</option>
                    <option>Perempuan</option>
                  </select>
                </div>
                <div class="col-md-6 mb-2">
                  <label>Pekerjaan</label>
                  <input type="text" class="form-control" value="<?= $data['pekerjaan']??"" ?>" name="pekerjaan" <?= $read??"" ?>
                    required>
                </div>
              </div>

              <div class="mb-2">
                <label>Alamat</label>
                <textarea class="form-control" value="" <?= $read??"" ?> required
                  name="alamat"><?= $data['alamat']??"" ?> </textarea>
              </div>

              <div class="row">
                <div class="col-md-6 mb-2">
                  <label>Provinsi</label>
                  <input type="text" class="form-control" value="<?= $data['provinsi']??"" ?>" <?= $read??"" ?> name="provinsi"
                    required>
                </div>
                <div class="col-md-6 mb-2">
                  <label>Kabupaten / Kota</label>
                  <input type="text" class="form-control" value="<?= $data['kabupaten']??"" ?>" <?= $read??"" ?> name="kota"
                    required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-2">
                  <label>Kecamatan</label>
                  <input type="text" class="form-control" name="kecamatan" value="<?= $data['kecamatan']??"" ?>" <?= $read??"" ?>
                    required>
                </div>
                <div class="col-md-6 mb-2">
                  <label>Desa / Kelurahan</label>
                  <input type="text" class="form-control" name="kelurahan" value="<?= $data['kelurahan']??"" ?>" <?= $read??"" ?>
                    required>
                </div>
              </div>

              <?php if (isset($read)): ?>
              <button type="button" class="btn btn-warning mt-3" onclick="enableEdit()"><i class="bi bi-pencil-square me-3"></i>
                Edit Data</button>
              <?php endif; ?>

              <button onclick="konfirm()" class="btn btn-success float-end mt-3" name="simpan"><i class="bi bi-cloud-check me-3"></i>
                Simpan</button>
            </form>
          </div>


        </div>
      </div>
    </section>

    <footer>
      <p class="fw-bold">AJUK - Copyright © 2025. All rights reserved.</p>
    </footer>
  </body>
  <script>
function enableEdit() {
  document.querySelectorAll("input, select, textarea").forEach(el => {
    el.removeAttribute("readonly");
    el.removeAttribute("disabled");
  });
}
  </script>

</html>
