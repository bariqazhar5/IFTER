-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Jan 2025 pada 05.26
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensitfid`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time DEFAULT NULL,
  `nokartu` int(20) NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpha') NOT NULL DEFAULT 'Hadir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id`, `nama`, `tanggal`, `jam_masuk`, `jam_pulang`, `nokartu`, `status`) VALUES
(14, '73', '2024-12-21', '21:47:59', NULL, 209156473, 'Izin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id` int(20) NOT NULL,
  `nokartu` varchar(20) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jabatan` varchar(30) NOT NULL,
  `wali` varchar(20) NOT NULL,
  `lahir` date NOT NULL,
  `alamat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id`, `nokartu`, `nisn`, `nama`, `jabatan`, `wali`, `lahir`, `alamat`) VALUES
(4, '12163662', '198507322005022001', 'Arib Arkaan', 'Kepsek', '4c', '1966-01-07', 'jl.karawitan no 20, kota Bandung'),
(5, '209156473', '91287301294812', 'Bariq', 'Guru Olahraga', '-', '1985-04-05', 'jl. bangreng no 21, kota Bandung'),
(6, '635017438', '98712398314', 'Muslih', 'Guru', '5c', '1971-02-10', 'jl.symphony no 198, kota Bandung');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL,
  `pagi_mulai` time NOT NULL,
  `pagi_batas` float NOT NULL,
  `sore_mulai` time NOT NULL,
  `sore_batas` float NOT NULL,
  `aktifkan_absensi` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `pagi_mulai`, `pagi_batas`, `sore_mulai`, `sore_batas`, `aktifkan_absensi`) VALUES
(1, '07:00:00', 2, '14:00:00', 2, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `status`
--

CREATE TABLE `status` (
  `mode` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `status`
--

INSERT INTO `status` (`mode`) VALUES
(1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tmprfid`
--

CREATE TABLE `tmprfid` (
  `nokartu` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`mode`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
