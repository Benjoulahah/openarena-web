-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Lun 11 Mai 2026 à 08:58
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `openarena`
--

-- --------------------------------------------------------

--
-- Structure de la table `matchs`
--

CREATE TABLE `matchs` (
  `id_match` int(11) NOT NULL,
  `id_tournoi` int(11) NOT NULL,
  `round_num` int(11) NOT NULL,
  `id_participation_1` int(11) NOT NULL,
  `id_participation_2` int(11) NOT NULL,
  `score_1` int(11) DEFAULT NULL,
  `score_2` int(11) DEFAULT NULL,
  `map_nom` varchar(50) DEFAULT 'dm1',
  `gamemode` int(11) DEFAULT '1',
  `type_match` varchar(50) DEFAULT 'swiss',
  `termine` tinyint(4) DEFAULT '0',
  `gagnant_participation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `matchs`
--

INSERT INTO `matchs` (`id_match`, `id_tournoi`, `round_num`, `id_participation_1`, `id_participation_2`, `score_1`, `score_2`, `map_nom`, `gamemode`, `type_match`, `termine`, `gagnant_participation_id`) VALUES
(1, 7, 1, 11, 12, 3, 2, 'dm1', 1, 'swiss', 1, NULL),
(2, 8, 1, 14, 19, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(3, 8, 1, 13, 18, 8, 1, 'dm1', 1, 'swiss', 1, NULL),
(4, 8, 1, 16, 17, 1, 0, 'dm1', 1, 'swiss', 1, NULL),
(5, 8, 1, 15, 20, 8, 1, 'dm1', 1, 'swiss', 1, NULL),
(6, 8, 2, 13, 16, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(7, 8, 2, 15, 14, 1, 8, 'dm1', 1, 'swiss', 1, NULL),
(8, 8, 2, 19, 20, 1, 0, 'dm1', 1, 'swiss', 1, NULL),
(9, 8, 2, 17, 18, 2, 1, 'dm1', 1, 'swiss', 1, NULL),
(10, 8, 3, 14, 13, 3, 0, 'dm1', 1, 'swiss', 1, NULL),
(11, 8, 3, 17, 16, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(12, 8, 3, 19, 15, 5, 0, 'dm1', 1, 'swiss', 1, NULL),
(13, 8, 3, 20, 18, 1, 0, 'dm1', 1, 'swiss', 1, NULL),
(14, 8, 4, 13, 17, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(15, 8, 4, 16, 15, 1, 0, 'dm1', 1, 'swiss', 1, NULL),
(16, 8, 5, 20, 17, 1, 0, 'dm1', 1, 'swiss', 1, NULL),
(17, 9, 1, 21, 24, 2, 1, 'dm1', 1, 'swiss', 1, NULL),
(18, 9, 1, 22, 26, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(19, 9, 1, 25, 28, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(20, 9, 1, 23, 27, 8, 0, 'dm1', 1, 'swiss', 1, NULL),
(21, 9, 2, 22, 25, 8, 0, 'dm1', 1, 'swiss', 1, NULL),
(22, 9, 2, 23, 21, 4, 0, 'dm1', 1, 'swiss', 1, NULL),
(23, 9, 2, 27, 26, 4, 0, 'dm1', 1, 'swiss', 1, NULL),
(24, 9, 2, 28, 24, 1, 0, 'dm1', 1, 'swiss', 1, NULL),
(25, 9, 3, 23, 22, 3, 0, 'dm1', 1, 'swiss', 1, NULL),
(26, 9, 3, 28, 27, 4, 0, 'dm1', 1, 'swiss', 1, NULL),
(27, 9, 3, 21, 25, 3, 0, 'dm1', 1, 'swiss', 1, NULL),
(28, 9, 3, 26, 24, 5, 0, 'dm1', 1, 'swiss', 1, NULL),
(29, 9, 4, 28, 22, 4, 0, 'dm1', 1, 'swiss', 1, NULL),
(30, 9, 4, 25, 26, 54, 0, 'dm1', 1, 'swiss', 1, NULL),
(31, 9, 5, 21, 25, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(32, 10, 1, 31, 30, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(33, 10, 1, 35, 34, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(34, 10, 1, 33, 29, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(35, 10, 2, 31, 35, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(36, 10, 2, 29, 32, 1, 0, 'dm1', 1, 'swiss', 1, NULL),
(37, 10, 2, 30, 34, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(38, 10, 3, 33, 30, 2, 0, 'dm1', 1, 'swiss', 1, NULL),
(39, 10, 3, 29, 35, 1, 0, 'dm1', 1, 'swiss', 1, NULL),
(40, 10, 3, 34, 32, 3, 0, 'dm1', 1, 'swiss', 1, NULL),
(41, 10, 4, 31, 29, 6, 0, 'dm1', 1, 'swiss', 1, NULL),
(42, 10, 4, 34, 30, 6, 0, 'dm1', 1, 'swiss', 1, NULL),
(43, 10, 4, 33, 35, 6, 0, 'dm1', 1, 'swiss', 1, NULL),
(44, 10, 5, 29, 34, 7, 0, 'dm1', 1, 'swiss', 1, NULL),
(45, 10, 1, 33, 29, NULL, NULL, 'dm1', 1, 'final', 1, 29),
(46, 10, 1, 31, 32, NULL, NULL, 'dm1', 1, 'final', 1, 31),
(47, 10, 2, 29, 31, NULL, NULL, 'dm1', 1, 'final', 1, 31),
(48, 9, 1, 23, 28, NULL, NULL, 'dm1', 1, 'final', 1, 28),
(49, 8, 1, 14, 13, NULL, NULL, 'dm1', 1, 'final', 0, NULL),
(50, 11, 1, 40, 44, 3, 0, 'dm1', 1, 'swiss', 1, 40),
(51, 11, 1, 42, 45, 3, 0, 'dm1', 1, 'swiss', 1, 42),
(52, 11, 1, 43, 38, 2, 1, 'dm1', 1, 'swiss', 1, 43),
(53, 11, 1, 36, 37, 4, 0, 'dm1', 1, 'swiss', 1, 36),
(54, 11, 1, 41, 39, 2, 4, 'dm1', 1, 'swiss', 1, 39),
(55, 11, 2, 40, 36, 4, 0, 'dm1', 1, 'swiss', 1, 40),
(56, 11, 2, 39, 43, 2, 0, 'dm1', 1, 'swiss', 1, 39),
(57, 11, 2, 45, 37, 5, 0, 'dm1', 1, 'swiss', 1, 45),
(58, 11, 2, 38, 44, 0, 6, 'dm1', 1, 'swiss', 1, 44),
(59, 11, 2, 42, 41, 7, 0, 'dm1', 1, 'swiss', 1, 42),
(60, 11, 3, 39, 42, 5, 0, 'dm1', 1, 'swiss', 1, 39),
(61, 11, 3, 45, 44, 7, 0, 'dm1', 1, 'swiss', 1, 45),
(62, 11, 3, 36, 43, 0, 6, 'dm1', 1, 'swiss', 1, 43),
(63, 11, 3, 37, 41, 0, 6, 'dm1', 1, 'swiss', 1, 41),
(64, 11, 3, 40, 38, 7, 0, 'dm1', 1, 'swiss', 1, 40),
(65, 11, 4, 42, 43, 7, 0, 'dm1', 1, 'swiss', 1, 42),
(66, 11, 4, 41, 36, 6, 0, 'dm1', 1, 'swiss', 1, 41),
(67, 11, 4, 45, 44, 6, 0, 'dm1', 1, 'swiss', 1, 45),
(68, 11, 5, 43, 41, 8, 2, 'dm1', 1, 'swiss', 1, 43),
(69, 11, 1, 40, 42, NULL, NULL, 'dm1', 1, 'final', 1, 42),
(70, 11, 1, 39, 45, NULL, NULL, 'dm1', 1, 'final', 1, 39),
(71, 11, 2, 42, 39, NULL, NULL, 'dm1', 1, 'final', 1, 42),
(72, 12, 1, 46, 47, NULL, NULL, 'dm1', 1, 'swiss', 0, NULL),
(73, 6, 1, 10, 9, 4, 1, 'dm1', 1, 'swiss', 1, 10),
(74, 6, 2, 10, 9, 4, 1, 'dm1', 1, 'swiss', 1, 10),
(75, 6, 3, 10, 9, NULL, NULL, 'dm1', 1, 'swiss', 0, NULL),
(76, 13, 1, 57, 49, 7, 4, 'dm1', 1, 'swiss', 1, 57),
(77, 13, 1, 50, 55, 7, 2, 'dm1', 1, 'swiss', 1, 50),
(78, 13, 1, 48, 52, 1, 7, 'dm1', 1, 'swiss', 1, 52),
(79, 13, 1, 51, 53, 18, 16, 'dm1', 1, 'swiss', 1, 51),
(80, 13, 1, 54, 59, 1, 7, 'dm1', 1, 'swiss', 1, 59),
(81, 13, 1, 58, 56, 1, 7, 'dm1', 1, 'swiss', 1, 56),
(82, 13, 2, 50, 56, 1, 2, 'dm1', 1, 'swiss', 1, 56),
(83, 13, 2, 57, 59, 8, 1, 'dm1', 1, 'swiss', 1, 57),
(84, 13, 2, 52, 51, 8, 7, 'dm1', 1, 'swiss', 1, 52),
(85, 13, 2, 48, 53, 7, 0, 'dm1', 1, 'swiss', 1, 48),
(86, 13, 2, 54, 49, 0, 7, 'dm1', 1, 'swiss', 1, 49),
(87, 13, 2, 58, 55, 2, 7, 'dm1', 1, 'swiss', 1, 55),
(88, 13, 3, 52, 57, 5, 2, 'dm1', 1, 'swiss', 1, 52),
(89, 13, 3, 59, 51, 4, 6, 'dm1', 1, 'swiss', 1, 51),
(90, 13, 3, 48, 50, 8, 1, 'dm1', 1, 'swiss', 1, 48),
(91, 13, 3, 49, 55, 7, 0, 'dm1', 1, 'swiss', 1, 49),
(92, 13, 3, 53, 54, 8, 0, 'dm1', 1, 'swiss', 1, 53),
(93, 13, 3, 56, 58, 7, 1, 'dm1', 1, 'swiss', 1, 56),
(94, 13, 4, 49, 51, 1, 7, 'dm1', 1, 'swiss', 1, 51),
(95, 13, 4, 57, 48, 1, 7, 'dm1', 1, 'swiss', 1, 48),
(96, 13, 4, 53, 55, 17, 7, 'dm1', 1, 'swiss', 1, 53),
(97, 13, 4, 50, 59, 7, 1, 'dm1', 1, 'swiss', 1, 50),
(98, 13, 5, 50, 57, 17, 7, 'dm1', 1, 'swiss', 1, 50),
(99, 13, 5, 49, 53, 7, 1, 'dm1', 1, 'swiss', 1, 49),
(100, 13, 1, 56, 48, NULL, NULL, 'dm1', 1, 'final', 1, 56),
(101, 13, 1, 52, 51, NULL, NULL, 'dm1', 1, 'final', 1, 51),
(102, 13, 2, 56, 51, NULL, NULL, 'dm1', 1, 'final', 1, 56);

-- --------------------------------------------------------

--
-- Structure de la table `participations`
--

CREATE TABLE `participations` (
  `id_participation` int(11) NOT NULL,
  `id_tournoi` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `victoires` int(11) DEFAULT '0',
  `defaites` int(11) DEFAULT '0',
  `qualifie` tinyint(4) DEFAULT '0',
  `elimine` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `participations`
--

INSERT INTO `participations` (`id_participation`, `id_tournoi`, `id_utilisateur`, `victoires`, `defaites`, `qualifie`, `elimine`) VALUES
(1, 2, 2, 0, 0, 0, 0),
(2, 2, 1, 0, 0, 0, 0),
(3, 3, 2, 0, 0, 0, 0),
(4, 3, 1, 0, 0, 0, 0),
(5, 4, 2, 0, 0, 0, 0),
(6, 4, 1, 0, 0, 0, 0),
(7, 5, 2, 0, 0, 0, 0),
(8, 5, 1, 0, 0, 0, 0),
(9, 6, 2, 0, 2, 0, 0),
(10, 6, 1, 2, 0, 0, 0),
(11, 7, 2, 2, 0, 0, 0),
(12, 7, 1, 1, 1, 0, 0),
(13, 8, 10, 3, 1, 1, 0),
(14, 8, 7, 3, 0, 1, 0),
(15, 8, 1, 1, 3, 0, 1),
(16, 8, 8, 4, 2, 0, 0),
(17, 8, 4, 2, 3, 0, 1),
(18, 8, 11, 0, 3, 0, 1),
(19, 8, 6, 5, 1, 0, 0),
(20, 8, 5, 3, 2, 1, 0),
(21, 9, 10, 3, 1, 1, 0),
(22, 9, 7, 2, 2, 0, 0),
(23, 9, 1, 3, 0, 1, 0),
(24, 9, 8, 0, 3, 0, 1),
(25, 9, 4, 2, 3, 0, 1),
(26, 9, 11, 1, 3, 0, 1),
(27, 9, 6, 1, 2, 0, 0),
(28, 9, 5, 3, 1, 1, 0),
(29, 10, 10, 3, 2, 1, 0),
(30, 10, 7, 1, 3, 0, 1),
(31, 10, 1, 3, 0, 1, 0),
(32, 10, 8, 3, 2, 1, 0),
(33, 10, 4, 3, 0, 1, 0),
(34, 10, 11, 2, 3, 0, 1),
(35, 10, 5, 1, 3, 0, 1),
(36, 11, 10, 1, 3, 0, 1),
(37, 11, 7, 0, 3, 0, 1),
(38, 11, 1, 0, 3, 0, 1),
(39, 11, 8, 4, 1, 1, 0),
(40, 11, 4, 3, 1, 1, 0),
(41, 11, 11, 2, 3, 0, 1),
(42, 11, 6, 5, 1, 1, 0),
(43, 11, 5, 3, 2, 1, 0),
(44, 11, 2, 1, 3, 0, 1),
(45, 11, 9, 3, 2, 1, 0),
(46, 12, 12, 0, 0, 0, 0),
(47, 12, 2, 0, 0, 0, 0),
(48, 13, 10, 3, 2, 1, 0),
(49, 13, 7, 3, 2, 1, 0),
(50, 13, 1, 3, 2, 1, 0),
(51, 13, 8, 4, 2, 1, 0),
(52, 13, 4, 3, 1, 1, 0),
(53, 13, 11, 2, 3, 0, 1),
(54, 13, 6, 0, 3, 0, 1),
(55, 13, 5, 1, 3, 0, 1),
(56, 13, 2, 5, 0, 1, 0),
(57, 13, 9, 2, 3, 0, 1),
(58, 13, 12, 0, 3, 0, 1),
(59, 13, 3, 1, 3, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `touches_utilisateur`
--

CREATE TABLE `touches_utilisateur` (
  `id_touches` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `touche_avancer` varchar(20) DEFAULT 'Z',
  `touche_reculer` varchar(20) DEFAULT 'S',
  `touche_gauche` varchar(20) DEFAULT 'Q',
  `touche_droite` varchar(20) DEFAULT 'D',
  `touche_sauter` varchar(20) DEFAULT 'ESPACE',
  `touche_tirer` varchar(20) DEFAULT 'CLIC GAUCHE',
  `touche_recharger` varchar(20) DEFAULT 'R',
  `touche_sprint` varchar(20) DEFAULT 'SHIFT'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `touches_utilisateur`
--

INSERT INTO `touches_utilisateur` (`id_touches`, `id_utilisateur`, `touche_avancer`, `touche_reculer`, `touche_gauche`, `touche_droite`, `touche_sauter`, `touche_tirer`, `touche_recharger`, `touche_sprint`) VALUES
(1, 12, 'S', 'S', 'Q', 'D', 'ESPACE', 'CLIC GAUCHE', 'R', 'SHIFT');

-- --------------------------------------------------------

--
-- Structure de la table `tournois`
--

CREATE TABLE `tournois` (
  `id_tournoi` int(11) NOT NULL,
  `nom_tournoi` varchar(100) NOT NULL,
  `nombre_joueurs` int(11) NOT NULL,
  `round_actuel` int(11) DEFAULT '0',
  `phase` varchar(50) DEFAULT 'swiss',
  `vainqueur_participation_id` int(11) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tournois`
--

INSERT INTO `tournois` (`id_tournoi`, `nom_tournoi`, `nombre_joueurs`, `round_actuel`, `phase`, `vainqueur_participation_id`, `date_creation`) VALUES
(1, 'us', 19, 0, 'swiss', NULL, '2026-05-06 10:25:56'),
(2, 'EA', 2, 0, 'swiss', NULL, '2026-05-06 10:25:56'),
(3, 'EAS', 2, 0, 'swiss', NULL, '2026-05-06 10:25:56'),
(4, 'EASs', 2, 0, 'swiss', NULL, '2026-05-06 10:25:56'),
(5, '32', 2, 0, 'swiss', NULL, '2026-05-06 10:25:56'),
(6, 'FIF', 2, 3, 'swiss', NULL, '2026-05-06 10:25:56'),
(7, '"éf"', 2, 2, 'swiss', NULL, '2026-05-06 10:25:56'),
(8, 'Test', 8, 6, 'finale', NULL, '2026-05-06 10:25:56'),
(9, 'Test2', 8, 5, 'termine', NULL, '2026-05-06 10:25:56'),
(10, 'test3', 7, 5, 'swiss_termine', NULL, '2026-05-06 10:25:56'),
(11, 'ldp', 10, 5, 'swiss_termine', NULL, '2026-05-06 10:25:56'),
(12, 'B', 2, 1, 'swiss', NULL, '2026-05-06 10:32:17'),
(13, 'TEST123', 12, 5, 'termine', NULL, '2026-05-06 11:05:43');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'joueur',
  `total_kill` int(11) DEFAULT '0',
  `total_death` int(11) DEFAULT '0',
  `total_victoires` int(11) DEFAULT '0',
  `total_defaites` int(11) DEFAULT '0',
  `email_open_arena` varchar(255) DEFAULT NULL,
  `mail_open_arena_cree` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `pseudo`, `email`, `mot_de_passe`, `role`, `total_kill`, `total_death`, `total_victoires`, `total_defaites`, `email_open_arena`, `mail_open_arena_cree`) VALUES
