-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 09:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it_news`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `viewed` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `title`, `description`, `date`, `image`, `tag`, `viewed`, `topic_id`, `user_id`) VALUES
(4, 'Реліз Grok 2.0', 'Компанія XAi випустила оновлену версію свого чат-боту Grok, було покращено функцію генерації зображень, тепер вона доступна всім користувачам сайту', '2024-12-12', 'grok.png', 'it', 201, 1, 9),
(5, 'Mozilla випустила нову версію Firefox з покращеною безпекою та продуктивністю', 'Компанія Mozilla оголосила про випуск нової версії браузера Firefox, яка пропонує низку значних покращень для користувачів. Оновлення зосереджено на підвищенні безпеки, швидкості роботи та зручності використання.', '2024-12-10', 'firefox.jpg', 'it', 166, 1, 9),
(6, 'Інновації в новинних сайтах: сучасний підхід до інформування', 'Новинні сайти стали невід\'ємною частиною повсякденного життя, забезпечуючи швидкий доступ до актуальної інформації. Але щоб залишатися конкурентоспроможними, вони активно впроваджують інновації, адаптуючись до змін у поведінці користувачів та технологічних можливостей.', '2024-12-10', 'news.jpg', 'DB', 121, 1, 8),
(7, 'Мобільні додатки для генерації зображень', 'Популярні застосунки та їх функціонал На ринку вже існує чимало додатків, які підтримують генерацію зображень на смартфонах: Lensa AI: Додаток для створення аватарів у різних стилях на основі завантажених фотографій користувача. DeepArt: Генерація художніх зображень у стилі відомих митців, таких як Ван Гог чи Моне. Runway ML: Мобільна версія платформи для творчої роботи з відео та зображеннями. Крім того, з являються додатки, що дозволяють створювати повністю унікальні ілюстрації для соціальних мереж, маркетингу або особистих проєктів.', '2020-02-13', 'mob_generate.jpg', 'it', 22, 1, 10),
(8, 'New Article', 'SDSADDS', '2024-12-05', NULL, 'it', 0, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `delete` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `text`, `user_id`, `comment_id`, `article_id`, `date`, `delete`) VALUES
(2, 'gdfsdsgdgdfs', 10, NULL, 4, '2024-12-12', NULL),
(3, 'Oppo mon smartphone préféré', 11, 2, 4, '2024-12-12', NULL),
(4, 'Wow!', 10, NULL, 4, '2024-12-14', NULL),
(5, 'I can replay!)', 10, 2, 4, '2024-12-14', NULL),
(6, 'awesome', 10, 4, 4, '2024-12-14', NULL),
(7, 'Cool', 13, NULL, 7, '2024-12-14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1733152906),
('m241202_152232_create_topic_table', 1733153318),
('m241202_152251_create_user_table', 1733153318),
('m241202_152301_create_article_table', 1733153318),
('m241202_152311_create_comment_table', 1733153319),
('m241209_203249_add_created_at_updated_at_to_article_table', 1733776430),
('m241209_203505_add_created_at_updated_at_to_article_table', 1733776536);

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`id`, `name`) VALUES
(1, 'Topic 1');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `auth_key` varchar(32) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `login`, `image`, `password_hash`, `auth_key`, `role`) VALUES
(5, 'Antony', 'antony@gmail.com', NULL, '$2y$13$Ln8GE9d.YsY0gkHEYasfqeipic3LolEoCnlUhzAsXpD5Bf4O1W3gS', 'zTizp7ExGrKiHu25V5xw3GFUhJqSq3rM', 'admin'),
(7, 'artem', 'darkshadow@gmail.com', NULL, '$2y$13$4/HTXXRxN324JbPXLg9tjeZahY4hIMlEH.kn63qDKR0bnGyLXR1hK', 'bBjG4YP_lYGG7RBS9JEiWGTub5NrLjYS', NULL),
(8, 'Andrii', 'neymarpro224@gmail.com', NULL, '$2y$13$YLIfyc0pog26sJoRt8PFyeJ6VzgQusQiJBPzxKfGAtVVyOJ1KWOOG', 'fpEQitMFHnsPzUh6mN2slOA-EI1CKGKt', NULL),
(9, 'John', 'Johnn228@gmail.com', NULL, '$2y$13$voT0R/zTgnd.XXI0BCvapeaif61lz7rgwPKZKYaaw0TfZorXbr5oW', 'qK1zete5aVyeLsAg9xOVRAjdY4uePS-f', NULL),
(10, 'OppoFindX4554', 'mateUSDinar56@gmail.com', 'capy_france.jpg', '$2y$13$vKY2YYPPB3JoGPILbIpMJ.iR.QLCXSwzMbvZfEmR8.uMx1xLZBDPq', '_d4VISiOo7HqN4dN4MJ055StJkwkk1r1', NULL),
(11, 'MarselPrust', 'MarselPrust@france.com', 'capy_france_2.jpg', '$2y$13$FRLPFqcIOlyLn1PKSK.86Oju.VmJvtwpaY0WjMQR8OHu9gh9cYYhe', '4EUOvr1eMH-0AirdpNLSuWmVGxZpvdn7', NULL),
(12, 'LGNOKIA50679', 'nokia_mobile@gmail.com', NULL, '$2y$13$gTSJaS0FS.SRWos0Q56bsuCuBOylPGPhN1sjVFPmqI4Oq/Dc5g7P.', 'Y2_oedhNLIKZTyWuiCgpSRqeGmUGmvbK', NULL),
(13, 'NewUser', 'orn@gmail.com', '675dcb66b1af4.jpg', '$2y$13$AFcj.qWRkd9yY82nBEcuE.leiv70bOUAY9t89tJbpERzSqYzh9Gbq', 'je6uTWqaPSWoM00uokKSORgAIme--cYA', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-topic_id` (`topic_id`),
  ADD KEY `idx-post-user_id` (`user_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-post-user_id` (`user_id`),
  ADD KEY `idx-article_id` (`article_id`),
  ADD KEY `idx-comment_id` (`comment_id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk-post-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-topic_id` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk-article_id` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-comment-post-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-comment_id` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
