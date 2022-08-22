-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 22 août 2022 à 12:39
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `quiz_app`
--

-- --------------------------------------------------------

--
-- Structure de la table `gfc_dossiers`
--

CREATE TABLE `gfc_dossiers` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `gfc_dossiers`
--

INSERT INTO `gfc_dossiers` (`id`, `name`) VALUES
(1, 'Boris'),
(2, 'Ben'),
(3, 'Rachelle'),
(4, 'Claire'),
(5, 'Joyce'),
(6, 'Franck'),
(7, 'Dora'),
(8, 'Olivia'),
(9, 'Belva');

-- --------------------------------------------------------

--
-- Structure de la table `gfc_questions`
--

CREATE TABLE `gfc_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `question` text NOT NULL,
  `is_requered` tinyint(1) NOT NULL,
  `is_more_answers` tinyint(1) NOT NULL DEFAULT 0,
  `answers` text NOT NULL,
  `correct_answer` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `gfc_questions`
--

INSERT INTO `gfc_questions` (`id`, `quiz_id`, `type`, `question`, `is_requered`, `is_more_answers`, `answers`, `correct_answer`, `date`) VALUES
(2, 2, 'text', 'Comment faire du pain', 0, 0, 'a:0:{}', 'Avec la farine', '2022-08-20 17:57:40'),
(3, 2, 'number', 'Quel âge avez-vous ?', 0, 0, 'a:2:{i:0;s:7:\"15 - 18\";i:1;s:7:\"19 - 24\";}', '', '2022-08-20 13:41:00'),
(6, 2, 'checkbox', 'Choisissez vos pays', 1, 0, 'a:3:{i:0;s:6:\"France\";i:1;s:6:\"Canada\";i:2;s:1:\"E\";}', '', '2022-08-20 17:51:21'),
(7, 1, 'radio', 'choix', 1, 0, 'a:4:{i:0;s:1:\"A\";i:1;s:1:\"B\";i:2;s:1:\"C\";i:3;s:1:\"D\";}', '', '2022-08-20 13:55:31'),
(8, 1, 'textarea', 'Test', 1, 0, 'a:0:{}', '', '2022-08-20 17:57:32'),
(10, 3, 'rating', 'NEW TEST', 0, 0, 'a:2:{i:0;s:5:\"moins\";i:1;s:4:\"plus\";}', '', '2022-08-20 17:56:00'),
(11, 2, 'checkbox', 'TEST', 1, 0, 'a:3:{i:0;s:1:\"V\";i:1;s:1:\"W\";i:2;s:1:\"Y\";}', '', '2022-08-20 18:25:32'),
(12, 1, 'checkbox', 'Quelles langues parlez-vous ?', 1, 0, 'a:3:{i:0;s:9:\"Français\";i:1;s:7:\"Anglais\";i:2;s:7:\"Chinois\";}', 'Français', '2022-08-21 13:04:26'),
(13, 1, 'checkbox', 'Comment faire pour être en bonne santé ?', 0, 0, 'a:0:{}', '', '2022-08-21 01:30:46'),
(14, 5, 'checkbox', 'Sélectionnez les ou la valeur(s) juste(s)', 1, 0, 'a:4:{i:0;s:6:\"Danser\";i:1;s:4:\"Tuer\";i:2;s:17:\"Manger c\'est bien\";i:3;s:11:\"Bien manger\";}', 'Manger c&#039;est bien', '2022-08-21 02:55:00'),
(15, 5, 'radio', 'Moi j&#039;aime quoi ?', 1, 0, 'a:3:{i:0;s:1:\"A\";i:1;s:1:\"B\";i:2;s:1:\"C\";}', 'A', '2022-08-21 08:28:12'),
(16, 1, 'checkbox', 'NEW TEST 2', 1, 1, 'a:4:{i:0;s:1:\"a\";i:1;s:1:\"b\";i:2;s:11:\"ma réponse\";i:3;s:1:\"c\";}', 'a:1:{i:0;s:11:\"Bien manger\";}', '2022-08-21 02:30:39'),
(17, 3, 'checkbox', 'HH', 0, 0, 'a:4:{i:0;s:1:\"E\";i:1;s:1:\"A\";i:2;s:4:\"Dora\";i:3;s:1:\"F\";}', 'Dora', '2022-08-21 02:35:30'),
(18, 5, 'text', 'Comment s&#039;appelle la peluche de Mr Bean ?', 0, 1, 'a:0:{}', 'a:2:{i:0;s:4:\"Tedi\";i:1;s:5:\"Thedi\";}', '2022-08-21 09:18:50'),
(19, 5, 'checkbox', 'Le ou les plus riches du monde sont ?', 1, 1, 'a:7:{i:0;s:12:\"Barack Obama\";i:1;s:15:\"Mark zukerberck\";i:2;s:15:\"Micheal jackson\";i:3;s:11:\"Tom & Jerry\";i:4;s:9:\"Paul Biya\";i:5;s:9:\"Elon Musk\";i:6;s:9:\"Bill Gate\";}', 'a:3:{i:0;s:15:\"Mark zukerberck\";i:1;s:9:\"Elon Musk\";i:2;s:9:\"Bill Gate\";}', '2022-08-21 09:32:23'),
(20, 1, 'radio', 'Quelle est la couleur principale  ?', 0, 0, 'a:4:{i:0;s:4:\"Bleu\";i:1;s:5:\"Rouge\";i:2;s:6:\"Violet\";i:3;s:5:\"Blanc\";}', 'blanc', '2022-08-21 12:43:36');

-- --------------------------------------------------------

--
-- Structure de la table `gfc_quizs`
--

CREATE TABLE `gfc_quizs` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `date` datetime NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `gfc_quizs`
--

INSERT INTO `gfc_quizs` (`id`, `name`, `date`, `description`) VALUES
(1, 'Culture générale', '2022-08-21 12:45:57', 'Desc ..'),
(2, 'Test quiz 2', '2022-08-20 03:29:06', NULL),
(3, 'Nouveau quiz', '2022-08-20 14:47:15', 'YO QUIZ'),
(5, 'Santé &amp; Bien Être', '2022-08-21 01:38:48', '');

-- --------------------------------------------------------

--
-- Structure de la table `gfc_types`
--

CREATE TABLE `gfc_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `display_name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `gfc_types`
--

INSERT INTO `gfc_types` (`id`, `name`, `display_name`) VALUES
(1, 'text', 'Texte'),
(2, 'textarea', 'Long texte'),
(3, 'email', 'E-mail'),
(4, 'checkbox', 'Choix multiple'),
(5, 'radio', 'Choix unique'),
(6, 'rating', 'Note de 1 à 5'),
(7, 'number', 'Numérique');

-- --------------------------------------------------------

--
-- Structure de la table `gfc_user_answers`
--

CREATE TABLE `gfc_user_answers` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `dossier_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `gfc_user_answers`
--

