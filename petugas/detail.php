<?php
// --- CONFIG & AUTH ---
include "../config/kol.php";
include('../config/auth.php');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validasi Login
if (!isset($_SESSION['id_petugas'])) {
  echo "<script>alert('Anda tidak memiliki akses!'); window.close();</script>";
  exit();
}

// Ambil parameter
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$dok = isset($_GET['dok']) ? $_GET['dok'] : '';
$apa = isset($_GET['a']) ? $_GET['a'] : '';

// Validasi Parameter Kosong
if ($id == 0 || empty($dok) || empty($apa)) {
  die("Parameter URL tidak lengkap.");
}

// --- LOGIKA PEMILIHAN KOLOM DATABASE ---

// 1. SKTM
if ($dok == "SKTM") {
  $tabel = "dokumen_sktm";
  $kolom = match ($apa) {
    "fsp" => "foto_persetujuan",
    "fr"  => "foto_rumah",
    "fkk" => "foto_kk",
    "fsg" => "foto_slip_gaji",
    default => "foto_tagihan"
  };

  // 2. SURAT KEMATIAN
} elseif ($dok == "SKK") {
  $tabel = "dokumen_skk";
  $kolom = match ($apa) {
    "fsrs" => "foto_surat_RS",
    "fkp"  => "foto_ktp_pelapor",
    "fsp"  => "foto_surat_pengantar",
    default => "foto_akte_nikah",
  };

  // 3. SURAT RUMAH
} elseif ($dok == "SRM") {
  $tabel = "dokumen_rumah";
  $kolom = match ($apa) {
    "fs"   => "foto_sertifikat",
    "fam"  => "foto_akta_mendirikan",
    "fkk"  => "foto_kk",
    "fktp" => "foto_ktp",
    "fbp"  => "foto_BPPBB", // Perbaikan: fbp untuk BPPBB
    "fsts" => "foto_surat_tidak_sengketa", // Perbaikan: fsts untuk tidak sengketa
    default => "foto_surat_tidak_sengketa",
  };

  // 4. SURAT IZIN USAHA
} elseif ($dok == "SIU") {
  $tabel = "dokumen_izin_usaha";
  $kolom = match ($apa) {
    "fnpwp" => "foto_npwp",
    "fsp"   => "foto_pengantar", // Pastikan nama kolom di DB sesuai (foto_pengantar / foto_surat_pengantar)
    "fkk"   => "foto_kk",
    "fktp"  => "foto_ktp",
    "fsd"   => "foto_surat_domisili",
    "fpbb"  => "foto_bukti", // PENAMBAHAN: Pajak Bumi & Bangunan
    default => "foto_surat_domisili",
  };

  // 5. SURAT DOMISILI (Default)
} else {
  $tabel = "dokumen_domisili";
  $kolom = match ($apa) {
    "fsp" => "foto_surat_pengantar",
    "fp"  => "foto_pas", // PENAMBAHAN: Pas Foto
    default => "foto_kk",
  };
}

// --- EKSEKUSI QUERY ---
$query = "SELECT `$kolom` FROM `$tabel` WHERE id_surat = $id LIMIT 1";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Cek Data
if (!$data || empty($data[$kolom])) {
  die("<h3 style='text-align:center; margin-top:50px;'>File/Foto belum diunggah atau data kosong.</h3>");
}

$blobData = $data[$kolom];

// --- DETEKSI TIPE FILE (PDF vs GAMBAR) ---
// Kita cek header binary file untuk menentukan MIME type secara otomatis
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->buffer($blobData);

// Encode ke Base64 untuk ditampilkan
$base64 = base64_encode($blobData);
$src = "data:$mimeType;base64,$base64";

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lihat Dokumen</title>
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
      height: 100%;
      background-color: #525659;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
      box-sizing: border-box;
    }

    img {
      max-width: 100%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
      border-radius: 4px;
    }

    .pdf-container {
      width: 100%;
      height: 100vh;
    }
  </style>
</head>

<body>

  <?php if ($mimeType === 'application/pdf') : ?>
    <div class="pdf-container">
      <embed src="<?= $src ?>" type="application/pdf" width="100%" height="100%">
    </div>

  <?php else : ?>
    <div class="container">
      <img src="<?= $src ?>" alt="Dokumen Warga">
    </div>

  <?php endif; ?>

</body>

</html>