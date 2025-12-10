<?php 
// err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//link
include "../config/kol.php";
include "../config/auth.php";

//ambil dari form
if($_SERVER['REQUEST_METHOD'] == "POST"){

  $id = $_SESSION['id_warga'];
  $nik = $_POST['nik'];

  $Q_nik = mysqli_query($conn, "select nik from dokumen_domisili where nik = $nik");

  //validasi nik jika sudah terdaftar
  if(mysqli_num_rows($Q_nik) > 0 ){
    echo "<script>
    alert('NIK SUDAH TERDAFTAR SEBELUMNYA')
    window.location.href = ' ../surat/surat-domisili.php';</script>";

  }
  //ambil data
  $nama = $_POST['nama_lengkap'];
  $agama = $_POST['agama'];
  $pekerjaan = $_POST['pekerjaan'];
  $alamat = $_POST['alamat'];
  $foto_surat_pengantar = addslashes(file_get_contents($_FILES['foto_surat_pengantar']['tmp_name']));
  $foto_kk =  addslashes(file_get_contents($_FILES['foto_kk']['tmp_name']));
  $foto_pas =  addslashes(file_get_contents($_FILES['foto_pas']['tmp_name']));

  //query insert dokumen_domisili
  $query = "INSERT INTO `dokumen_domisili`(`nik`, `nama_lengkap`, `agama`, `pekerjaan`, `alamat`, `foto_surat_pengantar`, `foto_kk`,foto_pas) VALUES ('$nik','$nama','$agama','$pekerjaan','$alamat','$foto_surat_pengantar','$foto_kk','$foto_pas')";
  $validasi = mysqli_query($conn, $query);

  //PROSES MEMASUKKAN status ke table dokumens
  if($validasi){

    //ambil id surat
    $ambil_id_surat = mysqli_query($conn,"SELECT * FROM dokumen_domisili where nik = $nik");

    $array_ambil= mysqli_fetch_assoc($ambil_id_surat);
    $id_surat = $array_ambil['id_surat'];

    //masukkan data status ke table dokumens
    $qry_dokumen = "INSERT INTO `dokumens`( `nama_dokumen`, `ids_warga`, `nama_warga`,`id_surat`,`status`) VALUES ('SDM','$id','$nama','$id_surat' ,'PENDING')";
    $cek_petugas = mysqli_query($conn,$qry_dokumen);

    echo '<meta http-equiv="refresh" content="1; url=../warga/riwayat.php?note=berhasil">';

  }else{
    echo "<script> 
    alert('Data Yang Anda Masukkan Tidak Sesuai')
    </script>";
    header("Location:../surat/surat-domisili.php");
  }

}else {
  echo "<script> 
  alert('Data Yang Anda Masukkan Tidak Sesuai')
  </script>";
  header("Location:../surat/surat-domisili.php");



}
?>

