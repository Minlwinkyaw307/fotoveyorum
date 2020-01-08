-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 03, 2020 at 02:17 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fotoveyorum`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `parentid_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `parentid_id`, `title`, `keywords`, `description`, `image`, `created_at`, `updated_at`, `status`) VALUES
(4, NULL, 'People', 'people, photos', 'photos of people', '3809-5e0e005201cf1.jpeg', '2020-01-02 17:38:10', '2020-01-02 17:38:10', 1),
(5, 4, 'Selfie', 'Selfie', 'Selfie of yourself', 'smiling-young-man-wearing-sunglasses-taking-selfie-showing-thumb-up-gesture_23-2148203116-5e0e00828740a.jpeg', '2020-01-02 17:38:58', '2020-01-02 17:38:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` int(11) NOT NULL,
  `ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `commented_by_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `post_id`, `comment`, `rate`, `ip`, `status`, `created_at`, `updated_at`, `commented_by_id`, `title`) VALUES
(1, 7, '<p>Hello this is a comment</p>', 1, '1.1.1.1', 1, NULL, NULL, 3, 'Title - 1');

-- --------------------------------------------------------

--
-- Table structure for table `comment_report`
--

CREATE TABLE `comment_report` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `reported_by_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment_report`
--

INSERT INTO `comment_report` (`id`, `comment_id`, `reported_by_id`, `created_at`, `status`) VALUES
(5, 1, 3, '2019-12-29 21:27:21', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `title`, `image`) VALUES
(9, 'Title of Image - 2', 'coffee-5e07a92481634.jpeg'),
(10, 'Title of Image - 3', 'profile image - 3-5e07a97aec9f8.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `name`, `email`, `message`, `ip`, `subject`, `status`, `created_at`, `updated_at`) VALUES
(1, 'My Name?', 'test@gmail.com', 'fadfadgaer', '127.0.0.1', 'Testing', 'Read', '2019-12-29 22:09:54', '2019-12-29 22:09:54'),
(2, 'My Name?', 'test@gmail.com', 'fadfeafer', '127.0.0.1', 'Testing', 'New', '2019-12-29 22:11:02', '2019-12-29 22:11:02'),
(3, 'My Name?', 'test@gmail.com', 'dafdafdaer', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:38:05', '2020-01-02 18:38:05'),
(4, 'My Name?', 'test@gmail.com', 'dafdafdaer', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:38:17', '2020-01-02 18:38:17'),
(5, 'My Name?', 'test@gmail.com', 'dafdafdaer', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:38:40', '2020-01-02 18:38:40'),
(6, 'My Name?', 'test@gmail.com', 'dafdafdaer', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:39:37', '2020-01-02 18:39:37'),
(7, 'My Name?', 'test@gmail.com', 'dafdafdaer', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:39:46', '2020-01-02 18:39:46'),
(8, 'My Name?', 'test@gmail.com', 'dafdafdaer', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:41:34', '2020-01-02 18:41:34'),
(9, 'My Name?', 'test@gmail.com', 'dafdafdaer', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:42:03', '2020-01-02 18:42:03'),
(10, 'My Name?', 'test@gmail.com', 'dafdafdaer', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:43:32', '2020-01-02 18:43:32'),
(11, 'My Name?', 'test@gmail.com', 'dafdafdaer', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:46:37', '2020-01-02 18:46:37'),
(12, 'My Name?', 'minlwinkyaw307@gmail.com', 'hello this is testing', '127.0.0.1', 'Testing', 'New', '2020-01-02 18:47:05', '2020-01-02 18:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20191228174655', '2019-12-28 17:47:00'),
('20191228181719', '2019-12-28 18:17:30'),
('20191228192658', '2019-12-28 19:27:11'),
('20191228192840', '2019-12-28 19:28:44'),
('20191229110137', '2019-12-29 11:01:50'),
('20191229121114', '2019-12-29 12:11:17'),
('20191229152812', '2019-12-29 15:28:14'),
('20191229173759', '2019-12-29 17:38:03'),
('20191229185943', '2019-12-29 18:59:48'),
('20191229190851', '2019-12-29 19:08:54'),
('20191229231254', '2019-12-29 23:12:57'),
('20191229235027', '2019-12-29 23:50:30'),
('20191229235134', '2019-12-29 23:51:37'),
('20200102144409', '2020-01-02 14:44:11'),
('20200102154539', '2020-01-02 15:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `view` int(11) DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `category_id`, `title`, `keywords`, `description`, `status`, `created_at`, `updated_at`, `content`, `img_id`, `comment_id`, `view`, `created_by_id`) VALUES
(7, 5, 'Title of the post - 2', 'Keywords', 'Description', 1, '2019-12-28 22:13:49', '2019-12-28 22:13:49', 'Content', 10, NULL, 68, 3);

-- --------------------------------------------------------

--
-- Table structure for table `post_image`
--

CREATE TABLE `post_image` (
  `post_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_image`
--

INSERT INTO `post_image` (`post_id`, `image_id`) VALUES
(7, 9),
(7, 10);

-- --------------------------------------------------------

--
-- Table structure for table `post_report`
--

CREATE TABLE `post_report` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `reported_by_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_report`
--

INSERT INTO `post_report` (`id`, `post_id`, `reported_by_id`, `created_at`, `status`) VALUES
(3, 7, 3, '2019-12-29 20:59:39', 'New'),
(4, 7, 3, '2019-12-29 21:26:43', 'New'),
(5, 7, 3, '2019-12-29 21:26:47', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtpserver` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `smtpemail` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtppassword` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtpport` int(11) DEFAULT NULL,
  `facebook` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aboutus` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referns` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `title`, `keywords`, `description`, `company`, `phone`, `address`, `fax`, `email`, `smtpserver`, `smtpemail`, `smtppassword`, `smtpport`, `facebook`, `instagram`, `twitter`, `aboutus`, `contact`, `referns`, `status`) VALUES
(1, 'FotoGrapher', 'photo, comment, sharing, image, picture', 'Share Your Opinion, Share Your Photographs', 'FotoGrapher', '+90505123456', 'Kastamonu Yolu Demir Çelik Kampüsü, 78050 Kılavuzlar/Karabük Merkez/Karabük', '+90505123456', 'fotographer@gmail.com', 'gmail', 'fotographer@gmail.com', 'Thisispassword', 578, 'http://facebook.com/', 'https://www.instagram.com/', 'https://twitter.com/', '<p style=\"text-align:justify\"><a href=\"https://www.technologynewsntrends.com/wp-content/uploads/2019/12/About-Us-Technology-News-Trends-Write-For-U.jpg\" target=\"_self\"><img alt=\"\" src=\"https://www.technologynewsntrends.com/wp-content/uploads/2019/12/About-Us-Technology-News-Trends-Write-For-U.jpg\" style=\"height:133px; width:200px\" /></a>Having hands on experience in creating innovative designs, I do offer design solutions which harness the new possibilities of web based communication.I do specialize in all aspects of website designing,Nam libero tempore,aspects of website designing cum soluta nobis est eligendi theme development, possimus omnis dolor repellendus.Nam libero tempore, cum soluta nobis est eligendi voluptatum...</p>\r\n\r\n<p style=\"text-align:justify\">Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint Consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt labore dolore magna et molestiae non recusandae.</p>', '<p style=\"text-align:center\"><strong>Contact Us Page</strong></p>', '<p style=\"text-align:center\"><strong>Refrences Page</strong></p>', 'Status');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `surname`, `status`, `created_at`, `updated_at`, `image`) VALUES
(2, 'user@gmail.com', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$mX5jrT9JMoo3h2rSZMiA4A$jA1BbazumTvje6LsZqONfxBYWq+oF8mHxJFsauq+2lI', 'Normal', 'User', 1, NULL, NULL, 'profile image - 3-5e07a97aec9f8.jpeg'),
(3, 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$ORurHsDATE5ClKk/fPaRAw$Hp+KelvFCsbzZuUcS6wRrAZwkLfRasf2C+PTInfUR1s', 'Admin', 'User', 1, NULL, NULL, 'profile image - 3-5e07a97aec9f8.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_64C19C11F82D8F8` (`parentid_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526C4B89032C` (`post_id`),
  ADD KEY `IDX_9474526C94F6F716` (`commented_by_id`);

--
-- Indexes for table `comment_report`
--
ALTER TABLE `comment_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E3C2F96F8697D13` (`comment_id`),
  ADD KEY `IDX_E3C2F9671CE806` (`reported_by_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5A8A6C8D12469DE2` (`category_id`),
  ADD KEY `IDX_5A8A6C8DC06A9F55` (`img_id`),
  ADD KEY `IDX_5A8A6C8DF8697D13` (`comment_id`),
  ADD KEY `IDX_5A8A6C8DB03A8386` (`created_by_id`);

--
-- Indexes for table `post_image`
--
ALTER TABLE `post_image`
  ADD PRIMARY KEY (`post_id`,`image_id`),
  ADD KEY `IDX_522688B04B89032C` (`post_id`),
  ADD KEY `IDX_522688B03DA5256D` (`image_id`);

--
-- Indexes for table `post_report`
--
ALTER TABLE `post_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F40D93E14B89032C` (`post_id`),
  ADD KEY `IDX_F40D93E171CE806` (`reported_by_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comment_report`
--
ALTER TABLE `comment_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `post_report`
--
ALTER TABLE `post_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C11F82D8F8` FOREIGN KEY (`parentid_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9474526C94F6F716` FOREIGN KEY (`commented_by_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_report`
--
ALTER TABLE `comment_report`
  ADD CONSTRAINT `FK_E3C2F9671CE806` FOREIGN KEY (`reported_by_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_E3C2F96F8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_5A8A6C8D12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_5A8A6C8DB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5A8A6C8DC06A9F55` FOREIGN KEY (`img_id`) REFERENCES `image` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5A8A6C8DF8697D13` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`);

--
-- Constraints for table `post_image`
--
ALTER TABLE `post_image`
  ADD CONSTRAINT `FK_522688B03DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_522688B04B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_report`
--
ALTER TABLE `post_report`
  ADD CONSTRAINT `FK_F40D93E14B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F40D93E171CE806` FOREIGN KEY (`reported_by_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
