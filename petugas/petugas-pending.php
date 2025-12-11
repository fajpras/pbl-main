<?php
//link
include "../config/kol.php";
include('../config/auth.php');

//err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//validasi login
if (!isset($_SESSION['id_petugas'])) {
  echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location='../login.php';</script>";
  exit();
}

$nama_petugas = $_SESSION['nama_petugas'];

//alert
if (isset($_GET['status']) == "berhasil") {
  echo "<script> alert('Data Berhasil Di Ubah')</script>";

} else {
  $modal = "flex";
}
?>


<!DOCTYPE html>
<html lang="id">

  <head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Petugas Pending | AJUK</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/petugas-pending.css" />

  </head>
  <div class="container-fluid">
    <div class="row flex-nowrap">
      <!-- sidebar -->
      <div class="col-auto px-0 sidebar ">
        <div id="sidebar" class="collapse collapse-horizontal show  ">
          <!-- logo -->
          <div class="d-flex container-fluid">

            <a class="text-black text-decoration-none">
              <img class="logo p-3" src="../assets/logo.svg" alt="logo" />
            </a>
            <a data-bs-target="#sidebar" data-bs-toggle="collapse"
              class="border fs-1  p-1 text-decoration-none text-light align-items-center border1"><i
                class="bi bi-x bi-lg py-2 p-1 "></i>
            </a>
          </div>
          <hr />
          <!-- sidebar -->
          <div id="sidebar-nav" class="list-group border-0 rounded-0 text-sm-start min-vh-100">
            <ul class="menu fs-6 fw-medium ">
              <li>
                <a href="petugas-pending.php"><i class="bi bi-hourglass "></i> Dokumen Pending</a>
              </li>
              <li>
                <a href="petugas-disetujui.php"><i class="bi bi-clipboard2-check "></i> Dokumen Disetujui</a>
              </li>
              <li>
                <a href="petugas-riwayat.php"><i class="bi bi-clock-history"></i> Riwayat Pemeriksaan</a>
              </li>
              <li>
                <a href="petugas-profil.php"><i class="bi bi-person"></i> Profil</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <main class="col p-0">
        <!-- main -->
        <main class="main-content">
          <!-- header -->
          <header class="topbar">
            <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
              class="border fs-1  p-1 text-decoration-none text-light border1"><i class="bi bi-list bi-lg py-2 p-1 "></i>
            </a>
            <a href="../config/_close.php" class="fs-7 fw-medium align-items-center btn btn-danger"
              href="../config/_close.php" onclick="return confirm('anda yakin ingin keluar')">
              <i class="bi bi-box-arrow-right p-1"></i>Keluar
            </a>

          </header>
          <!-- main content -->
          <div class="pengajuan-section">
            <div class="content">
              <div class="content-overlay">
                <h2 class="fw-medium mb-4 fw-bold text-left">Dokumen Diajukan Warga</h2>
                <div class="table-responsive">
                  <table class="table table-hover align-middle text-white">
                    <thead>
                      <?php $n = 1 ?>
                      <tr>
                        <th class="bg-light text-black">No</th>
                        <th>Nama Warga</th>
                        <th width="20%">Jenis Dokumen</th>
                        <th>Tanggal Diajukan</th>
                        <th>Periksa Dokumen</th>
                        <th>Status Pengajuan</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php

                      //ambil data dari dokumen
                      $query = "select date_format(tanggal, '%d %M %Y') as date, nama_dokumen,  status, id_warga, nama_warga, id_surat from dokumens where status = 'PENDING' order by date DESC";
                      $validasi = mysqli_query($conn, $query);

                      // tampilkan data 
                      if (mysqli_num_rows($validasi) > 0) {

                      while ($row = mysqli_fetch_assoc($validasi)) {

                      $nama_dokumen = $row['nama_dokumen'];
                      if ($nama_dokumen == 'SKTM') {
                      $dok_title = "Surat Keterangan Tidak Mampu";
                      } elseif ($nama_dokumen == 'SKK') {
                      $dok_title = "Surat Keterangan Kematian";
                      } elseif ($nama_dokumen == 'SRM') {
                      $dok_title = "Surat Rumah";
                      } elseif ($nama_dokumen == 'SIU') {
                      $dok_title = "Surat Izin Usaha";
                      } else {
                      $dok_title = "Surat Domisili";
                      }
                      echo '
                      <tr>
                      <td class="bg-light text-black">' . $n++ . '</td>
                      <td>' . $row['nama_warga'] . ' </td>
                      <td>' . $dok_title . '</td>
                      <td>' . $row['date'] . '</td>
                      <td><a href="petugas-lihat-dokumen.php?id=' . $row['id_surat'] . '&dok=' . $nama_dokumen . '"><button class="btn-lihat"><i
                      class="bi bi-eye me-2"></i>Lihat
                      </button></a></td>
                      <td>

                      <form class="" method="POST" action="../petugas-config/_proses_status.php" id="formAlasan">

                      <input type="hidden" name="idPetugas" id="idPetugas" value="' . $_SESSION['id_petugas'] . '">
                      <input type="hidden" name="idSurat" id="idSurat" value="' . $row['id_surat'] . '">
                      <input type="hidden" name="nama_dokumen" id="nama_dokumen" value="' . $nama_dokumen . '">

                      <input type="hidden" name="petugas" id="hidden_petugas">
                      <input type="hidden" name="alasan" id="hidden_alasan">

                      <select id="status" name="status" class="btn btn-light me-2 my-2" required>
                      <option value="">Pilih Status</option>
                      <option value="disetujui">Disetujui</option>
                      <option value="ditolak">Ditolak</option>
                      </select>

                      <button  type="button" class="btn btn-info float-center px-2 py-1"  onclick="tolakSurat()">Alasan</button>
                      </form>

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

        </main>
      </main>

    </div>
  </div>
  </body>
  <script src="../bootstrap/js/bootstrap.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>

//modal alasan tolak surat 
function tolakSurat() {
  Swal.fire({
    title: "Form Keterangan",
    background: "#e9f2ff",
    html: `
<div class="row">
<label class="py-3">Nama Petugas</label>
<input id="petugas" value="<?= $nama_petugas ?>" disabled type="input" class="rounded-2 py-2 px-2" placeholder="Nama Petugas">

<label class="pt-2 pb-1">Alasan</label>
<textarea style="resize:none;" id="alasan" class="rounded-2 px-2 py-2" placeholder="Tuliskan alasan anda"></textarea>

<label class="py-2">
<input type="checkbox" id="cekKonfirmasi">
Saya yakin dengan dokumen ini
</label>
</div>
`,

    confirmButtonText: "Kirim",
    cancelButtonText: "Batal",
    showCancelButton: true,
    confirmButtonColor: "#1d68d9",
    cancelButtonColor: "#6c757d",

    preConfirm: () => {

      const petugas = document.getElementById("petugas").value;
      const alasan = document.getElementById("alasan").value;
      const cek = document.getElementById("cekKonfirmasi").checked;
      const status = document.getElementById("status").value;

      if (!petugas.trim())
        return Swal.showValidationMessage("Nama petugas wajib diisi!");

      if (!alasan.trim())
        return Swal.showValidationMessage("Alasan wajib diisi!");

      if (!cek)
        return Swal.showValidationMessage("Checklist harus dicentang!");

      return { petugas, alasan, status };
    }
  }).then((result) => {
      if (result.isConfirmed) {

        const data = result.value;
        const f = document.getElementById("formAlasan");

        // kirim ke hidden input
        f.hidden_petugas.value = data.petugas;
        f.hidden_alasan.value = data.alasan;
        f.status.value = data.status;

        f.submit();
      }
    });
}
</script>

</html>