(1, 'paul', 'carette', 'pc', 'benjamin.palfray@groupe-esigelec.org', '$2y$10$Vv/blUk9tRLl.CO5KIrbgeNZpHdMqDY1t7eeN62CNaHaKGUcfGwKW', 'joueur', 0, 0, 2, 1, NULL, 0),
(2, 'e', 'a', 'ea', 'benjami.palfray@groupe-esigelec.org', '$2y$10$HMRc8yebWmZiuO4iaOw5MOmpQgzMrULJp8dmKmiqtlBtvTuY2Lqzq', 'joueur', 0, 0, 2, 0, NULL, 0),
(3, 'admin', 'admin', 'admin', 'admin@groupe-esigelec.org', '$2y$10$pmMWQWAegGT49NkplAhJb.8K2T5ODUt2EvxpeVjnnyV/WP.tsdKKS', 'admin', 0, 0, 0, 0, NULL, 0),
(4, 'Dupont', 'Lucas', 'lucas', 'lucas@test.fr', '$2y$10$pY1FlpcrLhC3gFPNV3dlFOLjAGn4aoUmB2KiO6HxZcwhQwpPqNbsO', 'joueur', 0, 0, 0, 3, NULL, 0),
(5, 'Martin', 'Emma', 'emma', 'emma@test.fr', '$2y$10$pY1FlpcrLhC3gFPNV3dlFOLjAGn4aoUmB2KiO6HxZcwhQwpPqNbsO', 'joueur', 0, 0, 1, 0, NULL, 0),
(6, 'Bernard', 'Hugo', 'hugo', 'hugo@test.fr', '$2y$10$pY1FlpcrLhC3gFPNV3dlFOLjAGn4aoUmB2KiO6HxZcwhQwpPqNbsO', 'joueur', 0, 0, 2, 0, NULL, 0),
(7, 'Petit', 'Sarah', 'sarah', 'sarah@test.fr', '$2y$10$pY1FlpcrLhC3gFPNV3dlFOLjAGn4aoUmB2KiO6HxZcwhQwpPqNbsO', 'joueur', 0, 0, 0, 0, NULL, 0),
(8, 'Moreau', 'Nathan', 'nathan', 'nathan@test.fr', '$2y$10$pY1FlpcrLhC3gFPNV3dlFOLjAGn4aoUmB2KiO6HxZcwhQwpPqNbsO', 'joueur', 0, 0, 2, 3, NULL, 0),
(9, 'Leroy', 'Chloe', 'chloe', 'chloe@test.fr', '$2y$10$pY1FlpcrLhC3gFPNV3dlFOLjAGn4aoUmB2KiO6HxZcwhQwpPqNbsO', 'joueur', 0, 0, 0, 1, NULL, 0),
(10, 'Roux', 'Tom', 'tom', 'tom@test.fr', '$2y$10$pY1FlpcrLhC3gFPNV3dlFOLjAGn4aoUmB2KiO6HxZcwhQwpPqNbsO', 'joueur', 0, 0, 1, 2, NULL, 0),
(11, 'Fournier', 'Julie', 'julie', 'julie@test.fr', '$2y$10$pY1FlpcrLhC3gFPNV3dlFOLjAGn4aoUmB2KiO6HxZcwhQwpPqNbsO', 'joueur', 0, 0, 0, 0, NULL, 0),
(12, 'Palfray', 'Benjamin', 'Benjoulah', 'benjoulah@gmai.com', '$2y$10$FqrdPVqIz.wePnAmy72jc.p.mSefat7gi.ufMquTqlDyP9wk1Ya3C', 'joueur', 0, 0, 0, 0, NULL, 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `matchs`
--
ALTER TABLE `matchs`
  ADD PRIMARY KEY (`id_match`),
  ADD KEY `id_tournoi` (`id_tournoi`),
  ADD KEY `id_participation_1` (`id_participation_1`),
  ADD KEY `id_participation_2` (`id_participation_2`);

--
-- Index pour la table `participations`
--
ALTER TABLE `participations`
  ADD PRIMARY KEY (`id_participation`),
  ADD UNIQUE KEY `id_tournoi` (`id_tournoi`,`id_utilisateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `touches_utilisateur`
--
ALTER TABLE `touches_utilisateur`
  ADD PRIMARY KEY (`id_touches`);

--
-- Index pour la table `tournois`
--
ALTER TABLE `tournois`
  ADD PRIMARY KEY (`id_tournoi`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `matchs`
--
ALTER TABLE `matchs`
  MODIFY `id_match` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;
--
-- AUTO_INCREMENT pour la table `participations`
--
ALTER TABLE `participations`
  MODIFY `id_participation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT pour la table `touches_utilisateur`
--
ALTER TABLE `touches_utilisateur`
  MODIFY `id_touches` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `tournois`
--
ALTER TABLE `tournois`
  MODIFY `id_tournoi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `matchs`
--
ALTER TABLE `matchs`
  ADD CONSTRAINT `matchs_ibfk_1` FOREIGN KEY (`id_tournoi`) REFERENCES `tournois` (`id_tournoi`) ON DELETE CASCADE,
  ADD CONSTRAINT `matchs_ibfk_2` FOREIGN KEY (`id_participation_1`) REFERENCES `participations` (`id_participation`) ON DELETE CASCADE,
  ADD CONSTRAINT `matchs_ibfk_3` FOREIGN KEY (`id_participation_2`) REFERENCES `participations` (`id_participation`) ON DELETE CASCADE;

--
-- Contraintes pour la table `participations`
--
ALTER TABLE `participations`
  ADD CONSTRAINT `participations_ibfk_1` FOREIGN KEY (`id_tournoi`) REFERENCES `tournois` (`id_tournoi`) ON DELETE CASCADE,
  ADD CONSTRAINT `participations_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