INSERT INTO `gfc_user_answers` (`id`, `quiz_id`, `dossier_id`, `question`, `answer`, `is_correct`, `date`) VALUES
(48, 5, 1, 'Sélectionnez les ou la valeur(s) juste(s)', 'Tuer', 0, '2022-08-21 13:30:14'),
(49, 5, 1, 'Moi j&#039;aime quoi ?', 'A', 1, '2022-08-21 13:30:14'),
(50, 5, 1, 'Comment s&#039;appelle la peluche de Mr Bean ?', 'Tedi', 1, '2022-08-21 13:30:14'),
(51, 5, 1, 'Le ou les plus riches du monde sont ?', 'Barack Obama, Tom &amp; Jerry, Elon Musk', 1, '2022-08-21 13:30:14'),
(52, 1, 1, 'choix', 'A', 0, '2022-08-21 14:09:08'),
(53, 1, 1, 'Test', 'je suis ..', 0, '2022-08-21 14:09:08'),
(54, 1, 1, 'Quelles langues parlez-vous ?', 'Français, Anglais', 1, '2022-08-21 14:09:08'),
(55, 1, 1, 'Comment faire pour être en bonne santé ?', '', 1, '2022-08-21 14:09:08'),
(56, 1, 1, 'NEW TEST 2', 'ma réponse', 0, '2022-08-21 14:09:08'),
(57, 1, 1, 'Quelle est la couleur principale  ?', 'Bleu', 0, '2022-08-21 14:09:08'),
(58, 5, 3, 'Sélectionnez les ou la valeur(s) juste(s)', 'Manger c&#039;est bien', 1, '2022-08-21 16:01:27'),
(59, 5, 3, 'Moi j&#039;aime quoi ?', 'A', 1, '2022-08-21 16:01:27'),
(60, 5, 3, 'Comment s&#039;appelle la peluche de Mr Bean ?', 'Tedi', 1, '2022-08-21 16:01:27'),
(61, 5, 3, 'Le ou les plus riches du monde sont ?', 'Mark zukerberck, Elon Musk, Bill Gate', 1, '2022-08-21 16:01:27'),
(62, 5, 4, 'Sélectionnez les ou la valeur(s) juste(s)', 'Tuer, Bien manger', 0, '2022-08-21 16:05:21'),
(63, 5, 4, 'Moi j&#039;aime quoi ?', 'A', 1, '2022-08-21 16:05:21'),
(64, 5, 4, 'Comment s&#039;appelle la peluche de Mr Bean ?', 'Tedi', 1, '2022-08-21 16:05:21'),
(65, 5, 4, 'Le ou les plus riches du monde sont ?', 'Elon Musk', 1, '2022-08-21 16:05:21'),
(66, 1, 4, 'choix', 'B', 0, '2022-08-21 16:07:16'),
(67, 1, 4, 'Test', 'test', 0, '2022-08-21 16:07:16'),
(68, 1, 4, 'Quelles langues parlez-vous ?', 'Français, Anglais', 1, '2022-08-21 16:07:16'),
(69, 1, 4, 'Comment faire pour être en bonne santé ?', '', 1, '2022-08-21 16:07:16'),
(70, 1, 4, 'NEW TEST 2', 'ma réponse', 0, '2022-08-21 16:07:16'),
(71, 1, 4, 'Quelle est la couleur principale  ?', 'Blanc', 1, '2022-08-21 16:07:16'),
(72, 5, 5, 'Sélectionnez les ou la valeur(s) juste(s)', 'Manger c&#039;est bien, Bien manger', 1, '2022-08-21 16:08:26'),
(73, 5, 5, 'Moi j&#039;aime quoi ?', 'A', 1, '2022-08-21 16:08:26'),
(74, 5, 5, 'Comment s&#039;appelle la peluche de Mr Bean ?', 'Tedi', 1, '2022-08-21 16:08:26'),
(75, 5, 5, 'Le ou les plus riches du monde sont ?', 'Mark zukerberck, Elon Musk, Bill Gate', 1, '2022-08-21 16:08:26'),
(76, 5, 6, 'Sélectionnez les ou la valeur(s) juste(s)', 'Manger c&#039;est bien, Bien manger', 1, '2022-08-21 16:12:20'),
(77, 5, 6, 'Moi j&#039;aime quoi ?', 'A', 1, '2022-08-21 16:12:20'),
(78, 5, 6, 'Comment s&#039;appelle la peluche de Mr Bean ?', 'Tedi', 1, '2022-08-21 16:12:20'),
(79, 5, 6, 'Le ou les plus riches du monde sont ?', 'Mark zukerberck, Elon Musk, Bill Gate', 1, '2022-08-21 16:12:20'),
(80, 5, 6, 'Sélectionnez les ou la valeur(s) juste(s)', 'Manger c&#039;est bien, Bien manger', 1, '2022-08-21 16:14:39'),
(81, 5, 6, 'Moi j&#039;aime quoi ?', 'A', 1, '2022-08-21 16:14:39'),
(82, 5, 6, 'Comment s&#039;appelle la peluche de Mr Bean ?', 'Tedi', 1, '2022-08-21 16:14:39'),
(83, 5, 6, 'Le ou les plus riches du monde sont ?', 'Mark zukerberck, Elon Musk, Bill Gate', 1, '2022-08-21 16:14:39'),
(84, 5, 6, 'Sélectionnez les ou la valeur(s) juste(s)', 'Manger c&#039;est bien, Bien manger', 1, '2022-08-21 16:15:32'),
(85, 5, 6, 'Moi j&#039;aime quoi ?', 'A', 1, '2022-08-21 16:15:32'),
(86, 5, 6, 'Comment s&#039;appelle la peluche de Mr Bean ?', 'Tedi', 1, '2022-08-21 16:15:32'),
(87, 5, 6, 'Le ou les plus riches du monde sont ?', 'Mark zukerberck, Elon Musk, Bill Gate', 1, '2022-08-21 16:15:32'),
(88, 5, 5, 'Sélectionnez les ou la valeur(s) juste(s)', 'Manger c&#039;est bien, Bien manger', 1, '2022-08-21 16:17:08'),
(89, 5, 5, 'Moi j&#039;aime quoi ?', 'A', 1, '2022-08-21 16:17:08'),
(90, 5, 5, 'Comment s&#039;appelle la peluche de Mr Bean ?', 'Tedi', 1, '2022-08-21 16:17:08'),
(91, 5, 5, 'Le ou les plus riches du monde sont ?', 'Mark zukerberck, Elon Musk, Bill Gate', 1, '2022-08-21 16:17:08'),
(92, 5, 5, 'Sélectionnez les ou la valeur(s) juste(s)', 'Manger c&#039;est bien, Bien manger', 1, '2022-08-21 16:17:35'),
(93, 5, 5, 'Moi j&#039;aime quoi ?', 'A', 1, '2022-08-21 16:17:35'),
(94, 5, 5, 'Comment s&#039;appelle la peluche de Mr Bean ?', 'Tedi', 1, '2022-08-21 16:17:35'),
(95, 5, 5, 'Le ou les plus riches du monde sont ?', 'Mark zukerberck, Elon Musk, Bill Gate', 1, '2022-08-21 16:17:35');

