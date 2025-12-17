<?php

/*
INI PROSES DATA DIRI WARGA
 */


//err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//link
include("kol.php");
include("../config/auth.php");

// pastikan login
if (!isset($_SESSION['id_warga'])) {
  header("Location: ../login.php");
  exit();
}

$id_warga = $_SESSION['id_warga'];

//ambil dari form 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // AMBIL DATA
  $nama          = $_POST['nama'] ?? '';
  $nik           = $_POST['nik'] ?? '';
  $email         = $_POST['email'] ?? '';
  $agama         = $_POST['agama'] ?? '';
  $tempat_lahir  = $_POST['tempat_lahir'] ?? '';
  $ttl           = $_POST['ttl'] ?? '';
  $jk            = $_POST['jk'] ?? '';
  $pekerjaan     = $_POST['pekerjaan'] ?? '';
  $alamat        = $_POST['alamat'] ?? '';
  $provinsi      = $_POST['provinsi'] ?? '';
  $kota          = $_POST['kota'] ?? '';
  $kecamatan     = $_POST['kecamatan'] ?? '';
  $kelurahan     = $_POST['kelurahan'] ?? '';

  // validasi
// $Cnik =CHAR_LENGTH($nik);

  $required = [$nama, $nik, $email, $agama, $tempat_lahir, $ttl, $jk, $pekerjaan, $alamat, $provinsi, $kota, $kecamatan, $kelurahan];
  foreach ($required as $item) {
    if ($item === "") {
      die("<script>alert('Semua field wajib diisi!');history.back();</script>");
    }
  }

  // cek status
  $Q = mysqli_query($conn, "SELECT status_data_diri FROM warga WHERE id_warga = $id_warga");
  $R = mysqli_fetch_assoc($Q);
  $isUpdate = ($R['status_data_diri'] == 1);

  //jika ambil data warg
  $oldData = null;
  if ($isUpdate) {
    $Q2 = mysqli_query($conn, "SELECT * FROM data_diri WHERE id_warga = $id_warga");
    $oldData = mysqli_fetch_assoc($Q2);
  }


  //foto
  $file_name = $oldData['foto_profil'] ?? null;
  if (!empty($_FILES['profil']['name'])) {
    $ext = pathinfo($_FILES['profil']['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array(strtolower($ext), $allowed)) {
      die("<script>alert('Format foto tidak valid!');history.back();</script>");
    }

    $file_name = $nik . "." . $ext;
    $upload_dir = "../uploads/" . $file_name;
    move_uploaded_file($_FILES['profil']['tmp_name'], $upload_dir);
  }

  // ESCAPE
  function esc($conn, $v)
  {
    return mysqli_real_escape_string($conn, $v);
  }

  $nama      = esc($conn, $nama);
  $email     = esc($conn, $email);
  $alamat    = esc($conn, $alamat);


  //isi dara langsung
  if (!$isUpdate) {

    $sql = "INSERT INTO data_diri 
      (nama_lengkap, foto_profil, nik, email, agama, tempat_lahir, tanggal_lahir, jenis_kelamin, pekerjaan, alamat, provinsi, kabupaten, kecamatan, kelurahan, id_warga) 
      VALUES 
      ('$nama', '$file_name', '$nik', '$email', '$agama', '$tempat_lahir', '$ttl', '$jk', '$pekerjaan', '$alamat', '$provinsi', '$kota', '$kecamatan', '$kelurahan', '$id_warga')";

    $insert = mysqli_query($conn, $sql);

    if ($insert) {
      // update status_data_diri
      mysqli_query($conn, "UPDATE warga SET status_data_diri = 1 WHERE id_warga = $id_warga");

      echo "<script>
        alert('Data berhasil ditambahkan!');
      window.location = '../dashboard.php';
                  </script>";
    } else {
      die("Insert Error: " . mysqli_error($conn));
    }
  }


  //update data
  else {

    $sql = "UPDATE data_diri SET
            nama_lengkap   = '$nama',
            foto_profil    = '$file_name',
            nik            = '$nik',
            email          = '$email',
            agama          = '$agama',
            tempat_lahir   = '$tempat_lahir',
            tanggal_lahir  = '$ttl',
            jenis_kelamin  = '$jk',
            pekerjaan      = '$pekerjaan',
            alamat         = '$alamat',
            provinsi       = '$provinsi',
            kabupaten      = '$kota',
            kecamatan      = '$kecamatan',
            kelurahan      = '$kelurahan'
            WHERE id_warga = '$id_warga'";

    $update = mysqli_query($conn, $sql);

    if ($update) {
      echo "<script>
              alert('Data berhasil diperbarui!');
            window.location.href = '../dashboard.php';
            </script>";
    } else {
      die("Update Error: " . mysqli_error($conn));
    }
  }
}
