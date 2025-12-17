<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("kol.php");
// Inisialisasi variabel
$step = 1;
$error = "";
$username_found = "";

// LOGIKA UTAMA
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['username_reset']) && isset($_POST['email_reset'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username_reset']);
        $email = mysqli_real_escape_string($conn, $_POST['email_reset']);

        // Cek di tabel warga
        $query = "SELECT * FROM warga WHERE nama = ? AND email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $step = 2;
            $username_found = $username;
        } else {
            header("Location: ../login.php?email_eror=Data tidak ditemukan!");
            exit();
        }
    }

    // --- KONDISI B: PENGGUNA MENGIRIM PASSWORD BARU (Proses Update) ---
    elseif (isset($_POST['btn_update_pass'])) {
        $user_to_update = $_POST['hidden_username'];
        $pass1 = $_POST['pass_baru'];
        $pass2 = $_POST['konfirmasi_pass'];

        if ($pass1 === $pass2) {
            // Hashing Password
            $hashed_password = password_hash($pass1, PASSWORD_DEFAULT);

            // Update Database
            $update_query = "UPDATE warga SET password = ? WHERE nama = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ss", $hashed_password, $user_to_update);

            if ($stmt->execute()) {
                // Berhasil, kembali ke login
                echo "<script>
                        alert('Password berhasil diubah! Silakan login.');
                        window.location.href='../login.php';
                      </script>";
                exit();
            } else {
                $error = "Gagal mengupdate database.";
                $step = 2; // Tetap di form
                $username_found = $user_to_update;
            }
        } else {
            $error = "Password konfirmasi tidak sama!";
            $step = 2; // Tetap di form
            $username_found = $user_to_update;
        }
    }
} else {
    // Jika user mencoba buka file ini langsung tanpa lewat form
    header("Location: login.php");
    exit();
}
?>

<?php if ($step == 2): ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password | AJUK</title>
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
        <style>
            body {
                background-color: #f5f5f5;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .reset-card {
                background: white;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 450px;
            }

            .form-control:focus {
                border-color: #000;
                box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>

    <body>

        <div class="reset-card">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Buat Password Baru</h3>
                <p class="text-secondary small">Halo <b><?php echo htmlspecialchars($username_found); ?></b>, silakan buat password baru Anda dengan ketentuan A-Z, 0-9, simbol.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 fs-6"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <input type="hidden" name="hidden_username" value="<?php echo htmlspecialchars($username_found); ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold small">Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control" name="pass_baru" placeholder="Minimal 6 karakter" required minlength="6">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small">Ulangi Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-check-circle"></i></span>
                        <input type="password" class="form-control" name="konfirmasi_pass" placeholder="Ketik ulang password" required>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="btn_update_pass" class="btn btn-dark fw-bold py-2">Simpan Password</button>
                    <a href="login.php" class="btn btn-light text-secondary small">Batal</a>
                </div>
            </form>
        </div>

    </body>

    </html>
<?php endif; ?>