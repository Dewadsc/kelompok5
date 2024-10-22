-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Okt 2024 pada 22.54
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
-- Database: `kelompok5`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barangkeluar`
--

CREATE TABLE `barangkeluar` (
  `idKeluar` int(255) NOT NULL,
  `username` varchar(500) NOT NULL,
  `idBarang` int(255) NOT NULL,
  `qtyKeluar` int(255) NOT NULL,
  `tglKeluar` date NOT NULL,
  `jamKeluar` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barangkeluar`
--

INSERT INTO `barangkeluar` (`idKeluar`, `username`, `idBarang`, `qtyKeluar`, `tglKeluar`, `jamKeluar`) VALUES
(7617, 'mawar', 1233, 17, '2024-10-23', '03:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barangmasuk`
--

CREATE TABLE `barangmasuk` (
  `idMasuk` int(255) NOT NULL,
  `username` varchar(500) NOT NULL,
  `idBarang` int(255) NOT NULL,
  `qtyMasuk` int(255) NOT NULL,
  `tglMasuk` date NOT NULL,
  `jamMasuk` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barangmasuk`
--

INSERT INTO `barangmasuk` (`idMasuk`, `username`, `idBarang`, `qtyMasuk`, `tglMasuk`, `jamMasuk`) VALUES
(5328, 'realme', 1233, 40, '2024-10-23', '03:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_barang`
--

CREATE TABLE `data_barang` (
  `idBarang` int(255) NOT NULL,
  `namaBarang` varchar(1000) NOT NULL,
  `hargaBarang` int(255) NOT NULL,
  `satuanBarang` varchar(1000) NOT NULL,
  `qtyBarang` int(255) NOT NULL,
  `username` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_barang`
--

INSERT INTO `data_barang` (`idBarang`, `namaBarang`, `hargaBarang`, `satuanBarang`, `qtyBarang`, `username`) VALUES
(587, 'Tisu Magic', 10000, 'pcs', 0, 'fiesta'),
(1233, 'Realme 5i', 3500000, 'pcs', 23, 'realme'),
(7506, 'Vivo V40 Lite', 3500000, 'pcs', 0, 'vivo');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `username` varchar(1000) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `nohp` varchar(25) NOT NULL,
  `filefoto` varchar(1000) NOT NULL,
  `alamat` text NOT NULL,
  `role` enum('Admin','Costumer','Supplier') NOT NULL,
  `salt` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nohp`, `filefoto`, `alamat`, `role`, `salt`) VALUES
(1754, 'citra', 'a90b090559f204bd51e3df695d9c56d9d8c7e9b2e5001b0d1244116a641fef0c', '021', 'citra_1729627429.jpg', 'Indonesia', 'Costumer', '2343fd4d1ebb3b5d5eab86c714c20883'),
(2875, 'kelompok5', '676f18d2bbd58c0bbc1f6d7d878cbf172cdf17297afad04872e1bfb3afe3949b', '021', 'kelompok5_1729627030.jpg', 'Indonesia', 'Admin', 'f582f2a01b0cb26e810a1ac683e79d63'),
(3777, 'realme', '99458067ceb2e99a2785e5cf7e600e3a99602fd3e076e8f6968d09a7c1798e4e', '021', 'realme_1729627343.jpg', 'Indonesia', 'Supplier', '19bd7e8bcb521f0819378e016d76a066'),
(5271, 'dewadsc', 'f226c202dba354e532ed98d3de83871c186b92552383b316459b992c2af99a00', '085215636662', 'dewadsc_1729627294.jpeg', 'Indonesia', 'Admin', 'eb31f1aa391061cf55429600bc8fd438'),
(5292, 'vivo', '7d644fddf888e0032563a1ba1ff7a5ef47acc291a700861ff6310aab7ac22828', '021', 'vivo_1729627323.jpg', 'Indonesia', 'Supplier', '2b33e97b61bdaeb530f2f9769a79e6a1'),
(6373, 'mawar', 'cf7bf5994952d36b031af68a854148c7a1327e80b6a4ea1d30c10506baa82994', '021', 'mawar_1729627415.jpg', 'Indonesia', 'Costumer', '27dda5f599a64dac8c7ca1b37f9e2946'),
(7750, 'indah', 'e39e4e09dc2aff51387913aa10575836128e1d61df478b704519ac2116f04b71', '021', 'indah_1729627396.jpg', 'Indonesia', 'Costumer', '3ba4a32359c49739034242e28c8872df'),
(7752, 'bunga', 'e5c39d75480dc83bd603b6718f8923015e0351cb10229cce0c20e6cd0e9eb068', '021', 'bunga_1729627381.jpg', 'Indonesia', 'Costumer', '6c78f14cf683fc483e44c5103d682b8a'),
(9347, 'fiesta', '9ce3c87387fccd14576ad8e400053d80e00829dd2d7192db9aeb7440b0efe76d', '021', 'fiesta_1729627364.jpg', 'Indonesia', 'Supplier', '1037196adab09705bfd2a112370283a0');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barangkeluar`
--
ALTER TABLE `barangkeluar`
  ADD PRIMARY KEY (`idKeluar`);

--
-- Indeks untuk tabel `barangmasuk`
--
ALTER TABLE `barangmasuk`
  ADD PRIMARY KEY (`idMasuk`);

--
-- Indeks untuk tabel `data_barang`
--
ALTER TABLE `data_barang`
  ADD PRIMARY KEY (`idBarang`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
