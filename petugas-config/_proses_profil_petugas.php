<?php
//link
include "../config/kol.php";
include ('../config/auth.php') ;

//err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//validasi login
if (!isset($_SESSION['id_petugas'])) {
  echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location='../login.php';</script>";
  exit();
}


if($_SERVER["REQUEST_METHOD"]=="POST"){

  //ambil data 
  $id = $_SESSION['id_petugas'];
  $foto = $_FILES['foto_profil_petugas']['name'];
  $nama = $_SESSION['nama_petugas'];
  $profil_tmp = $_FILES['foto_profil_petugas']['tmp_name'];

  //UPLOAD FOTO
  $ext = pathinfo($_FILES['foto_profil_petugas']['name'], PATHINFO_EXTENSION);
  $file_name =  $nama.".".$ext;
  $upload_dir = "../uploads-petugas/".$file_name;
  move_uploaded_file($profil_tmp, $upload_dir);  


  $qury = "UPDATE  data_diri_petugas SET foto_profil = '$file_name' where id_petugas = $id";
  $cek = mysqli_query($conn, $qury);

  if($cek){
    $_SESSION['status'] = 1;
    header("Location:../petugas/petugas-profil.php");
    echo "<script> alert('Data berhasil ditambahkan!')
        </script>;";

  } 
}
?>
