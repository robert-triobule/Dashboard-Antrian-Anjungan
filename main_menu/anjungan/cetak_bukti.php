<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once '../conf/conf.php';

$conn = bukakoneksi();
$no_rawat = $_GET['no_rawat'] ?? '';

$sql = "SELECT r.no_rawat, r.no_reg, p.no_rkm_medis, p.nm_pasien, pl.nm_poli, d.nm_dokter, pj.png_jawab
        FROM reg_periksa r
        JOIN pasien p ON r.no_rkm_medis=p.no_rkm_medis
        JOIN poliklinik pl ON r.kd_poli=pl.kd_poli
        JOIN dokter d ON r.kd_dokter=d.kd_dokter
        JOIN penjab pj ON r.kd_pj=pj.kd_pj
        WHERE r.no_rawat='$no_rawat'";

$result = $conn->query($sql);
if (!$result) {
    die("Query error: " . $conn->error);
}
$data = $result->fetch_assoc();

$setting = $conn->query("SELECT nama_instansi, alamat_instansi, kabupaten FROM setting LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Bukti Registrasi - <?= htmlspecialchars($data['nm_pasien'] ?? 'Pasien') ?></title>
  <style>
    /* Konfigurasi untuk kertas Thermal 80mm */
    @page { 
      margin: 0; 
      size: 80mm auto; 
    }
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      width: 74mm; /* Diberi margin sedikit agar tidak terpotong printer */
      margin: 4mm auto;
      font-size: 12px;
      line-height: 1.4;
      color: #000;
      background: #fff;
    }
    .header { 
      text-align: center; 
      margin-bottom: 12px; 
    }
    .header h1 { 
      margin: 0; 
      font-size: 16px; 
      font-weight: bold; 
      text-transform: uppercase; 
    }
    .header p { 
      margin: 2px 0; 
      font-size: 10px; 
    }
    .divider { 
      border-bottom: 1px dashed #000; 
      margin: 8px 0; 
    }
    .title { 
      text-align: center; 
      font-size: 14px; 
      font-weight: bold; 
      margin: 12px 0; 
      padding: 6px; 
      background: #000; 
      color: #fff; 
      border-radius: 4px; 
      -webkit-print-color-adjust: exact; 
      print-color-adjust: exact; 
    }
    .content table { 
      width: 100%; 
      border-collapse: collapse; 
      margin-bottom: 10px; 
    }
    .content td { 
      vertical-align: top; 
      padding: 3px 0; 
      font-size: 12px; 
    }
    .label { width: 30%; }
    .colon { width: 5%; text-align: center; }
    .val { width: 65%; font-weight: bold; }
    .footer { 
      text-align: center; 
      margin-top: 15px; 
      font-size: 11px; 
      margin-bottom: 20px;
    }
  </style>
</head>
<body onload="cetakDanKembali()">
  
  <div class="header">
    <h1><?= htmlspecialchars($setting['nama_instansi']) ?></h1>
    <p><?= htmlspecialchars($setting['alamat_instansi']) ?><br><?= htmlspecialchars($setting['kabupaten']) ?></p>
  </div>
  
  <div class="divider"></div>
  <div class="title">BUKTI REGISTRASI</div>
  
  <div class="content">
    <table>
      <tr><td class="label">No. Antrian</td><td class="colon">:</td><td class="val" style="font-size: 22px;"><?= htmlspecialchars($data['no_reg'] ?? '') ?></td></tr>
      <tr><td class="label">Poli</td><td class="colon">:</td><td class="val"><?= htmlspecialchars($data['nm_poli'] ?? '') ?></td></tr>
      <tr><td class="label">Dokter</td><td class="colon">:</td><td class="val"><?= htmlspecialchars($data['nm_dokter'] ?? '') ?></td></tr>
    </table>
  </div>

  <div class="divider"></div>

  <div class="content">
    <table>
      <tr><td class="label">No. Rawat</td><td class="colon">:</td><td class="val" style="font-weight:normal; font-size:11px;"><?= htmlspecialchars($data['no_rawat'] ?? '') ?></td></tr>
      <tr><td class="label">No. RM</td><td class="colon">:</td><td class="val"><?= htmlspecialchars($data['no_rkm_medis'] ?? '') ?></td></tr>
      <tr><td class="label">Pasien</td><td class="colon">:</td><td class="val"><?= htmlspecialchars($data['nm_pasien'] ?? '') ?></td></tr>
      <tr><td class="label">Penjamin</td><td class="colon">:</td><td class="val"><?= htmlspecialchars($data['png_jawab'] ?? '') ?></td></tr>
    </table>
  </div>
  
  <div class="divider"></div>
  
  <div class="content">
    <table>
      <tr><td class="label">Tanggal</td><td class="colon">:</td><td class="val" style="font-weight:normal;"><?= date("d-m-Y") ?></td></tr>
      <tr><td class="label">Jam</td><td class="colon">:</td><td class="val" style="font-weight:normal;"><?= date("H:i") ?> WIB</td></tr>
    </table>
  </div>
  
  <div class="footer">
    <div class="divider"></div>
    <p>Mohon dibawa saat pemeriksaan.<br>Semoga lekas sembuh!</p>
  </div>

  <script>
    function cetakDanKembali() {
      // Panggil popup print secara otomatis
      window.print();
      
      // Bersihkan session agar pengguna berikutnya mulai dari awal
      sessionStorage.clear();
      
      // Beri waktu 1.5 detik setelah jendela print muncul untuk kembali
      setTimeout(function() {
        window.location.href = 'anjungan.php';
      }, 1500);
    }
  </script>
</body>
</html>