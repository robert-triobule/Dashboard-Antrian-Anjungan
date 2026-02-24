<?php
include_once '../conf/conf.php';
include_once '../conf/helpers.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$identitas = $_GET['identitas'] ?? '';
if (!$identitas) {
    echo json_encode(["error" => "Identitas kosong"]);
    exit;
}

// ambil koneksi dari conf.php
$conn = bukakoneksi();

$sql = "SELECT 
            p.no_rkm_medis,
            p.nm_pasien,
            p.no_ktp,
            p.jk,
            p.tmp_lahir,
            p.tgl_lahir,
            p.umur,
            p.nm_ibu,
            p.alamat,
            kel.nm_kel AS nama_kelurahan,
            kec.nm_kec AS nama_kecamatan,
            kab.nm_kab AS nama_kabupaten,
            prop.nm_prop AS nama_propinsi,
            p.no_tlp,
            p.email,
            p.gol_darah,
            p.pekerjaan,
            p.stts_nikah,
            p.agama,
            p.tgl_daftar,
            p.pnd,
            p.keluarga,
            p.namakeluarga,
            p.kd_pj,
            pj.png_jawab AS nama_penjamin,
            p.no_peserta,
            p.perusahaan_pasien,
            pr.nama_perusahaan AS nama_perusahaan_pasien,
            pr.alamat AS alamat_perusahaan,
            pr.kota AS kota_perusahaan,
            pr.no_telp AS telp_perusahaan,
            p.suku_bangsa,
            sb.nama_suku_bangsa,
            p.bahasa_pasien,
            bp.nama_bahasa,
            p.cacat_fisik,
            cf.nama_cacat,
            p.nip
        FROM pasien p
        LEFT JOIN kelurahan kel ON p.kd_kel = kel.kd_kel
        LEFT JOIN kecamatan kec ON p.kd_kec = kec.kd_kec
        LEFT JOIN kabupaten kab ON p.kd_kab = kab.kd_kab
        LEFT JOIN propinsi prop ON p.kd_prop = prop.kd_prop
        LEFT JOIN perusahaan_pasien pr ON p.perusahaan_pasien = pr.kode_perusahaan
        LEFT JOIN penjab pj ON p.kd_pj = pj.kd_pj
        LEFT JOIN suku_bangsa sb ON p.suku_bangsa = sb.id
        LEFT JOIN bahasa_pasien bp ON p.bahasa_pasien = bp.id
        LEFT JOIN cacat_fisik cf ON p.cacat_fisik = cf.id
        WHERE p.no_ktp = ? OR p.no_rkm_medis = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $identitas, $identitas);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Pasien tidak ditemukan"]);
}

$stmt->close();
$conn->close();
exit;
