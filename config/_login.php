<?php 

//link
include("kol.php");

//err
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();

//ambil form login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //ambil
  $email = $_POST['email'];
  $password = $_POST['password'];

  //admin
  $Q_admin = "SELECT * from admin where email = '$email'";
  $F_admin = mysqli_query($conn, $Q_admin);

  //petugas
  $cek_petugas = "  SELECT * FROM petugas  WHERE email = '$email'";
  $cekk = mysqli_query($conn, $cek_petugas);

  //warga
  $sql_warga = "SELECT * FROM warga  WHERE email = '$email' ";
  $result = mysqli_query($conn, $sql_warga);

  //cek email petuggas
  if (mysqli_num_rows($cekk) > 0) {
    $data_cek = mysqli_fetch_assoc($cekk);
    $cek_email = $data_cek['email'];

    //jika petugas cek pass
    if (password_verify($password, $data_cek['password_petugas'])){

      $_SESSION['id_petugas'] = $data_cek['id_petugas'];
      $_SESSION['nama_petugas'] = $data_cek['nama_petugas'];
      $_SESSION['email'] = $data_cek['email'];

      $user = $data_cek['nama_petugas'];
      echo "<script>
      alert('Selamat datang $user')
      window.location.href = ' ../petugas/petugas-pending.php';</script>";
      exit();

      //jika tidak
    }else{
      $errpass = "Password anda salah";
      header("Location:../login.php?errpass=" . urlencode($errpass));

    }
    //cek warga
  } elseif (mysqli_num_rows($result) > 0) {

    //jika email warga
    if (mysqli_num_rows($result) === 1) {
      $data = mysqli_fetch_assoc($result);

      //cek pass
      if (password_verify($password, $data['password'])) {

        $_SESSION['id_warga'] = $data['id_warga'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['status'] = $data['status_data_diri'];

        $user = $data['nama'];
        echo "<script>
        alert('Selamat datang $user');
        window.location.href = ' ../dashboard.php';</script>";
        exit();

        //jika tidak
      } else {
        $errpass = "Password anda salah";
        header("Location:../login.php?errpass=" . urlencode($errpass));
      }
    } 

    //cek email petugas
  } elseif (mysqli_num_rows($F_admin) === 1) {
    $data = mysqli_fetch_assoc($F_admin);

    //cek pass
    if ($password == $data['password']) {
      $_SESSION['id_admin'] = $data['id_admin'];
      $_SESSION['nama_admin'] = $data['nama_admin'];
      $_SESSION['email'] = $data['email'];

      $user = $data['nama_admin'];
      echo "<script>
      alert('Selamat datang $user');
      window.location.href = ' ../admin/dashboard-admin.php';</script>";
      exit();

      //jika tidak
    } else {
      $errpass = "Password anda salah";
      header("Location:../login.php?errpass=" . urlencode($errpass));
    }

    //email belum terdaftar
  }else{
    $erremail = 'Email yang anda masukkan tidak valid  ';
    header('Location:../login.php?email_eror=' . urlencode($erremail));
  }

}
?>
