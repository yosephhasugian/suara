-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 22 Bulan Mei 2026 pada 03.28
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suara`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ads_schedule`
--

CREATE TABLE `ads_schedule` (
  `id` int(11) NOT NULL,
  `ad_title` varchar(255) DEFAULT NULL,
  `ad_text` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `interval_minutes` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `repeat_days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`repeat_days`)),
  `last_played` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ads_schedule`
--

INSERT INTO `ads_schedule` (`id`, `ad_title`, `ad_text`, `duration`, `interval_minutes`, `start_date`, `end_date`, `start_time`, `end_time`, `repeat_days`, `last_played`, `created_at`, `is_active`) VALUES
(1, 'Aqua', 'Kebaikan berawal dari sini. AQUA melindungi kemurnian alam untuk kesehatan keluarga Indonesia', 15, 5, '2026-04-15', '2026-04-18', '00:00:00', '23:59:59', '[\"0\",\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]', '2026-04-18 08:58:24', '2026-04-14 21:06:48', 1),
(2, 'Kebersihan', 'Perhatian, demi menjaga keasrian lingkungan terminal, kami ingatkan kepada seluruh pengunjung untuk tetap menjaga kebersihan dan tidak merokok di sembarang tempat. Mari saling menghargai sesama pengguna jalan dengan menjaga fasilitas umum agar tetap bersih. Terima kasih', 30, 30, '2026-04-20', '2026-04-20', '11:00:00', '12:00:00', '[\"1\"]', '2026-04-20 11:30:15', '2026-04-20 03:29:48', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ads_schedules`
--

CREATE TABLE `ads_schedules` (
  `id` int(11) NOT NULL,
  `ad_title` varchar(255) DEFAULT NULL,
  `ad_text` text DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `repeat_days` varchar(100) DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT 30,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `audio_queue`
--

CREATE TABLE `audio_queue` (
  `id` int(11) NOT NULL,
  `type` enum('bus','announcer','prayer','ads','youtube') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `priority` tinyint(4) DEFAULT 5,
  `status` enum('pending','playing','done','cancelled') DEFAULT 'pending',
  `scheduled_at` datetime DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT 30,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `area_updated_at` datetime DEFAULT NULL,
  `plat_nomor` varchar(20) DEFAULT NULL,
  `nama_po` varchar(100) DEFAULT NULL,
  `tujuan` varchar(100) DEFAULT NULL,
  `area` enum('masuk','keberangkatan','kedatangan','pengendapan','berangkat') DEFAULT 'masuk',
  `jam_keluar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `audio_queue`
--

INSERT INTO `audio_queue` (`id`, `type`, `title`, `text`, `ref_id`, `youtube_url`, `priority`, `status`, `scheduled_at`, `duration_seconds`, `created_at`, `updated_at`, `area_updated_at`, `plat_nomor`, `nama_po`, `tujuan`, `area`, `jam_keluar`) VALUES
(18, 'bus', NULL, 'Perhatian. Bus SINAR JAYA dengan nomor polisi B 7622 TGD telah memasuki area terminal. Terima kasih.', NULL, NULL, 3, 'playing', NULL, 30, '2026-04-27 03:34:48', '2026-05-03 03:46:58', '2026-04-27 10:34:48', 'B 7622 TGD', 'SINAR JAYA', '', 'berangkat', NULL),
(19, 'bus', NULL, 'Perhatian. Bus SINAR JAYA dengan nomor polisi B 7523 IS telah memasuki area terminal. Terima kasih.', NULL, NULL, 3, 'playing', NULL, 30, '2026-04-27 03:36:34', '2026-05-03 03:46:58', '2026-04-27 10:36:34', 'B 7523 IS', 'SINAR JAYA', 'Pekalongan', 'keberangkatan', NULL),
(20, 'prayer', NULL, 'Perhatian, waktu sholat dzuhur akan segera tiba dalam 10 menit. Silakan bersiap menuju masjid terminal.', NULL, NULL, 5, 'done', NULL, 30, '2026-04-27 04:54:13', '2026-05-03 03:46:58', '2026-04-27 11:54:13', NULL, NULL, NULL, 'masuk', NULL),
(21, 'prayer', NULL, 'Kepada seluruh penumpang, waktu sholat dzuhur telah tiba. , bagi Anda yang ingin menunaikan ibadah salat, tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal. Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasih.', NULL, NULL, 5, 'done', NULL, 30, '2026-04-27 05:04:13', '2026-05-03 03:46:58', '2026-04-27 12:04:13', NULL, NULL, NULL, 'masuk', NULL),
(22, 'bus', NULL, 'Perhatian. Bus SINAR JAYA dengan nomor polisi B 1245 VTY telah memasuki area terminal. Terima kasih.', NULL, NULL, 3, 'playing', NULL, 30, '2026-04-29 02:53:00', '2026-05-03 03:46:58', '2026-04-29 09:53:00', 'B 1245 VTY', 'SINAR JAYA', 'PARKIR', 'pengendapan', NULL),
(23, 'prayer', NULL, 'Perhatian, waktu sholat dzuhur akan segera tiba dalam 10 menit. Silakan bersiap menuju masjid terminal.', NULL, NULL, 5, 'done', NULL, 30, '2026-04-29 04:54:15', '2026-05-03 03:46:58', '2026-04-29 11:54:15', NULL, NULL, NULL, 'masuk', NULL),
(24, 'bus', NULL, 'Perhatian. Bus SINAR JAYA dengan nomor polisi B 7622 TGD telah memasuki area terminal. Terima kasih.', NULL, NULL, 3, 'done', NULL, 30, '2026-05-03 02:45:23', '2026-05-03 04:20:30', '2026-05-03 11:20:30', 'B 7622 TGD', 'SINAR JAYA', '', 'berangkat', NULL),
(25, 'announcer', NULL, 'Mohon perhatian. Panggilan ditujukan kepada penumpang atas nama POLTAK HASUGIAN. Untuk penumpang bus SINAR JAYA tujuan YOGYAKARTA, ditunggu kehadiran Anda di pintu 2, dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.', NULL, NULL, 2, 'done', NULL, 30, '2026-05-03 02:46:00', '2026-05-03 03:46:58', '2026-05-03 09:46:00', NULL, NULL, NULL, 'masuk', NULL),
(26, 'ads', NULL, 'Panggilan kepada pemilik dompet yang tertinggal. Mohon segera mendatangi Pusat Informasi yang berada di Lantai 2, Area Keberangkatan', NULL, NULL, 4, 'done', NULL, 30, '2026-05-03 02:46:34', '2026-05-03 03:46:58', '2026-05-03 09:46:34', NULL, NULL, NULL, 'masuk', NULL),
(27, 'bus', NULL, 'Perhatian. Bus GUNUG HARTA dengan nomor polisi G 1234 VGA telah memasuki area terminal. Terima kasih.', NULL, NULL, 3, 'done', NULL, 30, '2026-05-03 03:25:07', '2026-05-03 04:18:37', '2026-05-03 11:18:37', 'G 1234 VGA', 'GUNUG HARTA', '', 'berangkat', NULL),
(28, 'bus', NULL, 'Perhatian. Bus Madu Kismo dengan nomor polisi B 7845 XRT telah memasuki area terminal. Terima kasih.', NULL, NULL, 3, 'done', NULL, 30, '2026-05-03 03:29:48', '2026-05-03 04:20:42', '2026-05-03 11:20:42', 'B 7845 XRT', 'Madu Kismo', '', 'berangkat', NULL),
(29, 'bus', NULL, 'Perhatian. Bus bejeu dengan nomor polisi k 1111 ljk telah memasuki area terminal. Terima kasih.', NULL, NULL, 3, 'done', NULL, 30, '2026-05-03 04:42:26', '2026-05-03 04:49:17', '2026-05-03 11:49:17', 'k 1111 ljk', 'bejeu', 'pekalongan', 'keberangkatan', NULL),
(30, 'prayer', NULL, 'Perhatian, waktu sholat ashar akan segera tiba dalam 10 menit. Silakan bersiap menuju masjid terminal.', NULL, NULL, 5, 'done', NULL, 30, '2026-05-03 08:09:12', '2026-05-03 08:09:22', NULL, NULL, NULL, NULL, 'masuk', NULL),
(31, 'prayer', NULL, 'Kepada seluruh penumpang, waktu sholat ashar telah tiba. , bagi Anda yang ingin menunaikan ibadah salat, tersedia fasilitas masjid yang nyaman di Lantai 1 Terminal. Mari tetap menjaga ketepatan waktu ibadah di sela perjalanan Anda. Terima kasih.', NULL, NULL, 5, 'done', NULL, 30, '2026-05-03 08:19:12', '2026-05-03 08:19:34', NULL, NULL, NULL, NULL, 'masuk', NULL),
(32, 'announcer', NULL, 'Mohon perhatian. Panggilan ditujukan kepada penumpang atas nama Bapak jony. Untuk penumpang bus Sinar jaya tujuan Surabayq, ditunggu kehadiran Anda di pintu 3, dikarenakan bus Anda akan segera diberangkatkan. Terima kasih.', NULL, NULL, 2, 'done', NULL, 30, '2026-05-07 04:05:50', '2026-05-07 04:06:21', NULL, NULL, NULL, NULL, 'masuk', NULL),
(33, 'bus', NULL, 'Perhatian. Bus Sinar jaya dengan nomor polisi B 1234 xyz telah memasuki area terminal. Terima kasih.', NULL, NULL, 3, 'playing', NULL, 30, '2026-05-07 04:07:50', '2026-05-07 04:10:10', '2026-05-07 11:10:10', 'B 1234 xyz', 'Sinar jaya', 'Surabaya', 'pengendapan', NULL),
(34, 'bus', NULL, 'Perhatian. Bus 27 Trans dengan nomor polisi N 7105 UB telah memasuki area terminal. Terima kasih.', NULL, NULL, 3, 'done', NULL, 30, '2026-05-19 10:50:27', '2026-05-19 10:59:46', '2026-05-19 17:59:46', 'N 7105 UB', '27 Trans', '', 'berangkat', NULL),
(35, 'prayer', NULL, 'Perhatian, waktu sholat maghrib akan segera tiba dalam 10 menit. Silakan bersiap menuju masjid terminal.', NULL, NULL, 5, 'done', NULL, 30, '2026-05-19 10:50:40', '2026-05-19 10:50:53', NULL, NULL, NULL, NULL, 'masuk', NULL);

--
-- Trigger `audio_queue`
--
DELIMITER $$
CREATE TRIGGER `after_update_audio_queue` AFTER UPDATE ON `audio_queue` FOR EACH ROW BEGIN

    IF OLD.area <> NEW.area THEN

        -- Tutup history lama
        UPDATE bus_history 
        SET waktu_keluar = NOW(),
            durasi_detik = TIMESTAMPDIFF(SECOND, waktu_masuk, NOW())
        WHERE bus_id = NEW.id 
        AND waktu_keluar IS NULL;

        -- Insert history baru
        INSERT INTO bus_history (bus_id, area, waktu_masuk)
        VALUES (NEW.id, NEW.area, NOW());

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bus_history`
--

CREATE TABLE `bus_history` (
  `id` int(11) NOT NULL,
  `bus_id` int(11) DEFAULT NULL,
  `area` enum('masuk','kedatangan','pengendapan','keberangkatan','berangkat') DEFAULT NULL,
  `waktu_masuk` datetime DEFAULT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `durasi_detik` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bus_history`
--

INSERT INTO `bus_history` (`id`, `bus_id`, `area`, `waktu_masuk`, `waktu_keluar`, `durasi_detik`, `created_at`) VALUES
(39, 18, 'masuk', '2026-04-27 10:34:48', '2026-04-27 10:36:08', 80, '2026-04-27 03:34:48'),
(40, 18, 'kedatangan', '2026-04-27 10:36:08', '2026-04-27 14:19:33', 13405, '2026-04-27 03:36:08'),
(41, 19, 'masuk', '2026-04-27 10:36:34', '2026-04-27 10:36:58', 24, '2026-04-27 03:36:34'),
(42, 19, 'keberangkatan', '2026-04-27 10:36:58', NULL, 0, '2026-04-27 03:36:58'),
(43, 18, 'berangkat', '2026-04-27 14:19:33', NULL, 0, '2026-04-27 07:19:33'),
(44, 22, 'masuk', '2026-04-29 09:53:00', '2026-04-29 10:16:06', 1386, '2026-04-29 02:53:00'),
(45, 22, 'kedatangan', '2026-04-29 10:16:06', '2026-04-29 10:17:19', 73, '2026-04-29 03:16:06'),
(46, 22, 'pengendapan', '2026-04-29 10:17:19', NULL, 0, '2026-04-29 03:17:19'),
(47, 24, 'masuk', '2026-05-03 09:45:23', '2026-05-03 10:07:16', 1313, '2026-05-03 02:45:23'),
(48, 24, 'keberangkatan', '2026-05-03 10:07:16', '2026-05-03 11:20:30', 4394, '2026-05-03 03:07:16'),
(49, 27, 'masuk', '2026-05-03 10:25:07', '2026-05-03 10:41:02', 955, '2026-05-03 03:25:07'),
(50, 28, 'masuk', '2026-05-03 10:29:48', '2026-05-03 10:56:13', 1585, '2026-05-03 03:29:48'),
(51, 27, 'kedatangan', '2026-05-03 10:41:02', '2026-05-03 11:00:43', 1181, '2026-05-03 03:41:02'),
(52, 28, 'keberangkatan', '2026-05-03 10:56:13', '2026-05-03 11:20:42', 1469, '2026-05-03 03:56:13'),
(53, 27, 'pengendapan', '2026-05-03 11:00:43', '2026-05-03 11:13:33', 770, '2026-05-03 04:00:43'),
(54, 27, 'berangkat', '2026-05-03 11:13:33', '2026-05-03 11:18:16', 283, '2026-05-03 04:13:33'),
(55, 27, 'kedatangan', '2026-05-03 11:18:16', '2026-05-03 11:18:37', 21, '2026-05-03 04:18:16'),
(56, 27, 'berangkat', '2026-05-03 11:18:37', NULL, 0, '2026-05-03 04:18:37'),
(57, 24, 'berangkat', '2026-05-03 11:20:30', NULL, 0, '2026-05-03 04:20:30'),
(58, 28, 'berangkat', '2026-05-03 11:20:42', NULL, 0, '2026-05-03 04:20:42'),
(59, 29, 'masuk', '2026-05-03 11:42:26', '2026-05-03 11:49:17', 411, '2026-05-03 04:42:26'),
(60, 29, 'keberangkatan', '2026-05-03 11:49:17', NULL, 0, '2026-05-03 04:49:17'),
(61, 33, 'masuk', '2026-05-07 11:07:50', '2026-05-07 11:08:48', 58, '2026-05-07 04:07:50'),
(62, 33, 'keberangkatan', '2026-05-07 11:08:48', '2026-05-07 11:10:10', 82, '2026-05-07 04:08:48'),
(63, 33, 'pengendapan', '2026-05-07 11:10:10', NULL, 0, '2026-05-07 04:10:10'),
(64, 34, 'masuk', '2026-05-19 17:50:27', '2026-05-19 17:53:13', 166, '2026-05-19 10:50:27'),
(65, 34, 'kedatangan', '2026-05-19 17:53:13', '2026-05-19 17:54:07', 54, '2026-05-19 10:53:13'),
(66, 34, 'pengendapan', '2026-05-19 17:54:07', '2026-05-19 17:59:46', 339, '2026-05-19 10:54:07'),
(67, 34, 'berangkat', '2026-05-19 17:59:46', NULL, 0, '2026-05-19 10:59:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lost_found`
--

CREATE TABLE `lost_found` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `lokasi_ditemukan` varchar(150) DEFAULT NULL,
  `tanggal_ditemukan` date DEFAULT NULL,
  `nama_penemu` varchar(100) DEFAULT NULL,
  `kontak_penemu` varchar(50) DEFAULT NULL,
  `status` enum('ditemukan','diambil') DEFAULT 'ditemukan',
  `nama_pengambil` varchar(100) DEFAULT NULL,
  `tanggal_diambil` date DEFAULT NULL,
  `bukti_foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `no_hp_pengambil` varchar(20) DEFAULT NULL,
  `no_identitas` varchar(50) DEFAULT NULL,
  `alamat_pengambil` text DEFAULT NULL,
  `nama_petugas` varchar(100) DEFAULT NULL,
  `foto_pengambilan` varchar(255) DEFAULT NULL,
  `foto_identitas` varchar(255) DEFAULT NULL,
  `waktu_pengambilan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lost_found`
--

INSERT INTO `lost_found` (`id`, `nama_barang`, `kategori`, `deskripsi`, `lokasi_ditemukan`, `tanggal_ditemukan`, `nama_penemu`, `kontak_penemu`, `status`, `nama_pengambil`, `tanggal_diambil`, `bukti_foto`, `created_at`, `no_hp_pengambil`, `no_identitas`, `alamat_pengambil`, `nama_petugas`, `foto_pengambilan`, `foto_identitas`, `waktu_pengambilan`) VALUES
(1, 'Koper Hitam', 'Tas', 'Telah di Temukan Koper Penumpang warna Biru Moda Di Area Keberangkatan Tepat di Pintu 5', 'Area Keberangkatan', '2026-04-20', 'Poltak Hasugian', '082110101902', 'diambil', 'FAERAN', '2026-04-20', 'koper.jpeg', '2026-04-20 08:40:25', '08978109784', '3275041506840023', 'Bekasi', 'Poltak Hasugian', '', '', '2026-04-20 15:51:59'),
(2, 'Hape Samsung', 'Elektronik', 'Telah di temukan Sebuah Hape di Meja Check Point', 'Area Check Point', '2026-04-20', 'Amel', '089781264', 'ditemukan', NULL, NULL, 'hape.jpeg', '2026-04-20 09:07:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `signages`
--

CREATE TABLE `signages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) DEFAULT 'Lokasi Umum',
  `layout` enum('single','dual','video_text','carousel') DEFAULT 'single',
  `content` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `signages`
--

INSERT INTO `signages` (`id`, `name`, `location`, `layout`, `content`, `status`, `created_by`, `updated_at`) VALUES
(1, 'TTPG MZ 01', 'Lobi Utama', 'single', '{\"text\": \"Selamat Datang di Terminal Terpadu Pulo Gebang\", \"image\": \"assets/uploads/images/ttpg.jpeg\"}', 'active', 1, '2025-11-12 04:20:39'),
(2, 'TTPG MZ 02', 'Ruang Rapat', 'dual', '{\"text\": \"Meeting hari ini: Workshop CMS\", \"image\": \"assets/uploads/images/ttpg.jpeg\"}', 'inactive', 1, '2025-11-12 04:20:39'),
(3, 'TTPG MZ 03', 'Area Produksi', 'video_text', '{\"text\": \"Safety First!\", \"video\": \"assets/uploads/videos/bu_umi.mp4\"}', 'active', NULL, '2025-11-12 04:20:39'),
(4, 'TTPG Test', 'Kantin', 'dual', '{\"items\":[\"Promo Nasi Goreng\",\"Diskon Minuman\"],\"image\":\"assets\\/uploads\\/images\\/bb9cb37c8d87fac83d9b920126fd5166.png\",\"text\":\"Saat nya Uji Coba\",\"video\":\"assets\\/uploads\\/videos\\/6cb1ad6336de5fd649ebcfd43577c60c.mp4\"}', 'active', NULL, '2025-11-13 03:14:28'),
(5, 'TTPG MZ 05', 'Parkir', 'single', '{\"text\": \"Parkir Aman, Nyaman, Terjaga\", \"image\": \"assets/uploads/images/ttpg.jpeg\"}', 'active', 1, '2025-11-12 04:20:39'),
(7, 'D.Keberangkatan1', 'Keberangkatan', 'dual', '{\"image\":\"assets\\/uploads\\/images\\/4982092b83fa23c2fd1009e6c969f739.jpeg\",\"video\":\"assets\\/uploads\\/videos\\/c457b7575466947efd2de19ba71d632d.mp4\",\"text\":\"Saat nya Uji Coba\"}', 'active', 1, '2025-11-13 03:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','teknisi','petugas_masuk','petugas_keluar','petugas_kedatangan','petugas_keberangkatan','petugas_pengendapan') NOT NULL DEFAULT 'teknisi',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$UkDdd5KoiVDIQsLaCMTX0elkxymVToAs9k7lGWXB.BOIQcys8f2GW', 'Admin Utama', 'admin', '2025-11-11 07:57:58'),
(2, 'user_masuk', '$2y$10$tMw8q.65TB/tKFJgYpa/pu7uxcczu1E5EFQAtxll4lcX6l6MCkP1y', 'Petugas Masuk', 'petugas_masuk', '2026-06-02 00:00:00'),
(3, 'user_keluar', '$2y$10$BvuGo4M8AtpK2SY1xozuMeSCWkTD9UlF31MHSiPWeWlEqhRZvp/8y', 'Petugas Keluar', 'petugas_keluar', '2026-06-02 00:00:00'),
(4, 'user_kedatangan', '$2y$10$u4Y6GhpqeX4OP2kAKggfsuU/3rJyJ/vVByX4FEc8dHUkmgkOMdhiS', 'Petugas Kedatangan', 'petugas_kedatangan', '2026-06-02 00:00:00'),
(5, 'user_keberangkatan', '$2y$10$d4uzJQYK5/4VmQOdeI0U4OwR06wdJl.symkk/3cjFQW3StcfN7T1C', 'Petugas Keberangkatan', 'petugas_keberangkatan', '2026-06-02 00:00:00'),
(6, 'user_pengendapan', '$2y$10$yJxSof9Vquxy4km1pXSmvuR6N8QOlv2EAP45Blsm2fE4xxTxRqc46', 'Petugas Pengendapan', 'petugas_pengendapan', '2026-06-02 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `youtube_playlist`
--

CREATE TABLE `youtube_playlist` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `video_id` varchar(20) NOT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `play_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `youtube_playlist`
--

INSERT INTO `youtube_playlist` (`id`, `title`, `youtube_url`, `video_id`, `duration`, `is_active`, `play_count`, `created_at`) VALUES
(1, 'Lagu Santai ', 'https://www.youtube.com/watch?v=lRC8dgMjDF4&list=RDlRC8dgMjDF4&start_radio=1', 'lRC8dgMjDF4', NULL, 1, 0, '2026-04-13 02:30:40'),
(2, 'Lagu Lagi', 'https://www.youtube.com/watch?v=5k96z6MxX28&list=RD5k96z6MxX28&start_radio=1&t=52s', '5k96z6MxX28', NULL, 1, 0, '2026-04-13 02:31:14'),
(3, 'Lagu Santai', 'https://www.youtube.com/watch?v=34PUV5pAvHM&list=RD34PUV5pAvHM&start_radio=1', '34PUV5pAvHM', NULL, 1, 0, '2026-04-13 03:49:05');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ads_schedule`
--
ALTER TABLE `ads_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ads_schedules`
--
ALTER TABLE `ads_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_action` (`user_id`,`action`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indeks untuk tabel `audio_queue`
--
ALTER TABLE `audio_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status_priority` (`status`,`priority`),
  ADD KEY `idx_scheduled` (`scheduled_at`,`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_type_area` (`type`,`area`);

--
-- Indeks untuk tabel `bus_history`
--
ALTER TABLE `bus_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_waktu_masuk` (`waktu_masuk`),
  ADD KEY `idx_bus_area` (`bus_id`,`area`);

--
-- Indeks untuk tabel `lost_found`
--
ALTER TABLE `lost_found`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `signages`
--
ALTER TABLE `signages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `users`
  MODIFY `role` enum('admin','teknisi','petugas_masuk','petugas_keluar','petugas_kedatangan','petugas_keberangkatan','petugas_pengendapan') NOT NULL DEFAULT 'teknisi';

--
-- Indeks untuk tabel `youtube_playlist`
--
ALTER TABLE `youtube_playlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`is_active`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ads_schedule`
--
ALTER TABLE `ads_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `ads_schedules`
--
ALTER TABLE `ads_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `audio_queue`
--
ALTER TABLE `audio_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `bus_history`
--
ALTER TABLE `bus_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT untuk tabel `lost_found`
--
ALTER TABLE `lost_found`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `signages`
--
ALTER TABLE `signages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `youtube_playlist`
--
ALTER TABLE `youtube_playlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `signages`
--
ALTER TABLE `signages`
  ADD CONSTRAINT `signages_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
