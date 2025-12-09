<?php 

$email_eror = $_GET['email_eror']??'';
$pass_eror = $_GET['errpass']??'';

?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk AJUK</title>

    <!-- ===========linkk========== -->
    <link rel="stylesheet" href="bootstrap\css\bootstrap.min.css" />
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="./css/login.css" />
  </head>

  <body>

    <!-- main -->
    <main class="daftar d-flex justify-content-center ">

      <!-- back icons -->
      <a class="  text-light fw-bold p-3 back " onclick="history.back()"><i class="bi bi-arrow-left-circle"></i></a>
      <!-- =======MODAL LUPA pASSWORD============ -->
      <div class="lupa" id="lupa">
        <div class="container-fluid warp-card text-black  d-flex align-items-center justify-content-center">
          <div class="card-body col-md-10 my-4 rounded-3 moll p-4 flex-column">
            <label class="col-10 "><a>
              <i class="text-dark bi-x fs-1 fw-bold " onclick="lupa(event)"></i></a>
            </label>

            <label class="text-white col-10">Email</label>
            <input class="col-12 mt-2 p-2 fs-6" type="email" name="" id="" required />
            <p class="fw-normal text-dark">
              masukkan email pemulihan anda pastikan aktif
            </p>
            <button class="btn btn-secondary bg-black" onclick="lupa(event)" type="submit">
              Kirim
            </button>
          </div>
        </div>
      </div>

      <div class="container py-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="d-flex justify-content-center align-center">
              <img class="" src="./assets/logo.svg" alt="" />
            </div>

            <div class="card mt-4">
              <div class="card-body mt-3">
                <h3>LOGIN</h3>

                <form action="./config/_login.php" method="POST">
                  <div class="alert-container "></div>
                  <label class="form-label">Email (aktif)</label>
                  <input  type="email" value="" name="email" id="email" class="form-control " required placeholder="" />
                  <small class="text-danger  fw-bold fs-6"> <?php echo $email_eror?></small><br>

                  <label class="form-label mt-3">Password</label>
                  <input  type="password" name="password"  id="password" class="form-control" required />
                  <small class="text-danger  fw-bold fs-6">  <?php echo $pass_eror?></small>

                  <!-- tombol kirim -->
                  <div class="d-grid mt-5">
                    <button title="login" class="btn btn-dark loginBtn" type="submit" name="kirim"
                      onclick="login(event)">
                      Login
                    </button>
                  </div>
                  <!-- pesan lupa password -->
                  <div class="koll container-fluid d-flex justify-content-between fs-8">
                    <p class="mt-2 fs-8 fw-medium">Belum Punya Akun? <a class="fs-8 fw-medium" href="daftar.html">Daftar</a>
                    </p>

                    <a class="koll fs-8 mt-2 fs-8 fw-normal " href="" onclick="lupa(event)">lupa password</a>
                  </div>


                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <footer>
      <p class="fw-bold">AJUK - Copyright © 2025. All rights reserved.</p>
    </footer>
  </body>

  <script>
  function lupa(event) {
    event.preventDefault();
    const lupa = document.getElementById("lupa");
    if (lupa.style.display === "none" || lupa.style.display === "") {
      lupa.style.display = "block";
    } else {
      lupa.style.display = "none";
    }
  }
  </script>
</html>
