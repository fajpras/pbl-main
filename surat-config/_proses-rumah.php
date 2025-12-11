<?php 
// err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//link
include "../config/kol.php";
include "../config/auth.php";


//data form
if($_SERVER['REQUEST_METHOD'] == "POST"){

  $id = $_SESSION['id_warga'];
  $nik = $_POST['nik'];

  //validasi nik terdaftar
  $Q_nik = mysqli_query($conn, "select nik from dokumen_rumah where nik = $nik");
  if(mysqli_num_rows($Q_nik) > 0 ){
    echo "<script>
    alert('NIK SUDAH TERDAFTAR SEBELUMNYA')
    window.location.href = ' ../surat/surat-rumah.php';</script>";

  }

  //data
  $nama = $_POST['nama_lengkap'];
  $kecamatan = $_POST['kecamatan'];
  $desa = $_POST['desa'];
  $alamat = $_POST['alamat'];
  $foto_sertifikat= addslashes(file_get_contents($_FILES['foto_sertifikat']['tmp_name']));
  $foto_akta_rumah=  addslashes(file_get_contents($_FILES['foto_akta_mendirikan']['tmp_name']));
  $foto_kk=  addslashes(file_get_contents($_FILES['foto_kk']['tmp_name']));
  $foto_ktp=  addslashes(file_get_contents($_FILES['foto_ktp']['tmp_name'])); 
  $foto_bppbb=  addslashes(file_get_contents($_FILES['foto_BPPBB']['tmp_name'])); 
  $foto_surat_sengketa =  addslashes(file_get_contents($_FILES['foto_surat_tidak_sengketa']['tmp_name']));


  //masukkan data ke table dokumen_rumah
  $query = "INSERT INTO `dokumen_rumah`( `nik`, `nama_lengkap`, `kecamatan`, `desa`, `alamat`, `foto_sertifikat`, `foto_akta_mendirikan`, `foto_kk`, `foto_ktp`, `foto_BPPBB`, `foto_surat_tidak_sengketa`) VALUES ('$nik','$nama','$kecamatan','$desa','$alamat','$foto_sertifikat','$foto_akta_rumah','$foto_kk','$foto_ktp','$foto_bppbb','$foto_surat_sengketa')";

  //masukkan status ke table dokumens
  $validasi = mysqli_query($conn, $query);

  if($validasi){

    $ambil_id_surat = mysqli_query($conn,"SELECT * FROM dokumen_rumah where nik = $nik");

    $array_ambil= mysqli_fetch_assoc($ambil_id_surat);
    $id_surat = $array_ambil['id_surat'];

    //masukkan data ke dalam dokumens
    $qry_dokumen = "INSERT INTO `dokumens`( `nama_dokumen`, `id_warga`, `nama_warga`,`id_surat`,`status`) VALUES ('SRM','$id','$nama','$id_surat' ,'PENDING')";

    $cek_petugas = mysqli_query($conn,$qry_dokumen);

    echo '<meta http-equiv="refresh" content="1; url=../warga/riwayat.php?note=berhasil">';

  }else{
    echo "<script> 
    alert('Data Yang Anda Masukkan Tidak Sesuai')
    </script>";
    header("Location:../surat/surat-rumah.php");
  }

}else {
  echo "<script> 
  alert('Data Yang Anda Masukkan Tidak Sesuai')
  </script>";
  header("Location:../surat/surat-rumah.php");

}


?>
