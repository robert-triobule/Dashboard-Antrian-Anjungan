<?php
session_start();
if(!isset($_SESSION["ses_pengajuan_login"])) {
    header("Location: login.php");
    exit;
}

$nama_user = $_SESSION['nama_pengaju']; // gunakan nama_pengaju sesuai login.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Menu Pengajuan</title>
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="stylesheet" href="pengajuan.css">
</head>
<body class="pengajuan">
  <!-- Header -->
  <header class="header">
    <div class="logo"><?php include '../assets/logo.php'; ?></div>
    <div class="instansi">
      <h1>MENU PENGAJUAN</h1>
      <p>Selamat datang, <strong><?= htmlspecialchars($nama_user) ?></strong></p>
    </div>
    <div id="clock"></div>
    <div id="next-prayer"></div>
  </header>

  <main class="dashboard">
    <h2 class="anjungan-title">PILIH MENU</h2>
    <div class="button-group">
      <a href="daftar_pengajuan.php" class="btn-exit">Hapus Nota Salah</a>
      <a href="daftar_penggunaan_ruang.php" class="btn-exit">Penggunaan Ruang</a>
      <a href="logout.php" class="btn-exit">Logout</a>
    </div>
  </main>

  <?php include '../assets/banner.php'; ?>
  <script src="../assets/clock.js"></script>
</body>
</html>
