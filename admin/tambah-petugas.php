<?php
//link
include "../config/kol.php";
include "../config/auth.php";

//err log
error_reporting(E_ALL);
ini_set('display_errors', 1);

//notif ok
if (isset($_GET['note'])) {
  echo "<script>alert('Data Berhasil di simpan')</script>";
}


$id = $_SESSION['id_admin'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajukan Dokumen</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/dashboard-admin.css" />



  <style>
    .swal-large-input {
      height: 48px !important;
      font-size: 17px !important;
      /* padding: 10px 14px !important; */
      border-radius: 10px !important;
    }

    .swal2-input {
      margin: 0 !important;
    }
  </style>

</head>

<body>

  <div class="container-fluid ">
    <div class="row flex-nowrap ">
      <!-- sidebar  -->
      <div class="col-auto px-0 sidebar ">
        <div id="sidebar" class="collapse collapse-horizontal show  ">
          <div class="d-flex container-fluid ">

            <a href="#" class="text-black text-decoration-none">
              <img class="logo p-3" src="../assets/logo.svg" alt="logo" />
            </a>
            <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
              class="border fs-1  p-1 text-decoration-none text-light align-items-center border1"><i class="bi bi-x bi-lg py-2 p-1 "></i>
            </a>
          </div>

          <hr />
          <div id="sidebar-nav" class="list-group border-0 rounded-0 text-sm-start min-vh-100">
            <ul class="nav menu  fw-medium nav-pills flex-column mb-sm-auto mb-0 align-items-sm-start px-3"
              id="menu">
              <li class="">
                <a href="dashboard-admin.php" class="nav-link align-middle ">
                  <i class="bi  bi-person"></i>Dashboard
                </a>
              </li>

              <li class="">
                <a href="tambah-pgs.php" class="nav-link align-middle ">
                  <i class="bi  bi-person"></i>Tambah Petugas
                </a>
              </li>
             
            </ul>
          </div>

        </div>
      </div>


      <!-- main content -->
      <main class="col p-0 main-cons">

        <header class="topbar">
          <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
            class="border fs-1  p-1 text-decoration-none text-light border1"><i class="bi bi-list bi-lg py-2 p-1 "></i>
          </a>


          <div class="profile">
            <a class="d-flex align-items-center text-decoration-none text-light " href="#"> Halo, <?php echo $_SESSION['nama_admin'] ?><i class="bi bi-person-circle text-light ms-2"></i></a>
          </div>
        </header>

        <section class="riwayat-section">

          <div class="row">
            <h2 class="col-md-12">Tambah Petugas</h2>
          </div>


          <button class="btn btn-primary mb-2 mt-4 p-3 " type="button" onclick="openPetugasModal()">

            <i class="fas fa-plus-circle me-2"></i>TAMBAH PETUGAS

          </button>


          <table class="my-3 table-primary table table-hover ">

            <thead>

              <tr>
                <th scope="col">NO</th>
                <th scope="col">Nama Petugas</th>
                <th scope="col">Email Petugas</th>
                <th scope="col">NO HP</th>
                <th scope="col">AKSI</th>
              </tr>

            </thead>

            <tbody>
              <?php

              $n = 1;

              //ambil table petugas 
              $F_riwatat = mysqli_query($conn, "select * from petugas order by id_petugas DESC");

              //tampilkan list petugas
              if (mysqli_num_rows($F_riwatat) > 0) {

                while ($row = mysqli_fetch_assoc($F_riwatat)) {

                  //ambil data_diri_petugas
                  $idP = $row['id_petugas'];
                  $F_data_diri = mysqli_query($conn, "select id_petugas from data_diri_petugas where id_petugas =$idP");

                  //validasi jika sudah ada data diri disabled
                  if (mysqli_num_rows($F_data_diri) > 0) {
                    $dis = "disabled";
                  } else {
                    $dis = "";
                  }


                  echo '

<tr>
<td class=" text-black">' . $n++ . '</td>
<td>' . $row['nama_petugas'] . '</td>
<td> ' . $row['email'] . ' </td>
<td>' . $row['no_hp_petugas'] . '</td>
<td>

<form id="formAlasan" action="../admin-config/_tambah-data-petugas.php" method="POST">

 <input type="hidden" name="nama" id="hidden_nama">
    <input type="hidden" name="hp" id="hidden_hp">
    <input type="hidden" name="email" id="hidden_email">
    <input type="hidden" name="tl" id="hidden_tl">
    <input type="hidden" name="tgl" id="hidden_tgl">
    <input type="hidden" name="jk" id="hidden_jk">
    <input type="hidden" name="id_petugas" id="idP" >
    
<button ' . $dis . ' 
    type="button" 
    onclick="isiDataDiri(' . $row['id_petugas'] . ')" 
    class="btn btn-info btn-sm me-1 edit-button">
    <i class="fas fa-edit"></i> Isi Profil
</button>
</form>

   </td>
</tr>



';
                }
              }
              ?>



            </tbody>
          </table>

        </section>
        <footer class="container-fluid">
          <p>AJUK - Copyright © 2025. All rights reserved.</p>
        </footer>
      </main>
    </div>

  </div>

</body>
<script src="../bootstrap/js/bootstrap.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

  //modal tambah petugas alert
  function openPetugasModal() {

    Swal.fire({
      title: 'Tambah Petugas',
      width: '600px',
      padding: '2rem',
      background: '#37383b',
      color: '#f0f0f0',

      html: `
            <div style="display:flex; flex-direction:column; gap:18px; margin-top:10px;">
                       <input id="nama" 
                       class="swal2-input swal-large-input"
                       placeholder="Nama Petugas">

                <input id="email"
                       class="swal2-input swal-large-input"
                       placeholder="Email"
                       type="email">

                <input id="password"
                       class="swal2-input swal-large-input"
                       placeholder="Password"
                       type="password">

                <input id="nohp"
                       class="swal2-input swal-large-input"
                       placeholder="No HP"
                       type="number">
            </div>
        `,

      showCancelButton: true,
      confirmButtonText: 'Kirim',
      cancelButtonText: 'Batal',
      focusConfirm: false,

      preConfirm: () => {
        const nama = document.getElementById('nama').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const nohp = document.getElementById('nohp').value;

        if (!nama || !email || !password || !nohp) {
          Swal.showValidationMessage("Semua field harus diisi!");
          return false;
        }

        // Kirim POST ke server
        return fetch("../admin-config/_tambah-petugas.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `nama=${encodeURIComponent(nama)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&nohp=${encodeURIComponent(nohp)}`
          })
          
      }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire("Berhasil!", "Data petugas berhasil ditambahkan.", "success");
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
    });
  }


  //modal isi data diri petugas
  function isiDataDiri(v) {
    Swal.fire({
      title: "Isi Data Diri Petugas",
      width: "500px",
      background: "#2f3033",
      color: "#efefef",

      html: `
          <div style="display:flex; text-align:left; flex-direction:column; gap:18px; margin-top:10px;">
                <label>Nama Petugas </label>
                <input id="nama_lengkap"
                       class="swal2-input swal-large-input"
                       placeholder="Nama Lengkap" >

                <label>No HP Petugas</label>
                <input id="no_hp"
                       class="swal2-input swal-large-input"
                       placeholder="Nomor HP"
                       type="number">

                <label>Email Pribadi Petugas</label>
                <input id="email"
                       class="swal2-input swal-large-input"
                       placeholder="Email"
                       type="email">

                <label>Tempat Lahir Petugas</label>
                <input id="tempat_lahir"
                       class="swal2-input swal-large-input"
                       placeholder="Tempat Lahir">

                <label>Taggal Lahir Petugas</label>
                <input id="tanggal_lahir"
                       class="swal2-input swal-large-input"
                       type="date">

                <label>Jenis Kelamin</label>
                <select id="jenis_kelamin"
                        class="swal2-input text-light"
                        style="background:#2f3033;">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-Laki">Laki-Laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>

            </div>
        `,

      showCancelButton: true,
      confirmButtonText: "Kirim",
      cancelButtonText: "Batal",

      preConfirm: () => {

        //ambil dari modal form
        const nama = document.getElementById("nama_lengkap").value;
        const hp = document.getElementById("no_hp").value;
        const email = document.getElementById("email").value;
        const tl = document.getElementById("tempat_lahir").value;
        const tgl = document.getElementById("tanggal_lahir").value;
        const jk = document.getElementById("jenis_kelamin").value;

        //validasi
        if (!nama || !hp || !email || !tl || !tgl || !jk) {
          Swal.showValidationMessage("Semua field wajib diisi!");
          return false;
        }

        //kembalikan data
        return {
          nama,
          hp,
          email,
          tl,
          tgl,
          jk
        };
      }
    }).then((result) => {

      if (result.isConfirmed) {
        const data = result.value;

        // Kirim ke form hidden jika perlu
        const f = document.getElementById("formAlasan");

        f.id_petugas.value = v;
        f.hidden_nama.value = data.nama;
        f.hidden_hp.value = data.hp;
        f.hidden_email.value = data.email;
        f.hidden_tl.value = data.tl;
        f.hidden_tgl.value = data.tgl;
        f.hidden_jk.value = data.jk;

        f.submit();

        Swal.fire({
          icon: "success",
          title: "Berhasil!",
          text: "Data berhasil disimpan.",
          timer: 2000,
          showConfirmButton: true
        })
      }

    });
  }


</script>


</html>