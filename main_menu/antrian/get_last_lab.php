<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Jakarta');
$today = date('Y-m-d');

include_once '../conf/conf.php';
include_once '../conf/helpers.php';

header('Content-Type: application/json');

// --- MB ---
include 'get_last_mb.php';
$dataMB = getDataMB($today);

// --- PA ---
include 'get_last_pa.php';
$dataPA = getDataPA($today);

// --- PK ---
include 'get_last_pk.php';
$dataPK = getDataPK($today);

// --- Gabungan semua permintaan ---
$gabunganPermintaan = array_merge(
    $dataMB['permintaanHariIniMB'] ?? [],
    $dataPA['permintaanHariIniPA'] ?? [],
    $dataPK['permintaanHariIniPK'] ?? []
);

// --- Output gabungan ---
echo json_encode([
  'mb'       => $dataMB,
  'pa'       => $dataPA,
  'pk'       => $dataPK,
  'gabungan' => $gabunganPermintaan
], JSON_UNESCAPED_UNICODE);

exit;
