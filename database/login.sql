-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 11 Jun 2025 pada 14.01
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
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `lahan_dimiliki` text DEFAULT NULL,
  `total_lahan` int(11) DEFAULT NULL,
  `tipe_lahan` text DEFAULT NULL,
  `durasi_kontrak` text DEFAULT NULL,
  `persebaran` text DEFAULT NULL,
  `pengalaman` varchar(100) DEFAULT NULL,
  `tanaman_dikuasai` text DEFAULT NULL,
  `kemampuan_khusus` text DEFAULT NULL,
  `wilayah_digarap` text DEFAULT NULL,
  `tim_kerja` varchar(10) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `role` enum('petani','pemilik_lahan') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `password`, `deskripsi`, `lahan_dimiliki`, `total_lahan`, `tipe_lahan`, `durasi_kontrak`, `persebaran`, `pengalaman`, `tanaman_dikuasai`, `kemampuan_khusus`, `wilayah_digarap`, `tim_kerja`, `whatsapp`, `role`) VALUES
(12, 'gunawan', 'gun@lapar', '$2y$10$Y3MU2HhNk3V7NFOg/mE3ZuIY3V0VYIew5xM5GxNOzy.zVUMAbJMtS', '', '', 0, '', '', '', '0', '', '', '', '', '', 'petani'),
(13, 'arkan keren', 'arkan@gmail.com', '$2y$10$eADSb0ELaqdYG4BZVrezmOzQON773hjJrsrcqa5KmYAXrOsOu6aR6', 'tes', NULL, NULL, NULL, NULL, NULL, '500 tahun', 'dawd', 'awd', 'awd', 'awd', '82134698758', 'petani'),
(14, 'djaky', 'djaky@lapar', '$2y$10$w.CqD1ayiN9c3WiFJ/rCzeIfyjRM.bqUHw62S6qN.VbjWbI6cDOlK', 'eakkkkkk', 'qwqwe', 23, 'qwe', 'qwe', 'qwe', '0', '', '', '', '', '232323232', 'pemilik_lahan'),
(15, 'faiz1', 'faiz@gmail.com', '$2y$10$X09ixan33d33INtCbbQtT.SUsJbEmmN8pCWa4WtfyP1Kk4HPFvOZm', 'sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss', 'Lahan Padiiiiiiiii', 1, 'Sawah', '2 Tahun', 'Kalasan', '0', '', '', '', '', '082134698758', 'pemilik_lahan'),
(16, 'jelek banget ini web', 'prikitiw@jimeil.kom', '$2y$10$Ot03/YvTPuFWot6VHQwBj.bLYmSLGXizNcw5n/7skdekMQfi9GLhW', '', '', 0, '', '', '', '0', '', '', '', '', '', 'petani'),
(17, 'Klindong Hebat', 'klindong@gmail.com', '$2y$10$5ntgsiTFMEA3qpldP8i2LuBEQ/TqrBnkAy/1ZlVZ/KFeRoBDmjpSe', '', '', 0, '', '', '', '0', '', '', '', '', '', 'pemilik_lahan'),
(18, 'tes1', 'arkan1@a', '$2y$10$YsU1NZNDPpELowkfYrUegebgwKM6CT6yJCAOGfBsJvGTZBJHxa3fe', '', '', 0, '', '', '', '0', '', '', '', '', '', 'pemilik_lahan'),
(19, 'Gunawan Westertest', 'gunawanwibisana@gmail.com', '$2y$10$r6p.qhPOeMkI07F5ubVyreRRUou412Uj07hzH6M61LXlnSZISuirq', 'Saya suka Buah buahan', 'Lahan Padi', 2, 'Lahan Sawah', 'Minimal 1 Tahun', 'Kalasan', '0', '', '', '', '', '895125317823', 'pemilik_lahan'),
(20, 'gunawan wibisana2', 'nostagis@gmail.com', '$2y$10$h8WNYb0EX8/E.RMAln4zku0/chlPJ//h7NpuvIw9fKhx894SUjZM.', 'gunawan suka buah', '', 0, '', '', '', '10 Tahun', 'Padi', 'Pengendalian Hama', 'Sleman', 'Ya', '0895336205744', 'petani'),
(21, 'Tejo', 'tejo@email.com', '$2y$10$CGoFvaYhVFwcKlh7YXVWreO1fFGi7Zm28fQVbs/EdKqkuKo4OEDLe', '', '', 0, '', '', '', '0', '', '', '', '', '', 'pemilik_lahan'),
(22, 'GunLaparBet', 'eak@lapar', '$2y$10$dCRoSmsV2w3xyNtTPrimyOi6q.xGF90viOBKUe2yJFj2NnvMDb8Qm', 'Siap Membasmi Tanaqeweqeqweqweqwqwqwqwsssssssssssssssssssssssssssssssssssssssssssssssssssssssss\r\nsddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '080808080808', 'petani'),
(23, 'gun', 'gunawan@gmail.com', '$2y$10$PfscCFPKTC.UI129oEuUlO.u/LqtvU3oV2zCuy1TTe6Oo/hQqN8hO', '', '', 20, '', '', '', NULL, NULL, NULL, NULL, NULL, '', 'pemilik_lahan'),
(24, 'lah woi', 'lah@gmail.com', '$2y$10$e3aNubRVa.Qxl1UiGmE7YewS9Qqny9IzM1Vv7.ap1AeEA2KRcWvGW', 'apacoba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4444444444', 'petani'),
(26, 'adjaky1', 'djaky1@eak', '$2y$10$iX75CCQnlI2xmyX27RSELebw6.hK7I/6xl7kWKQnx682K3WZi2zFq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pemilik_lahan'),
(27, 'adjaky2', 'djaky2@eak', '$2y$10$iw49Wt9Y7r3JsX7TeWYwPuwIbmsYzeuDB.35uPeEU3Iokb6m3X8VS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'petani'),
(28, 'Dzakyy', 'dzaky@gmail', '$2y$10$CWT2/.4g90tJgcw3nTIrtOZt4Jo5ND44cZvUqnzASZ2BeKIcDD14K', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pemilik_lahan'),
(29, 'Gema Sabda Bhaskara', 'sabdabhaskara86@gmail.com', '$2y$10$GWln7oE9E8gZ7Z8Zv0eGb.u.tR.oFd.idL10wTZC5PST5U8iJwAe6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pemilik_lahan'),
(30, 'apaya', 'apaya@gmail.com', '$2y$10$UHFVyUXeisJwRtnHFsNW9usnWA1djGUl8Ewg/HakQHYDKLVHIcloC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pemilik_lahan'),
(31, 'cihuy', 'cihuy@gmail.com', '$2y$10$5VwXGP1LW/ne36bSo99xyueGvWPqdV6KM80M4dT7jWlJgQPgk19G2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pemilik_lahan'),
(33, 'gunawan wibi', 'arkanfalih9@gmail.com', '$2y$10$HJYmxh5/p78TUI7hy5V9rOyxo9LJ.xaYeFyAquLG7M/JVZAZMlGYq', 'saya suka jeruk', 'Lahan Padi sama lahan Sawit', 2, 'Sawit, Sawah', '1 Tahun maximal', 'Sleman, Yogyakarta', NULL, NULL, NULL, NULL, NULL, '895336205744', 'pemilik_lahan');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
