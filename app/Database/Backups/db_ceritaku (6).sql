-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 08, 2026 at 07:02 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ceritaku`
--

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `id` bigint NOT NULL,
  `story_id` bigint NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `chapter_number` int NOT NULL,
  `content` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `is_premium` tinyint(1) DEFAULT '0',
  `status` enum('DRAFT','PUBLISHED','ARCHIVED') COLLATE utf8mb4_general_ci DEFAULT 'DRAFT',
  `view_count` bigint DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`id`, `story_id`, `title`, `chapter_number`, `content`, `is_premium`, `status`, `view_count`, `created_at`, `updated_at`) VALUES
(1, 1, 'Awal Perlawanan', 1, 'Pagi itu datang dengan langit yang tampak biasa saja, seolah tidak ada apa-apa yang akan berubah. Namun bagi Biru dan kawan-kawannya, hari itu menjadi awal dari sesuatu yang jauh lebih besar daripada sekadar diskusi kampus. Mereka berkumpul di sebuah rumah kontrakan sempit di sudut kota, dengan dinding kusam dan kursi plastik yang jumlahnya tidak pernah cukup. Meski demikian, ruangan itu selalu dipenuhi semangat, tawa kecil, dan keyakinan bahwa suara mereka berarti.\r\n\r\nBiru duduk di pojok ruangan sambil mencatat, sesekali mengangkat kepala untuk mendengarkan pendapat teman-temannya. Di seberangnya, Alex berbicara dengan nada berapi-api tentang ketidakadilan yang mereka saksikan setiap hari. Di luar sana, berita tentang penangkapan aktivis mulai terdengar, namun tidak satu pun dari mereka berniat mundur. Ketakutan memang ada, tetapi keyakinan mereka jauh lebih besar.\r\n\r\nDiskusi pagi itu membahas rencana kecil: membagikan selebaran, mengadakan diskusi terbuka, dan menyuarakan pendapat di ruang-ruang yang masih memungkinkan. Tidak ada yang menyangka bahwa langkah-langkah kecil tersebut akan membawa konsekuensi besar. Bagi mereka, ini bukan soal keberanian semata, melainkan soal tanggung jawab sebagai manusia yang tidak ingin diam ketika ketidakadilan terjadi di depan mata.\r\n\r\nSaat matahari mulai meninggi, satu per satu mereka meninggalkan rumah kontrakan itu dengan perasaan campur aduk. Ada harapan, ada ketakutan, dan ada perasaan tidak terucapkan yang menggantung di udara. Biru berjalan menyusuri jalanan kota, memperhatikan wajah-wajah orang yang lalu lalang. Ia bertanya-tanya, berapa banyak dari mereka yang juga memendam keresahan yang sama.\r\n\r\nHari-hari berikutnya berjalan cepat. Diskusi demi diskusi, pertemuan demi pertemuan, semuanya terasa seperti potongan puzzle yang perlahan membentuk gambaran besar. Namun seiring dengan itu, bayang-bayang ancaman semakin nyata. Telepon-telepon misterius mulai berdatangan, orang-orang asing terlihat mondar-mandir di sekitar kampus, dan suasana berubah menjadi tegang.\r\n\r\nMeski demikian, persahabatan mereka justru semakin kuat. Di sela-sela diskusi serius, mereka masih bisa tertawa, berbagi makanan sederhana, dan bercerita tentang mimpi-mimpi pribadi. Ada yang ingin menjadi penulis, ada yang bercita-cita menjadi dosen, dan ada pula yang hanya ingin hidup tenang bersama keluarga. Semua mimpi itu terasa begitu dekat, namun juga rapuh.\r\n\r\nBiru menyadari bahwa apa yang mereka lakukan bukanlah perjuangan yang heroik seperti dalam buku-buku sejarah. Ini adalah perjuangan sunyi, penuh keraguan, dan sering kali terasa sia-sia. Namun justru di sanalah letak kekuatannya. Mereka tidak mencari pengakuan, hanya ingin tetap jujur pada suara hati.\r\n\r\nKetika malam tiba, Biru menuliskan semua yang ia rasakan dalam buku catatannya. Ia tahu, suatu hari nanti, mungkin tidak semua cerita ini akan sampai ke permukaan. Namun setidaknya, kebenaran pernah hidup dalam catatan kecil itu. Dan bagi Biru, itu sudah cukup untuk membuatnya bertahan, melangkah, dan percaya bahwa perubahan selalu dimulai dari keberanian untuk tidak diam.', 0, 'PUBLISHED', 162, '2026-01-28 13:05:50', '2026-02-02 07:03:40'),
(2, 1, 'Jejak yang Menghilang', 2, 'Hari-hari setelah pertemuan itu terasa berbeda. Kota yang sebelumnya akrab kini seolah menyimpan banyak rahasia. Biru mulai menyadari perubahan-perubahan kecil yang awalnya ia anggap sepele. Tatapan orang-orang asing di sudut jalan, kendaraan yang melambat terlalu lama di depan rumah kontrakan, hingga percakapan yang tiba-tiba terhenti ketika seseorang yang tidak dikenal mendekat. Semua terasa janggal, namun tidak ada yang benar-benar bisa mereka buktikan.\r\n\r\nPagi itu, Biru terbangun dengan perasaan tidak enak. Ia menatap langit-langit kamar yang dipenuhi retakan halus, mencoba menenangkan pikirannya. Ponselnya bergetar beberapa kali, namun tidak ada pesan masuk. Biasanya, Alex atau salah satu dari mereka sudah mengirim kabar, sekadar menanyakan rencana hari ini atau mengeluhkan kopi yang terlalu pahit. Keheningan itu terasa aneh.\r\n\r\nDi kampus, suasana jauh lebih tegang dari biasanya. Diskusi-diskusi kecil masih berlangsung, tetapi dengan suara yang lebih pelan dan wajah-wajah yang lebih waspada. Biru mencari Alex di tempat biasa mereka berkumpul, namun bangku itu kosong. Ia mencoba menghubungi nomor Alex, sekali, dua kali, hingga panggilan itu berakhir tanpa jawaban. Ada perasaan tidak nyaman yang perlahan tumbuh di dadanya.\r\n\r\nSiang menjelang, dan kabar itu akhirnya datang, bukan dari Alex, melainkan dari seorang teman yang berlari tergesa-gesa menghampiri Biru. Nafasnya terengah, wajahnya pucat. Ia hanya berkata singkat, “Alex tidak bisa dihubungi sejak tadi malam.” Kalimat itu sederhana, tetapi dampaknya begitu besar. Biru merasakan jantungnya berdetak lebih cepat, pikirannya dipenuhi berbagai kemungkinan yang berusaha ia tepis.\r\n\r\nMereka berkumpul kembali di rumah kontrakan sore itu. Tidak ada tawa, tidak ada candaan. Hanya keheningan dan wajah-wajah yang dipenuhi kecemasan. Satu per satu mereka mencoba mengingat kapan terakhir kali bertemu Alex, apa yang ia katakan, apakah ada tanda-tanda yang terlewatkan. Namun semakin mereka mengingat, semakin terasa bahwa kehilangan itu datang begitu tiba-tiba.\r\n\r\nMalam turun dengan cepat. Lampu rumah kontrakan redup, menyinari wajah-wajah lelah yang masih bertahan di ruang sempit itu. Biru duduk memandangi catatan-catatan lama, mencoba mencari jawaban di antara tulisan tangan yang kini terasa seperti potongan kenangan. Ia sadar, apa yang mereka hadapi bukan lagi sekadar ancaman abstrak. Ini nyata, dekat, dan menyakitkan.\r\n\r\nHari-hari berikutnya berjalan dalam ketidakpastian. Tidak ada kabar tentang Alex. Telepon tetap sunyi, pesan tidak pernah terkirim. Biru mulai merasakan rasa bersalah yang perlahan menggerogoti pikirannya. Ia bertanya-tanya, apakah semua ini sepadan? Apakah suara yang mereka suarakan pantas dibayar dengan hilangnya seseorang yang mereka sayangi?\r\n\r\nDi tengah kegelisahan itu, Biru menemukan kekuatan dari hal-hal kecil. Dari secangkir kopi yang dibagi bersama, dari pelukan singkat tanpa kata, dari keberanian untuk tetap saling menguatkan meski hati dipenuhi ketakutan. Ia menyadari bahwa perjuangan ini tidak pernah menjanjikan keselamatan, tetapi selalu menuntut kejujuran pada diri sendiri.\r\n\r\nSuatu malam, Biru menulis lebih lama dari biasanya. Ia menuliskan tentang Alex, tentang tawa dan kemarahannya, tentang mimpi-mimpi sederhana yang kini terasa begitu jauh. Tulisan itu bukan sekadar catatan, melainkan bentuk perlawanan kecil agar nama dan cerita Alex tidak hilang begitu saja. Ia percaya, selama cerita itu masih dituliskan, kehilangan tidak akan sepenuhnya menghapus keberadaan seseorang.\r\n\r\nJejak-jejak memang bisa dihapus, suara bisa dibungkam, tetapi ingatan selalu menemukan jalannya sendiri. Biru menutup buku catatannya dengan perasaan campur aduk. Di luar, malam terasa dingin, namun di dalam dirinya tumbuh keyakinan baru. Mereka mungkin kehilangan satu orang, tetapi semangat untuk terus bersuara tidak akan pernah benar-benar hilang.', 1, 'PUBLISHED', 107, '2026-01-28 13:06:09', '2026-02-05 18:04:15');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint NOT NULL,
  `chapter_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `chapter_id`, `user_id`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Cara ceritanya dibuka pelan tapi menghantam. Rasanya seperti diajak masuk ke dunia yang penuh harapan tapi juga ancaman.', '2026-01-28 13:08:59', '2026-01-28 13:08:59'),
