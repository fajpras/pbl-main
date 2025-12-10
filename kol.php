<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan path config benar
include "config/kol.php"; 
include "config/auth.php";

// MENANGKAP PARAMETER DARI URL & SANITASI
$id_surat    = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
$jenis_surat = isset($_GET['dok']) ? mysqli_real_escape_string($conn, $_GET['dok']) : '';
$id_warga    = isset($_GET['idw']) ? mysqli_real_escape_string($conn, $_GET['idw']) : '';

$data_final = []; 
$template_mode = '';

// --- FUNGSI BANTUAN ---
function tgl_indo($tanggal){
  if(empty($tanggal) || $tanggal == '0000-00-00') return "-";
  $bulan = array (1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
  $pecahkan = explode('-', $tanggal);
  if(count($pecahkan) < 3) return $tanggal;
  return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

function getRomawi($bln){
  $romawi = array("", "I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
  return isset($romawi[(int)$bln]) ? $romawi[(int)$bln] : "";
}

// LOGIKA PENGAMBILAN DATA (Sama seperti sebelumnya)
if($id_surat && $jenis_surat && $id_warga) {
  $q_main = mysqli_query($conn, "SELECT pada, tanggal FROM dokumens WHERE id_surat = '$id_surat'");
  $r_main = mysqli_fetch_assoc($q_main);

  if ($r_main) {
    $tgl_basis = !empty($r_main['pada']) ? $r_main['pada'] : ($r_main['tanggal'] ?? date('Y-m-d'));
    $bulan_surat = date('n', strtotime($tgl_basis));
    $tahun_surat = date('Y', strtotime($tgl_basis));
    $romawi      = getRomawi($bulan_surat);

    $q_urutan = mysqli_query($conn, "SELECT COUNT(*) as no_urut FROM dokumens WHERE nama_dokumen = '$jenis_surat' AND (status = 'SELESAI' OR status = 'DISETUJUI') AND id_surat <= '$id_surat'");
    $r_urutan = mysqli_fetch_assoc($q_urutan);
    $no_urut_str = sprintf("%03d", $r_urutan['no_urut']);
    $hasil_no_surat = $no_urut_str . " / " . $jenis_surat . " / " . $romawi . " / " . $tahun_surat;
  } else {
    $hasil_no_surat = "000 / XXX / X / " . date('Y');
  }

  $q_profil = mysqli_query($conn, "SELECT * FROM data_diri WHERE id_warga = '$id_warga'");
  $row_profil = mysqli_fetch_assoc($q_profil);

  $tabel_surat = "";
  switch ($jenis_surat) {
    case 'SKK': $tabel_surat = "dokumen_skk"; break;
    case 'SKTM': $tabel_surat = "dokumen_sktm"; break;
    case 'SIU': $tabel_surat = "dokumen_izin_usaha"; break;
    case 'SRM': $tabel_surat = "dokumen_rumah"; break;
    case 'SDM': $tabel_surat = "dokumen_domisili"; break;
  }

  $row_surat = [];
  if($tabel_surat) {
    $q_surat = mysqli_query($conn, "SELECT * FROM $tabel_surat WHERE id_surat = '$id_surat'");
    $row_surat = mysqli_fetch_assoc($q_surat);
  }

  if($row_profil || $row_surat) {
    $nama       = $row_profil['nama_lengkap'] ?? $row_surat['nama_lengkap'] ?? '-';
    $nik        = $row_profil['nik'] ?? $row_surat['nik'] ?? '-';
    $pekerjaan  = $row_profil['pekerjaan'] ?? $row_surat['pekerjaan'] ?? '-';
    $agama      = $row_profil['agama'] ?? $row_surat['agama'] ?? '-';
    $alamat     = $row_profil['alamat'] ?? $row_surat['alamat'] ?? '-';
    $jk         = $row_profil['jenis_kelamin'] ?? $row_surat['jenis_kelamin'] ?? '-';

    $tmp_lahir  = $row_profil['tempat_lahir'] ?? '';
    $tgl_lahir  = tgl_indo($row_profil['tanggal_lahir'] ?? '');
    $ttl_gabung = "$tmp_lahir, $tgl_lahir";

    $umur = '-';
    if(isset($row_profil['tanggal_lahir']) && $row_profil['tanggal_lahir'] != '0000-00-00') {
      $diff = (new DateTime())->diff(new DateTime($row_profil['tanggal_lahir']));
      $umur = $diff->y . " Tahun";
    }

    $data_umum = [
      'nomor_surat' => $hasil_no_surat, 'Nama' => $nama, 'NIK' => $nik, 'Jenis Kelamin' => $jk,
      'Pekerjaan' => $pekerjaan, 'Agama' => $agama, 'Alamat' => $alamat,
      'Tempat, Tanggal Lahir' => $ttl_gabung, 'Umur' => $umur,
      'Kewarganegaraan' => 'Indonesia', 'Status Perkawinan' => 'Kawin'
    ];

    switch ($jenis_surat) {
      case 'SKK': $template_mode = 'kematian';
        $data_final = array_merge($data_umum, ['tgl_wafat' => isset($row_surat['tanggal_kematian']) ? tgl_indo($row_surat['tanggal_kematian']) : '-', 'penyebab' => $row_surat['penyebab'] ?? '-']); break;
      case 'SKTM': $template_mode = 'sktm'; $data_final = $data_umum; break;
      case 'SIU': $template_mode = 'usaha';
        $data_final = array_merge($data_umum, ['Jenis usaha' => $row_surat['nama_kbli'] ?? '-', 'Kode KBLI' => $row_surat['nomor_kbli'] ?? '-', 'Nama KBLI' => $row_surat['nama_kbli'] ?? '-']); break;
      case 'SRM': $template_mode = 'rumah'; $data_final = $data_umum; break;
      case 'SDM': $template_mode = 'pindah';
        $data_final = array_merge($data_umum, ['Alamat Asal' => $alamat, 'Pindah Ke' => $row_surat['alamat_pindah'] ?? '-']); break;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Dokumen</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background-color: #525659; font-family: "Times New Roman", Times, serif; display: flex; flex-direction: column; align-items: center; min-height: 100vh; padding: 20px; }

    .ui-controls { position: fixed; top: 0; left: 0; width: 100%; background-color: #2c3e50; padding: 15px; display: flex; justify-content: space-between; align-items: center; z-index: 1000; color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
    .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; color: white; margin-left: 10px; font-family: sans-serif; }
    .btn-back { background-color: #e74c3c; }
    .btn-download { background-color: #2ecc71; }
    .btn:hover { opacity: 0.9; }

    #contentToPrint { 
      background-color: white; 
      width: 210mm; 
      min-height: 297mm; 
      padding: 20mm 25mm; 
      margin-top: 60px; 
      margin-bottom: 20px; 
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); 
      position: relative; 
    }

    .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double black; padding-bottom: 10px; }
    .header h3 { font-size: 14pt; font-weight: normal; margin: 0; }
    .header h1 { font-size: 18pt; font-weight: bold; margin: 5px 0; }
    .header p { font-size: 10pt; font-style: italic; margin: 0; }

    .judul-surat { text-align: center; margin-bottom: 25px; margin-top: 20px; }
    .judul-surat u { font-weight: bold; font-size: 12pt; text-transform: uppercase; display: block; }
    .judul-surat span { font-size: 12pt; display: block; margin-top: 5px; }

    .content { font-size: 12pt; line-height: 1.5; text-align: justify; }
    .biodata-table { width: 100%; margin: 10px 0 10px 20px; }
    .biodata-table td { vertical-align: top; padding-bottom: 3px; font-size: 12pt; }
    .label-cell { width: 180px; }
    .colon-cell { width: 20px; text-align: center; }

    .signature-section { margin-top: 40px; float: right; width: 40%; text-align: left; }
    .signature-space { height: 70px; }
    .signer-name { font-weight: bold; text-decoration: underline; text-transform: uppercase; }
    .text-bold { font-weight: bold; }
    </style>
  </head>
  <body>

    <div class="ui-controls">
      <div>
        <button class="btn btn-back" onclick="history.back()">&#8592; Kembali</button>
        <?php if(empty($data_final)): ?>
        <span style="color: #ff6b6b; margin-left: 10px;">Data Kosong!</span>
        <?php else: ?>
        <span style="margin-left: 10px;">Surat: <strong><?php echo $jenis_surat; ?></strong></span>
        <?php endif; ?>
      </div>
      <button class="btn btn-download" onclick="downloadPDF()">Download PDF</button>
    </div>

    <div class="page" id="contentToPrint">
      <div class="header">
        <h3>PEMERINTAH KOTA BATAM</h3>
        <h3>KECAMATAN APAYAAA</h3>
        <h1>KELURAHAN ISEKAI</h1>
        <p>Jl. Saturnus No. 123 telp/fax (+66) 1234-5678-9822 Kota Batam</p>
      </div>

      <div id="dynamicContent"></div>
      <div class="signature-section">
        <p>BATUKU , <?php echo tgl_indo(date('Y-m-d')); ?></p>
        <p>Kepala Kelurahan,</p>
        <div class="signature-space"></div>
        <p class="signer-name">BINTANG DWI</p>
      </div>
    </div>

    <script>
    const dbData = <?php echo json_encode($data_final); ?>;
    const currentMode = "<?php echo $template_mode; ?>";
    const jenisSurat = "<?php echo $jenis_surat; ?>";

    function downloadPDF() {
      const element = document.getElementById('contentToPrint');

      let filename = "Dokumen.pdf";
      if(dbData && dbData.Nama) {
        let cleanName = dbData.Nama.replace(/[^a-zA-Z0-9]/g, "_");
        filename = `${jenisSurat}_${cleanName}.pdf`;
      }

      var opt = {
        margin:       0, // Margin 0 karena di CSS .page sudah ada padding
        filename:     filename,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 }, // Scale 2 agar teks tajam (tidak pecah)
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };

      const btn = document.querySelector('.btn-download');
      const oldText = btn.innerText;
      btn.innerText = "Memproses...";
      btn.disabled = true;

      html2pdf().set(opt).from(element).save().then(function(){
        btn.innerText = oldText;
        btn.disabled = false;
      });
    }

    const templates = {
      kematian: {
        judul: "SURAT KETERANGAN KEMATIAN",
        fields: ["Nama", "NIK", "Jenis Kelamin", "Umur", "Pekerjaan", "Agama", "Kewarganegaraan", "Status Perkawinan"],
        bodyText: (data) => `Menyatakan yang bersangkutan diatas benar <span class="text-bold">berdomisili</span> di Kelurahan Isekai Kecamatan Apayaaa Kota Batam dan telah <span class="text-bold">meninggal dunia</span> di Kelurahan Isekai pada tanggal ${data.tgl_wafat || '...'} dikarenakan <span class="text-bold">${data.penyebab || 'sakit'}</span>.`
      },
      pindah: {
        judul: "SURAT KETERANGAN DOMISILI",
        fields: ["Nama", "NIK", "Tempat, Tanggal Lahir", "Jenis Kelamin", "Pekerjaan", "Agama", "Kewarganegaraan", "Status Perkawinan", "Alamat Asal", "Pindah Ke"],
        bodyText: (data) => `Menyatakan bahwa yang bersangkutan benar merupakan warga Kelurahan Isekai Kecamatan Apayaa Kota Batam dan berdomisili pada alamat tersebut.`
      },
      rumah: {
        judul: "SURAT KETERANGAN KEPEMILIKAN RUMAH",
        fields: ["Nama", "NIK", "Tempat, Tanggal Lahir", "Jenis Kelamin", "Pekerjaan", "Alamat"],
        bodyText: (data) => `Menyatakan bahwa yang bersangkutan benar merupakan warga Kelurahan Isekai Kecamatan Apayaa Kota Batam, dan selanjutnya diterangkan bahwa nama tersebut di atas benar memiliki rumah atau tempat tinggal pada alamat sebagaimana tercantum di atas.`
      },
      usaha: {
        judul: "SURAT IZIN USAHA",
        fields: ["Nama", "NIK", "Tempat, Tanggal Lahir", "Jenis Kelamin", "Agama", "Pekerjaan", "Alamat"],
        bodyText: (data) => `Menyatakan bahwa yang bersangkutan benar merupakan warga Kelurahan Isekai Kecamatan Apayaa Kota Batam, dan selanjutnya diterangkan bahwa yang bersangkutan memiliki usaha yang berlokasi di ${data.Alamat || 'wilayah'} kelurahan isekai.`,
        extraFields: ["Jenis usaha", "Kode KBLI", "Nama KBLI"]
      },
      sktm: {
        judul: "SURAT KETERANGAN TIDAK MAMPU",
        fields: ["Nama", "NIK", "Tempat, Tanggal Lahir", "Agama", "Jenis Kelamin", "Pekerjaan", "Alamat"],
        bodyText: (data) => `Menyatakan bahwa yang bersangkutan benar merupakan warga Kelurahan Isekai Kecamatan Apayaa Kota Batam, dan selanjutnya menerangkan bahwa nama tersebut di atas termasuk keluarga tidak mampu serta telah terdaftar dalam Data Terpadu Kesejahteraan Sosial (DTKS).`
      }
    };

    function renderSurat() {
      if (!currentMode || !templates[currentMode]) {
        document.getElementById('dynamicContent').innerHTML = '<p style="text-align:center; margin-top:50px;">Data belum tersedia.</p>'; return;
      }
      const template = templates[currentMode];
      let rowsHTML = '';
      template.fields.forEach(field => {
        let val = dbData[field] || "";
        if(!val && field.includes("Lahir")) val = dbData["Tempat, Tanggal Lahir"];
        if(!val && field.includes("kelamin")) val = dbData["Jenis Kelamin"];
        rowsHTML += `<tr><td class="label-cell">${field}</td><td class="colon-cell">:</td><td>${val || "................................................"}</td></tr>`;
      });

      let extraHTML = '';
      if (template.extraFields) {
        let extraRows = '';
        template.extraFields.forEach(field => {
          extraRows += `<tr><td class="label-cell">${field}</td><td class="colon-cell">:</td><td>${dbData[field] || "................................................"}</td></tr>`;
        });
        extraHTML = `<table class="biodata-table" style="margin-top:10px;">${extraRows}</table>`;
      }

      let bodyTxt = typeof template.bodyText === "function" ? template.bodyText(dbData) : template.bodyText;

      document.getElementById('dynamicContent').innerHTML = `
<div class="judul-surat"><u>${template.judul}</u><span>Nomor : ${dbData.nomor_surat}</span></div>
<div class="content">
  <p>Yang bertanda Tangan dibawah ini, Kepala Kelurahan Isekai Kecamatan Apayaaa Kota Batam menerangkan dengan sesungguhnya bahwa:</p>
  <table class="biodata-table">${rowsHTML}</table>
  <p>${bodyTxt}</p>
  ${extraHTML}
  <p style="margin-top: 15px;">Demikian Surat Keterangan ini kami buat dengan sebenarnya dan dapat dipergunakan sebagaimana mestinya.</p>
</div>
`;
    }
    window.onload = renderSurat;
    </script>
  </body>
</html>
