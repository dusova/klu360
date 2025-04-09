-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 09 Nis 2025, 23:07:25
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `kirklareli_virtual_tour`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `campuses`
--

CREATE TABLE `campuses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `map_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `campuses`
--

INSERT INTO `campuses` (`id`, `name`, `slug`, `description`, `map_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Teknik Bilimler MYO', 'teknik-bilimler', 'Teknik Bilimler Meslek Yüksekokulu Kampüsü', 'assets/images/maps/teknik-bilimler.png', 'active', '2025-04-07 19:18:40', '2025-04-07 19:18:40'),
(2, 'Kayalı Kampüsü', 'kayali', 'Kayalı Kampüsü', 'assets/images/campuses/kayali.jpg', 'inactive', '2025-04-07 19:18:40', '2025-04-07 19:18:40'),
(3, 'Lüleburgaz Kampüsü', 'luleburgaz', 'Lüleburgaz Kampüsü', 'assets/images/campuses/luleburgaz.jpg', 'inactive', '2025-04-07 19:18:40', '2025-04-07 19:18:40');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hotspots`
--

CREATE TABLE `hotspots` (
  `id` int(11) NOT NULL,
  `scene_id` int(11) NOT NULL,
  `type` enum('scene','info','link') NOT NULL DEFAULT 'scene',
  `text` varchar(255) NOT NULL,
  `pitch` decimal(8,4) NOT NULL,
  `yaw` decimal(8,4) NOT NULL,
  `target_scene_id` varchar(255) DEFAULT NULL,
  `target_pitch` decimal(8,4) DEFAULT NULL,
  `target_yaw` decimal(8,4) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `hotspots`
--

INSERT INTO `hotspots` (`id`, `scene_id`, `type`, `text`, `pitch`, `yaw`, `target_scene_id`, `target_pitch`, `target_yaw`, `created_at`, `updated_at`) VALUES
(9, 4, 'scene', 'Bahçeye Git', -4.0000, -30.0000, 'tbmyo-bahce', -4.0000, -30.0000, '2025-04-09 09:42:30', '2025-04-09 13:15:21'),
(10, 5, 'scene', 'Kantin Yolu', -4.0000, -22.0000, 'tbmyo-kantin-yolu', -4.0000, -30.0000, '2025-04-09 09:44:26', '2025-04-09 22:24:27'),
(11, 5, 'scene', 'B Blok Yoluna Git', -7.5000, 12.0000, 'tbmyo-b-blok-yolu', NULL, NULL, '2025-04-09 22:27:13', '2025-04-09 22:28:47'),
(13, 5, 'scene', 'A Blok Yoluna Git', -14.0000, -80.0000, 'tbmyo-a-blok-yolu', NULL, NULL, '2025-04-09 22:31:26', '2025-04-09 22:35:09'),
(15, 5, 'scene', 'Kampüs Girişine Git', -10.0000, -203.3000, 'tbmyo-kampus-girisi', NULL, NULL, '2025-04-09 22:38:39', '2025-04-09 22:42:32'),
(16, 21, 'scene', 'A Blok Önüne Git', -5.0000, -18.0000, 'tbmyo-a-blok-onu', NULL, NULL, '2025-04-09 22:44:53', '2025-04-09 22:48:04'),
(17, 21, 'scene', 'Kampüs Bahçesine Git', -15.0000, 74.6000, 'tbmyo-bahce', NULL, NULL, '2025-04-09 22:49:43', '2025-04-09 22:53:18'),
(18, 22, 'scene', 'A Blok Girişe Git', -5.0000, -15.0000, 'tbmyo-a-blok-giris', NULL, NULL, '2025-04-09 22:55:00', '2025-04-09 22:56:13'),
(19, 22, 'scene', 'A Blok Yoluna Git', -10.0000, -195.3000, 'tbmyo-a-blok-yolu', NULL, NULL, '2025-04-09 22:58:47', '2025-04-09 23:00:45'),
(20, 23, 'scene', 'A Blok Önüne Git', -18.0000, -187.0000, 'tbmyo-a-blok-onu', NULL, NULL, '2025-04-09 23:02:20', '2025-04-09 23:04:49'),
(23, 23, 'scene', 'A Blok Derslik 1&amp;#039;e Git', -12.0000, -277.0000, 'tbmyo-a-blok-derslik1', NULL, NULL, '2025-04-09 23:08:05', '2025-04-09 23:09:34'),
(24, 23, 'scene', 'A Blok Zemin Koridora Git', -12.0000, -95.0000, 'tbmyo-a-blok-zemin-koridor', NULL, NULL, '2025-04-09 23:11:11', '2025-04-09 23:26:54'),
(26, 23, 'scene', 'A Blok Bodrum Kata Git', -26.0000, -20.0000, 'tbmyo-a-blok-bodrum-kati', NULL, NULL, '2025-04-09 23:13:52', '2025-04-09 23:14:58'),
(29, 23, 'scene', 'A Blok 1. Kata Git', -15.0000, 0.0000, 'tbmyo-a-blok-kat1', NULL, NULL, '2025-04-09 23:17:09', '2025-04-09 23:17:39'),
(30, 28, 'scene', 'A Blok Girişe Git', -7.0000, 27.2500, 'tbmyo-a-blok-giris', NULL, NULL, '2025-04-09 23:20:25', '2025-04-09 23:25:29'),
(31, 17, 'scene', 'Kantin Girişine Git', -2.0000, -44.0000, 'tbmyo-kantin-giris', NULL, NULL, '2025-04-09 23:21:48', '2025-04-09 23:23:03'),
(32, 17, 'scene', 'Bahçeye Git', -7.0000, -178.0000, 'tbmyo-bahce', NULL, NULL, '2025-04-09 23:23:54', '2025-04-09 23:24:50'),
(33, 18, 'scene', 'Kantine Git', -12.0000, -6.0000, 'tbmyo-kantin', NULL, NULL, '2025-04-09 23:26:01', '2025-04-09 23:29:56'),
(34, 18, 'scene', 'Yemekhaneye Git', 0.0000, 60.0000, 'tbmyo-yemekhane', NULL, NULL, '2025-04-09 23:26:51', '2025-04-09 23:30:29'),
(35, 18, 'scene', 'Kantin Yoluna Git', -4.0000, -180.0000, 'tbmyo-kantin-yolu', NULL, NULL, '2025-04-09 23:27:29', '2025-04-09 23:27:29'),
(36, 24, 'scene', 'A Blok Girişe Git', -17.0000, 185.0000, 'tbmyo-a-blok-giris', NULL, NULL, '2025-04-09 23:28:13', '2025-04-09 23:29:25'),
(38, 24, 'scene', 'A Blok Okuma Salonuna Git', -4.0000, -5.0000, 'tbmyo-a-blok-okuma-salonu', NULL, NULL, '2025-04-09 23:30:57', '2025-04-09 23:31:57'),
(39, 19, 'scene', 'Kantin Girişine Git', -4.0000, 31.5000, 'tbmyo-kantin-giris', NULL, NULL, '2025-04-09 23:31:28', '2025-04-09 23:33:05'),
(41, 20, 'scene', 'Kantin Girişine Git', -32.0000, -24.0000, 'tbmyo-kantin-giris', NULL, NULL, '2025-04-09 23:33:47', '2025-04-09 23:35:20'),
(42, 25, 'scene', 'A Blok Zemin Koridora Git', -11.0000, -67.0000, 'tbmyo-a-blok-zemin-koridor', NULL, NULL, '2025-04-09 23:33:52', '2025-04-09 23:35:54'),
(46, 26, 'scene', 'A Blok Girişe Git', -7.0000, 185.0000, 'tbmyo-a-blok-giris', NULL, NULL, '2025-04-09 23:46:52', '2025-04-09 23:48:15'),
(47, 26, 'scene', 'A Blok Resim Atölyesine Git', -6.0000, 267.0000, 'tbmyo-a-blok-resim-atolyesi', NULL, NULL, '2025-04-09 23:52:01', '2025-04-09 23:53:38'),
(48, 27, 'scene', 'A Blok Bodrum Kata Git', -7.0000, -30.0000, 'tbmyo-a-blok-bodrum-kati', NULL, NULL, '2025-04-09 23:54:26', '2025-04-09 23:54:55'),
(49, 29, 'scene', 'A Blok Girişe Git', -22.0000, 170.0000, 'tbmyo-a-blok-giris', NULL, NULL, '2025-04-09 23:56:07', '2025-04-09 23:57:53'),
(50, 29, 'scene', 'A Blok 2. Kata Git', -9.0000, 190.0000, 'tbmyo-a-blok-kat2', NULL, NULL, '2025-04-10 00:01:03', '2025-04-10 00:01:45'),
(51, 30, 'scene', 'Öğrenci İşlerine Git', -4.0000, 78.0000, 'tbmyo-ogrenci-isleri', NULL, NULL, '2025-04-10 00:02:44', '2025-04-10 00:04:24'),
(52, 30, 'scene', 'A Blok 1. Kata Git', -25.0000, 173.0000, 'tbmyo-a-blok-kat1', NULL, NULL, '2025-04-10 00:05:21', '2025-04-10 00:06:04'),
(53, 31, 'scene', 'A Blok 2. Kata Git', -6.0000, -50.0000, 'tbmyo-a-blok-kat2', NULL, NULL, '2025-04-10 00:07:09', '2025-04-10 00:07:09');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `expiry_time` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `scenes`
--

CREATE TABLE `scenes` (
  `id` int(11) NOT NULL,
  `campus_id` int(11) NOT NULL,
  `scene_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) NOT NULL,
  `map_x` decimal(5,2) DEFAULT NULL,
  `map_y` decimal(5,2) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `scenes`
--

INSERT INTO `scenes` (`id`, `campus_id`, `scene_id`, `title`, `description`, `image_path`, `thumbnail_path`, `map_x`, `map_y`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(4, 1, 'tbmyo-kampus-girisi', 'Kampüs Girişi', 'Kırklareli Üniversitesi Teknik Bilimler Meslek Yüksekokulu kampüsümüzün girişidir. Burada güvenlik görevlileri tarafından yapılan kimlik kontrolünden sonra turnikeler aracılığıyla okula giriş sağlanmaktadır.', 'uploads/panoramas/67f58b9cd1614.jpg', 'uploads/thumbnails/67f58b9cd1614_thumb.jpg', 45.63, 50.45, 0, 'active', '2025-04-08 23:48:28', '2025-04-09 09:05:55'),
(5, 1, 'tbmyo-bahce', 'Kampüs Bahçesi', 'Teknik Bilimler MYO kampüsümüzdeki öğrencilerimiz sosyalleşebileceği bahçe.', 'uploads/panoramas/67f58c46a3805.jpg', 'uploads/thumbnails/67f58c46a3805_thumb.jpg', 54.81, 47.90, 0, 'active', '2025-04-08 23:51:18', '2025-04-09 09:06:08'),
(6, 1, 'tbmyo-b-blok-yolu', 'B Blok Yolu', 'Teknik Bilimler MYO kampüsümüzün B bloğuna giden yol.', 'uploads/panoramas/67f58dc1184b1.jpg', 'uploads/thumbnails/67f58dc1184b1_thumb.jpg', 57.15, 71.32, 0, 'active', '0000-00-00 00:00:00', '2025-04-08 23:57:37'),
(7, 1, 'tbmyo-b-blok-onu', 'B Blok Önü', 'Teknik Bilimler MYO kampüsümüzün B bloğunun önüdür.', 'uploads/panoramas/67f58e15c9ee7.jpg', 'uploads/thumbnails/67f58e15c9ee7_thumb.jpg', 63.33, 74.01, 0, 'active', '2025-04-08 23:59:01', '2025-04-08 23:59:01'),
(8, 1, 'tbmyo-b-blok-giris', 'B Blok Giriş', 'Teknik Bilimler MYO kampüsümüzün B bloğun girişidir.', 'uploads/panoramas/67f607ed37964.jpg', 'uploads/thumbnails/67f607ed37964_thumb.jpg', 65.66, 73.43, 0, 'active', '2025-04-09 08:38:53', '2025-04-09 08:41:48'),
(9, 1, 'tbmyo-b-blok-bodrum-kati', 'B Blok Bodrum Katı', 'Teknik Bilimler MYO kampüsümüzün B bloğunun bodrum katıdır.', 'uploads/panoramas/67f60867b0f86.jpg', 'uploads/thumbnails/67f60867b0f86_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 08:40:55', '2025-04-09 08:40:55'),
(10, 1, 'tbmyo-makine-bolumu-atolyesi', 'Makine Bölümü Atölyesi', 'Teknik Bilimler MYO kampüsümüzün B bloğunda bulunan makine bölümüne ait çalışma atölyesidir.', 'uploads/panoramas/67f6090908df2.jpg', 'uploads/thumbnails/67f6090908df2_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 08:43:37', '2025-04-09 08:43:37'),
(11, 1, 'tbmyo-ic-mekan-tasarim-atolyesi', 'İç Mekan Tasarım Atölyesi', 'Teknik Bilimler MYO kampüsümüzün B bloğunda bulunan iç mekan tasarım bölümünün çalışma atölyesidir.', 'uploads/panoramas/67f609606f92b.jpg', 'uploads/thumbnails/67f609606f92b_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 08:45:04', '2025-04-09 08:45:04'),
(12, 1, 'b-blok-derslik1', 'B Blok Derslik 1', 'Teknik Bilimler MYO kampüsümüzün B bloğunda bulunan dersliklerden biridir.', 'uploads/panoramas/67f60a235f738.jpg', 'uploads/thumbnails/67f60a235f738_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 08:48:19', '2025-04-09 08:48:19'),
(13, 1, 'b-blok-derslik2', 'B Blok Derslik 2', 'Teknik Bilimler MYO kampüsümüzün B bloğunda bulunan dersliklerden biridir.', 'uploads/panoramas/67f60a51caf3d.jpg', 'uploads/thumbnails/67f60a51caf3d_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 08:49:05', '2025-04-09 08:49:05'),
(14, 1, 'tmbyo-b-blok-kat2', 'B Blok 2. Kat', 'Teknik Bilimler MYO kampüsümüzün B bloğunun ikinci katıdır.', 'uploads/panoramas/67f60a8c009ee.jpg', 'uploads/thumbnails/67f60a8c009ee_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 08:50:04', '2025-04-09 08:50:04'),
(15, 1, 'tbmyo-b-blok-tomer', 'B Blok TÖMER', 'Teknik Bilimler MYO kampüsümüzün B bloğunda bulunan Türkçe Eğitim Merkezi(TÖMER)&#039;in bulunduğu kısımdır.', 'uploads/panoramas/67f60ad18c584.jpg', 'uploads/thumbnails/67f60ad18c584_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 08:51:13', '2025-04-09 08:51:13'),
(16, 1, 'tbmyo-b-blok-bilgisayar-laboratuvari', 'B Blok Bilgisayar Laboratuvarı', 'Teknik Bilimler MYO kampüsümüzün B bloğunda bulunan bilgisayar programcılığı bölümünün çalışma laboratuvarıdır.', 'uploads/panoramas/67f60ba075ac1.jpg', 'uploads/thumbnails/67f60ba075ac1_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 08:54:40', '2025-04-09 08:54:40'),
(17, 1, 'tbmyo-kantin-yolu', 'Kantin Yolu', 'Teknik Bilimler MYO kampüsümüzün kantine giden yoludur.', 'uploads/panoramas/67f60cb79de7b.jpg', 'uploads/thumbnails/67f60cb79de7b_thumb.jpg', 74.35, 50.63, 0, 'active', '2025-04-09 08:59:19', '2025-04-09 08:59:19'),
(18, 1, 'tbmyo-kantin-giris', 'Kantin Girişi', 'Teknik Bilimler MYO kampüsümüzün kantin girişidir. Zemin katta kantin, üst katta yemekhane bulunmaktadır.', 'uploads/panoramas/67f60d35d1b39.jpg', 'uploads/thumbnails/67f60d35d1b39_thumb.jpg', 77.18, 42.81, 0, 'active', '2025-04-09 09:01:25', '2025-04-09 09:01:25'),
(19, 1, 'tbmyo-kantin', 'Kantin', 'Teknik Bilimler MYO kampüsümüzün kantinidir.', 'uploads/panoramas/67f60d780e9e1.jpg', 'uploads/thumbnails/67f60d780e9e1_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:02:32', '2025-04-09 09:02:32'),
(20, 1, 'tbmyo-yemekhane', 'Yemekhane', 'Teknik Bilimler MYO kampüsümüzün yemekhanesidir. Turnike sistemi ile çalışmaktadır. Öğrencilerimize verilen kart ile yemek alımı gerçekleştirilir.', 'uploads/panoramas/67f60dca88da5.jpg', 'uploads/thumbnails/67f60dca88da5_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:03:54', '2025-04-09 09:03:54'),
(21, 1, 'tbmyo-a-blok-yolu', 'A Blok Yolu', 'Teknik Bilimler MYO kampüsümüzün A bloğuna giden yoldur.', 'uploads/panoramas/67f60e817f269.jpg', 'uploads/thumbnails/67f60e817f269_thumb.jpg', 56.82, 24.31, 0, 'active', '2025-04-09 09:06:57', '2025-04-09 09:06:57'),
(22, 1, 'tbmyo-a-blok-onu', 'A Blok Önü', 'Teknik Bilimler MYO kampüsümüzün A bloğunun önüdür.', 'uploads/panoramas/67f60ec7d4ec5.jpg', 'uploads/thumbnails/67f60ec7d4ec5_thumb.jpg', 67.17, 22.69, 0, 'active', '2025-04-09 09:08:07', '2025-04-09 09:08:07'),
(23, 1, 'tbmyo-a-blok-giris', 'A Blok Giriş', 'Teknik Bilimler MYO kampüsümüzün A bloğunun girişidir.', 'uploads/panoramas/67f60efd98145.jpg', 'uploads/thumbnails/67f60efd98145_thumb.jpg', 69.34, 24.97, 0, 'active', '2025-04-09 09:09:01', '2025-04-09 09:09:01'),
(24, 1, 'tbmyo-a-blok-zemin-koridor', 'A Blok Zemin Koridor', 'Teknik Bilimler MYO kampüsümüzün A bloğunun zemin kat koridorudur.', 'uploads/panoramas/67f60f3e06b23.jpg', 'uploads/thumbnails/67f60f3e06b23_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:10:06', '2025-04-09 09:10:06'),
(25, 1, 'tbmyo-a-blok-okuma-salonu', 'A Blok Okuma Salonu', 'Teknik Bilimler MYO kampüsümüzün A bloğunda bulunan okuma salonudur.', 'uploads/panoramas/67f60f6e53b20.jpg', 'uploads/thumbnails/67f60f6e53b20_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:10:54', '2025-04-09 09:10:54'),
(26, 1, 'tbmyo-a-blok-bodrum-kati', 'A Blok Bodrum Katı', 'Teknik Bilimler MYO kampüsümüzün A bloğunun bodrum katıdır.', 'uploads/panoramas/67f60fa015508.jpg', 'uploads/thumbnails/67f60fa015508_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:11:44', '2025-04-09 09:11:44'),
(27, 1, 'tbmyo-a-blok-resim-atolyesi', 'A Blok Resim Atölyesi', 'Teknik Bilimler MYO kampüsümüzün A bloğunun resim atölyesidir.', 'uploads/panoramas/67f60ff409c07.jpg', 'uploads/thumbnails/67f60ff409c07_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:13:08', '2025-04-09 09:13:08'),
(28, 1, 'tbmyo-a-blok-derslik1', 'A Blok Derslik 1', 'Teknik Bilimler MYO kampüsümüzün A bloğunda bulunan dersliklerden biridir.', 'uploads/panoramas/67f610327f4ae.jpg', 'uploads/thumbnails/67f610327f4ae_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:14:10', '2025-04-09 09:14:10'),
(29, 1, 'tbmyo-a-blok-kat1', 'A Blok 1. Kat', 'Teknik Bilimler MYO kampüsümüzün A bloğunun 1. katıdır.', 'uploads/panoramas/67f61058ca0c1.jpg', 'uploads/thumbnails/67f61058ca0c1_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:14:48', '2025-04-09 09:14:48'),
(30, 1, 'tbmyo-a-blok-kat2', 'A Blok 2. Kat', 'Teknik Bilimler MYO kampüsümüzün A bloğunun 2. katıdır.', 'uploads/panoramas/67f610832857e.jpg', 'uploads/thumbnails/67f610832857e_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:15:31', '2025-04-09 09:15:31'),
(31, 1, 'tbmyo-ogrenci-isleri', 'Öğrenci İşleri', 'Teknik Bilimler MYO kampüsümüzün A bloğunda bulunan öğrenci işleridir.', 'uploads/panoramas/67f610f15eae0.jpg', 'uploads/thumbnails/67f610f15eae0_thumb.jpg', NULL, NULL, 0, 'active', '2025-04-09 09:17:21', '2025-04-09 09:17:21');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'site_title', 'Kırklareli Üniversitesi 360° Sanal Tur', '2025-04-07 19:18:40', '2025-04-09 13:41:06'),
(2, 'welcome_text', '360° sanal kampüs turu ile üniversitemizi keşfedin. Tüm kampüslerimizi, fakültelerimizi ve tesislerimizi sanal olarak gezin ve yakından tanıyın.', '2025-04-07 19:18:40', '2025-04-09 13:41:06'),
(3, 'footer_text', 'Copyright © 2025 Kırklareli Üniversitesi. Tüm hakları saklıdır.', '2025-04-07 19:18:40', '2025-04-09 13:41:06'),
(4, 'analytics_enabled', '1', '2025-04-07 19:18:40', '2025-04-09 13:41:06'),
(5, 'default_campus', 'teknik-bilimler', '2025-04-07 19:18:40', '2025-04-09 13:41:06'),
(6, 'meta_description', '360° sanal kampüs turu ile üniversitemizi keşfedin. Tüm kampüslerimizi, fakültelerimizi ve tesislerimizi sanal olarak gezin ve yakından tanıyın.', '2025-04-09 13:35:45', '2025-04-09 13:41:06'),
(7, 'meta_keywords', 'sanal tur, 360 derece, kampüs, klü, kırklareli üniversitesi, klu, kırklareli, kirklareli, uni, kırklareli üni, kirklareli uni, 360, tc kırklareli üniversitesi, tc kirklareli universitesi', '2025-04-09 13:35:45', '2025-04-09 13:41:06'),
(8, 'google_analytics', 'UA-3SU2LJA3N45Z', '2025-04-09 13:35:45', '2025-04-09 13:41:06'),
(9, 'max_login_attempts', '5', '2025-04-09 13:35:45', '2025-04-09 13:41:06'),
(10, 'session_lifetime', '7200', '2025-04-09 13:35:45', '2025-04-09 13:41:06'),
(11, 'log_retention_days', '30', '2025-04-09 13:35:45', '2025-04-09 13:41:06'),
(12, 'visit_count_interval', '30', '2025-04-09 13:35:45', '2025-04-09 13:41:06'),
(13, 'maintenance_mode', '1', '2025-04-09 13:36:02', '2025-04-09 13:41:06'),
(14, 'login_required', '1', '2025-04-09 13:36:13', '2025-04-09 13:41:06'),
(15, 'creators', '[{\"name\":\"Mustafa Arda D\\u00fc\\u015fova\",\"title\":\"Proje Y\\u00f6neticisi, \\u00d6n-Arka Y\\u00fcz Geli\\u015ftirici\",\"photo\":\"1\"},{\"name\":\"Fatih \\u00c7oban\",\"title\":\"Panoramik Foto\\u011fraf\\u00e7\\u0131, UI\\/UX Tasar\\u0131mc\\u0131\",\"photo\":\"2\"},{\"name\":\"Eyl\\u00fcl Kay\",\"title\":\"Proje S\\u00f6zc\\u00fcs\\u00fc, Sosyal Medya Y\\u00f6netimi, G\\u00f6rsel Tasar\\u0131m\",\"photo\":\"3\"},{\"name\":\"Dr. \\u00d6\\u011fr. \\u00dcyesi Nadir Suba\\u015f\\u0131\",\"title\":\"Proje Dan\\u0131\\u015fman\\u0131\",\"photo\":\"4\"}]', '2025-04-09 13:39:25', '2025-04-09 13:41:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','user') NOT NULL DEFAULT 'user',
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(3, 'Mustafa Arda', 'info@mdusova.com', '$2y$10$0RcuCbAE5jrtYpL1ctRJ.eupWPuYx/UW21L6DTiINmt34DhrccRJK', 'admin', 'active', '2025-04-09 23:20:08', '2025-04-07 19:40:34', '2025-04-07 19:40:34'),
(4, 'Fatih Çoban', 'info@fatihcbn.com', '$2y$10$pb.7nSg.hZy41X6JG1pHOe6W3g5Pf16NK3uX0044Dw/KNtSN443y.', 'editor', 'active', '2025-04-09 22:15:46', '2025-04-09 13:32:39', '2025-04-09 13:32:39'),
(5, 'Eylül Kay', 'eylulkay039@gmail.com', '$2y$10$uQZNX9xT5sskUI9JBZzi4e9rI8pSvjU1xYbFb1FR8.oNvysaOUflO', 'editor', 'active', NULL, '2025-04-09 13:33:10', '2025-04-09 13:42:14');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_activities`
--

CREATE TABLE `user_activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `campus` varchar(255) DEFAULT NULL,
  `scene_id` varchar(255) DEFAULT NULL,
  `activity_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_type` enum('desktop','mobile','tablet','unknown') NOT NULL DEFAULT 'unknown',
  `browser` varchar(100) DEFAULT NULL,
  `operating_system` varchar(100) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `campus` varchar(255) NOT NULL,
  `scene_id` varchar(255) DEFAULT NULL,
  `visit_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `visits`
--

INSERT INTO `visits` (`id`, `ip_address`, `device_type`, `browser`, `operating_system`, `referrer`, `campus`, `scene_id`, `visit_time`) VALUES
(1, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-07 19:19:45'),
(2, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 19:19:46'),
(3, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 19:19:47'),
(4, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 19:19:47'),
(5, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 19:19:49'),
(6, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 19:19:52'),
(7, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 19:22:20'),
(8, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 19:22:29'),
(9, '127.0.0.1', 'desktop', 'Firefox', 'Windows', '', 'teknik-bilimler', NULL, '2025-04-07 19:22:37'),
(10, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-07 19:32:19'),
(11, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-07 20:04:17'),
(12, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-07 20:06:24'),
(13, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-07 20:08:20'),
(14, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-07 20:10:53'),
(15, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:11:23'),
(16, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:12:20'),
(17, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:12:42'),
(18, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 20:12:51'),
(19, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 20:17:51'),
(20, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-07 20:24:09'),
(21, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:34:12'),
(22, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:34:36'),
(23, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:35:09'),
(24, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:35:41'),
(25, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:36:23'),
(26, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:38:03'),
(27, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:39:26'),
(28, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:39:34'),
(29, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:42:26'),
(30, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:44:40'),
(31, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:45:39'),
(32, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-07 20:46:19'),
(33, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 01:17:19'),
(34, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 01:22:55'),
(35, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 01:41:30'),
(36, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 01:41:36'),
(37, '127.0.0.1', 'desktop', 'Firefox', 'Windows', '', 'teknik-bilimler', NULL, '2025-04-08 01:41:41'),
(38, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 01:42:40'),
(39, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'avlu-1744045668', '2025-04-08 01:44:38'),
(40, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-08 01:48:07'),
(41, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-08 01:48:41'),
(42, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-08 01:49:15'),
(43, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-08 01:49:32'),
(44, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 08:35:53'),
(45, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', '23456-1744045328', '2025-04-08 08:39:26'),
(46, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 08:47:58'),
(47, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', '23456-1744045328', '2025-04-08 08:48:51'),
(48, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 08:56:51'),
(49, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 09:07:22'),
(50, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'avlu-1744045668', '2025-04-08 09:30:31'),
(51, '172.20.10.1', 'mobile', 'Safari', 'Mac', 'http://172.20.10.2/', 'teknik-bilimler', NULL, '2025-04-08 10:01:56'),
(52, '172.20.10.1', 'mobile', 'Safari', 'Mac', 'http://172.20.10.2/', 'teknik-bilimler', NULL, '2025-04-08 10:02:42'),
(53, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 10:22:26'),
(54, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 10:32:05'),
(55, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 10:34:14'),
(56, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 10:36:01'),
(57, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 10:37:02'),
(58, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'avlu-1744045668', '2025-04-08 10:39:27'),
(59, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', '23456-1744045328', '2025-04-08 10:39:38'),
(60, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 10:40:47'),
(61, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 10:48:34'),
(62, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 10:50:06'),
(63, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 11:25:12'),
(64, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 12:54:41'),
(65, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-08 16:18:03'),
(66, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 23:42:35'),
(67, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 23:43:58'),
(68, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 23:44:39'),
(69, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 23:44:57'),
(70, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', '23456-1744045328', '2025-04-08 23:48:37'),
(71, '127.0.0.1', 'desktop', 'Firefox', 'Windows', '', 'teknik-bilimler', NULL, '2025-04-08 23:48:44'),
(72, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 23:49:07'),
(73, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 23:52:49'),
(74, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 23:53:32'),
(75, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 23:55:18'),
(76, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-yolu', 'teknik-bilimler', 'tbmyo-b-blok-yolu', '2025-04-08 23:55:22'),
(77, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-08 23:57:46'),
(78, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kampus-girisi', '2025-04-08 23:59:04'),
(79, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-b-blok-onu', '2025-04-09 00:08:53'),
(80, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 00:10:17'),
(81, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-b-blok-onu', '2025-04-09 00:21:26'),
(82, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-09 08:39:03'),
(83, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler', 'teknik-bilimler', 'tbmyo-b-blok-giris', '2025-04-09 08:39:06'),
(84, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-b-blok-giris', '2025-04-09 08:40:58'),
(85, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-giris', 'teknik-bilimler', 'tbmyo-b-blok-bodrum-kati', '2025-04-09 08:41:02'),
(86, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-b-blok-bodrum-kati', '2025-04-09 08:43:39'),
(87, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-makine-bolumu-atolyesi', '2025-04-09 08:43:43'),
(88, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-makine-bolumu-atolyesi', '2025-04-09 08:45:06'),
(89, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-makine-bolumu-atolyesi', 'teknik-bilimler', 'tbmyo-ic-mekan-tasarim-atolyesi', '2025-04-09 08:45:11'),
(90, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-ic-mekan-tasarim-atolyesi', 'teknik-bilimler', 'tbmyo-ic-mekan-tasarim-atolyesi', '2025-04-09 08:45:12'),
(91, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-ic-mekan-tasarim-atolyesi', 'teknik-bilimler', 'tbmyo-b-blok-bodrum-kati', '2025-04-09 08:45:19'),
(92, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-b-blok-giris', '2025-04-09 08:45:23'),
(93, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-giris', 'teknik-bilimler', 'tbmyo-ic-mekan-tasarim-atolyesi', '2025-04-09 08:45:35'),
(94, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-ic-mekan-tasarim-atolyesi', 'teknik-bilimler', 'tbmyo-makine-bolumu-atolyesi', '2025-04-09 08:45:39'),
(95, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-makine-bolumu-atolyesi', 'teknik-bilimler', 'tbmyo-ic-mekan-tasarim-atolyesi', '2025-04-09 08:46:03'),
(96, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-ic-mekan-tasarim-atolyesi', 'teknik-bilimler', 'tbmyo-ic-mekan-tasarim-atolyesi', '2025-04-09 08:46:30'),
(97, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-ic-mekan-tasarim-atolyesi', '2025-04-09 08:51:16'),
(98, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-ic-mekan-tasarim-atolyesi', 'teknik-bilimler', 'tbmyo-b-blok-tomer', '2025-04-09 08:51:23'),
(99, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-b-blok-tomer', '2025-04-09 08:54:43'),
(100, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-tomer', 'teknik-bilimler', 'tmbyo-b-blok-kat2', '2025-04-09 08:54:48'),
(101, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tmbyo-b-blok-kat2', 'teknik-bilimler', 'tbmyo-b-blok-bilgisayar-laboratuvari', '2025-04-09 08:54:54'),
(102, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-b-blok-bilgisayar-laboratuvari', '2025-04-09 08:59:22'),
(103, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-bilgisayar-laboratuvari', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 08:59:32'),
(104, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 08:59:41'),
(105, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-bahce', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 08:59:47'),
(106, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 09:01:31'),
(107, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-yolu', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 09:01:35'),
(108, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 09:02:33'),
(109, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-giris', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 09:02:38'),
(110, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 09:03:58'),
(111, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 09:04:03'),
(112, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 09:07:00'),
(113, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-yemekhane', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 09:07:05'),
(114, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 09:07:22'),
(115, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 09:09:03'),
(116, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-yolu', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 09:09:08'),
(117, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 09:11:46'),
(118, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 09:11:51'),
(119, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-zemin-koridor', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 09:11:57'),
(120, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-okuma-salonu', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 09:11:59'),
(121, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-okuma-salonu', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 09:12:02'),
(122, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-zemin-koridor', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 09:12:11'),
(123, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 09:12:11'),
(124, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 09:17:25'),
(125, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-09 09:18:26'),
(126, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 09:18:33'),
(127, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-bahce', 'teknik-bilimler', 'tbmyo-b-blok-yolu', '2025-04-09 09:18:35'),
(128, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-yolu', 'teknik-bilimler', 'tbmyo-b-blok-onu', '2025-04-09 09:18:38'),
(129, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-onu', 'teknik-bilimler', 'tbmyo-b-blok-giris', '2025-04-09 09:18:46'),
(130, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-giris', 'teknik-bilimler', 'tbmyo-b-blok-bodrum-kati', '2025-04-09 09:18:48'),
(131, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-makine-bolumu-atolyesi', '2025-04-09 09:18:50'),
(132, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-makine-bolumu-atolyesi', 'teknik-bilimler', 'tbmyo-ic-mekan-tasarim-atolyesi', '2025-04-09 09:18:59'),
(133, '127.0.0.1', 'desktop', 'Firefox', 'Windows', '', 'teknik-bilimler', NULL, '2025-04-09 09:42:35'),
(134, '127.0.0.1', 'desktop', 'Firefox', 'Windows', '', 'teknik-bilimler', NULL, '2025-04-09 09:43:04'),
(135, '127.0.0.1', 'desktop', 'Firefox', 'Windows', '', 'teknik-bilimler', NULL, '2025-04-09 09:43:25'),
(136, '127.0.0.1', 'desktop', 'Firefox', 'Windows', '', 'teknik-bilimler', NULL, '2025-04-09 09:43:36'),
(137, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 09:43:42'),
(138, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-09 10:10:39'),
(139, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 10:10:49'),
(140, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 10:11:10'),
(141, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-bahce', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 10:11:30'),
(142, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 10:11:43'),
(143, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-zemin-koridor', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 10:11:53'),
(144, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-09 13:15:33'),
(145, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 13:16:01'),
(146, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-bahce', 'teknik-bilimler', 'tbmyo-b-blok-yolu', '2025-04-09 13:16:06'),
(147, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-yolu', 'teknik-bilimler', 'tbmyo-b-blok-onu', '2025-04-09 13:16:08'),
(148, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-onu', 'teknik-bilimler', 'tbmyo-b-blok-giris', '2025-04-09 13:16:12'),
(149, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-giris', 'teknik-bilimler', 'tbmyo-b-blok-bodrum-kati', '2025-04-09 13:16:14'),
(150, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-makine-bolumu-atolyesi', '2025-04-09 13:16:16'),
(151, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-makine-bolumu-atolyesi', 'teknik-bilimler', 'tbmyo-ic-mekan-tasarim-atolyesi', '2025-04-09 13:16:19'),
(152, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-ic-mekan-tasarim-atolyesi', 'teknik-bilimler', 'b-blok-derslik1', '2025-04-09 13:16:21'),
(153, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=b-blok-derslik1', 'teknik-bilimler', 'b-blok-derslik2', '2025-04-09 13:16:24'),
(154, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=b-blok-derslik2', 'teknik-bilimler', 'tmbyo-b-blok-kat2', '2025-04-09 13:16:41'),
(155, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tmbyo-b-blok-kat2', 'teknik-bilimler', 'tbmyo-b-blok-tomer', '2025-04-09 13:16:48'),
(156, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-tomer', 'teknik-bilimler', 'tbmyo-b-blok-bilgisayar-laboratuvari', '2025-04-09 13:16:51'),
(157, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-bilgisayar-laboratuvari', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 13:16:55'),
(158, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-yolu', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 13:16:59'),
(159, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-giris', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 13:17:07'),
(160, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 13:17:12'),
(161, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-yemekhane', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 13:17:17'),
(162, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-yolu', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 13:17:20'),
(163, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-09 13:36:19'),
(164, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', NULL, '2025-04-09 13:45:21'),
(165, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 13:45:42'),
(166, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 13:45:49'),
(167, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-giris', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 13:45:56'),
(168, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-yolu', 'teknik-bilimler', 'tbmyo-b-blok-bilgisayar-laboratuvari', '2025-04-09 13:45:59'),
(169, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/', 'teknik-bilimler', 'tbmyo-b-blok-bilgisayar-laboratuvari', '2025-04-09 13:46:01'),
(170, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-b-blok-bilgisayar-laboratuvari', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 13:46:18'),
(171, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-yolu', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 13:46:21'),
(172, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-giris', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 13:46:22'),
(173, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 13:46:23'),
(174, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 13:46:24'),
(175, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', NULL, '2025-04-09 22:18:35'),
(176, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:22:48'),
(177, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-bahce', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:22:49'),
(178, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:24:31'),
(179, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:27:17'),
(180, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:28:50'),
(181, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:31:02'),
(182, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:31:29'),
(183, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:33:57'),
(184, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:34:21'),
(185, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:35:12'),
(186, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:38:09'),
(187, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:38:41'),
(188, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:40:47'),
(189, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:41:04'),
(190, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:41:19'),
(191, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:41:39'),
(192, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:42:00'),
(193, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:42:13'),
(194, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:42:34'),
(195, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-bahce', 'teknik-bilimler', 'tbmyo-kampus-girisi', '2025-04-09 22:42:47'),
(196, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-kampus-girisi', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:42:51'),
(197, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-bahce', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:42:54'),
(198, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:44:55'),
(199, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:45:41'),
(200, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:46:19'),
(201, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:46:58'),
(202, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:47:14'),
(203, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:47:21'),
(204, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:47:41'),
(205, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:48:06'),
(206, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:50:16'),
(207, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:50:48'),
(208, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:51:01'),
(209, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:51:14'),
(210, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:51:30'),
(211, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-yolu', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:51:47'),
(212, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-yolu', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:51:50'),
(213, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-yolu', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:51:52'),
(214, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-onu', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:51:57'),
(215, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:53:23'),
(216, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-yolu', 'teknik-bilimler', 'tbmyo-bahce', '2025-04-09 22:53:27'),
(217, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-bahce', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 22:53:32'),
(218, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-yolu', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:53:34'),
(219, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:55:03'),
(220, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:55:44'),
(221, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:56:00'),
(222, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:56:15'),
(223, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-onu', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 22:56:21'),
(224, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:56:26'),
(225, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:58:57'),
(226, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:58:58'),
(227, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:59:21'),
(228, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:59:38'),
(229, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 22:59:56'),
(230, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 23:00:07'),
(231, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 23:00:19'),
(232, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 23:00:30'),
(233, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 23:00:47'),
(234, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-onu', 'teknik-bilimler', 'tbmyo-a-blok-yolu', '2025-04-09 23:01:09'),
(235, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-yolu', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 23:01:12'),
(236, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-onu', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:01:13'),
(237, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:02:28'),
(238, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:03:04'),
(239, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:03:56'),
(240, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:04:18'),
(241, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:04:34'),
(242, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:04:51'),
(243, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-onu', '2025-04-09 23:05:05'),
(244, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-onu', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:05:08'),
(245, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:07:13'),
(246, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:07:34'),
(247, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:08:07'),
(248, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:08:41'),
(249, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:08:58'),
(250, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:09:12'),
(251, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:09:24'),
(252, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:09:35'),
(253, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:11:20'),
(254, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:11:42'),
(255, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:11:54'),
(256, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:12:05'),
(257, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:12:15'),
(258, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:12:25'),
(259, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:13:21'),
(260, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:13:53'),
(261, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:14:10'),
(262, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:14:27'),
(263, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:14:45'),
(264, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:14:59'),
(265, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:16:32'),
(266, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:16:59'),
(267, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:17:11'),
(268, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:17:31'),
(269, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:17:40'),
(270, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-09 23:17:47'),
(271, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-kat1', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:17:57'),
(272, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-derslik1', 'teknik-bilimler', 'tbmyo-a-blok-resim-atolyesi', '2025-04-09 23:18:00'),
(273, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-resim-atolyesi', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:18:01'),
(274, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:18:03'),
(275, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-okuma-salonu', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:18:04'),
(276, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-zemin-koridor', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:18:04'),
(277, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:18:09'),
(278, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-derslik1', 'teknik-bilimler', 'tbmyo-a-blok-resim-atolyesi', '2025-04-09 23:18:15'),
(279, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-resim-atolyesi', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:18:16'),
(280, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:18:17'),
(281, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-okuma-salonu', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:18:18'),
(282, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:18:29'),
(283, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-derslik1', 'teknik-bilimler', 'tbmyo-a-blok-resim-atolyesi', '2025-04-09 23:19:48'),
(284, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-resim-atolyesi', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:19:49'),
(285, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:19:50'),
(286, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-okuma-salonu', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:19:51'),
(287, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:19:54'),
(288, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-zemin-koridor', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:20:06'),
(289, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:20:06'),
(290, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:20:27'),
(291, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', NULL, '2025-04-09 23:21:50'),
(292, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 23:22:03'),
(293, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 23:22:29'),
(294, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 23:22:42'),
(295, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 23:22:55'),
(296, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 23:23:06'),
(297, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 23:23:56'),
(298, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:24:20'),
(299, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 23:24:34'),
(300, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:24:34'),
(301, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:24:44'),
(302, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 23:24:52'),
(303, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:24:52'),
(304, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-yolu', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:25:16'),
(305, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:25:27'),
(306, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-derslik1', '2025-04-09 23:25:30'),
(307, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-derslik1', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:25:40'),
(308, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:25:47'),
(309, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-okuma-salonu', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:25:54'),
(310, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-zemin-koridor', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:26:09'),
(311, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:26:11'),
(312, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:26:53'),
(313, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:27:02'),
(314, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-okuma-salonu', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:27:05'),
(315, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:27:07'),
(316, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:27:12'),
(317, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:27:30'),
(318, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:28:16'),
(319, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:28:59'),
(320, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:29:12');
INSERT INTO `visits` (`id`, `ip_address`, `device_type`, `browser`, `operating_system`, `referrer`, `campus`, `scene_id`, `visit_time`) VALUES
(321, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:29:14'),
(322, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:29:26'),
(323, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-zemin-koridor', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:29:33'),
(324, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:29:38'),
(325, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:29:43'),
(326, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:29:58'),
(327, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:30:17'),
(328, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:30:31'),
(329, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:30:32'),
(330, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:30:59'),
(331, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:31:20'),
(332, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-giris', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 23:31:34'),
(333, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:31:43'),
(334, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:31:59'),
(335, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 23:32:06'),
(336, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-zemin-koridor', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:32:11'),
(337, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 23:32:22'),
(338, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 23:32:34'),
(339, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 23:32:45'),
(340, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 23:32:57'),
(341, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-kantin', '2025-04-09 23:33:07'),
(342, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:33:11'),
(343, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-giris', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 23:33:15'),
(344, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:33:25'),
(345, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 23:33:49'),
(346, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:33:53'),
(347, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 23:34:15'),
(348, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:34:26'),
(349, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 23:34:36'),
(350, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 23:34:55'),
(351, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 23:35:10'),
(352, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 23:35:22'),
(353, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/index.php', 'teknik-bilimler', 'tbmyo-yemekhane', '2025-04-09 23:35:32'),
(354, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-yemekhane', 'teknik-bilimler', 'tbmyo-kantin-giris', '2025-04-09 23:35:39'),
(355, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:35:43'),
(356, '127.0.0.1', 'desktop', 'Firefox', 'Windows', 'http://localhost/tour.php?campus=teknik-bilimler&scene=tbmyo-kantin-giris', 'teknik-bilimler', 'tbmyo-kantin-yolu', '2025-04-09 23:35:47'),
(357, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-okuma-salonu', '2025-04-09 23:35:56'),
(358, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-okuma-salonu', 'teknik-bilimler', 'tbmyo-a-blok-zemin-koridor', '2025-04-09 23:36:16'),
(359, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-zemin-koridor', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:36:20'),
(360, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:36:49'),
(361, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:37:00'),
(362, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:37:51'),
(363, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:39:04'),
(364, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:39:28'),
(365, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:40:14'),
(366, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:41:00'),
(367, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:41:21'),
(368, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:42:43'),
(369, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:43:20'),
(370, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:43:35'),
(371, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:43:45'),
(372, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:43:58'),
(373, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:44:17'),
(374, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:44:24'),
(375, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:44:49'),
(376, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:45:11'),
(377, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:45:22'),
(378, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:45:32'),
(379, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:46:54'),
(380, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:47:30'),
(381, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:47:41'),
(382, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:47:54'),
(383, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:48:06'),
(384, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:48:16'),
(385, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-a-blok-resim-atolyesi', '2025-04-09 23:50:21'),
(386, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-resim-atolyesi', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:50:47'),
(387, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:52:14'),
(388, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:52:50'),
(389, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:53:09'),
(390, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:53:29'),
(391, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:53:40'),
(392, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-a-blok-resim-atolyesi', '2025-04-09 23:53:45'),
(393, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-resim-atolyesi', '2025-04-09 23:54:28'),
(394, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-resim-atolyesi', '2025-04-09 23:54:56'),
(395, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-resim-atolyesi', 'teknik-bilimler', 'tbmyo-a-blok-bodrum-kati', '2025-04-09 23:55:03'),
(396, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-bodrum-kati', 'teknik-bilimler', 'tbmyo-a-blok-giris', '2025-04-09 23:55:11'),
(397, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-giris', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-09 23:55:15'),
(398, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-09 23:56:09'),
(399, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-09 23:57:29'),
(400, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-09 23:57:45'),
(401, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-09 23:57:55'),
(402, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-10 00:01:05'),
(403, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-10 00:01:30'),
(404, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-10 00:01:46'),
(405, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-kat1', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:01:58'),
(406, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:02:46'),
(407, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:03:58'),
(408, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:04:09'),
(409, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:04:25'),
(410, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-kat2', 'teknik-bilimler', 'tbmyo-ogrenci-isleri', '2025-04-10 00:04:32'),
(411, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-ogrenci-isleri', 'teknik-bilimler', 'tbmyo-a-blok-kat1', '2025-04-10 00:04:37'),
(412, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-kat1', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:04:40'),
(413, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-kat2', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:05:25'),
(414, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:05:26'),
(415, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:05:53'),
(416, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-a-blok-kat2', '2025-04-10 00:06:06'),
(417, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/tour.php?campus=teknik-bilimler&scene=tbmyo-a-blok-kat2', 'teknik-bilimler', 'tbmyo-ogrenci-isleri', '2025-04-10 00:06:15'),
(418, '172.20.10.3', 'desktop', 'Chrome', 'Windows', 'http://172.20.10.7/', 'teknik-bilimler', 'tbmyo-ogrenci-isleri', '2025-04-10 00:07:17');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `campuses`
--
ALTER TABLE `campuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Tablo için indeksler `hotspots`
--
ALTER TABLE `hotspots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scene_id` (`scene_id`);

--
-- Tablo için indeksler `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `token` (`token`);

--
-- Tablo için indeksler `scenes`
--
ALTER TABLE `scenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `scene_id` (`scene_id`),
  ADD KEY `campus_id` (`campus_id`);

--
-- Tablo için indeksler `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `campus` (`campus`),
  ADD KEY `scene_id` (`scene_id`);

--
-- Tablo için indeksler `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campus` (`campus`),
  ADD KEY `scene_id` (`scene_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `campuses`
--
ALTER TABLE `campuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `hotspots`
--
ALTER TABLE `hotspots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Tablo için AUTO_INCREMENT değeri `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `scenes`
--
ALTER TABLE `scenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Tablo için AUTO_INCREMENT değeri `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=419;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `hotspots`
--
ALTER TABLE `hotspots`
  ADD CONSTRAINT `hotspots_ibfk_1` FOREIGN KEY (`scene_id`) REFERENCES `scenes` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `scenes`
--
ALTER TABLE `scenes`
  ADD CONSTRAINT `scenes_ibfk_1` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `user_activities`
--
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
