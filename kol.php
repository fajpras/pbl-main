<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "config/kol.php"; 
include ('config/auth.php');

// MENANGKAP PARAMETER DARI URL
$id_surat    = isset($_GET['id']) ? $_GET['id'] : '';
$jenis_surat = isset($_GET['dok']) ? $_GET['dok'] : '';
$id_warga    = isset($_GET['idw']) ? $_GET['idw'] : '';

$data_final = []; 
$template_mode = '';

// --- FUNGSI BANTUAN ---

// 1. Format Tanggal Indonesia
function tgl_indo($tanggal){
  if(empty($tanggal) || $tanggal == '0000-00-00') return "-";
  $bulan = array (
    1 =>   'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
  $pecahkan = explode('-', $tanggal);
  return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

// 2. Format Bulan Romawi
function getRomawi($bln){
  $bulan = intval($bln);
  $romawi = array("", "I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
  return $romawi[$bulan];
}

if($id_surat && $jenis_surat && $id_warga) {

  // A. LOGIKA NOMOR SURAT OTOMATIS
  // -----------------------------------------------------------
  // 1. Ambil Tanggal Disetujui (pada) atau Tanggal Ajuan (tanggal) dari tabel utama 'dokumens'
  $q_main = mysqli_query($conn, "SELECT pada, tanggal FROM dokumens WHERE id_surat = '$id_surat'");
  $r_main = mysqli_fetch_assoc($q_main);

  // Gunakan tanggal 'pada' (ACC) jika ada, jika belum pakai tanggal ajuan, jika tidak ada pakai hari ini
  $tgl_basis = !empty($r_main['pada']) ? $r_main['pada'] : ($r_main['tanggal'] ?? date('Y-m-d'));

  $bulan_surat = date('n', strtotime($tgl_basis));
  $tahun_surat = date('Y', strtotime($tgl_basis));
  $romawi      = getRomawi($bulan_surat);

  // 2. Hitung Urutan Surat  th
  // Menghitung jumlah surat sejenis yang sudah DISETUJUI dan ID-nya <= ID saat ini
  $q_urutan = mysqli_query($conn, "SELECT COUNT(*) as no_urut 
    FROM dokumens 
    WHERE nama_dokumen = '$jenis_surat' 
    AND status = 'SELESAI' or status = 'DISETUJUI' 
    AND id_surat = '$id_surat'");
  $r_urutan = mysqli_fetch_assoc($q_urutan);

  // Format 3 digit (001, 002, dst)
  $no_urut_str = sprintf("%03d", $r_urutan['no_urut']);

  // GABUNGKAN VARIABEL (001 / SKTM / XII / 2025)
  $hasil_no_surat = $no_urut_str . " / " . $jenis_surat . " / " . $romawi . " / " . $tahun_surat;
  // -----------------------------------------------------------


  // B. AMBIL DATA BIODATA & DETAIL SURAT
  $q_profil = mysqli_query($conn, "SELECT * FROM data_diri WHERE id_warga = '$id_warga'");
  $row_profil = mysqli_fetch_assoc($q_profil);

  $tabel_surat = "";
  if($jenis_surat == 'SKK') $tabel_surat = "dokumen_skk";
  elseif($jenis_surat == 'SKTM') $tabel_surat = "dokumen_sktm";
  elseif($jenis_surat == 'SIU') $tabel_surat = "dokumen_izin_usaha";
  elseif($jenis_surat == 'SRM') $tabel_surat = "dokumen_rumah";
  elseif($jenis_surat == 'SDM') $tabel_surat = "dokumen_domisili";

  $q_surat = mysqli_query($conn, "SELECT * FROM $tabel_surat WHERE id_surat = '$id_surat'");
  $row_surat = mysqli_fetch_assoc($q_surat);

  if($row_profil || $row_surat) {

    $nama       = $row_profil['nama_lengkap'] ?? $row_surat['nama_lengkap'] ?? '-';
    $nik        = $row_profil['nik'] ?? $row_surat['nik'] ?? '-';
    $pekerjaan  = $row_profil['pekerjaan'] ?? $row_surat['pekerjaan'] ?? '-';
    $agama      = $row_profil['agama'] ?? $row_surat['agama'] ?? '-';
    $alamat     = $row_profil['alamat'] ?? $row_surat['alamat'] ?? '-';
    $jk         = $row_profil['jenis_kelamin'] ?? $row_surat['jenis_kelamin'] ?? '-';

    $tmp_lahir  = $row_profil['tempat_lahir'] ?? '';
    $tgl_lahir  = isset($row_profil['tanggal_lahir']) ? tgl_indo($row_profil['tanggal_lahir']) : '';
    $ttl_gabung = "$tmp_lahir, $tgl_lahir";

    $umur = '-';
    if(isset($row_profil['tanggal_lahir']) && $row_profil['tanggal_lahir'] != '0000-00-00') {
      $biday = new DateTime($row_profil['tanggal_lahir']);
      $today = new DateTime();
      $diff = $today->diff($biday);
      $umur = $diff->y . " Tahun";
        }

        // C. SUSUN DATA UMUM (MASUKKAN NOMOR SURAT KESINI)
        $data_umum = [
          'nomor_surat' => $hasil_no_surat, 
          'Nama' => $nama,
          'NIK' => $nik,
          'Jenis Kelamin' => $jk,
          'Pekerjaan' => $pekerjaan,
          'Agama' => $agama,
          'Alamat' => $alamat,
          'Tempat, Tanggal Lahir' => $ttl_gabung,
          'Umur' => $umur,
          'Kewarganegaraan' => 'Indonesia', 
          'Status Perkawinan' => 'Kawin'
        ];

        // D. GABUNGKAN DENGAN DATA SPESIFIK
        switch ($jenis_surat) {
        case 'SKK': // Kematian
          $template_mode = 'kematian';
          $data_final = array_merge($data_umum, [
            'tgl_wafat' => isset($row_surat['tanggal_kematian']) ? tgl_indo($row_surat['tanggal_kematian']) : '-',
            'penyebab' => $row_surat['penyebab'] ?? '-'
                ]);
          break;

        case 'SKTM': // Tidak Mampu
          $template_mode = 'sktm';
          $data_final = $data_umum; 
          break;

        case 'SIU': // Izin Usaha
          $template_mode = 'usaha';
          $data_final = array_merge($data_umum, [
            'Jenis usaha' => $row_surat['nama_kbli'] ?? '-', 
            'Kode KBLI' => $row_surat['nomor_kbli'] ?? '-',
            'Nama KBLI' => $row_surat['nama_kbli'] ?? '-'
                ]);
          break;

        case 'SRM': // Rumah
          $template_mode = 'rumah';
          $data_final = $data_umum;
          break;

        case 'SDM': // Domisili
          $template_mode = 'pindah';
          $data_final = array_merge($data_umum, [
            'Alamat Asal' => $alamat,
            'Pindah Ke' => '-' 
                ]);
          break;
        }
    }
}
?>

  <!DOCTYPE html>
  <html lang="id">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Surat</title>
  <style>
  /* CSS SAMA SEPERTI SEBELUMNYA */
  * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #525659; font-family: "Times New Roman", Times, serif; display: flex; flex-direction: column; align-items: center; min-height: 100vh; padding: 20px; }

        .ui-controls { position: fixed; top: 0; left: 0; width: 100%; background-color: #2c3e50; padding: 15px; display: flex; justify-content: space-between; align-items: center; z-index: 1000; color: white; }
        .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; color: white; margin-left: 10px;}
        .btn-back { background-color: #e74c3c; }
        .btn-download { background-color: #2ecc71; }

        .page { background-color: white; width: 210mm; min-height: 297mm; padding: 20mm 25mm; margin-top: 60px; margin-bottom: 20px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); position: relative; }

        /* KOP SURAT */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double black; padding-bottom: 10px; }
        .header h3 { font-size: 14pt; font-weight: normal; letter-spacing: 1px; text-transform: uppercase; margin: 0; }
        .header h1 { font-size: 18pt; font-weight: bold; text-transform: uppercase; margin: 5px 0; }
        .header p { font-size: 10pt; font-style: italic; margin: 0; }

        /* BODY SURAT */
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

        @media print {
        body { background-color: white; padding: 0; }
            .ui-controls { display: none !important; }
            .page { margin: 0; box-shadow: none; width: 100%; }
            @page { size: A4; margin: 0; }
        }
    </style>
      </head>
      <body>

      <div class="ui-controls">
      <div>
      <button class="btn btn-back" onclick="history.back()">&#8592; Tutup</button>
      <?php if(empty($data_final)): ?>
      <span style="color: #e74c3c; margin-left: 10px;">Data tidak ditemukan! (ID: <?php echo $id_surat; ?>)</span>
      <?php else: ?>
      <span style="margin-left: 10px;">Jenis Surat : <?php echo $jenis_surat; ?> 
            <?php endif; ?>
        </div>
          <button class="btn btn-download" onclick="window.print()">Cetak / Simpan PDF</button>
          </div>

          <div class="page">
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
// Data dari PHP
const dbData = <?php echo json_encode($data_final); ?>;
const currentMode = "<?php echo $template_mode; ?>";

const templates = {
kematian: {
judul: "SURAT KETERANGAN KEMATIAN",
  fields: ["Nama", "NIK", "Jenis Kelamin", "Umur", "Pekerjaan", "Agama", "Kewarganegaraan", "Status Perkawinan"],
  bodyText: (data) => `Menyatakan yang bersangkutan diatas benar <span class="text-bold">berdomisili</span> di Kelurahan Isekai Kecamatan Apayaaa Kota Batam dan telah <span class="text-bold">meninggal dunia</span> di Kelurahan Isekai pada tanggal ${data.tgl_wafat || '................'} dikarenakan <span class="text-bold">${data.penyebab || 'sakit'}</span>.`
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
            document.getElementById('dynamicContent').innerHTML = '<p style="text-align:center; margin-top:50px;">Data tidak ditemukan atau data belum dipilih.</p>';
            return;
            }

            const template = templates[currentMode];
            const container = document.getElementById('dynamicContent');

            // Render Biodata
            let rowsHTML = '';
            template.fields.forEach(field => {
            let value = dbData[field] || "";

            if(!value && field === "Tempat, Tanggal Lahir") value = dbData["Tempat, Tanggal Lahir"];
            if(!value && field === "Jenis kelamin") value = dbData["Jenis Kelamin"];

            if(value === "" || value === "-") value = ": .......................................................................";
            else value = ": " + value;

            rowsHTML += `
                    <tr>
                        <td class="label-cell">${field}</td>
                        <td class="colon-cell"></td>
                        <td>${value}</td>
                    </tr>`;
            });

            // Render Extra Fields
            let extraHTML = '';
            if (template.extraFields) {
              let extraRows = '';
              template.extraFields.forEach(field => {
              let value = dbData[field] || "";
              if(value === "") value = ": .......................................................................";
              else value = ": " + value;

              extraRows += `
                        <tr>
                            <td class="label-cell">${field}</td>
                            <td class="colon-cell"></td>
                            <td>${value}</td>
                        </tr>`;
                });
                extraHTML = `<table class="biodata-table" style="margin-top:10px;">${extraRows}</table>`;
            }

            // Render Body Text
            let finalBodyText = "";
            if (typeof template.bodyText === "function") {
              finalBodyText = template.bodyText(dbData);
            } else {
              finalBodyText = template.bodyText;
            }

            // RENDER HTML
            // Disini 'Nomor' diambil dari dbData.nomor_surat yang sudah kita hitung di PHP
            const html = `
                <div class="judul-surat">
                    <u>${template.judul}</u>
                    <span>Nomor : ${dbData.nomor_surat}</span> 
                </div>

                <div class="content">
                    <p>Yang bertanda Tangan dibawah ini, Kepala Kelurahan Isekai Kecamatan Apayaaa Kota Batam menerangkan dengan sesungguhnya bahwa:</p>

                    <table class="biodata-table">
                        ${rowsHTML}
                    </table>

                    <p>${finalBodyText}</p>

                    ${extraHTML}

                    <p style="margin-top: 15px;">Demikian Surat Keterangan ini kami buat dengan sebenarnya dan dapat dipergunakan sebagaimana mestinya.</p>
                </div>
            `;

            container.innerHTML = html;
        }

        window.onload = renderSurat;
    </script>
</body>
</html>
