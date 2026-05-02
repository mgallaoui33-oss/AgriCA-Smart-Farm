-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 02 mai 2026 à 15:55
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_config`
--

-- --------------------------------------------------------

--
-- Structure de la table `sensor_logs`
--

CREATE TABLE `sensor_logs` (
  `id` int(11) NOT NULL,
  `user_phone` varchar(8) DEFAULT NULL,
  `humidity_level` int(11) DEFAULT NULL,
  `pump_status` enum('ON','OFF') DEFAULT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sensor_logs`
--

INSERT INTO `sensor_logs` (`id`, `user_phone`, `humidity_level`, `pump_status`, `log_time`) VALUES
(1, '94858266', NULL, 'ON', '2026-05-02 13:15:41'),
(2, '94858266', NULL, 'OFF', '2026-05-02 13:15:42'),
(3, '94858266', NULL, 'ON', '2026-05-02 13:15:44'),
(4, '94858266', NULL, 'ON', '2026-05-02 13:17:27'),
(5, '94858266', NULL, 'OFF', '2026-05-02 13:17:28'),
(6, '94858266', NULL, 'ON', '2026-05-02 13:19:33'),
(7, '94858266', NULL, 'ON', '2026-05-02 13:19:35'),
(8, '94858266', NULL, 'ON', '2026-05-02 13:19:36'),
(9, '94858266', NULL, 'ON', '2026-05-02 13:19:39'),
(10, '94858266', NULL, 'ON', '2026-05-02 13:19:52'),
(11, '94858266', NULL, 'ON', '2026-05-02 13:22:20'),
(12, '94858266', NULL, 'ON', '2026-05-02 13:45:54'),
(13, '94858266', NULL, 'OFF', '2026-05-02 13:45:59'),
(14, '94858266', NULL, 'ON', '2026-05-02 13:47:12');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(8) NOT NULL,
  `farm_location` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `fullname`, `phone`, `farm_location`, `password`, `created_at`) VALUES
(1, 'Malek Gallaoui', '23192012', 'sidi bou zide', '$2y$10$GICqLwToxDPhXY5qRPnoGunsQG0dma/XsZ/Ece2m.nmYwy9RQpFYG', '2026-05-02 12:52:37'),
(2, 'mouhamed gallaoui', '94860457', 'bouhajla', '$2y$10$2peq0T0EKvxoths6YWTjlOP5govOYdffKKpgA4d2yrgZial59t5BK', '2026-05-02 12:59:33'),
(3, 'anas', '94860455', 'bouhajla', '$2y$10$qaoq65XDxxUBeodr4/q6nOeqjuFe5s42ek2pGc8RAwyqvAo/FcD8G', '2026-05-02 13:00:39'),
(4, 'habib', '94858266', 'bouhajla', '$2y$10$S0cpsGaflKYd87UNcZYe1er6umPbUB2kY9wmDNokdcXR4gUTm/6rW', '2026-05-02 13:03:15');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_phone` (`user_phone`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  ADD CONSTRAINT `sensor_logs_ibfk_1` FOREIGN KEY (`user_phone`) REFERENCES `users` (`phone`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
