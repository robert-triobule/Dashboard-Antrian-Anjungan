<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../conf/conf.php';
include_once '../conf/helpers.php';

// Ambil setting instansi
$setting = fetch_assoc("SELECT nama_instansi, alamat_instansi, kabupaten FROM setting LIMIT 1");

// Ambil data jadwal operasi
$sql = "SELECT bo.no_rawat, bo.kode_paket, bo.tanggal, bo.jam_mulai, bo.jam_selesai, bo.status,
               po.nm_perawatan,
               d.nm_dokter,
               ro.nm_ruang_ok,
               p.nm_pasien, p.jk, p.tgl_lahir
        FROM booking_operasi bo
        JOIN reg_periksa rp ON bo.no_rawat = rp.no_rawat
        JOIN pasien p ON rp.no_rkm_medis = p.no_rkm_medis
        JOIN paket_operasi po ON bo.kode_paket = po.kode_paket
        JOIN dokter d ON bo.kd_dokter = d.kd_dokter
        JOIN ruang_ok ro ON bo.kd_ruang_ok = ro.kd_ruang_ok
        WHERE bo.status IN ('Menunggu','Proses Operasi')
        ORDER BY bo.tanggal, bo.jam_mulai";
$result = bukaquery($sql);
$count = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Jadwal Operasi</title>
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); /* 1–2 kolom */
      gap: 20px;
      max-height: calc(100vh - 220px);
      overflow-y: auto;
      padding: 10px;
    }
    .operasi-card {
      background: rgba(0,128,255,0.1);
      border: 1px solid rgba(0,128,255,0.4);
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.4);
      padding: 15px;
      color: #fff;
    }
    .operasi-card h3 {
      margin: 0 0 10px;
      font-size: 20px;
      color: #4fc3f7;
      text-align: center; /* nama pasien rata tengah */
    }
    .operasi-card .kelas {
      text-align: center;
      font-style: italic;
      margin-bottom: 12px;
      color: #81d4fa;
    }
    .detail {
      display: grid;
      grid-template-columns: 120px 1fr; /* label sejajar dengan nilai */
      row-gap: 6px;
      column-gap: 10px;
    }
    .detail div {
      padding: 6px 0;
      border-bottom: 1px dashed rgba(255,255,255,0.3);
      font-size: 14px;
    }
    .detail .label {
      font-weight: bold;
      color: #cce7ff;
    }
    .status {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 6px;
      font-weight: bold;
    }
    .status.menunggu { background:#4caf50; color:#fff; }
    .status.proses-operasi { background:#ff9800; color:#fff; }
  </style>
</head>
<body>
  <header class="header">
    <div class="logo"><?php include '../assets/logo.php'; ?></div>
    <div class="instansi">
      <h1><?= $setting['nama_instansi'] ?></h1>
      <p><?= $setting['alamat_instansi'] ?> – <?= $setting['kabupaten'] ?></p>
    </div>
    <div id="clock"></div>
    <div id="next-prayer"></div>
  </header>

  <main class="dashboard">
    <h2>DASHBOARD JADWAL OPERASI</h2>

    <div class="grid-container <?= ($count > 6 ? 'scrollable' : '') ?>">
      <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="operasi-card">
          <h3><?= sensorNama($row['nm_pasien']) ?> (<?= $row['jk'] ?>)</h3>
          <div class="kelas">No. Rawat: <?= $row['no_rawat'] ?></div>
          <div class="detail">
            <div class="label">Umur</div><div><?= hitungUmur($row['tgl_lahir']) ?> </div>
            <div class="label">Tanggal</div><div><?= $row['tanggal'] ?></div>
            <div class="label">Mulai</div><div><?= $row['jam_mulai'] ?> - Selesai: <?= $row['jam_selesai'] ?></div>
            <div class="label">Operasi</div><div><?= $row['nm_perawatan'] ?></div>
            <div class="label">Operator</div><div><?= $row['nm_dokter'] ?></div>
            <div class="label">Ruang</div><div><?= $row['nm_ruang_ok'] ?></div>
            <div class="label">Status</div>
            <div>
              <span class="status <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                <?= $row['status'] ?>
              </span>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <?php include '../assets/banner.php'; ?>
  </main>

  <script src="../assets/clock.js"></script>

  <script>
    setTimeout(function(){ location.reload(); }, 60000);
  </script>

  <script>
    document.querySelectorAll('.grid-container.scrollable').forEach(container => {
      let direction = 1;
      function autoScroll() {
        container.scrollTop += direction;
        if (container.scrollTop + container.clientHeight >= container.scrollHeight) {
          direction = -1;
        } else if (container.scrollTop <= 0) {
          direction = 1;
        }
      }
      setInterval(autoScroll, 50);
    });
  </script>
</body>
</html>
