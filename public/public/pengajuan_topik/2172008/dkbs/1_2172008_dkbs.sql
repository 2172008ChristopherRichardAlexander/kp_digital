-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 10:04 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kp2_digital`
--

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `id_batch` int(10) UNSIGNED NOT NULL,
  `nama_batch` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai_batch` date NOT NULL,
  `tanggal_selesai_batch` date DEFAULT NULL,
  `jenis_batch` tinyint(4) NOT NULL,
  `minimal_bimbingan` tinyint(4) NOT NULL DEFAULT 0,
  `status_batch` tinyint(4) NOT NULL DEFAULT 1,
  `id_jurusan` int(10) UNSIGNED NOT NULL,
  `id_semester` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batch`
--

INSERT INTO `batch` (`id_batch`, `nama_batch`, `tanggal_mulai_batch`, `tanggal_selesai_batch`, `jenis_batch`, `minimal_bimbingan`, `status_batch`, `id_jurusan`, `id_semester`) VALUES
(1, 'KP 24/25', '2024-12-01', '2024-12-16', 1, 0, 1, 2, 1),
(2, 'Sidang 24/25', '2024-12-01', '2024-12-17', 2, 0, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bimbingan`
--

CREATE TABLE `bimbingan` (
  `id_bimbingan` int(10) UNSIGNED NOT NULL,
  `deskripsi_bimbingan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_bimbingan` date NOT NULL,
  `status_bimbingan` tinyint(4) NOT NULL DEFAULT 2,
  `id_topik` int(10) UNSIGNED NOT NULL,
  `id_pembimbing_bimbingan` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bimbingan`
--

INSERT INTO `bimbingan` (`id_bimbingan`, `deskripsi_bimbingan`, `tanggal_bimbingan`, `status_bimbingan`, `id_topik`, `id_pembimbing_bimbingan`) VALUES
(1, 'aaa', '2024-12-02', 1, 1, 243512);

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(10) UNSIGNED NOT NULL,
  `nama_jabatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_jabatan` tinyint(4) NOT NULL DEFAULT 1,
  `id_role` int(10) UNSIGNED NOT NULL,
  `id_kbk` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `status_jabatan`, `id_role`, `id_kbk`) VALUES
(2, 'Koordinator KP', 1, 2, 1),
(3, 'Ketua KBK Multimedia dan Jaringan', 1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jabatan_pengguna`
--

CREATE TABLE `jabatan_pengguna` (
  `id_jabatan_pengguna` int(10) UNSIGNED NOT NULL,
  `id_pengguna` int(10) UNSIGNED NOT NULL,
  `id_jabatan` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatan_pengguna`
--

INSERT INTO `jabatan_pengguna` (`id_jabatan_pengguna`, `id_pengguna`, `id_jabatan`) VALUES
(2, 243512, 3),
(243512, 243512, 2);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_mahasiswa`
--

CREATE TABLE `jadwal_mahasiswa` (
  `id_jadwal_mahasiswa` int(10) UNSIGNED NOT NULL,
  `hari_jadwal_mahasiswa` tinyint(4) NOT NULL,
  `waktu_mulai_jadwal_mahasiswa` time NOT NULL,
  `waktu_selesai_jadwal_mahasiswa` time NOT NULL,
  `keterangan_jadwal_mahasiswa` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_topik` int(10) UNSIGNED NOT NULL,
  `id_batch` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_mahasiswa`
--

INSERT INTO `jadwal_mahasiswa` (`id_jadwal_mahasiswa`, `hari_jadwal_mahasiswa`, `waktu_mulai_jadwal_mahasiswa`, `waktu_selesai_jadwal_mahasiswa`, `keterangan_jadwal_mahasiswa`, `id_topik`, `id_batch`) VALUES
(1, 2, '01:00:00', '01:00:00', 'asd', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_mbkm`
--

CREATE TABLE `jenis_mbkm` (
  `id_jenis_mbkm` int(10) UNSIGNED NOT NULL,
  `nama_mbkm` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_mbkm`
--

INSERT INTO `jenis_mbkm` (`id_jenis_mbkm`, `nama_mbkm`, `created_at`, `updated_at`) VALUES
(1, 'Magang', NULL, NULL),
(2, 'Studi Independen', NULL, NULL),
(3, 'KKN', NULL, NULL),
(4, 'Magang Mandiri', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_semester`
--

CREATE TABLE `jenis_semester` (
  `id_jenis_semester` int(10) UNSIGNED NOT NULL,
  `nama_semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_semester`
--

INSERT INTO `jenis_semester` (`id_jenis_semester`, `nama_semester`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Semester 1', 1, NULL, NULL),
(2, 'Semester 2', 1, NULL, NULL),
(3, 'Semester 3', 1, NULL, NULL),
(4, 'Semester 4', 1, NULL, NULL),
(5, 'Semester 5', 1, NULL, NULL),
(6, 'Semester 6', 1, NULL, NULL),
(7, 'Semester 7', 1, NULL, NULL),
(8, 'Semester 8', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_sidang`
--

CREATE TABLE `jenis_sidang` (
  `id_jenis_sidang` int(10) UNSIGNED NOT NULL,
  `nama_jenis_sidang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `komponen_nilai_jenis_sidang` tinyint(4) NOT NULL,
  `status_jenis_sidang` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(10) UNSIGNED NOT NULL,
  `nama_jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_jurusan` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`, `status_jurusan`) VALUES
(2, 'S1 Teknik Informatika', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kbk`
--

CREATE TABLE `kbk` (
  `id_kbk` int(10) UNSIGNED NOT NULL,
  `nama_kbk` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_kbk` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kbk`
--

INSERT INTO `kbk` (`id_kbk`, `nama_kbk`, `status_kbk`) VALUES
(1, 'Multimedia dan Jaringan', 1),
(2, 'Rekayasa Perangkat Lunak', 1),
(3, 'Sistem Informasi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `id_komentar` int(10) UNSIGNED NOT NULL,
  `isi_komentar` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_komentar` date NOT NULL,
  `id_topik` int(10) UNSIGNED NOT NULL,
  `id_pengguna` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komponen_nilai`
--

CREATE TABLE `komponen_nilai` (
  `id_komponen_nilai` int(10) UNSIGNED NOT NULL,
  `nama_komponen_nilai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pembimbing` tinyint(4) NOT NULL,
  `pembimbing_lapangan` tinyint(4) NOT NULL,
  `penguji` tinyint(4) NOT NULL,
  `koordinator_kp` tinyint(4) NOT NULL,
  `status_komponen_nilai` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komponen_penilaian`
--

CREATE TABLE `komponen_penilaian` (
  `id_komponen_penilaian` int(10) UNSIGNED NOT NULL,
  `judul_komponen_penilaian` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_komponen_penilaian` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bobot_komponen_penilaian` double(4,3) NOT NULL,
  `status_komponen_penilaian` tinyint(4) NOT NULL DEFAULT 1,
  `id_role` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komponen_penilaian_sidang`
--

CREATE TABLE `komponen_penilaian_sidang` (
  `id_komponen_penilaian_sidang` int(10) UNSIGNED NOT NULL,
  `id_komponen_penilaian` int(10) UNSIGNED NOT NULL,
  `id_sidang` int(10) UNSIGNED NOT NULL,
  `nilai_komponen_penilaian_sidang` double(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `konversi_sks`
--

CREATE TABLE `konversi_sks` (
  `id_matakuliah_detail` int(10) UNSIGNED NOT NULL,
  `id_matakuliah` int(10) UNSIGNED NOT NULL,
  `id_pengguna` int(10) UNSIGNED NOT NULL,
  `id_semester` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `konversi_sks`
--

INSERT INTO `konversi_sks` (`id_matakuliah_detail`, `id_matakuliah`, `id_pengguna`, `id_semester`) VALUES
(3, 102, 336, 1),
(4, 103, 336, 1),
(5, 104, 336, 1),
(6, 105, 336, 1),
(7, 106, 336, 1),
(8, 117, 336, 1);

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(10) UNSIGNED NOT NULL,
  `file_laporan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pengguna` int(10) UNSIGNED NOT NULL,
  `id_semester` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id_matakuliah` int(10) UNSIGNED NOT NULL,
  `nama_matakul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_sks` int(10) UNSIGNED NOT NULL,
  `id_jenis_semester` int(10) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id_matakuliah`, `nama_matakul`, `jumlah_sks`, `id_jenis_semester`, `status`, `created_at`, `updated_at`) VALUES
(101, 'Matematika Dasar', 20, 1, 1, NULL, NULL),
(102, 'Fisika Umum', 4, 1, 1, NULL, NULL),
(103, 'Kimia Dasar', 3, 1, 1, NULL, NULL),
(104, 'Pemrograman Dasar', 3, 2, 1, NULL, NULL),
(105, 'Algoritma dan Struktur Data', 3, 2, 1, NULL, NULL),
(106, 'Statistika', 3, 2, 1, NULL, NULL),
(107, 'Basis Data', 3, 3, 1, NULL, NULL),
(108, 'Pemrograman Lanjut', 3, 3, 1, NULL, NULL),
(109, 'Sistem Operasi', 3, 3, 1, NULL, NULL),
(110, 'Jaringan Komputer', 3, 4, 1, NULL, NULL),
(111, 'Manajemen Proyek', 2, 4, 1, NULL, NULL),
(112, 'Kecerdasan Buatan', 4, 4, 1, NULL, NULL),
(113, 'Sistem Informasi', 3, 5, 1, NULL, NULL),
(114, 'Pemrograman Web', 3, 5, 1, NULL, NULL),
(115, 'Analisis Data', 3, 6, 1, NULL, NULL),
(116, 'Cloud Computing', 3, 6, 1, NULL, NULL),
(117, 'Machine Learning', 4, 7, 1, NULL, NULL),
(118, 'Proyek Akhir 1', 6, 7, 1, NULL, NULL),
(119, 'Proyek Akhir 2', 6, 8, 1, NULL, NULL),
(120, 'Etika dan Profesi', 2, 8, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mbkm`
--

CREATE TABLE `mbkm` (
  `id_mbkm` int(11) NOT NULL,
  `nama_instansi` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `status` tinyint(4) DEFAULT 0,
  `id_jenis_mbkm` int(10) UNSIGNED DEFAULT NULL,
  `id_pengguna` int(10) UNSIGNED DEFAULT NULL,
  `id_semester` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mbkm`
--

INSERT INTO `mbkm` (`id_mbkm`, `nama_instansi`, `deskripsi`, `status`, `id_jenis_mbkm`, `id_pengguna`, `id_semester`, `created_at`, `updated_at`) VALUES
(2, 'Coursenet', 'Ini deskripsi', 0, 1, 336, 1, NULL, NULL),
(6, 'HiCollege', 'Deskripsadi', 0, 2, 359, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2018_11_22_155038_create_pengguna_role_table', 1),
(2, '2019_05_28_103001_create_semester_table', 1),
(3, '2019_09_22_095309_create_jurusan_table', 1),
(4, '2019_09_22_095643_create_role_table', 1),
(5, '2019_09_22_095644_create_pengguna_table', 1),
(6, '2019_09_22_095644_create_syarat_ketentuan_sidang', 1),
(7, '2019_09_22_095650_create_batch_table', 1),
(8, '2019_09_22_095657_create_kbk_table', 1),
(9, '2019_09_22_095705_create_jabatan_table', 1),
(10, '2019_09_22_095713_create_jabatan_pengguna_table', 1),
(11, '2019_09_22_095719_create_topik_table', 1),
(12, '2019_09_22_095730_create_komentar_table', 1),
(13, '2019_09_22_095736_create_bimbingan_table', 1),
(14, '2019_09_22_095743_create_komponen_nilai_table', 1),
(15, '2019_09_22_095755_create_jenis_sidang_table', 1),
(16, '2019_09_22_095802_create_sidang_table', 1),
(17, '2019_11_05_160343_create_jadwal_mahasiswa_table', 1),
(18, '2019_11_15_181241_create_komponen_penilaian_table', 1),
(19, '2019_11_15_182017_create_komponen_penilaian_sidang_table', 1),
(20, '2020_01_24_143638_create_pergantian_nilai_akhir_sidang_table', 1),
(21, '2024_11_15_230054_create_jenis_mbkm_table', 1),
(22, '2024_11_16_175526_create_jenis_semesters_table', 1),
(23, '2024_11_16_195603_create_mata_kuliah_table', 1),
(24, '2024_11_17_113230_create_laporan_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(10) UNSIGNED NOT NULL,
  `nama_pengguna` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kota_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_pengguna` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak_lainnya` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_pengguna_role` int(10) UNSIGNED DEFAULT NULL,
  `id_jurusan` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_pengguna`, `kota_lahir`, `tanggal_lahir`, `password`, `kode_pengguna`, `photo_profile`, `nomor_hp`, `nomor_telepon`, `email`, `kontak_lainnya`, `id_pengguna_role`, `id_jurusan`) VALUES
(336, 'CHRISTOPHER RICHARD ALEXANDER', NULL, NULL, NULL, '2172008', NULL, NULL, NULL, NULL, NULL, 13, 2),
(359, 'TRISTAN VIVALDY', NULL, NULL, NULL, '2172032', NULL, NULL, NULL, NULL, NULL, 13, 2),
(243512, '243512', NULL, NULL, NULL, '243512', NULL, NULL, NULL, NULL, NULL, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna_role`
--

CREATE TABLE `pengguna_role` (
  `id_pengguna_role` int(10) UNSIGNED NOT NULL,
  `nama_pengguna_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pengguna_role` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna_role`
--

INSERT INTO `pengguna_role` (`id_pengguna_role`, `nama_pengguna_role`, `status_pengguna_role`, `created_at`, `updated_at`) VALUES
(1, 'Koordinator KP', 1, NULL, NULL),
(13, 'Mahasiswa', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pergantian_nilai_akhir_sidang`
--

CREATE TABLE `pergantian_nilai_akhir_sidang` (
  `id_pergantian_nilai_akhir_sidang` int(10) UNSIGNED NOT NULL,
  `nilai_awal` double(5,2) NOT NULL,
  `nilai_ubah` double(5,2) DEFAULT NULL,
  `status_pembimbing_pergantian_nilai_akhir_sidang` tinyint(4) NOT NULL DEFAULT 2,
  `status_penguji_pergantian_nilai_akhir_sidang` tinyint(4) NOT NULL DEFAULT 2,
  `status_penguji_dua_pergantian_nilai_akhir_sidang` tinyint(4) NOT NULL DEFAULT 2,
  `status_koordinator_kp_pergantian_nilai_akhir_sidang` tinyint(4) NOT NULL DEFAULT 2,
  `id_sidang` int(10) UNSIGNED NOT NULL,
  `id_koordinator_kp` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int(10) UNSIGNED NOT NULL,
  `nama_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_role` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `nama_role`, `status_role`) VALUES
(2, 'Koordinator KP', 1),
(4, 'Ketua KBK Multimedia dan Jaringan', 1),
(13, 'Mahasiswa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `id_semester` int(10) UNSIGNED NOT NULL,
  `nama_semester` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai_semester` date DEFAULT NULL,
  `tanggal_selesai_semester` date DEFAULT NULL,
  `status_semester` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`id_semester`, `nama_semester`, `tanggal_mulai_semester`, `tanggal_selesai_semester`, `status_semester`) VALUES
(1, 'Ganjil 24/25', '2024-11-30', '2024-11-30', 1),
(2, 'Genap 23/24', '2024-12-01', '2024-12-01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sidang`
--

CREATE TABLE `sidang` (
  `id_sidang` int(10) UNSIGNED NOT NULL,
  `ruangan_sidang` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_sidang` date DEFAULT NULL,
  `waktu_mulai_sidang` time DEFAULT NULL,
  `waktu_akhir_sidang` time DEFAULT NULL,
  `nilai_pembimbing` double(5,2) DEFAULT NULL,
  `nilai_pembimbing_lapangan` double(5,2) DEFAULT NULL,
  `nilai_penguji` double(5,2) DEFAULT NULL,
  `nilai_penguji_dua` double(5,2) DEFAULT NULL,
  `nilai_koor_kp` double(5,2) DEFAULT NULL,
  `text_revisi_pembimbing` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_revisi_pembimbing` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_revisi_penguji` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_revisi_penguji` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pembimbing_utama` tinyint(4) NOT NULL DEFAULT 2,
  `status_pembimbing_lapangan` tinyint(4) NOT NULL DEFAULT 2,
  `status_revisi_pembimbing` tinyint(4) NOT NULL DEFAULT 2,
  `status_revisi_penguji` tinyint(4) NOT NULL DEFAULT 2,
  `status_sidang` tinyint(4) NOT NULL DEFAULT 2,
  `id_penguji_sidang` int(10) UNSIGNED DEFAULT NULL,
  `id_penguji_sidang_dua` int(10) UNSIGNED DEFAULT NULL,
  `id_topik` int(10) UNSIGNED NOT NULL,
  `id_batch` int(10) UNSIGNED NOT NULL,
  `id_komponen_nilai` int(10) UNSIGNED NOT NULL,
  `id_jenis_sidang` int(10) UNSIGNED NOT NULL,
  `id_syarat_ketentuan_sidang_mahasiswa` int(10) UNSIGNED DEFAULT NULL,
  `id_syarat_ketentuan_sidang_dosen` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `syarat_ketentuan_sidang`
--

CREATE TABLE `syarat_ketentuan_sidang` (
  `id_syarat_ketentuan_sidang` int(10) UNSIGNED NOT NULL,
  `judul_syarat_ketentuan_sidang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi_syarat_ketentuan_sidang` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_syarat_ketentuan_sidang` tinyint(4) NOT NULL DEFAULT 1,
  `id_role` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `syarat_ketentuan_sidang`
--

INSERT INTO `syarat_ketentuan_sidang` (`id_syarat_ketentuan_sidang`, `judul_syarat_ketentuan_sidang`, `deskripsi_syarat_ketentuan_sidang`, `status_syarat_ketentuan_sidang`, `id_role`) VALUES
(3, 'aa', 'aa', 1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `topik`
--

CREATE TABLE `topik` (
  `id_topik` int(10) UNSIGNED NOT NULL,
  `judul_topik` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug_topik` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `latar_belakang` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rumusan_masalah` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tujuan_pembahasan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruang_lingkup` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sumber_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sistematika_penyajian` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `transkrip_nilai` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `dkbs` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pengajuan_topik` date NOT NULL,
  `form_persetujuan_instansi` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lampiran_gambar` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_persetujuan_dosen` tinyint(4) DEFAULT NULL,
  `status_topik` tinyint(4) NOT NULL,
  `id_batch` int(10) UNSIGNED NOT NULL,
  `id_kbk` int(10) UNSIGNED NOT NULL,
  `id_pengaju` int(10) UNSIGNED NOT NULL,
  `id_pembimbing` int(10) UNSIGNED DEFAULT NULL,
  `id_pembimbing_lapangan` int(10) UNSIGNED DEFAULT NULL,
  `id_penguji_sidang` int(10) UNSIGNED DEFAULT NULL,
  `id_penguji_sidang_dua` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topik`
--

INSERT INTO `topik` (`id_topik`, `judul_topik`, `slug_topik`, `latar_belakang`, `rumusan_masalah`, `tujuan_pembahasan`, `ruang_lingkup`, `sumber_data`, `sistematika_penyajian`, `transkrip_nilai`, `dkbs`, `tanggal_pengajuan_topik`, `form_persetujuan_instansi`, `lampiran_gambar`, `status_persetujuan_dosen`, `status_topik`, `id_batch`, `id_kbk`, `id_pengaju`, `id_pembimbing`, `id_pembimbing_lapangan`, `id_penguji_sidang`, `id_penguji_sidang_dua`) VALUES
(1, 'Coba topik', 'coba-topik', 'Latar', 'Rumusan', 'TUjuan', 'Ruang', 'SUmber', 'Sistematika', '1_2172008_transkrip_nilai.png', '1_2172008_dkbs.png', '2024-12-02', NULL, NULL, 1, 5, 1, 1, 2172008, 243512, 243512, 243512, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`id_batch`),
  ADD KEY `batch_id_jurusan_foreign` (`id_jurusan`),
  ADD KEY `batch_id_semester_foreign` (`id_semester`);

--
-- Indexes for table `bimbingan`
--
ALTER TABLE `bimbingan`
  ADD PRIMARY KEY (`id_bimbingan`),
  ADD KEY `bimbingan_id_topik_foreign` (`id_topik`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`),
  ADD KEY `jabatan_id_role_foreign` (`id_role`),
  ADD KEY `jabatan_id_kbk_foreign` (`id_kbk`);

--
-- Indexes for table `jabatan_pengguna`
--
ALTER TABLE `jabatan_pengguna`
  ADD PRIMARY KEY (`id_jabatan_pengguna`),
  ADD KEY `jabatan_pengguna_id_jabatan_foreign` (`id_jabatan`);

--
-- Indexes for table `jadwal_mahasiswa`
--
ALTER TABLE `jadwal_mahasiswa`
  ADD PRIMARY KEY (`id_jadwal_mahasiswa`),
  ADD KEY `jadwal_mahasiswa_id_topik_foreign` (`id_topik`),
  ADD KEY `jadwal_mahasiswa_id_batch_foreign` (`id_batch`);

--
-- Indexes for table `jenis_mbkm`
--
ALTER TABLE `jenis_mbkm`
  ADD PRIMARY KEY (`id_jenis_mbkm`);

--
-- Indexes for table `jenis_semester`
--
ALTER TABLE `jenis_semester`
  ADD PRIMARY KEY (`id_jenis_semester`);

--
-- Indexes for table `jenis_sidang`
--
ALTER TABLE `jenis_sidang`
  ADD PRIMARY KEY (`id_jenis_sidang`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `kbk`
--
ALTER TABLE `kbk`
  ADD PRIMARY KEY (`id_kbk`);

--
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id_komentar`),
  ADD KEY `komentar_id_topik_foreign` (`id_topik`);

--
-- Indexes for table `komponen_nilai`
--
ALTER TABLE `komponen_nilai`
  ADD PRIMARY KEY (`id_komponen_nilai`);

--
-- Indexes for table `komponen_penilaian`
--
ALTER TABLE `komponen_penilaian`
  ADD PRIMARY KEY (`id_komponen_penilaian`),
  ADD KEY `komponen_penilaian_id_role_foreign` (`id_role`);

--
-- Indexes for table `komponen_penilaian_sidang`
--
ALTER TABLE `komponen_penilaian_sidang`
  ADD PRIMARY KEY (`id_komponen_penilaian_sidang`),
  ADD KEY `komponen_penilaian_sidang_id_komponen_penilaian_foreign` (`id_komponen_penilaian`),
  ADD KEY `komponen_penilaian_sidang_id_sidang_foreign` (`id_sidang`);

--
-- Indexes for table `konversi_sks`
--
ALTER TABLE `konversi_sks`
  ADD PRIMARY KEY (`id_matakuliah_detail`),
  ADD KEY `konversi_sks_id_matakuliah_foreign` (`id_matakuliah`),
  ADD KEY `konversi_sks_id_pengguna_foreign` (`id_pengguna`),
  ADD KEY `id_semester` (`id_semester`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `laporan_id_pengguna_foreign` (`id_pengguna`),
  ADD KEY `laporan_id_semester_foreign` (`id_semester`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id_matakuliah`),
  ADD KEY `mata_kuliah_id_jenis_semester_foreign` (`id_jenis_semester`);

--
-- Indexes for table `mbkm`
--
ALTER TABLE `mbkm`
  ADD PRIMARY KEY (`id_mbkm`),
  ADD KEY `id_jenis_mbkm` (`id_jenis_mbkm`),
  ADD KEY `id_pengguna` (`id_pengguna`),
  ADD KEY `id_semester` (`id_semester`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD KEY `pengguna_id_pengguna_role_foreign` (`id_pengguna_role`),
  ADD KEY `pengguna_id_jurusan_foreign` (`id_jurusan`);

--
-- Indexes for table `pengguna_role`
--
ALTER TABLE `pengguna_role`
  ADD PRIMARY KEY (`id_pengguna_role`);

--
-- Indexes for table `pergantian_nilai_akhir_sidang`
--
ALTER TABLE `pergantian_nilai_akhir_sidang`
  ADD PRIMARY KEY (`id_pergantian_nilai_akhir_sidang`),
  ADD KEY `pergantian_nilai_akhir_sidang_id_sidang_foreign` (`id_sidang`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id_semester`);

--
-- Indexes for table `sidang`
--
ALTER TABLE `sidang`
  ADD PRIMARY KEY (`id_sidang`),
  ADD KEY `sidang_id_topik_foreign` (`id_topik`),
  ADD KEY `sidang_id_batch_foreign` (`id_batch`),
  ADD KEY `sidang_id_komponen_nilai_foreign` (`id_komponen_nilai`),
  ADD KEY `sidang_id_jenis_sidang_foreign` (`id_jenis_sidang`),
  ADD KEY `sidang_id_syarat_ketentuan_sidang_mahasiswa_foreign` (`id_syarat_ketentuan_sidang_mahasiswa`),
  ADD KEY `sidang_id_syarat_ketentuan_sidang_dosen_foreign` (`id_syarat_ketentuan_sidang_dosen`);

--
-- Indexes for table `syarat_ketentuan_sidang`
--
ALTER TABLE `syarat_ketentuan_sidang`
  ADD PRIMARY KEY (`id_syarat_ketentuan_sidang`),
  ADD KEY `syarat_ketentuan_sidang_id_role_foreign` (`id_role`);

--
-- Indexes for table `topik`
--
ALTER TABLE `topik`
  ADD PRIMARY KEY (`id_topik`),
  ADD KEY `topik_id_batch_foreign` (`id_batch`),
  ADD KEY `topik_id_kbk_foreign` (`id_kbk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batch`
--
ALTER TABLE `batch`
  MODIFY `id_batch` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bimbingan`
--
ALTER TABLE `bimbingan`
  MODIFY `id_bimbingan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jabatan_pengguna`
--
ALTER TABLE `jabatan_pengguna`
  MODIFY `id_jabatan_pengguna` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243513;

--
-- AUTO_INCREMENT for table `jadwal_mahasiswa`
--
ALTER TABLE `jadwal_mahasiswa`
  MODIFY `id_jadwal_mahasiswa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jenis_mbkm`
--
ALTER TABLE `jenis_mbkm`
  MODIFY `id_jenis_mbkm` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jenis_semester`
--
ALTER TABLE `jenis_semester`
  MODIFY `id_jenis_semester` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jenis_sidang`
--
ALTER TABLE `jenis_sidang`
  MODIFY `id_jenis_sidang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kbk`
--
ALTER TABLE `kbk`
  MODIFY `id_kbk` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id_komentar` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komponen_nilai`
--
ALTER TABLE `komponen_nilai`
  MODIFY `id_komponen_nilai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komponen_penilaian`
--
ALTER TABLE `komponen_penilaian`
  MODIFY `id_komponen_penilaian` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komponen_penilaian_sidang`
--
ALTER TABLE `komponen_penilaian_sidang`
  MODIFY `id_komponen_penilaian_sidang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konversi_sks`
--
ALTER TABLE `konversi_sks`
  MODIFY `id_matakuliah_detail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id_matakuliah` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `mbkm`
--
ALTER TABLE `mbkm`
  MODIFY `id_mbkm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pengguna_role`
--
ALTER TABLE `pengguna_role`
  MODIFY `id_pengguna_role` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pergantian_nilai_akhir_sidang`
--
ALTER TABLE `pergantian_nilai_akhir_sidang`
  MODIFY `id_pergantian_nilai_akhir_sidang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `id_semester` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sidang`
--
ALTER TABLE `sidang`
  MODIFY `id_sidang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `syarat_ketentuan_sidang`
--
ALTER TABLE `syarat_ketentuan_sidang`
  MODIFY `id_syarat_ketentuan_sidang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `topik`
--
ALTER TABLE `topik`
  MODIFY `id_topik` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batch`
--
ALTER TABLE `batch`
  ADD CONSTRAINT `batch_id_jurusan_foreign` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`),
  ADD CONSTRAINT `batch_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester` (`id_semester`);

--
-- Constraints for table `bimbingan`
--
ALTER TABLE `bimbingan`
  ADD CONSTRAINT `bimbingan_id_topik_foreign` FOREIGN KEY (`id_topik`) REFERENCES `topik` (`id_topik`);

--
-- Constraints for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD CONSTRAINT `jabatan_id_kbk_foreign` FOREIGN KEY (`id_kbk`) REFERENCES `kbk` (`id_kbk`),
  ADD CONSTRAINT `jabatan_id_role_foreign` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);

--
-- Constraints for table `jabatan_pengguna`
--
ALTER TABLE `jabatan_pengguna`
  ADD CONSTRAINT `jabatan_pengguna_id_jabatan_foreign` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`);

--
-- Constraints for table `jadwal_mahasiswa`
--
ALTER TABLE `jadwal_mahasiswa`
  ADD CONSTRAINT `jadwal_mahasiswa_id_batch_foreign` FOREIGN KEY (`id_batch`) REFERENCES `batch` (`id_batch`),
  ADD CONSTRAINT `jadwal_mahasiswa_id_topik_foreign` FOREIGN KEY (`id_topik`) REFERENCES `topik` (`id_topik`);

--
-- Constraints for table `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `komentar_id_topik_foreign` FOREIGN KEY (`id_topik`) REFERENCES `topik` (`id_topik`);

--
-- Constraints for table `komponen_penilaian`
--
ALTER TABLE `komponen_penilaian`
  ADD CONSTRAINT `komponen_penilaian_id_role_foreign` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);

--
-- Constraints for table `komponen_penilaian_sidang`
--
ALTER TABLE `komponen_penilaian_sidang`
  ADD CONSTRAINT `komponen_penilaian_sidang_id_komponen_penilaian_foreign` FOREIGN KEY (`id_komponen_penilaian`) REFERENCES `komponen_penilaian` (`id_komponen_penilaian`),
  ADD CONSTRAINT `komponen_penilaian_sidang_id_sidang_foreign` FOREIGN KEY (`id_sidang`) REFERENCES `sidang` (`id_sidang`);

--
-- Constraints for table `konversi_sks`
--
ALTER TABLE `konversi_sks`
  ADD CONSTRAINT `id_semester` FOREIGN KEY (`id_semester`) REFERENCES `semester` (`id_semester`),
  ADD CONSTRAINT `konversi_sks_id_matakuliah_foreign` FOREIGN KEY (`id_matakuliah`) REFERENCES `mata_kuliah` (`id_matakuliah`),
  ADD CONSTRAINT `konversi_sks_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`);

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`),
  ADD CONSTRAINT `laporan_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester` (`id_semester`);

--
-- Constraints for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_id_jenis_semester_foreign` FOREIGN KEY (`id_jenis_semester`) REFERENCES `jenis_semester` (`id_jenis_semester`);

--
-- Constraints for table `mbkm`
--
ALTER TABLE `mbkm`
  ADD CONSTRAINT `mbkm_ibfk_1` FOREIGN KEY (`id_jenis_mbkm`) REFERENCES `jenis_mbkm` (`id_jenis_mbkm`),
  ADD CONSTRAINT `mbkm_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`),
  ADD CONSTRAINT `mbkm_ibfk_3` FOREIGN KEY (`id_semester`) REFERENCES `semester` (`id_semester`);

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_id_jurusan_foreign` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`),
  ADD CONSTRAINT `pengguna_id_pengguna_role_foreign` FOREIGN KEY (`id_pengguna_role`) REFERENCES `pengguna_role` (`id_pengguna_role`);

--
-- Constraints for table `pergantian_nilai_akhir_sidang`
--
ALTER TABLE `pergantian_nilai_akhir_sidang`
  ADD CONSTRAINT `pergantian_nilai_akhir_sidang_id_sidang_foreign` FOREIGN KEY (`id_sidang`) REFERENCES `sidang` (`id_sidang`);

--
-- Constraints for table `sidang`
--
ALTER TABLE `sidang`
  ADD CONSTRAINT `sidang_id_batch_foreign` FOREIGN KEY (`id_batch`) REFERENCES `batch` (`id_batch`),
  ADD CONSTRAINT `sidang_id_jenis_sidang_foreign` FOREIGN KEY (`id_jenis_sidang`) REFERENCES `jenis_sidang` (`id_jenis_sidang`),
  ADD CONSTRAINT `sidang_id_komponen_nilai_foreign` FOREIGN KEY (`id_komponen_nilai`) REFERENCES `komponen_nilai` (`id_komponen_nilai`),
  ADD CONSTRAINT `sidang_id_syarat_ketentuan_sidang_dosen_foreign` FOREIGN KEY (`id_syarat_ketentuan_sidang_dosen`) REFERENCES `syarat_ketentuan_sidang` (`id_syarat_ketentuan_sidang`),
  ADD CONSTRAINT `sidang_id_syarat_ketentuan_sidang_mahasiswa_foreign` FOREIGN KEY (`id_syarat_ketentuan_sidang_mahasiswa`) REFERENCES `syarat_ketentuan_sidang` (`id_syarat_ketentuan_sidang`),
  ADD CONSTRAINT `sidang_id_topik_foreign` FOREIGN KEY (`id_topik`) REFERENCES `topik` (`id_topik`);

--
-- Constraints for table `syarat_ketentuan_sidang`
--
ALTER TABLE `syarat_ketentuan_sidang`
  ADD CONSTRAINT `syarat_ketentuan_sidang_id_role_foreign` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);

--
-- Constraints for table `topik`
--
ALTER TABLE `topik`
  ADD CONSTRAINT `topik_id_batch_foreign` FOREIGN KEY (`id_batch`) REFERENCES `batch` (`id_batch`),
  ADD CONSTRAINT `topik_id_kbk_foreign` FOREIGN KEY (`id_kbk`) REFERENCES `kbk` (`id_kbk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
