<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Bukti Registrasi - <?= htmlspecialchars($data['nm_pasien'] ?? 'Pasien') ?></title>
  <link rel="stylesheet" href="anjungan.css">
</head>
<body class="cetak-body" onload="cetakDanKembali()">
  
  <div class="cetak-header">
    <h1><?= htmlspecialchars($setting['nama_instansi']) ?></h1>
    <p><?= htmlspecialchars($setting['alamat_instansi']) ?><br><?= htmlspecialchars($setting['kabupaten']) ?></p>
  </div>
  
  <div class="cetak-divider"></div>
  <div class="cetak-title">BUKTI REGISTRASI</div>
  
  <div class="cetak-content">
    <table>
      <tr><td class="cetak-label">No. Antrian</td><td class="cetak-colon">:</td><td class="cetak-val" style="font-size: 22px;"><?= htmlspecialchars($data['no_reg'] ?? '') ?></td></tr>
      <tr><td class="cetak-label">Poli</td><td class="cetak-colon">:</td><td class="cetak-val"><?= htmlspecialchars($data['nm_poli'] ?? '') ?></td></tr>
      <tr><td class="cetak-label">Dokter</td><td class="cetak-colon">:</td><td class="cetak-val"><?= htmlspecialchars($data['nm_dokter'] ?? '') ?></td></tr>
    </table>
  </div>

  <div class="cetak-divider"></div>

  <div class="cetak-content">
    <table>
      <tr><td class="cetak-label">No. Rawat</td><td class="cetak-colon">:</td><td class="cetak-val" style="font-weight:normal; font-size:11px;"><?= htmlspecialchars($data['no_rawat'] ?? '') ?></td></tr>
      <tr><td class="cetak-label">No. RM</td><td class="cetak-colon">:</td><td class="cetak-val"><?= htmlspecialchars($data['no_rkm_medis'] ?? '') ?></td></tr>
      <tr><td class="cetak-label">Pasien</td><td class="cetak-colon">:</td><td class="cetak-val"><?= htmlspecialchars($data['nm_pasien'] ?? '') ?></td></tr>
      <tr><td class="cetak-label">Penjamin</td><td class="cetak-colon">:</td><td class="cetak-val"><?= htmlspecialchars($data['png_jawab'] ?? '') ?></td></tr>
    </table>
  </div>
  
  <div class="cetak-divider"></div>
  
  <div class="cetak-content">
    <table>
      <tr><td class="cetak-label">Tanggal</td><td class="cetak-colon">:</td><td class="cetak-val" style="font-weight:normal;"><?= date("d-m-Y") ?></td></tr>
      <tr><td class="cetak-label">Jam</td><td class="cetak-colon">:</td><td class="cetak-val" style="font-weight:normal;"><?= date("H:i") ?> WIB</td></tr>
    </table>
  </div>
  
  <div class="cetak-footer">
    <div class="cetak-divider"></div>
    <p>Mohon dibawa saat pemeriksaan.<br>Semoga lekas sembuh!</p>
  </div>

  <script>
    function cetakDanKembali() {
      window.print();
      sessionStorage.clear();
      setTimeout(function() {
        window.location.href = 'anjungan.php';
      }, 1500);
    }
  </script>
</body>
</html>
