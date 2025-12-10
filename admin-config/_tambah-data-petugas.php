<?php
//link
include "../config/kol.php";
include "../config/auth.php";

//err log
error_reporting(E_ALL);
ini_set('display_errors', 3);

//notif ok
if (isset($_GET['note'])) {
  echo "<script>alert('Data Berhasil di simpan')</script>";
}


//ambil POST dari hiden input
$id = $_POST['id_petugas'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$tl = $_POST['tl'];
$tgl = $_POST['tgl'];
$jk = $_POST['jk'];
$hp = $_POST['hp'];

//insert data data_diri_petugas
$Q_petugas = "INSERT INTO data_diri_petugas (id_petugas, nama_lengkap, nomor, email, tempat_lahir, tanggal_lahir, jenis_kelamin)VALUES($id, '$nama', '$hp', '$email', '$tl', '$tgl', '$jk')";
$F_petugas = mysqli_query($conn, $Q_petugas);

if ($F_petugas) {
header("Refresh:2; url:=../admin/tambah-petugas.php");

}

?>