-- --------------------------------------------------------

--
-- Structure de la table `gfc_user_result_quiz`
--

CREATE TABLE `gfc_user_result_quiz` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `dossier_id` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `total_note` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `gfc_user_result_quiz`
--

INSERT INTO `gfc_user_result_quiz` (`id`, `quiz_id`, `dossier_id`, `note`, `total_note`, `date`) VALUES
(2, 5, 1, 3, 4, '2022-08-21 13:30:14'),
(3, 1, 2, 2, 6, '2022-08-21 14:09:08'),
(4, 5, 3, 4, 4, '2022-08-21 16:01:27'),
(5, 5, 4, 3, 4, '2022-08-21 16:05:21'),
(6, 1, 4, 3, 6, '2022-08-21 16:07:16'),
(7, 5, 5, 4, 4, '2022-08-21 16:08:26'),
(8, 5, 6, 5, 4, '2022-08-21 16:12:20'),
(9, 5, 6, 5, 4, '2022-08-21 16:14:39'),
(10, 5, 5, 5, 4, '2022-08-21 16:17:08'),
(11, 5, 5, 4, 4, '2022-08-21 16:17:35');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `gfc_dossiers`
--
ALTER TABLE `gfc_dossiers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gfc_questions`
--
ALTER TABLE `gfc_questions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gfc_quizs`
--
ALTER TABLE `gfc_quizs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gfc_types`
--
ALTER TABLE `gfc_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gfc_user_answers`
--
ALTER TABLE `gfc_user_answers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gfc_user_result_quiz`
--
ALTER TABLE `gfc_user_result_quiz`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `gfc_dossiers`
--
ALTER TABLE `gfc_dossiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `gfc_questions`
--
ALTER TABLE `gfc_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `gfc_quizs`
--
ALTER TABLE `gfc_quizs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `gfc_types`
--
ALTER TABLE `gfc_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `gfc_user_answers`
--
ALTER TABLE `gfc_user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT pour la table `gfc_user_result_quiz`
--
ALTER TABLE `gfc_user_result_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
