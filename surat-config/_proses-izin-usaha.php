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

  //validasi nik terdaftar
  $Q_nik = mysqli_query($conn, "select nik from dokumen_izin_usaha where nik = $nik");
  if(mysqli_num_rows($Q_nik) > 0 ){
    echo "<script>
      alert('NIK SUDAH TERDAFTAR SEBELUMNYA')
      window.location.href = ' ../surat/surat-izin-usaha.php';</script>";
    
  }

  //ambil data
  $nama = $_POST['nama_lengkap'];
  $nama_kbli = $_POST['nama_kbli'];
  $nomor_kbli = $_POST['nomor_kbli'];
  $kecamatan = $_POST['kecamatan'];
  $desa = $_POST['desa'];
  $alamat = $_POST['alamat'];
  $foto_npwp = addslashes(file_get_contents($_FILES['foto_npwp']['tmp_name']));
  $foto_pengantar=  addslashes(file_get_contents($_FILES['foto_pengantar']['tmp_name']));
  $foto_kk=  addslashes(file_get_contents($_FILES['foto_kk']['tmp_name']));
  $foto_ktp=  addslashes(file_get_contents($_FILES['foto_ktp']['tmp_name'])); 
  $foto_surat_domisili =  addslashes(file_get_contents($_FILES['foto_surat_domisili']['tmp_name'])); 
  $foto_bukti=  addslashes(file_get_contents($_FILES['foto_bukti']['tmp_name'])); 

  //masukkan data ke surat_izin_usaha
  $query = "INSERT INTO `dokumen_izin_usaha`(`nik`, `nama_lengkap`, `nama_kbli`, `nomor_kbli`, `kecamatan`, `desa`, `alamat`, `foto_npwp`, `foto_pengantar`, `foto_kk`, `foto_ktp`, `foto_surat_domisili`, foto_bukti) VALUES ('$nik','$nama','$nama_kbli','$nomor_kbli','$kecamatan','$desa','$alamat','$foto_npwp','$foto_pengantar','$foto_kk','$foto_ktp','$foto_surat_domisili','$foto_bukti')";

  //masukkan log ke tabel dokumens
  $validasi = mysqli_query($conn, $query);
  if($validasi){

    //ambil id $ masukkan ke tabel dokumens
    $ambil_id_surat = mysqli_query($conn,"SELECT * FROM dokumen_izin_usaha where nik = $nik");

    $array_ambil= mysqli_fetch_assoc($ambil_id_surat);
    $id_surat = $array_ambil['id_surat'];

    $qry_dokumen = "INSERT INTO `dokumens`( `nama_dokumen`, `ids_warga`, `nama_warga`,`id_surat`,`status`) VALUES ('SIU','$id','$nama','$id_surat' ,'PENDING')";

    $cek_petugas = mysqli_query($conn,$qry_dokumen);

    echo '<meta http-equiv="refresh" content="1; url=../warga/riwayat.php?note=berhasil">';

  }else{
    echo "<script> 
      alert('Data Yang Anda Masukkan Tidak Sesuai')
    </script>";
    header("Location:../surat/surat-izin-usaha.php");
  }

}else {
  echo "<script> 
    alert('Data Yang Anda Masukkan Tidak Sesuai')
     </script>";
    header("Location:../surat/surat-izin-usaha.php");





  }