(2, 1, 2, 'Aku suka detail-detail kecilnya. Obrolan sederhana tapi maknanya dalam, bikin penasaran sama kelanjutannya.', '2026-01-28 13:08:59', '2026-01-28 13:08:59'),
(3, 2, 1, 'Chapter ini jujur bikin sesak. Kehilangan yang datang tanpa penjelasan terasa sangat nyata.', '2026-01-28 13:08:59', '2026-01-28 13:08:59'),
(4, 2, 5, 'Bagian ini bikin aku berhenti baca sebentar. Emosinya kuat banget, terutama saat suasana mulai sunyi dan tegang.', '2026-01-28 13:08:59', '2026-01-28 13:08:59');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint NOT NULL,
  `story_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `rating` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `story_id`, `user_id`, `rating`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(2, 1, 2, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(3, 1, 5, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(4, 2, 1, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(5, 2, 2, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(6, 2, 5, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(7, 3, 1, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(8, 3, 2, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(9, 3, 5, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(10, 4, 1, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(11, 4, 2, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(12, 4, 5, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(13, 5, 1, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(14, 5, 2, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(15, 5, 5, 3, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(16, 6, 1, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(17, 6, 2, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(18, 6, 5, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(22, 8, 1, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(23, 8, 2, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(24, 8, 5, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(25, 9, 1, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(26, 9, 2, 4, '2026-01-28 13:11:03', '2026-01-28 13:11:03'),
(27, 9, 5, 5, '2026-01-28 13:11:03', '2026-01-28 13:11:03');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint NOT NULL,
  `story_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `review` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `story_id`, `user_id`, `review`, `is_featured`, `created_at`, `updated_at`) VALUES
