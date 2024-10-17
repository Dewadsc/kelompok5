-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Okt 2024 pada 23.16
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
-- Struktur dari tabel `barangmasuk`
--

CREATE TABLE `barangmasuk` (
  `idMasuk` int(255) NOT NULL,
  `idSuplier` int(255) NOT NULL,
  `idBarang` int(255) NOT NULL,
  `qtyMasuk` int(255) NOT NULL,
  `tglMasuk` date NOT NULL,
  `jamMasuk` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barangmasuk`
--

INSERT INTO `barangmasuk` (`idMasuk`, `idSuplier`, `idBarang`, `qtyMasuk`, `tglMasuk`, `jamMasuk`) VALUES
(1784, 6944, 1441, 90, '2024-10-18', '04:02'),
(2854, 9881, 856, 40, '2024-10-17', '17:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `costumer`
--

CREATE TABLE `costumer` (
  `idCostumer` int(255) NOT NULL,
  `namaCostumer` varchar(1000) NOT NULL,
  `nohpCostumer` varchar(25) NOT NULL,
  `alamatCostumer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `costumer`
--

INSERT INTO `costumer` (`idCostumer`, `namaCostumer`, `nohpCostumer`, `alamatCostumer`) VALUES
(8741, 'Mutiara Utami', '0852xxxxxxxx', 'btn jl.asri, jambi'),
(8876, 'Aura', '0852xxxxxxxx', 'Kwitang'),
(9519, 'Cleo', '0852xxxxxxxx', 'Jl.Kembang VIII');

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
  `idSuplier` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_barang`
--

INSERT INTO `data_barang` (`idBarang`, `namaBarang`, `hargaBarang`, `satuanBarang`, `qtyBarang`, `idSuplier`) VALUES
(856, 'Tisu Magic', 10000, 'pcs', 40, 9881),
(1441, 'Infinix Note 40 Pro', 3500000, 'pcs', 90, 6944),
(4937, 'Vivo V40 Lite', 3500000, 'pcs', 0, 7271);

-- --------------------------------------------------------

--
-- Struktur dari tabel `suplier`
--

CREATE TABLE `suplier` (
  `idSuplier` int(255) NOT NULL,
  `namaSuplier` varchar(1000) NOT NULL,
  `kontakSuplier` varchar(1000) NOT NULL,
  `alamatSuplier` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `suplier`
--

INSERT INTO `suplier` (`idSuplier`, `namaSuplier`, `kontakSuplier`, `alamatSuplier`) VALUES
(6944, 'Infinix', '021', 'Indonesia'),
(7271, 'Vivo', '021', 'Indonesia'),
(9881, 'Indofood', '021', 'Indonesia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `username` varchar(1000) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `nohp` varchar(25) NOT NULL,
  `filefoto` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nohp`, `filefoto`) VALUES
(3539, 'dewadsc', '$2y$10$z9XzWhwQOvcybxzgsGok2uZmQyunxXf.0smpRAqXqF1wpj202fVn6', '085215636662', 'dewa_1728897945.jpeg'),
(7349, 'kelompok5', '$2y$10$489RU8yISKTAGfGwVSrMb.7s3UicZQpMi6kU7uSVhQgtPgHinE48S', '021', 'user_670c9944cc0016.43300786.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barangmasuk`
--
ALTER TABLE `barangmasuk`
  ADD PRIMARY KEY (`idMasuk`);

--
-- Indeks untuk tabel `costumer`
--
ALTER TABLE `costumer`
  ADD PRIMARY KEY (`idCostumer`);

--
-- Indeks untuk tabel `data_barang`
--
ALTER TABLE `data_barang`
  ADD PRIMARY KEY (`idBarang`);

--
-- Indeks untuk tabel `suplier`
--
ALTER TABLE `suplier`
  ADD PRIMARY KEY (`idSuplier`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
