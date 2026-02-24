<?php
// Fungsi untuk masking nama pasien
function sensorNama($nama) {
    $len = strlen($nama);
    if ($len <= 2) return $nama; 
    return substr($nama,0,1) . str_repeat("*",$len-2) . substr($nama,-1);
}

// Hitung umur dari tgl lahir
function hitungUmur($tgl_lahir) {
    $lahir = new DateTime($tgl_lahir);
    $today = new DateTime();
    $umur = $today->diff($lahir);
    return $umur->y . " th " . $umur->m . " bl " . $umur->d . " hr";
}

// Bersihkan embel-embel nama pasien
function cleanNamaPasien($nama) {
    $nama = trim($nama, " ,.");
    $nama = preg_replace(
        '/^(AN|TN|BY|NY|NN|H|HJ|SDR)\.?(\s+)?|\s*(AN|TN|BY|NY|NN|H|HJ|SDR)\.?$/i',
        '',
        $nama
    );
    return trim($nama, " ,.");
}

// Ambil nomor antrian berikutnya
function get_next_no_reg($kd_poli, $tgl) {
    $row = fetch_assoc("SELECT MAX(no_reg) as last_reg 
                        FROM reg_periksa 
                        WHERE kd_poli='$kd_poli' AND tgl_registrasi='$tgl'");
    $last = $row['last_reg'] ?? 0;
    return str_pad($last + 1, 3, '0', STR_PAD_LEFT);
}

// Eksekusi query insert/update/delete
function run_query($sql) {
    $result = bukaquery($sql);
    if (!$result) {
        error_log("SQL Error: gagal eksekusi query: ".$sql);
    }
    return $result;
}

// Generator no_rawat berurutan otomatis
function generate_no_rawat($tgl) {
    // Ambil no_rawat terakhir di tanggal ini
    $last = fetch_assoc("SELECT no_rawat 
                         FROM reg_periksa 
                         WHERE tgl_registrasi='$tgl' 
                         ORDER BY no_rawat DESC LIMIT 1");

    if ($last && isset($last['no_rawat'])) {
        // Format: YYYY/MM/DD/NNNNNN
        $parts = explode("/", $last['no_rawat']);
        $urut  = str_pad((int)$parts[3] + 1, 6, "0", STR_PAD_LEFT);
    } else {
        $urut = "000001";
    }

    return date('Y/m/d', strtotime($tgl)) . "/" . $urut;
}
