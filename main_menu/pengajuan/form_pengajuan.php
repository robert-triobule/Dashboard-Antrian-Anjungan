<?php
session_start();
if(!isset($_SESSION["ses_pengajuan_login"])) {
    header("Location: login.php");
    exit;
}

include_once '../conf/conf.php';          // DB utama
include_once '../conf/command.php';

$setting = fetch_assoc("SELECT nama_instansi, alamat_instansi, kabupaten, kontak, email FROM setting LIMIT 1");
$conn = bukakoneksi();

// Query gabungan RALAN + RANAP
$qPasien = mysqli_query($conn, "
    SELECT 'Ralan' AS jenis, rp.no_reg, rp.no_rawat, rp.tgl_registrasi AS tanggal,
           rp.no_rkm_medis, ps.nm_pasien, d.nm_dokter, p.nm_poli
    FROM reg_periksa rp
    JOIN pasien ps ON rp.no_rkm_medis=ps.no_rkm_medis
    JOIN dokter d ON rp.kd_dokter=d.kd_dokter
    JOIN poliklinik p ON rp.kd_poli=p.kd_poli
    WHERE rp.tgl_registrasi = CURDATE()
      AND rp.status_lanjut = 'Ralan'
    UNION
    SELECT 'Ranap' AS jenis, rp.no_reg, ki.no_rawat, ki.tgl_masuk AS tanggal,
           ps.no_rkm_medis, ps.nm_pasien, '' AS nm_dokter, '' AS nm_poli
    FROM kamar_inap ki
    JOIN reg_periksa rp ON ki.no_rawat=rp.no_rawat
    JOIN pasien ps ON rp.no_rkm_medis=ps.no_rkm_medis
    WHERE ki.stts_pulang = '-'
    ORDER BY tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Pengajuan Hapus Nota Salah</title>
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="stylesheet" href="pengajuan.css">
</head>
<body class="pengajuan">
  <!-- Header -->
  <header class="header">
    <div class="logo"><?php include '../assets/logo.php'; ?></div>
    <div class="instansi">
      <h1><?= $setting['nama_instansi'] ?></h1>
      <p><?= $setting['alamat_instansi'] ?> – <?= $setting['kabupaten'] ?></p>
      <p><?= $setting['kontak'] ?> | <?= $setting['email'] ?></p>
    </div>
    <div id="clock"></div>
    <div id="next-prayer"></div>
  </header>

  <main class="dashboard">
    <!-- Judul -->
    <h2 class="anjungan-title">PENGAJUAN HAPUS NOTA SALAH</h2>
    <p>Mohon diproseskan HAPUS NOTA SALAH dari pasien:</p>

    <!-- Form langsung ke daftar_pengajuan.php -->
    <form class="pengajuan-form" method="post" action="daftar_pengajuan.php">
      <!-- Dropdown gabungan -->
      <div class="field-row">
        <label for="pasien">Pasien:</label>
        <select id="pasien" name="pasien" onchange="tampilkanPasien(this)">
          <option value="">-- Pilih Pasien --</option>
          <?php while($row = mysqli_fetch_assoc($qPasien)) { ?>
            <option value="<?= $row['no_rawat'] ?>"
                    data-no_reg="<?= $row['no_reg'] ?>"
                    data-no_rawat="<?= $row['no_rawat'] ?>"
                    data-no_rkm_medis="<?= $row['no_rkm_medis'] ?>"
                    data-nm_pasien="<?= htmlspecialchars($row['nm_pasien'], ENT_QUOTES) ?>"
                    data-tanggal="<?= $row['tanggal'] ?>"
                    data-status="<?= $row['jenis'] ?>"
                    data-nm_dokter="<?= htmlspecialchars($row['nm_dokter'], ENT_QUOTES) ?>"
                    data-nm_poli="<?= htmlspecialchars($row['nm_poli'], ENT_QUOTES) ?>">
              <?= $row['jenis']." - ".$row['no_rawat']." - ".$row['no_rkm_medis']." - ".$row['nm_pasien']." - ".date('d-m-Y', strtotime($row['tanggal'])) ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <!-- Hidden input -->
      <input type="hidden" name="no_reg" id="no_reg">
      <input type="hidden" name="no_rawat" id="no_rawat">
      <input type="hidden" name="no_rkm_medis" id="no_rkm_medis">
      <input type="hidden" name="nm_pasien" id="nm_pasien">
      <input type="hidden" name="tgl_registrasi" id="tgl_registrasi">
      <input type="hidden" name="status_lanjut" id="status_lanjut">
      <input type="hidden" name="nm_dokter" id="nm_dokter">
      <input type="hidden" name="nm_poli" id="nm_poli">

      <!-- Hasil detail pasien -->
      <table id="hasil-pasien" class="tabel-pasien">
        <tr>
          <td class="label">No Rawat</td><td>:</td><td class="value">-</td>
          <td class="label">No RM</td><td>:</td><td class="value">-</td>
          <td class="label">Nama Pasien</td><td>:</td><td class="value">-</td>
        </tr>
        <tr>
          <td class="label">Tanggal</td><td>:</td><td class="value">-</td>
          <td class="label">Dokter</td><td>:</td><td class="value">-</td>
          <td class="label">Poli</td><td>:</td><td class="value">-</td>
        </tr>
        <tr>
          <td class="label">Status</td><td>:</td><td class="value">-</td>
          <td></td><td></td><td></td><td></td><td></td>
        </tr>
      </table>

      <!-- Alasan -->
      <label for="alasan">Alasan:</label>
      <textarea name="alasan" id="alasan" required></textarea>

      <!-- Nama pengaju + tombol -->
      <div class="pengaju-row">
        <p>Yang mengajukan: <strong><?= $_SESSION['nama_pengaju']; ?></strong></p>
        <input type="hidden" name="yang_mengajukan" value="<?= $_SESSION['nama_pengaju']; ?>">
        <div class="button-group">
          <button type="submit">Simpan</button>
          <a href="daftar_pengajuan.php" class="btn-exit">Keluar</a>
        </div>
      </div>
    </form>
  </main>

  <!-- Banner bawah -->
  <?php include '../assets/banner.php'; ?>

  <!-- Script jam -->
  <script src="../assets/clock.js"></script>
  <!-- Script pengajuan -->
  <script src="pengajuan.js"></script>
</body>
</html>
