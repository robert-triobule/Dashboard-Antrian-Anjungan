CREATE DATABASE pengajuan;
USE pengajuan;

CREATE TABLE pengajuan_nota_salah (
    id_pengajuan INT AUTO_INCREMENT PRIMARY KEY,
    no_reg VARCHAR(20) NOT NULL,
    no_rawat VARCHAR(20) NOT NULL UNIQUE,   -- unik per kunjungan
    no_rkm_medis VARCHAR(20) NOT NULL,
    nm_pasien VARCHAR(100) NOT NULL,
    tgl_registrasi DATE NOT NULL,
    status_lanjut VARCHAR(20) NOT NULL,
    nm_dokter VARCHAR(100) NOT NULL,
    nm_poli VARCHAR(100) NOT NULL,
    alasan TEXT NOT NULL,
    yang_mengajukan VARCHAR(100) NOT NULL,   -- nama pegawai dari session login
    tindak_lanjut TEXT NOT NULL DEFAULT 'Menunggu verifikasi', -- default agar tidak kosong
    diproses_oleh VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    jam_proses TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel pengaturan ruang
CREATE TABLE pengaturan_ruang (
    id_ruang INT AUTO_INCREMENT PRIMARY KEY,
    nama_ruang VARCHAR(100) NOT NULL
);

-- Tabel pengaturan user akses
CREATE TABLE pengaturan_user_akses (
    id_user VARCHAR(30) PRIMARY KEY,
    nama_pegawai VARCHAR(100) NOT NULL,
    hak_akses ENUM('pengaju','pemroses') DEFAULT 'pengaju'
);

-- Tabel pengajuan penggunaan ruang
CREATE TABLE pengajuan_penggunaan_ruang (
    id_pengajuan INT AUTO_INCREMENT PRIMARY KEY,
    pengaju VARCHAR(100) NOT NULL,
    nama_kegiatan VARCHAR(200) NOT NULL,
    tgl_mulai DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    tgl_selesai DATE NOT NULL,
    jam_selesai TIME NOT NULL,
    ruang VARCHAR(100) NOT NULL,
    tindak_lanjut ENUM('Pengajuan','Setuju','Tolak') DEFAULT 'Pengajuan',
    alasan_tolak TEXT DEFAULT NULL,
    diproses_oleh VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    jam_proses TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    status_dashboard ENUM('AKTIF','NONAKTIF') NOT NULL DEFAULT 'NONAKTIF'
);
