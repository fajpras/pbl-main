<?php 
// err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//link
include "../config/kol.php";
include "../config/auth.php";

//ambil data
if($_SERVER['REQUEST_METHOD'] == "POST"){

  $id = $_SESSION['id_warga'];
  $nik = $_POST['nik'];

  //validasi nikk
  $Q_nik = mysqli_query($conn, "select nik from dokumen_skk where nik = $nik");
  if(mysqli_num_rows($Q_nik) > 0 ){
    echo "<script>
    alert('NIK SUDAH TERDAFTAR SEBELUMNYA')
    window.location.href = ' ../surat/surat-SKK.php';</script>";
    exit();
  }

  $nama = $_POST['nama_lengkap'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $pekerjaan = $_POST['pekerjaan'];
  $alamat = $_POST['alamat'];
  $penyebab = $_POST['penyebab'];
  $tanggal = $_POST['tanggal_kematian'];
  $foto_surat_rs = addslashes(file_get_contents($_FILES['surat_rumah_sakit']['tmp_name']));
  $foto_ktp =  addslashes(file_get_contents($_FILES['ktp_pelapor']['tmp_name']));
  $foto_surat_pengantar =  addslashes(file_get_contents($_FILES['surat_pengantar']['tmp_name']));
  $foto_akte_nikah =  addslashes(file_get_contents($_FILES['akte_nikah']['tmp_name'])); 

  //query
  $query = "INSERT INTO `dokumen_skk`( `nik`,`nama_lengkap`, `jenis_kelamin`, `pekerjaan`, `alamat`, `penyebab`, `tanggal_kematian`, `foto_surat_RS`, `foto_ktp_pelapor`, `foto_surat_pengantar`, `foto_akte_nikah`) VALUES ('$nik','$nama','$jenis_kelamin','$pekerjaan','$alamat','$penyebab','$tanggal','$foto_surat_rs','$foto_ktp','$foto_surat_pengantar','$foto_akte_nikah')";

  $validasi = mysqli_query($conn, $query);

  //insert dokumens
  if($validasi){
    $ambil_id_surat = mysqli_query($conn,"SELECT * FROM dokumen_skk where nik = $nik");
    $array_ambil= mysqli_fetch_assoc($ambil_id_surat);
    $id_surat = $array_ambil['id_surat'];

    $qry_dokumen = "INSERT INTO `dokumens`( `nama_dokumen`, `ids_warga`, `nama_warga`,`id_surat`,`status`) VALUES ('SKK','$id','$nama','$id_surat' ,'PENDING')";

    $cek_petugas = mysqli_query($conn,$qry_dokumen);

    echo '<meta http-equiv="refresh" content="1; url=../warga/riwayat.php?note=berhasil">';

  }else{
    echo "<script> 
    alert('Data Yang Anda Masukkan Tidak Sesuai')
    </script>";
    header("Location:../surat/surat-SKK.php");
  }

}else {
  echo "<script> 
  alert('Data Yang Anda Masukkan Tidak Sesuai')
  </script>";
  header("Location:../surat/surat-SKK.php");

}


?>
