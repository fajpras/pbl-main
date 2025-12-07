<?php 


$err = $_GET['err'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Daftar AJUK</title>

  <!-- ================LINK============ -->
  <link rel="stylesheet" href="bootstrap\css\bootstrap.min.css" />
  <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/daftar.css" />

</head>


<body>
  <!-- main content -->
  <main content class="daftar d-flex justify-content-center">
    <!-- back -->
    <a class="text-light fw-bold p-3 back " onclick="history.back()"><i class="bi bi-arrow-left-circle"></i></a>
    <div class="container py-5 pb-4">
      <div class="row d-flex justify-content-center">
        <div class="col-md-6">
          <div class="text-center">
            <img src="./assets/logo.svg" alt="" />
          </div>

          <!-- card form -->
          <div class="card mt-4">
            <div class="card-body mt-3">
              <h3 class="pt-2">Daftar Akun Baru</h3>

              <!-- form content -->
              <form action="./config/_daftar.php" method="POST">
                <label class="form-label">Nama</label>
                <input type="text" value="<?php echo $_POST['nama']; ?>" name="nama" id="nama" class="form-control" required placeholder="" />


                <label class="form-label mt-3 ">Email (aktif)</label>
                <input type="email" name="email" value="<?php echo $_POST['email']; ?>" id="email" class="form-control" required placeholder="" />
                <small class="fs-8 text-danger"><?php echo $erremail ?></small>

                <label class="form-label mt-3">Password</label>
                <input type="password" name="password" value="<?php $_POST['password']; ?>" class="form-control" required minlength="6" />
                <small class="fs-8 " style="color:yellow;"><?php echo $err ?></small>

                <div class="d-grid mt-5 ">
                  <button title="Masuk" class="btn btn-dark masukBtn" type="submit" onclick="daftar()" name="kirim">
                    Daftar
                  </button>
                </div>
                <p class="mt-2 fw-medium fs-8">
                  Sudah Punya Akun? <a title="login" href="login.html">Login</a>
                </p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer>
    <p>AJUK - Copyright © 2025. All rights reserved.</p>
  </footer>
</body>
<script>
  // button --> dashboard
  /* function daftar() { */
  /*   window.location.href = "dashboard.html"; */
  /*   alert('Login berhasil') */
  /* } */
</script>
<script src="./script/script.js"></script>

</html>
