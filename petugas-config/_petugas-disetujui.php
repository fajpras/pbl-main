<?php
//link
include "../config/kol.php";
include ('../config/auth.php') ;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';


//err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//validasi login
if (!isset($_SESSION['id_petugas'])) {
  echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location='../login.php';</script>";
  exit();
}


if(isset($_GET['id'])) {
  $id_surat = $_GET['id'];
  $nama_dok = $_GET['dok'];
  $id_warga = $_GET['idw'];

  $mail = new PHPMailer(true);
  
  //ambil email dari table data_diri
  $Q = "SELECT dd.email 
  FROM dokumens d
  JOIN warga w ON d.id_warga = w.id_warga
  JOIN data_diri dd ON w.id_warga = dd.id_warga
  WHERE w.id_warga = $id_warga 
  LIMIT 1"; 
  $F = mysqli_query($conn, $Q);
  $D = mysqli_fetch_assoc($F);
  try {

    // Konfigurasi SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    //ganti email sesuai dengan kebutuhan
    $mail->Username   = 'fajrinurpras07@gmail.com'; 
    //app key dari gmail
    $mail->Password   = 'xkzb fsjv lher vriu'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Penerima
    $email = $D['email'];
    $mail->setFrom('faleftplays@gmail.com', 'Admin Kelurahan');
    $mail->addAddress($email, 'Warga'); 

    // Uabah isi sesuia dengan apa yang di anjurkan
    $mail->isHTML(true);
    $mail->Subject = 'Pemberitahuan Surat';
    $mail->Body    = 'Halo tuan ini surat anda sudah selesai dan bisa di unduh pada web pengajuan http://localhost/pblC/login.php';

    $mail->send();
    $query = "UPDATE dokumens SET status = 'SELESAI' WHERE id_surat = '$id_surat' and nama_dokumen = '$nama_dok'";

    if(mysqli_query($conn, $query)){
      echo "<script> 
      alert('Surat berhasil dikirim ke Warga!');
      window.location.href = '../petugas/petugas-disetujui.php'; 
      </script>";
    } else {
      echo "<script>
      alert('Gagal mengirim surat.');
      window.history.back();
      </script>";
    }

    echo 'Sukses! Email berhasil dikirim.';

    //email gagal terkirim
  } catch (Exception $e) {
   echo "<script>alert('Email Tidak Terdaftar!'); window.location='../petugas/petugas-disetujui.php';</script>"; 
  }

}
?>
