-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 11 Jun 2025 pada 14.00
-- Versi server: 10.11.11-MariaDB-0+deb12u1
-- Versi PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `card`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `card`
--

CREATE TABLE `card` (
  `id` int(11) NOT NULL,
  `pemilik` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `deskripsi_rinci` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `harga` int(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `jenis_lahan` varchar(50) DEFAULT NULL,
  `jenis_tanaman` varchar(50) DEFAULT NULL,
  `luas_lahan` varchar(50) DEFAULT NULL,
  `ketentuan_bertani` text DEFAULT NULL,
  `kontak` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `card`
--

INSERT INTO `card` (`id`, `pemilik`, `deskripsi`, `deskripsi_rinci`, `tanggal`, `harga`, `status`, `lokasi`, `jenis_lahan`, `jenis_tanaman`, `luas_lahan`, `ketentuan_bertani`, `kontak`) VALUES
(25, 'djaky', NULL, 'eakkkk eakkkkkke eakkkk', '2025-05-08', 1000000, 'tersedia', 'sleman', 'sawah', 'padi', '223', 'eak tanam', '0089980898'),
(26, 'djaky', NULL, 'eakkkk eakkkkkke eakkkk', '2025-05-08', 1000000, 'tersedia', 'sleman', 'sawah', 'padi', '223', 'eak tanam', '0089980898'),
(27, 'arkan keren', NULL, 'apayaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-05-23', 15000000, 'tersedia', 'Kalasan', 'Sawah', 'Jagung', '100', 'harus keren', '082134698758'),
(28, 'gunawan', NULL, 'subur bur', '2025-05-23', 15000, 'tersedia', 'Sleman', 'Lahan Sawit', 'Padi', '500', 'ganteng', '0101010101'),
(29, 'dzaky', NULL, 'lahan yang luas', '2025-05-26', 1000000, 'tersedia', 'sleman', 'sawah', 'padi', '120', 'memiliki kelompok bertani', '800808077'),
(30, 'gunawan', NULL, 'lahan subur siap tanam', '2025-06-11', 1000000, 'tersedia', 'Sleman', 'Lahan Sawah', 'Padi', '10', 'sehat bugar', '895336205727');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `card`
--
ALTER TABLE `card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
