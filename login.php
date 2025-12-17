<?php 
// ambil kalo eror
$email_eror = $_GET['email_eror'] ?? '';
$pass_eror = $_GET['errpass'] ?? '';

$has_error = !empty($email_eror) || !empty($pass_eror);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk | AJUK</title>

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="./css/login.css" />
    
</head>

<body>

    <main class="daftar d-flex justify-content-center">
        <a class="text-light fw-bold p-3 back" onclick="history.back()"><i class="bi bi-arrow-left-circle"></i></a>
        
        <!--    
        ===========================
        modal kalo lupa password
        ============================
        -->
        <div id="lupa" class="lupa-overlay">
            <div class="lupa-content">
                <div class="lupa-header">
                    <h5>Reset Password</h5>
                    <i class="bi bi-x btn-close-custom" onclick="lupa(event)"></i>
                </div>

                <div class="lupa-body">
                    <p class="lupa-info text-center">
                        Masukkan username dan email yang terdaftar. Kami akan mengirimkan instruksi pemulihan.
                    </p>
                    
                    <form action="config/_reset_password.php" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="username_reset" placeholder="Masukkan username" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" name="email_reset" placeholder="nama@email.com" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-dark py-2 fw-bold" type="submit">
                                <i class="bi bi-send me-2"></i> Kirim 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- main kontent -->

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-md-8 col-sm-12">
                    <div class="d-flex justify-content-center align-center">
                        <img class="" src="./assets/logo.svg" alt="" />
                    </div>

                    <div class="card mt-4 shadow-sm">
                        <div class="card-body mt-3 p-4">
                            <h3 class="mb-2 fw-bold">LOGIN</h3>

                            <form action="./config/_login.php" method="POST">
                                
                            <!-- muncul pop up eror  -->
                                <?php if($has_error): ?>
                                <div class="alert-custom">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <div>
                                        <p>Login Gagal</p>
                                        <small style="color:#721c24;">Periksa kembali email dan password Anda.</small>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <label class="form-label fw-semibold">Email (aktif)</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control <?php echo !empty($email_eror) ? 'is-invalid' : ''; ?>" 
                                       required 
                                       placeholder="contoh@email.com" />
                                
                                <?php if(!empty($email_eror)): ?>
                                    <div class="error-message">
                                        <i class="bi bi-info-circle-fill"></i> 
                                        <span><?php echo $email_eror ?></span>
                                    </div>
                                <?php endif; ?>

                                <label class="form-label mt-3 fw-semibold">Password</label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control <?php echo !empty($pass_eror) ? 'is-invalid' : ''; ?>" 
                                       required />

                                <?php if(!empty($pass_eror)): ?>
                                    <div class="error-message">
                                        <i class="bi bi-x-circle-fill"></i> 
                                        <span><?php echo $pass_eror ?></span>
                                    </div>
                                <?php endif; ?>


                                <!-- main utama form -->
                                <div class="d-grid mt-5">
                                    <button title="login" class="btn btn-dark loginBtn py-2 fw-bold" type="submit" name="kirim">
                                        Login
                                    </button>
                                </div>

                                <div class="koll container-fluid d-flex justify-content-between fs-8 mt-3 align-items-center">
                                    <p class="mb-0 fs-8 fw-medium">Belum Punya Akun? <a class="fs-8 fw-bold text-dark text-decoration-none" href="daftar.php">Daftar</a>
                                    </p>

                                    <a class="koll text-black fs-8 fw-normal text-decoration-none" href="#" onclick="lupa(event)">Lupa Password?</a>
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
        if(event) event.preventDefault();
        const lupa = document.getElementById("lupa");
        
        if (lupa.style.display === "flex") {
            lupa.style.display = "none";
        } else {
            lupa.style.justifyContent = "center";
        }
    }

    window.onclick = function(event) {
        const lupa = document.getElementById("lupa");
        if (event.target == lupa) {
            lupa.style.display = "none";
        }
    }
</script>

</html>