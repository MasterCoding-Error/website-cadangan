-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Apr 2026 pada 09.36
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desa_web`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelapor`
--

CREATE TABLE `pelapor` (
  `id_pelapor` int(225) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `jk` varchar(20) NOT NULL,
  `no_hp` varchar(12) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `id_user` int(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelapor`
--

INSERT INTO `pelapor` (`id_pelapor`, `nama`, `alamat`, `email`, `nik`, `jk`, `no_hp`, `username`, `password`, `id_user`) VALUES
(17, 'Ilfiyatun Nurul Lailiyah', 'Muharto Gang 5', 'Budi12@gmail.com', '3573032605070003', 'Perempuan', '085607714164', 'cantik', 'cantik', 18),
(288, 'kaffa', 'palembang', 'kaffa12@gmail.com', '32523256', 'Laki-Laki', '089625654', 'q', 'q', 295),
(289, 'Achmad Zidni Anwaruttaufiq', 'Mlang', 'ach.zidni12@gmail.com', '3573032605070003', 'Laki-Laki', '085607714164', 'z', 'z', 296),
(290, 'Navarina', 'Sidoarjo', 'Nava@gmail.com', '359641582', 'Perempuan', '086585451', 'nava', 'nava', 297);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id_pengaduan` int(225) NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `tgl_kejadian` date NOT NULL,
  `jenis_pengaduan` varchar(50) NOT NULL,
  `foto` varchar(225) NOT NULL,
  `isi_pengaduan` varchar(225) NOT NULL,
  `status` varchar(20) NOT NULL,
  `id_pelapor` int(225) NOT NULL,
  `kategori` varchar(225) NOT NULL,
  `tanggapan` text NOT NULL,
  `dilihat_pelapor` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan`
--

INSERT INTO `pengaduan` (`id_pengaduan`, `tgl_pengaduan`, `tgl_kejadian`, `jenis_pengaduan`, `foto`, `isi_pengaduan`, `status`, `id_pelapor`, `kategori`, `tanggapan`, `dilihat_pelapor`) VALUES
(38, '2026-04-07', '2026-04-15', 'Kritik', '', 'laporan ', 'Selesai', 17, 'Bantuan Sosial', 'sudah selesai', 1),
(39, '2026-04-07', '2026-04-02', 'Saran', '', 'di tolak', 'Ditolak', 17, 'Bantuan Sosial', 'gateli', 1),
(40, '2026-04-07', '2026-04-01', 'Saran', '', 'selesai', 'Selesai', 17, 'Sekolah & Pendidikan', 'mantap', 1),
(44, '2026-04-07', '2026-04-10', 'Kritik', '', 'asdfweasdw', 'Selesai', 288, 'Keamanan Desa', 'mari', 1),
(45, '2026-04-07', '2026-04-01', 'Kritik', '', 'asdwasdw', 'Selesai', 289, 'Kebersihan Lingkungan', 'ini pesan asli \r\n', 0),
(46, '2026-04-07', '2026-04-01', 'Keluhan', '', 'asdwasdwas', 'Antri', 288, 'Kesehatan', '', 1),
(47, '2026-04-07', '2026-05-01', 'Kritik', '', 'asdwasdwasdw', 'Ditolak', 288, 'Kesehatan', 'ini pesan anonim', 1),
(48, '2026-04-07', '2026-04-01', 'Kritik', '', 'sawdww2223', 'Antri', 289, 'Bantuan Sosial', '', 0),
(49, '2026-04-07', '2026-04-03', 'Kritik', '', 'asdwwwwasfggg', 'Antri', 289, 'Bantuan Sosial', '', 0),
(50, '2026-04-07', '2026-04-07', 'Saran', 'Skin+(1)+(9).png', 'asuuu', 'Selesai', 288, 'Kerusakan Jalan / Fasilitas Umum', 'oke', 1),
(51, '2026-04-07', '2026-04-01', 'Kritik', 'videoplayback.mp4', 'fsesfdfes', 'Antri', 289, 'Sekolah & Pendidikan', '', 0),
(52, '2026-04-09', '2026-04-02', 'Keluhan', '', 'dasdwasdwasdw', 'Selesai', 288, 'Sekolah & Pendidikan', 'mantap mennnnnnnn', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(225) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `status`) VALUES
(1, 'admin', 'admin', 'admin'),
(18, 'cantik', 'cantik', 'pelapor'),
(292, 'zidni', 'zidni', 'pelapor'),
(295, 'q', 'q', 'pelapor'),
(296, 'z', 'z', 'pelapor'),
(297, 'nava', 'nava', 'pelapor');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pelapor`
--
ALTER TABLE `pelapor`
  ADD PRIMARY KEY (`id_pelapor`);

--
-- Indeks untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id_pengaduan`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pelapor`
--
ALTER TABLE `pelapor`
  MODIFY `id_pelapor` int(225) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

--
-- AUTO_INCREMENT untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id_pengaduan` int(225) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(225) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
