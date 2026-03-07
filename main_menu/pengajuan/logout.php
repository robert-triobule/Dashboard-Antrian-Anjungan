<?php
session_start();
session_destroy(); // hapus semua session
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Logout</title>
  <script>
    // coba tutup jendela
    window.onload = function() {
      window.open('', '_self'); // trik untuk beberapa browser
      window.close();
    };
  </script>
</head>
<body>
  <p>Anda sudah logout. Silakan tutup browser jika jendela tidak tertutup otomatis.</p>
</body>
</html>