<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../conf/conf.php';
include_once '../conf/helpers.php';

// Ambil setting instansi
$setting = fetch_assoc("SELECT nama_instansi, alamat_instansi, kabupaten FROM setting LIMIT 1");

// Ambil data jadwal operasi dari booking_operasi
$sql = "SELECT bo.no_rawat, bo.kode_paket, bo.tanggal, bo.jam_mulai, bo.jam_selesai, bo.status,
               bo.kd_dokter, bo.kd_ruang_ok,
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

// Hitung jumlah baris
$count = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Jadwal Operasi</title>
  <!-- CSS global -->
  <link rel="stylesheet" href="../assets/style.css">
  <!-- CSS khusus operasi -->
  <link rel="stylesheet" href="operasi.css">
</head>
<body>
  <header class="header">
    <div class="logo">
      <?php include '../assets/logo.php'; ?>
    </div>
    <div class="instansi">
      <h1><?= $setting['nama_instansi'] ?></h1>
      <p><?= $setting['alamat_instansi'] ?> – <?= $setting['kabupaten'] ?></p>
    </div>
    <div id="clock"></div>
    <div id="next-prayer"></div>
  </header>

  <main class="dashboard">
    <h2>DASHBOARD JADWAL OPERASI</h2>

    <!-- Scroll aktif hanya jika baris > 7 -->
    <div class="table-container <?= ($count > 7 ? 'scrollable' : '') ?>">
      <table class="operasi-table">
        <thead>
          <tr>
            <th>No. Rawat</th>
            <th>Nama Pasien</th>
            <th>Umur</th>
            <th>JK</th>
            <th>Tanggal</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Operasi</th>
            <th>Operator</th>
            <th>Ruang Operasi</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $row['no_rawat'] ?></td>
              <td><?= sensorNama($row['nm_pasien']) ?></td>
              <td><?= hitungUmur($row['tgl_lahir']) ?></td>
              <td><?= $row['jk'] ?></td>
              <td><?= $row['tanggal'] ?></td>
              <td><?= $row['jam_mulai'] ?></td>
              <td><?= $row['jam_selesai'] ?></td>
              <td><?= $row['nm_perawatan'] ?></td>
              <td><?= $row['nm_dokter'] ?></td>
              <td><?= $row['nm_ruang_ok'] ?></td>
              <td>
                <span class="status <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                  <?= $row['status'] ?>
                </span>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <?php include '../assets/banner.php'; ?>
  </main>

  <script src="../assets/clock.js"></script>

  <!-- Refresh otomatis setiap 60 detik -->
  <script>
  setTimeout(function(){
     location.reload();
  }, 60000);
  </script>

  <!-- Auto scroll vertikal hanya jika scrollable -->
  <script>
    document.querySelectorAll('.table-container.scrollable').forEach(container => {
      let direction = 1; // 1 = turun, -1 = naik
      function autoScroll() {
        container.scrollTop += direction;
        if (container.scrollTop + container.clientHeight >= container.scrollHeight) {
          direction = -1; // ganti arah ke atas
        } else if (container.scrollTop <= 0) {
          direction = 1; // ganti arah ke bawah
        }
      }
      setInterval(autoScroll, 50); // kecepatan scroll
    });
  </script> 
</body>
</html>