(31, 1, 1, 'Novel ini bukan sekadar cerita, tapi pengalaman emosional. Setiap halaman membuat saya ikut merasakan harapan, ketakutan, dan kehilangan para tokohnya. Sangat kuat dan membekas.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(32, 1, 2, 'Cerita yang membuka mata tentang sisi gelap sejarah, disampaikan dengan bahasa yang indah dan menyayat. Setelah selesai membaca, rasanya sulit untuk langsung move on.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(33, 1, 5, 'Laut Bercerita berhasil membuat saya terdiam lama setelah tamat. Kisah persahabatan dan perjuangannya terasa nyata dan penuh makna.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(34, 2, 1, 'Ringan, lucu, dan romantis. Dialog Dilan benar-benar ikonik dan bikin senyum-senyum sendiri saat membaca.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(35, 2, 2, 'Cerita cinta remaja yang sederhana tapi jujur. Membaca novel ini seperti mengulang kenangan masa SMA.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(36, 2, 5, 'Dilan 1990 cocok dibaca kapan saja. Alurnya santai, karakternya unik, dan romancenya terasa hangat.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(37, 3, 1, 'Novel ini terasa sangat relate. Tentang luka, bertahan, dan belajar menerima diri sendiri ketika cinta tidak berjalan seperti harapan.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(38, 3, 2, 'Cerita yang emosional dan menyentuh hati. Banyak bagian yang membuat saya merenung tentang hubungan dan perasaan.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(39, 3, 5, 'Bahasanya lembut namun dalam. Cocok untuk pembaca yang suka cerita romance dengan konflik batin yang kuat.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(40, 4, 1, 'Cerita yang sederhana tapi sangat dalam. Banyak kalimat yang membuat saya berhenti sejenak untuk berpikir tentang hidup.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(41, 4, 2, 'Novel ini unik dan reflektif. Dari hal sederhana seperti semangkuk mie ayam, ceritanya berkembang menjadi renungan kehidupan.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(42, 4, 5, 'Bacaan yang tenang namun menusuk. Cocok dibaca saat ingin menyendiri dan merenung.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(43, 5, 1, 'Sudut pandang Dilan membuat cerita ini terasa lebih emosional dan dewasa. Banyak perasaan yang akhirnya terjawab.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(44, 5, 2, 'Milea memberikan sudut pandang yang berbeda dari kisah sebelumnya. Lebih serius dan penuh konflik perasaan.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09'),
(45, 5, 5, 'Sebagai penutup kisah Dilan dan Milea, novel ini terasa pas dan menyentuh.', 0, '2026-01-27 16:53:09', '2026-01-27 16:53:09');

-- --------------------------------------------------------

--
-- Table structure for table `review_likes`
--

CREATE TABLE `review_likes` (
  `id` bigint NOT NULL,
  `review_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `review_likes`
--

INSERT INTO `review_likes` (`id`, `review_id`, `user_id`, `created_at`) VALUES
(1, 31, 2, '2026-01-28 15:25:00'),
(2, 31, 3, '2026-01-28 15:25:00'),
(3, 31, 4, '2026-01-28 15:25:00'),
(4, 34, 2, '2026-01-28 15:26:50'),
(5, 34, 3, '2026-01-28 15:26:50'),
(6, 34, 5, '2026-01-28 15:26:50'),
(7, 35, 1, '2026-01-28 15:26:50'),
(8, 35, 3, '2026-01-28 15:26:50'),
(9, 35, 5, '2026-01-28 15:26:50'),
(10, 36, 1, '2026-01-28 15:26:50'),
(11, 36, 2, '2026-01-28 15:26:50'),
(12, 36, 3, '2026-01-28 15:26:50'),
(13, 37, 2, '2026-01-28 15:26:50'),
(14, 37, 4, '2026-01-28 15:26:50'),
(15, 37, 5, '2026-01-28 15:26:50'),
(16, 38, 1, '2026-01-28 15:26:50'),
(17, 38, 4, '2026-01-28 15:26:50'),
(18, 38, 5, '2026-01-28 15:26:50'),
(19, 39, 1, '2026-01-28 15:26:50'),
(20, 39, 2, '2026-01-28 15:26:50'),
(21, 39, 4, '2026-01-28 15:26:50'),
(22, 40, 2, '2026-01-28 15:26:50'),
(23, 40, 3, '2026-01-28 15:26:50'),
(24, 40, 5, '2026-01-28 15:26:50'),
(25, 41, 1, '2026-01-28 15:26:50'),
(26, 41, 3, '2026-01-28 15:26:50'),
(27, 41, 5, '2026-01-28 15:26:50'),
(28, 42, 1, '2026-01-28 15:26:50'),
(29, 42, 2, '2026-01-28 15:26:50'),
(30, 42, 3, '2026-01-28 15:26:50'),
(31, 43, 2, '2026-01-28 15:26:50'),
(32, 43, 4, '2026-01-28 15:26:50'),
(33, 43, 5, '2026-01-28 15:26:50'),
(34, 44, 1, '2026-01-28 15:26:50'),
(35, 44, 4, '2026-01-28 15:26:50'),
(36, 44, 5, '2026-01-28 15:26:50'),
(37, 45, 1, '2026-01-28 15:26:50'),
(38, 45, 2, '2026-01-28 15:26:50'),
(39, 45, 4, '2026-01-28 15:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `id` bigint NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `author_id` bigint NOT NULL,
  `genres` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('DRAFT','PENDING_REVIEW','PUBLISHED','ARCHIVED') COLLATE utf8mb4_general_ci DEFAULT 'DRAFT',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`id`, `title`, `author_id`, `genres`, `description`, `cover_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Laut Bercerita', 6, 'Drama Sejarah', 'Sebuah kisah tentang sekelompok mahasiswa yang berani bermimpi di tengah represi dan ketakutan. Di balik persahabatan yang hangat, tersimpan perjuangan sunyi, pengorbanan, dan luka mendalam akibat sejarah kelam yang tak pernah benar-benar selesai.', 'covers/laut-bercerita.jpg', 'PUBLISHED', '2026-01-27 16:48:57', '2026-01-27 16:48:57'),
(2, 'Dilan 1990', 7, 'Romance', 'Bandung tahun 1990 menjadi saksi kisah cinta remaja yang sederhana namun membekas. Dilan, dengan caranya yang unik dan tak terduga, menghadirkan pengalaman jatuh cinta pertama yang manis, lucu, dan penuh kenangan bagi Milea.', 'covers/dilan-1990.jpg', 'PUBLISHED', '2026-01-27 16:48:57', '2026-01-27 16:48:57'),
(3, 'Love Me When It Hurts', 8, 'Romance', 'Ketika cinta tak selalu datang dalam bentuk bahagia, luka menjadi bagian dari perjalanan. Novel ini mengisahkan hubungan yang rapuh, pergulatan emosi, dan keberanian untuk bertahan serta mencintai diri sendiri di saat segalanya terasa menyakitkan.', 'covers/love-me-when-it-hurts.jpg', 'PUBLISHED', '2026-01-27 16:48:57', '2026-01-27 16:48:57'),
(4, 'Seporsi Mie Ayam Sebelum Mati', 9, 'Slice of Life', 'Di tengah kelelahan hidup dan pikiran tentang kematian, sebuah seporsi mie ayam menjadi titik balik yang tak terduga. Cerita ini mengajak pembaca merenungkan arti hidup, kehilangan, dan kebahagiaan kecil yang sering terabaikan.', 'covers/seporsi-mie-ayam-sebelum-mati.jpg', 'PUBLISHED', '2026-01-27 16:48:57', '2026-01-28 14:54:58'),
(5, 'Milea', 7, 'Romance', 'Kisah cinta Dilan dan Milea berlanjut, kali ini diceritakan dari sudut pandang Dilan. Cerita ini mengungkap perasaan, pilihan, dan konsekuensi yang menyertai cinta remaja ketika kenyataan mulai berbenturan dengan harapan.', 'covers/milea.jpg', 'PUBLISHED', '2026-01-27 16:48:57', '2026-01-27 16:48:57'),
(6, 'Hujan', 10, 'Drama', 'Di tengah dunia masa depan yang dipenuhi teknologi dan bencana, Lail berjuang menyimpan kenangan tentang cinta, kehilangan, dan harapan. Sebuah kisah menyentuh tentang pilihan hidup dan perasaan yang tak pernah benar-benar hilang.', 'covers/hujan.jpg', 'PUBLISHED', '2026-01-27 16:49:56', '2026-01-27 16:49:56'),
(8, 'Rindu', 10, 'Religi', 'Sebuah perjalanan panjang menuju Tanah Suci mempertemukan berbagai latar belakang manusia dengan cerita hidup masing-masing. Rindu adalah kisah tentang pencarian makna, keikhlasan, dan rindu yang mendalam kepada Tuhan.', 'covers/rindu.jpg', 'PUBLISHED', '2026-01-27 16:49:56', '2026-01-27 16:49:56'),
(9, 'Daun yang Jatuh Tak Pernah Membenci Angin', 10, 'Romance', 'Kisah cinta yang sederhana namun penuh ketulusan, tentang menerima kenyataan dan mencintai tanpa harus memiliki. Novel ini menyentuh sisi paling lembut dari perasaan manusia.', 'covers/daun-yang-jatuh-tak-pernah-membenci-angin.jpg', 'PUBLISHED', '2026-01-27 16:49:56', '2026-01-28 14:59:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('USER','ADMIN') COLLATE utf8mb4_general_ci DEFAULT 'USER',
  `bio` text COLLATE utf8mb4_general_ci,
  `profile_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `bio`, `profile_photo`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'Khanza Haura', 'khanza', 'khanza.haura.148@mail.com', '$2y$10$examplehashkanza1234567890', 'USER', 'Penikmat novel dan penulis pemula.', 'uploads/profiles/khanza.jpg', 1, '2026-01-24 18:40:11', '2026-01-28 15:37:39'),
(2, 'Alya Putri', 'alyap', 'alya@mail.com', '$2y$10$examplehashalya1234567890', 'USER', 'Suka membaca novel romance.', 'uploads/profiles/alya.jpg', 1, '2026-01-24 18:40:11', '2026-01-24 18:40:11'),
(3, 'Bima Pratama', 'bimap', 'bima@mail.com', '$2y$10$examplehashbima1234567890', 'USER', 'Penulis cerita fantasi.', 'uploads/profiles/bima.jpg', 0, '2026-01-24 18:40:11', '2026-01-24 18:40:11'),
(4, 'Admin Platform', 'admin', 'admin@mail.com', '$2y$10$examplehashadmin1234567890', 'ADMIN', 'Administrator platform novel.', 'uploads/profiles/admin.jpg', 1, '2026-01-24 18:40:11', '2026-01-24 18:40:11'),
(5, 'Dewi Lestari', 'dewil', 'dewi@mail.com', '$2y$10$examplehashdewi1234567890', 'USER', 'Pembaca aktif dan reviewer.', 'uploads/profiles/dewi.jpg', 0, '2026-01-24 18:40:11', '2026-01-24 18:40:11'),
(6, 'Leila S. Chudori', 'leilachudori', 'leila.chudori@mail.com', '$2y$10$dummyhashleila1234567890', 'USER', 'Penulis novel dan jurnalis Indonesia, dikenal melalui karya sastra bertema sejarah dan kemanusiaan.', 'uploads/profiles/leila.jpg', 1, '2026-01-27 16:45:53', '2026-01-27 16:45:53'),
(7, 'Pidi Baiq', 'pidibaiq', 'pidi.baiq@mail.com', '$2y$10$dummpyhashpidi1234567890', 'USER', 'Penulis dan seniman Indonesia, dikenal lewat novel Dilan dan Milea.', 'uploads/profiles/pidi.jpg', 1, '2026-01-27 16:45:53', '2026-01-27 16:45:53'),
(8, 'Shey Caelan', 'sheycaelan', 'shey.caelan@mail.com', '$2y$10$dummyhashshey1234567890', 'USER', 'Penulis novel romance dengan tema emosi dan penyembuhan diri.', 'uploads/profiles/shey.jpg', 1, '2026-01-27 16:45:53', '2026-01-27 16:45:53'),
(9, 'Brian Khrisna', 'briankhrisna', 'brian.khrisna@mail.com', '$2y$10$dummyhashbrian1234567890', 'USER', 'Penulis novel reflektif tentang kehidupan dan makna kebahagiaan.', 'uploads/profiles/brian.jpg', 1, '2026-01-27 16:45:53', '2026-01-27 16:45:53'),
(10, 'Tere Liye', 'tereliye', 'tere.liye@mail.com', '$2y$10$dummyhashtereliye1234567890', 'USER', 'Penulis novel Indonesia yang dikenal melalui karya-karya bertema keluarga, kehidupan, perjuangan, dan nilai-nilai kemanusiaan.', 'uploads/profiles/tere-liye.jpg', 1, '2026-01-27 16:46:22', '2026-01-27 16:46:22'),
(11, 'Khanza Haura', NULL, 'khanza.haura.148@gmail.com', '$2y$10$pSvZeaKUNjd6Z147b5f4B.kkgUKVaxu6uv9jPpbRBApE2GTXj1Wbe', 'USER', NULL, NULL, 0, '2026-02-01 15:18:37', '2026-02-01 15:18:37');

-- --------------------------------------------------------

--
-- Table structure for table `user_library`
--

CREATE TABLE `user_library` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `story_id` bigint NOT NULL,
  `progress` int DEFAULT '0' COMMENT 'Chapter terakhir yang dibaca (0 = belum mulai)',
  `is_reading` tinyint(1) DEFAULT '1' COMMENT '1 = sedang dibaca, 0 = sudah selesai',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_library`
--

INSERT INTO `user_library` (`id`, `user_id`, `story_id`, `progress`, `is_reading`, `added_at`, `updated_at`) VALUES
(10, 11, 3, 0, 1, '2026-02-01 16:15:25', '2026-02-01 16:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE `views` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `story_id` bigint DEFAULT NULL,
  `chapter_id` bigint DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `story_id` (`story_id`,`chapter_number`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comment_chapter` (`chapter_id`),
  ADD KEY `fk_comment_user` (`user_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `story_id` (`story_id`,`user_id`),
  ADD KEY `fk_rating_user` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_review_story` (`story_id`),
  ADD KEY `fk_review_user` (`user_id`);

--
-- Indexes for table `review_likes`
--
ALTER TABLE `review_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `review_id` (`review_id`,`user_id`),
  ADD KEY `fk_review_likes_user` (`user_id`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_story_author` (`author_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_library`
--
ALTER TABLE `user_library`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_views_user` (`user_id`),
  ADD KEY `fk_views_story` (`story_id`),
  ADD KEY `fk_views_chapter` (`chapter_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `review_likes`
--
ALTER TABLE `review_likes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_library`
--
ALTER TABLE `user_library`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `views`
--
ALTER TABLE `views`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `fk_chapter_story` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comment_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `fk_rating_story` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rating_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_review_story` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_likes`
--
ALTER TABLE `review_likes`
  ADD CONSTRAINT `fk_review_likes_review` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stories`
--
ALTER TABLE `stories`
  ADD CONSTRAINT `fk_story_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `views`
--
ALTER TABLE `views`
  ADD CONSTRAINT `fk_views_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_views_story` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_views_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
