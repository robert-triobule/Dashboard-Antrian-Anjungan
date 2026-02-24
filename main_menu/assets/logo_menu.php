<?php
include_once __DIR__ . '/../conf/conf.php'; // pastikan hanya sekali

// Ambil logo dari tabel setting
$row = fetch_assoc("SELECT logo FROM setting LIMIT 1");

// Dekripsi logo (jika memang disimpan terenkripsi base64/AES)
$logo_data = $row['logo'];

// Deteksi MIME otomatis
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_buffer($finfo, $logo_data);
finfo_close($finfo);

// Output sebagai <img>
echo '<img src="data:' . $mime . ';base64,' . base64_encode($logo_data) . '" 
      alt="Logo Instansi" style="height:60px;">';
?>
