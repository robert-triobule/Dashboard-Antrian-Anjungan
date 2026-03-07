<?php
// Koneksi khusus untuk DB pengajuan
function bukakoneksi_pengajuan() {
    $host = "localhost";
    $user = "root";
    $pass = "xxx";
    $db   = "pengajuan";

    $conn = mysqli_connect($host, $user, $pass, $db);
    if(!$conn){
        die("Koneksi ke DB pengajuan gagal: " . mysqli_connect_error());
    }
    return $conn;
}
?>
