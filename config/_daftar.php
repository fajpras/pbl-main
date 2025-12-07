<?php
//err
error_reporting(E_ALL);
ini_set('display_errors', 1);

//link
include("kol.php");

//ambil form daftar
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = trim($_POST['nama']);
    $password = $_POST['password'];
    $email = $_POST['email'];

    //simbol
    $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{6,}$/";

    //validasi simbol
    if (!preg_match($pattern, $password)) {

        $errpass = "Silahkan masukkan sesuai dengan ketentuan (A-Z, 0-9, panjang 6)";
        header("Location: ../daftar.php?err=". urlencode($errpass) );

    } else {
        //enkripsi pass
        $Password = password_hash($password, PASSWORD_DEFAULT);

        //validasi cek email
        $check = "SELECT * FROM `warga` WHERE email = '$email' ";
        $check_status = mysqli_query($conn, $check);

        if (mysqli_num_rows($check_status) > 0) {
            echo "<script>alert('email sudah terdaftar');
            window.location.href='../login.php'</script>"; 

        } else {

            //aman insert warga
            $insert1 = "INSERT INTO warga (nama, email, password) VALUES ('$nama', '$email', '$Password')";
            mysqli_query($conn, $insert1);

            header("Location:../login.php");
            echo "<script>alert('email tersimpan');</script> ";
        }
    }

}
?>
