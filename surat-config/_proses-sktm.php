<?php 
// err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//link
include "../config/kol.php";
include "../config/auth.php";


if($_SERVER['REQUEST_METHOD'] == "POST"){

  $id = $_SESSION['id_warga'];
  $nik = $_POST['nik'];

  //validasi nikk
  $Q_nik = mysqli_query($conn, "select nik from dokumen_sktm where nik = $nik");
  if(mysqli_num_rows($Q_nik) > 0 ){
    echo "<script>
      alert('NIK SUDAH TERDAFTAR SEBELUMNYA')
      window.location.href = ' ../surat/surat-SKTM.php';</script>";
    
  }

  $nama_lengkap = $_POST['nama_lengkap'];
  $agama = $_POST['agama'];
  $pekerjaan = $_POST['pekerjaan'];
  $alamat = $_POST['alamat'];
  $surat_tidak_mampu = addslashes(file_get_contents($_FILES['surat_tidak_mampu']['tmp_name']));
  $foto_rumah =  addslashes(file_get_contents($_FILES['fotorumah']['tmp_name']));
  $foto_kk =  addslashes(file_get_contents($_FILES['fotokk']['tmp_name']));
  $foto_slip =  addslashes(file_get_contents($_FILES['fotoslip']['tmp_name'])); 
  $foto_tagihan =  addslashes(file_get_contents($_FILES['fototagihan']['tmp_name']));

  //query
  $query = "INSERT INTO `dokumen_sktm`( `nik`, `nama_lengkap`, `agama`, `pekerjaan`, `alamat`, `foto_persetujuan`, `foto_rumah`, `foto_kk`, `foto_slip_gaji`, `foto_tagihan`) VALUES ('$nik','$nama_lengkap','$agama','$pekerjaan','$alamat','$surat_tidak_mampu','$foto_rumah','$foto_kk','$foto_slip','$foto_tagihan')";

  $cek = mysqli_query($conn,$query);

  //masukkan ke tabel dokumens
  if($cek){
    $ambil_id_surat = mysqli_query($conn,"SELECT * FROM dokumen_sktm where nik = $nik");
    $array_ambil= mysqli_fetch_assoc($ambil_id_surat);
    $id_surat = $array_ambil['id_surat'];

    //query insert dokumens
    $qry_dokumen = "INSERT INTO `dokumens`( `nama_dokumen`, `ids_warga`, `nama_warga`,`id_surat`,`status`) VALUES ('SKTM','$id','$nama_lengkap','$id_surat' ,'PENDING')";

    $cek_petugas = mysqli_query($conn,$qry_dokumen);
    
    echo '<meta http-equiv="refresh" content="3; url=../warga/riwayat.php?note=berhasil">';
 

  }else{
    echo "<script> 
    alert('Data Yang Anda Masukkan Tidak Sesuai')
    </script>";
    header("Location:../surat/surat-SKTM.php");
  }

}else {
   echo "<script> 
    alert('Data Yang Anda Masukkan Tidak Sesuai')
    </script>";
    header("Location:../surat/surat-SKTM.php");

}


?>
