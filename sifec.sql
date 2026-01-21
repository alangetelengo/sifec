-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 17, 2026 at 03:01 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sifec`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_demande_document`
--

CREATE TABLE `detail_demande_document` (
  `code_detail_demande_document` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_demande_document` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_otp` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lien_telechargement` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation_lien` datetime NOT NULL DEFAULT '2026-01-05 14:33:56',
  `duree_validite` smallint NOT NULL DEFAULT '31',
  `statut_lien` enum('actif','expiré') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `nombre_telechargement` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"66238b04-2810-44f4-9436-1200304115a1\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"command\":\"O:36:\\\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\\":12:{s:40:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000to\\\";s:13:\\\"+242066835332\\\";s:45:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000content\\\";s:119:\\\"M.(Mme) MAMPOUELE, un registre de NAISSANCE numero R.A.N01 provenance MAIRIE DE MAKELEKELE est en attente de validation\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1767798420, 1767798420),
(2, 'default', '{\"uuid\":\"6a173e14-5783-44c2-b0ce-0ac7211ac49b\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\",\"command\":\"O:45:\\\"Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\\":15:{s:55:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000tribunal\\\";s:9:\\\"MAMPOUELE\\\";s:60:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000type_registre\\\";s:9:\\\"NAISSANCE\\\";s:60:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000code_registre\\\";s:7:\\\"R.A.N01\\\";s:50:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000cec\\\";s:20:\\\"MAIRIE DE MAKELEKELE\\\";s:49:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000to\\\";s:25:\\\"alangetelengo87@gmail.com\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1767798420, 1767798420),
(3, 'default', '{\"uuid\":\"50e4cfed-4070-40e2-840a-55136d80ca2c\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"command\":\"O:36:\\\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\\":12:{s:40:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000to\\\";s:13:\\\"+242066835332\\\";s:45:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000content\\\";s:97:\\\"M (Mme) MAMPOUELE Liane Marlise, votre code pour parapher le registre numero R.A.N01 est 67798503\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1767798504, 1767798504),
(4, 'default', '{\"uuid\":\"6e7b936b-b9e5-4f65-abc8-b443bca4ba5e\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\",\"command\":\"O:47:\\\"Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\\":14:{s:57:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000tribunal\\\";s:23:\\\"MAMPOUELE Liane Marlise\\\";s:52:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000otp\\\";s:8:\\\"67798503\\\";s:62:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000code_registre\\\";s:8:\\\"R.A.N_01\\\";s:51:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000to\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1767798504, 1767798504),
(5, 'default', '{\"uuid\":\"66eefc82-8fd8-478f-a7a0-f255bd2e2c30\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"command\":\"O:36:\\\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\\":12:{s:40:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000to\\\";s:13:\\\"+242066835332\\\";s:45:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000content\\\";s:97:\\\"M (Mme) MAMPOUELE Liane Marlise, votre code pour parapher le registre numero R.A.N01 est 68221399\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768221404, 1768221404),
(6, 'default', '{\"uuid\":\"1f86621e-1a50-40b2-88d8-e6193a8932ef\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\",\"command\":\"O:47:\\\"Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\\":14:{s:57:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000tribunal\\\";s:23:\\\"MAMPOUELE Liane Marlise\\\";s:52:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000otp\\\";s:8:\\\"68221399\\\";s:62:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000code_registre\\\";s:8:\\\"R.A.N_01\\\";s:51:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000to\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768221404, 1768221404),
(7, 'default', '{\"uuid\":\"7e56fa5d-ce8d-4fe7-a862-950a2390c06a\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"command\":\"O:36:\\\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\\":12:{s:40:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000to\\\";s:13:\\\"+242066835332\\\";s:45:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000content\\\";s:137:\\\"M.(Mme) MAMPOUELE, un registre de DECES numero R.A.D02 provenance POMPES FUNEBRES MUNICIPALES DE BRAZZAVILLE est en attente de validation\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768543329, 1768543329),
(8, 'default', '{\"uuid\":\"aec0adfc-02a6-4844-a6b2-c721016e847f\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\",\"command\":\"O:45:\\\"Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\\":15:{s:55:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000tribunal\\\";s:9:\\\"MAMPOUELE\\\";s:60:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000type_registre\\\";s:5:\\\"DECES\\\";s:60:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000code_registre\\\";s:7:\\\"R.A.D02\\\";s:50:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000cec\\\";s:42:\\\"POMPES FUNEBRES MUNICIPALES DE BRAZZAVILLE\\\";s:49:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\CreationRegistreJob\\u0000to\\\";s:25:\\\"alangetelengo87@gmail.com\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768543329, 1768543329),
(9, 'default', '{\"uuid\":\"211f92a4-530d-4e66-9391-6e60091d6311\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"command\":\"O:36:\\\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\\":12:{s:40:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000to\\\";s:13:\\\"+242066835332\\\";s:45:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000content\\\";s:97:\\\"M (Mme) MABIALA Landry Gauthier, votre code pour parapher le registre numero R.A.D02 est 68543432\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768543434, 1768543434),
(10, 'default', '{\"uuid\":\"0e13872b-bf8e-47ed-b08a-2dd1a74f9638\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\",\"command\":\"O:47:\\\"Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\\":14:{s:57:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000tribunal\\\";s:23:\\\"MABIALA Landry Gauthier\\\";s:52:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000otp\\\";s:8:\\\"68543432\\\";s:62:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000code_registre\\\";s:8:\\\"R.A.D_02\\\";s:51:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationRegistreJob\\u0000to\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768543434, 1768543434),
(11, 'default', '{\"uuid\":\"cc5f7f7f-6e24-40e2-92c1-bf4068909622\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"command\":\"O:36:\\\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\\":12:{s:40:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000to\\\";s:13:\\\"+242066835332\\\";s:45:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000content\\\";s:81:\\\"M (Mme) MALONGA, votre code pour valider l\'acte de deces AD_00000001 est 68543997\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768544007, 1768544007),
(12, 'default', '{\"uuid\":\"2905fc20-1f52-4bd3-96a5-f3412c8c4aa1\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\",\"command\":\"O:48:\\\"Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\\":15:{s:73:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000directeur_pompe_funebre\\\";s:15:\\\"MALONGA Alfonse\\\";s:72:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000code_declaration_deces\\\";N;s:56:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000nombre\\\";i:1;s:58:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000code_otp\\\";s:8:\\\"68543997\\\";s:52:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000to\\\";N;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768544007, 1768544007),
(13, 'default', '{\"uuid\":\"e5286e22-5488-4ca7-bc8a-c3bf6d2c686c\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\",\"command\":\"O:36:\\\"Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\\":12:{s:40:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000to\\\";s:13:\\\"+242066835332\\\";s:45:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\SendSmsJob\\u0000content\\\";s:234:\\\"M.(Mme) KOUBAKI Bernard prince, l\'acte de la declaration de deces AD_00000001 de KOUBAKI Laure marie,  dont vous etes declarant est disponible,priere de vous rapprocher du centre d\'etat civil POMPES FUNEBRES MUNICIPALES DE BRAZZAVILLE\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768544034, 1768544034),
(14, 'default', '{\"uuid\":\"06c74d71-5d33-4925-a277-4aafaa649feb\",\"displayName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\",\"command\":\"O:48:\\\"Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\\":15:{s:73:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000directeur_pompe_funebre\\\";s:15:\\\"MALONGA Alfonse\\\";s:72:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000code_declaration_deces\\\";N;s:56:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000nombre\\\";i:1;s:58:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000code_otp\\\";s:8:\\\"68543997\\\";s:52:\\\"\\u0000Modules\\\\Notification\\\\Jobs\\\\ValidationacteDecesJob\\u0000to\\\";s:21:\\\"directeurpf@gmail.com\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1768544034, 1768544034);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_09_09_000003_create_tr_type_categorie_ins_table', 1),
(2, '2014_09_09_000004_create_tr_pompes_funebres_table', 1),
(3, '2014_10_01_000000_create_departement_table', 1),
(4, '2014_10_01_000000_create_tr_type_localite_table', 1),
(5, '2014_10_01_00001_create_tr_commune_table', 1),
(6, '2014_10_01_00002_create_tr_district_table', 1),
(7, '2014_10_01_00004_create_tr_communaute_urbaine_table', 1),
(8, '2014_10_01_00005_create_tr_arrondissement_table', 1),
(9, '2014_10_09_000001_create_tr_localisation_table', 1),
(10, '2014_10_09_000001_create_tr_localite_table', 1),
(11, '2014_10_09_000002_create_tr_nationalite_table', 1),
(12, '2014_10_09_000003_create_tr_fonction_table', 1),
(13, '2014_10_09_000004_create_tr_profession_table', 1),
(14, '2014_10_09_000005_create_tr_cour_appel_table', 1),
(15, '2014_10_09_000005_create_tr_tribunal_table', 1),
(16, '2014_10_09_000005_create_tr_type_cec_table', 1),
(17, '2014_10_09_000005_create_tr_type_institution_table', 1),
(18, '2014_10_09_000006_create_tr_institution_table', 1),
(19, '2014_10_09_000007_create_tr_module_table', 1),
(20, '2014_10_09_000008_create_tr_fonctionnalite_table', 1),
(21, '2014_10_09_000009_create_tr_identification_personne_table', 1),
(22, '2014_10_09_000010_create_users_table', 1),
(23, '2014_10_09_000011_create_password_resets_table', 1),
(24, '2014_10_09_000012_create_failed_jobs_table', 1),
(25, '2014_10_09_000014_create_tr_lieu_survenance_table', 1),
(26, '2014_10_09_000015_create_tr_regime_table', 1),
(27, '2014_10_09_000016_create_tr_situation_matrimoniale_table', 1),
(28, '2014_10_09_000017_create_tr_filiation_table', 1),
(29, '2014_10_09_000018_create_tr_type_document_table', 1),
(30, '2014_10_09_000019_create_tr_religion_table', 1),
(31, '2014_10_09_000020_create_tr_cause_deces_table', 1),
(32, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(33, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(34, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(35, '2016_06_01_000004_create_oauth_clients_table', 1),
(36, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(37, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(38, '2022_09_27_100143_create_tr_mouvement_table', 1),
(39, '2022_09_27_230934_create_t_document_table', 1),
(40, '2022_09_28_094520_create_t_declaration_deces_table', 1),
(41, '2022_09_28_103131_create_t_declaration_naissance_table', 1),
(42, '2022_10_03_095531_create_t_mouvement_deces_table', 1),
(43, '2022_10_03_104302_create_t_mouvement_naissance_table', 1),
(44, '2022_10_17_210901_create_t_personne_situation_matrimoniale_table', 1),
(45, '2022_10_22_200031_create_tr_type_registre_table', 1),
(46, '2022_10_22_200215_create_tr_option_mariage_table', 1),
(47, '2022_10_22_200215_create_tr_registre_table', 1),
(48, '2022_10_22_202013_create_t_acte_naissance_table', 1),
(49, '2022_10_25_174605_create_t_acte_deces_table', 1),
(50, '2022_11_26_164712_create_tr_canal_table', 1),
(51, '2022_11_26_165023_create_tr_sms_providers_table', 1),
(52, '2022_11_26_165401_create_t_api_headers_table', 1),
(53, '2022_11_26_165908_create_t_api_params_table', 1),
(54, '2022_11_26_170209_create_t_action_table', 1),
(55, '2022_11_26_170210_create_t_sms_templates_table', 1),
(56, '2022_12_01_154723_create_jobs_table', 1),
(57, '2022_12_15_205237_create_tr_type_acte_table', 1),
(58, '2022_12_17_114338_create_paiement_details_table', 1),
(59, '2022_12_19_113436_create_detail_demande_document_table', 1),
(60, '2023_01_23_110900_create_contact_personne_table', 1),
(61, '2023_03_30_073703_create_t_user_arrondissement_table', 1),
(62, '2023_04_26_084058_create_t_declaration_mariage_table', 1),
(63, '2023_04_26_084059_create_t_mouvement_mariage_table', 1),
(64, '2023_05_19_200937_create_t_signature_mariage_table', 1),
(65, '2023_05_25_080259_create_t_acte_mariage_table', 1),
(66, '2023_07_21_121756_create_t_residence_personne_table', 1),
(67, '2023_08_08_084204_create_tr_type_extrait_table', 1),
(68, '2023_08_08_084205_create_t_livret_famille_table', 1),
(69, '2023_08_08_084229_create_t_detail_livret_table', 1),
(70, '2023_11_20_134235_create_mobile_money_transaction_details_table', 1),
(71, '2023_12_19_220125_create_t_ins_user_localite_table', 1),
(72, '2024_01_01_000000_create_t_mouvement_rectification_table', 1),
(73, '2024_01_15_203119_create_t_feuillet_registre_table', 1),
(74, '2024_02_15_102837_create_t_retrait_acte_table', 1),
(75, '2024_07_31_144728_create_notifications_table', 1),
(76, '2024_08_02_125325_create_t_copie_table', 1),
(77, '2024_08_02_131458_create_t_extrait_table', 1),
(78, '2024_10_07_134714_create_paiement_documents_table', 1),
(79, '2024_10_22_205502_create_tr_type_document_demande_table', 1),
(80, '2024_10_22_205618_create_t_demande_document_table', 1),
(81, '2024_10_22_205619_add_code_demande_document_foreign_key_to_paiement_details_table', 1),
(82, '2024_10_22_205620_add_code_demande_document_foreign_key_to_detail_demande_document_table', 1),
(83, '2024_10_22_205741_create_t_tarification_table', 1),
(84, '2024_10_22_205907_create_t_paiement_document_table', 1),
(85, '2025_01_16_000000_add_two_factor_authentication_to_tr_user_table', 1),
(86, '2025_01_16_000001_create_user_audit_trail_table', 1),
(87, '2025_03_18_231655_create_tr_type_jugement_table', 1),
(88, '2025_03_19_163333_create_t_jugement_table', 1),
(89, '2025_03_28_172051_create_tr_type_requisition_table', 1),
(90, '2025_03_29_105344_create_t_requisition_table', 1),
(91, '2025_04_09_125523_add_column_to_tr_localite_table', 1),
(92, '2025_04_09_125707_add_column_to_tr_type_localite_table', 1),
(93, '2025_05_13_160039_create_t_rectification_table', 1),
(94, '2025_05_13_160040_add_code_rectification_foreign_key_to_t_mouvement_rectification_table', 1),
(95, '2025_05_13_162156_create_tr_rubrique_table', 1),
(96, '2025_05_13_164705_create_t_detail_rectification_table', 1),
(97, '2025_07_10_145647_create_t_mouvement_dossier_table', 1),
(98, '2025_07_18_000018_add_code_institution_destinataire_to_t_declaration_deces_table', 1),
(99, '2025_07_21_094938_add_champ_pour_enfant_abandonne_to_t_declaration_naissance_table', 1),
(100, '2025_08_11_085653_add_column_to_t_declaration_naissance_table', 1),
(101, '2025_08_26_153821_add_code_pompe_funebre_on_tr_institution_table', 1),
(102, '2025_08_26_162721_add_foreign_code_institution_destinataire_to_t_declaration_deces', 1),
(103, '2025_09_05_124751_add_piece_fields_to_t_declaration_mariage_table', 1),
(104, '2025_09_06_004238_remove_code_jugement_and_code_requisition_from_t_declaration_mariage_table', 1),
(105, '2025_09_06_090521_recreate_acte_mariage_table', 1),
(106, '2026_01_05_135031_add_code_jugement_foreign_key_to_t_declaration_naissance_table', 1),
(107, '2026_01_13_135746_remove_supprimer_from_tr_localite_table', 2),
(108, '2026_01_14_162304_add_deleted_at_to_tr_localite_table', 2),
(109, '2026_01_14_231300_migrate_obsolete_localite_to_code_localite', 3),
(110, '2026_01_14_231258_remove_obsolete_localite_fields_from_tr_institution_table', 4),
(111, '2026_01_14_162954_add_deleted_at_to_tr_localite_table_if_not_exists', 5),
(112, '2026_01_15_110041_remove_redundant_fields_from_tr_type_institution_table', 5),
(113, '2026_01_15_110649_remove_code_type_cec_from_tr_institution_table', 5),
(114, '2026_01_15_150756_replace_supprimer_with_soft_deletes_in_tr_cause_deces_table', 5),
(115, '2026_01_15_151243_replace_supprimer_with_soft_deletes_in_tr_religion_table', 5),
(116, '2026_01_15_151328_replace_supprimer_with_soft_deletes_in_tr_nationalite_table', 5),
(117, '2026_01_15_195700_rename_approuver_to_declarant_approuver_in_t_declaration_deces_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_money_transaction_details`
--

CREATE TABLE `mobile_money_transaction_details` (
  `id` bigint UNSIGNED NOT NULL,
  `code_paiement_document` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` enum('AM','MOMO','OTHER') COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `channel_payment_ref` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','successful','failed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_id`, `notifiable_type`, `cui`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('214de3b0-d078-47ac-a15d-8c1eb27d0c56', 'Modules\\Notification\\Notifications\\ActeDecesAValiderNotification', 'USR_00000005', 'App\\Models\\User', NULL, '{\"message\":\"Un acte de d\\u00e9c\\u00e8s (AD_00000001) est disponible pour la validation.\",\"observation\":\"Acte de d\\u00e9c\\u00e8s produit avec succ\\u00e8s\",\"url\":\"http:\\/\\/sifec\\/acteDeces\\/AD_00000001\\/print\\/acte\"}', NULL, '2026-01-16 05:13:54', '2026-01-16 05:13:54'),
('225ee021-131c-4c0f-bea7-d16bbf1723fe', 'Modules\\Notification\\Notifications\\DeclarationEnvoyeeCentreNotification', 'USR_00000011', 'App\\Models\\User', NULL, '{\"message\":\"D\\u00e9claration de d\\u00e9c\\u00e8s envoy\\u00e9e au centre d\'\\u00e9tat civil pour KOUBAKI Laure marie.\",\"code_declaration\":\"CDD_00000001\",\"personne\":\"KOUBAKI Laure marie\",\"url\":\"http:\\/\\/sifec\\/declarationDeces\\/CDD_00000001\\/show\",\"action\":\"envoy\\u00e9e\",\"institution_type\":\"centre d\'\\u00e9tat civil\",\"document_type\":null,\"document_details\":null}', '2026-01-15 19:12:54', '2026-01-15 19:10:37', '2026-01-15 19:12:54'),
('69e6e54b-90cb-49ee-8e0b-effb3d358d77', 'Modules\\Notification\\Notifications\\ActeDecesAValiderNotification', 'USR_00000029', 'App\\Models\\User', NULL, '{\"message\":\"Un acte de d\\u00e9c\\u00e8s (AD_00000001) est disponible pour la validation.\",\"observation\":\"Acte de d\\u00e9c\\u00e8s g\\u00e9n\\u00e9r\\u00e9 et en attente de la signature de l\'officier d\'\\u00e9tat civil\",\"url\":\"http:\\/\\/sifec\\/acteDeces\\/AD_00000001\\/print\\/acte\"}', '2026-01-16 05:12:15', '2026-01-16 05:05:50', '2026-01-16 05:12:15'),
('715b8439-db3f-498c-91d4-a7b0c6e7f907', 'Modules\\Notification\\Notifications\\ActeAValiderNotification', 'USR_00000009', 'App\\Models\\User', NULL, '{\"message\":\"Un acte de naissance (2202601BZVBVCOM0001) est disponible pour la validation.\",\"observation\":\"Acte de naissance g\\u00e9n\\u00e9r\\u00e9 et en attente de la signature de l\'officier d\'\\u00e9tat civil\",\"url\":\"http:\\/\\/sifec\\/acteNaissance\\/2202601BZVBVCOM0001\\/print\\/acte\"}', NULL, '2026-01-15 18:06:20', '2026-01-15 18:06:20'),
('71d0bf6a-a9dc-404a-9df2-50fe168caa60', 'Modules\\Notification\\Notifications\\ActeAValiderNotification', 'USR_00000003', 'App\\Models\\User', NULL, '{\"message\":\"Un acte de naissance (2202601BZVBVCOM0001) est disponible pour la validation.\",\"observation\":\"Acte de naissance g\\u00e9n\\u00e9r\\u00e9 et en attente de la signature de l\'officier d\'\\u00e9tat civil\",\"url\":\"http:\\/\\/sifec\\/acteNaissance\\/2202601BZVBVCOM0001\\/print\\/acte\"}', '2026-01-16 20:41:22', '2026-01-15 18:06:20', '2026-01-16 20:41:22'),
('89dc5472-24d2-4242-ad47-46e6bb001cb1', 'Modules\\Notification\\Notifications\\DeclarationEnvoyeeCentreNotification', 'USR_00000029', 'App\\Models\\User', NULL, '{\"message\":\"D\\u00e9claration de d\\u00e9c\\u00e8s envoy\\u00e9e au centre d\'\\u00e9tat civil pour KOUBAKI Laure marie.\",\"code_declaration\":\"CDD_00000001\",\"personne\":\"KOUBAKI Laure marie\",\"url\":\"http:\\/\\/sifec\\/declarationDeces\\/CDD_00000001\\/show\",\"action\":\"envoy\\u00e9e\",\"institution_type\":\"centre d\'\\u00e9tat civil\",\"document_type\":null,\"document_details\":null}', '2026-01-16 05:12:15', '2026-01-15 19:10:37', '2026-01-16 05:12:15'),
('939c63c4-ac6d-4700-9885-4d31635678da', 'Modules\\Notification\\Notifications\\ActeDecesAValiderNotification', 'USR_00000011', 'App\\Models\\User', NULL, '{\"message\":\"Un acte de d\\u00e9c\\u00e8s (AD_00000001) est disponible pour la validation.\",\"observation\":\"Acte de d\\u00e9c\\u00e8s g\\u00e9n\\u00e9r\\u00e9 et en attente de la signature de l\'officier d\'\\u00e9tat civil\",\"url\":\"http:\\/\\/sifec\\/acteDeces\\/AD_00000001\\/print\\/acte\"}', '2026-01-16 05:10:36', '2026-01-16 05:05:50', '2026-01-16 05:10:36'),
('d029c840-d86a-4f88-87e9-f0ec1154f6ec', 'Modules\\Notification\\Notifications\\DeclarationEnvoyeeCentreNotification', 'USR_00000029', 'App\\Models\\User', NULL, '{\"message\":\"D\\u00e9claration de d\\u00e9c\\u00e8s envoy\\u00e9e au centre d\'\\u00e9tat civil pour KOUBAKI Laure marie.\",\"code_declaration\":\"CDD_00000001\",\"personne\":\"KOUBAKI Laure marie\",\"url\":\"http:\\/\\/sifec\\/declarationDeces\\/CDD_00000001\\/show\",\"action\":\"envoy\\u00e9e\",\"institution_type\":\"centre d\'\\u00e9tat civil\",\"document_type\":null,\"document_details\":null}', '2026-01-16 05:12:15', '2026-01-15 18:27:27', '2026-01-16 05:12:15'),
('d5e5f1b9-0a24-4da9-943a-61fb1004631d', 'Modules\\Notification\\Notifications\\DeclarationEnvoyeeCentreNotification', 'USR_00000003', 'App\\Models\\User', NULL, '{\"message\":\"D\\u00e9claration envoy\\u00e9e au centre d\'\\u00e9tat civil.\",\"code_declaration\":\"CDN_00000001\",\"personne\":\"MAVOUNGOU Princesse gloire\",\"url\":\"http:\\/\\/sifec\\/declarationNaissance\\/CDN_00000001\\/show\",\"action\":\"envoy\\u00e9e\",\"institution_type\":\"centre d\'\\u00e9tat civil\",\"document_type\":null,\"document_details\":null}', '2026-01-15 16:18:17', '2026-01-15 16:17:03', '2026-01-15 16:18:17'),
('e42e788d-abf8-4c93-b466-3309c6ff9cd9', 'Modules\\Notification\\Notifications\\DeclarationEnvoyeeCentreNotification', 'USR_00000009', 'App\\Models\\User', NULL, '{\"message\":\"D\\u00e9claration envoy\\u00e9e au centre d\'\\u00e9tat civil.\",\"code_declaration\":\"CDN_00000001\",\"personne\":\"MAVOUNGOU Princesse gloire\",\"url\":\"http:\\/\\/sifec\\/declarationNaissance\\/CDN_00000001\\/show\",\"action\":\"envoy\\u00e9e\",\"institution_type\":\"centre d\'\\u00e9tat civil\",\"document_type\":null,\"document_details\":null}', NULL, '2026-01-15 16:17:03', '2026-01-15 16:17:03'),
('f9bc8965-48e6-4f76-8ff8-3c2bbc324890', 'Modules\\Notification\\Notifications\\DeclarationEnvoyeeCentreNotification', 'USR_00000011', 'App\\Models\\User', NULL, '{\"message\":\"D\\u00e9claration de d\\u00e9c\\u00e8s envoy\\u00e9e au centre d\'\\u00e9tat civil pour KOUBAKI Laure marie.\",\"code_declaration\":\"CDD_00000001\",\"personne\":\"KOUBAKI Laure marie\",\"url\":\"http:\\/\\/sifec\\/declarationDeces\\/CDD_00000001\\/show\",\"action\":\"envoy\\u00e9e\",\"institution_type\":\"centre d\'\\u00e9tat civil\",\"document_type\":null,\"document_details\":null}', '2026-01-15 18:28:26', '2026-01-15 18:27:27', '2026-01-15 18:28:26');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paiement_details`
--

CREATE TABLE `paiement_details` (
  `id` bigint UNSIGNED NOT NULL,
  `payer_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_code` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `x_reference_id` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_demande_document` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` double NOT NULL,
  `payment_methode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_col_1` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_col_2` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut_payment` enum('success','failed','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paiement_documents`
--

CREATE TABLE `paiement_documents` (
  `code_paiement_document` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_document` enum('ACTE','DUPLICATA','COPIE','EXTRAIT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_paiement` date DEFAULT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_arrondissement`
--

CREATE TABLE `tr_arrondissement` (
  `code_arrondissement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_arrondissement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude_arrondissement` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude_arrondissement` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_commune` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_arrondissement`
--

INSERT INTO `tr_arrondissement` (`code_arrondissement`, `lib_arrondissement`, `longitude_arrondissement`, `latitude_arrondissement`, `code_commune`, `supprimer`, `created_at`, `updated_at`) VALUES
('ARR_0001', 'FOUNDOU-FOUNDOU', NULL, NULL, 'COM_0001', 0, NULL, NULL),
('ARR_0002', 'YOULOU-POUNGUI', NULL, NULL, 'COM_0001', 0, NULL, NULL),
('ARR_0003', 'BOUALI', NULL, NULL, 'COM_0002', 0, NULL, NULL),
('ARR_0004', 'ITSIBOU', NULL, NULL, 'COM_0002', 0, NULL, NULL),
('ARR_0005', 'SIBITI', NULL, NULL, 'COM_0003', 0, NULL, NULL),
('ARR_0006', 'LUMUMBA', NULL, NULL, 'COM_0004', 0, NULL, NULL),
('ARR_0007', 'MVOUMVOU', NULL, NULL, 'COM_0004', 0, NULL, NULL),
('ARR_0008', 'TIE-TIE', NULL, NULL, 'COM_0004', 0, NULL, NULL),
('ARR_0009', 'LOANDJILI', NULL, NULL, 'COM_0004', 0, NULL, NULL),
('ARR_0010', 'MONGO-POUKOU', NULL, NULL, 'COM_0004', 0, NULL, NULL),
('ARR_0011', 'NGOYO', NULL, NULL, 'COM_0004', 0, NULL, NULL),
('ARR_0012', 'DJAMBALA', NULL, NULL, 'COM_0005', 0, NULL, NULL),
('ARR_0013', 'KINKALA', NULL, NULL, 'COM_0006', 0, NULL, NULL),
('ARR_0014', 'KINTELE', NULL, NULL, 'COM_0007', 0, NULL, NULL),
('ARR_0015', 'IMPFONDO', NULL, NULL, 'COM_0008', 0, NULL, NULL),
('ARR_0016', 'OWANDO', NULL, NULL, 'COM_0009', 0, NULL, NULL),
('ARR_0017', 'OYO', NULL, NULL, 'COM_0010', 0, NULL, NULL),
('ARR_0018', 'EWO', NULL, NULL, 'COM_0011', 0, NULL, NULL),
('ARR_0019', 'NZALANGOYE', NULL, NULL, 'COM_0012', 0, NULL, NULL),
('ARR_0020', 'LOANGO', NULL, NULL, 'COM_0013', 0, NULL, NULL),
('ARR_0021', 'MAKELEKELE', NULL, NULL, 'COM_0014', 0, NULL, NULL),
('ARR_0022', 'BACONGO', NULL, NULL, 'COM_0014', 0, NULL, NULL),
('ARR_0023', 'POTO-POTO', NULL, NULL, 'COM_0014', 0, NULL, NULL),
('ARR_0024', 'MOUNGALI', NULL, NULL, 'COM_0014', 0, NULL, NULL),
('ARR_0025', 'OUENZE', NULL, NULL, 'COM_0014', 0, NULL, NULL),
('ARR_0026', 'TALANGAI', NULL, NULL, 'COM_0014', 0, NULL, NULL),
('ARR_0027', 'MFILOU', NULL, NULL, 'COM_0014', 0, NULL, NULL),
('ARR_0028', 'MADIBOU', NULL, NULL, 'COM_0014', 0, NULL, NULL),
('ARR_0029', 'DJIRI', NULL, NULL, 'COM_0014', 0, NULL, NULL),
('ARR_0030', 'MADINGOU', NULL, NULL, 'COM_0015', 0, NULL, NULL),
('ARR_0031', 'MWANA-NTO', NULL, NULL, 'COM_0016', 0, NULL, NULL),
('ARR_0032', 'SOULOUKA', NULL, NULL, 'COM_0016', 0, NULL, NULL),
('ARR_0033', 'DOLISIE', NULL, NULL, 'COM_0001', 0, NULL, NULL),
('ARR_0034', 'POKOLA', NULL, NULL, 'COM_0017', 0, NULL, NULL),
('ARR_0035', 'MBINDJO', NULL, NULL, 'COM_0012', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_canal`
--

CREATE TABLE `tr_canal` (
  `code_canal` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_canal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_cause_deces`
--

CREATE TABLE `tr_cause_deces` (
  `code_cause_deces` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_cause_deces` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_cause_deces`
--

INSERT INTO `tr_cause_deces` (`code_cause_deces`, `lib_cause_deces`, `created_at`, `updated_at`, `deleted_at`) VALUES
('CD_0001', 'Paludisme', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0002', 'Paludisme grave forme anémique', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0003', 'Anémie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0004', 'Anémie sévère', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0005', 'Diarrhée', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0006', 'Diarrhée vomissement', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0007', 'Diarrhée Gastro-entérite aigue', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0008', 'Sepsis', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0009', 'Septicemie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0010', 'Sepsis sévère', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0011', 'Hypertension artérielle', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0012', 'Accident vasculaire cérébral (A.V.C)', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0013', 'Arrêt cardiaque', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0014', 'Arrêt cardio respiratoire', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0015', 'Etat de choc septique', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0016', 'Hyperglycémie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0017', 'Hypoglycémie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0018', 'Diabète', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0019', 'Cellulite', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0020', 'Carie dentaire', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0021', 'Corona virus', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0022', 'Blessure', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0023', 'Traumatisme', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0024', 'Traumatisme crânien', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0025', 'Traumatisme pied', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0026', 'Traumatisme jambe', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0027', 'Œdème aigue des poumons (O.A.P)', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0028', 'Infection respiratoire aigüe (L.R.A)', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0029', 'Otite aigue ou chronique', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0030', 'Méningite', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0031', 'Toxoplamose', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0032', 'Sida', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0033', 'Tuberculose', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0034', 'Insuffisance rénale', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0035', 'Insufisance cardiaque', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0036', 'Cancer du poumon', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0037', 'Cancer du foie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0038', 'Cancer de l\'estomac', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0039', 'Cancer du sein', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0040', 'Cancer de l\'utérus', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0041', 'Malnutrition', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0042', 'Asphyxie périnatale', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0043', 'Malformation congénitale', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0044', 'Fièvre jaune', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0045', 'Pneumopathie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0046', 'Pneumonie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0047', 'Infection foeto-maternelle', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0048', 'Hemorragie de Ja délivrance', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0049', 'Cancer primitif du foie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0050', 'Cancer de l’estomac', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0051', 'Cancer de la prostate', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0052', 'Eclampsie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0053', 'Dystocie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0054', 'Hematome retro placentaire', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0055', 'Coagulation intraveineuse disséminée (CIVD)', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0056', 'Fausse couche provoquée', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0057', 'Hemorragie digestive', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0058', 'Embolie amniotique', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0059', 'Rupture utérine', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0060', 'Souffrance cérébrale', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0061', 'Hemorragie post-partum', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0062', 'Placenta praevia', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CD_0063', 'Cyrrhose du foie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('CDCES_0010', 'opala4', '2026-01-15 14:46:16', '2026-01-15 14:46:34', '2026-01-15 14:46:34');

-- --------------------------------------------------------

--
-- Table structure for table `tr_communaute_urbaine`
--

CREATE TABLE `tr_communaute_urbaine` (
  `code_communaute_urbaine` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_communaute_urbaine` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude_communaute_urbaine` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude_communaute_urbaine` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_district` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_communaute_urbaine`
--

INSERT INTO `tr_communaute_urbaine` (`code_communaute_urbaine`, `lib_communaute_urbaine`, `longitude_communaute_urbaine`, `latitude_communaute_urbaine`, `code_district`, `created_at`, `updated_at`, `deleted_at`) VALUES
('COMUR_0001', 'DIVENIE', NULL, NULL, 'DIS_0002', NULL, NULL, NULL),
('COMUR_0002', 'KIBANGOU', NULL, NULL, 'DIS_0003', NULL, NULL, NULL),
('COMUR_0003', 'KIMONGO', NULL, NULL, 'DIS_0004', NULL, NULL, NULL),
('COMUR_0004', 'MAKABANA', NULL, NULL, 'DIS_0007', NULL, NULL, NULL),
('COMUR_0005', 'MBINDA', NULL, NULL, 'DIS_0009', NULL, NULL, NULL),
('COMUR_0006', 'KOMONO', NULL, NULL, 'DIS_0016', NULL, NULL, NULL),
('COMUR_0007', 'ZANAGA', NULL, NULL, 'DIS_0017', NULL, NULL, NULL),
('COMUR_0008', 'TCHIAMBA NZASSI', NULL, NULL, 'DIS_0020', NULL, NULL, NULL),
('COMUR_0009', 'ABALA', NULL, NULL, 'DIS_0021', NULL, NULL, NULL),
('COMUR_0010', 'GAMBOMA', NULL, NULL, 'DIS_0023', NULL, NULL, NULL),
('COMUR_0011', 'LEKANA', NULL, NULL, 'DIS_0026', NULL, NULL, NULL),
('COMUR_0012', 'NGO', NULL, NULL, 'DIS_0025', NULL, NULL, NULL),
('COMUR_0013', 'OLLOMBO', NULL, NULL, 'DIS_0027', NULL, NULL, NULL),
('COMUR_0014', 'ONGONGNI', NULL, NULL, 'DIS_0029', NULL, NULL, NULL),
('COMUR_0015', 'BOKO', NULL, NULL, 'DIS_0041', NULL, NULL, NULL),
('COMUR_0016', 'IGNIE', NULL, NULL, 'DIS_0034', NULL, NULL, NULL),
('COMUR_0017', 'KIBOUENDE', NULL, NULL, 'DIS_0043', NULL, NULL, NULL),
('COMUR_0018', 'KINDAMBA', NULL, NULL, 'DIS_0043', NULL, NULL, NULL),
('COMUR_0019', 'MINDOULI', NULL, NULL, 'DIS_0040', NULL, NULL, NULL),
('COMUR_0020', 'NGABE', NULL, NULL, 'DIS_0039', NULL, NULL, NULL),
('COMUR_0021', 'BETOU', NULL, NULL, 'DIS_0045', NULL, NULL, NULL),
('COMUR_0022', 'DONGOU', NULL, NULL, 'DIS_0047', NULL, NULL, NULL),
('COMUR_0023', 'ENYELLE', NULL, NULL, 'DIS_0048', NULL, NULL, NULL),
('COMUR_0024', 'EPENA', NULL, NULL, 'DIS_0049', NULL, NULL, NULL),
('COMUR_0025', 'BOUNDJI', NULL, NULL, 'DIS_0060', NULL, NULL, NULL),
('COMUR_0026', 'MAKOUA', NULL, NULL, 'DIS_0054', NULL, NULL, NULL),
('COMUR_0027', 'TCHICAPIKA', NULL, NULL, 'DIS_0055', NULL, NULL, NULL),
('COMUR_0028', 'MOSSAKA', NULL, NULL, 'DIS_0058', NULL, NULL, NULL),
('COMUR_0029', 'LOUKOLELA', NULL, NULL, 'DIS_0059', NULL, NULL, NULL),
('COMUR_0030', 'ETOUMBI', NULL, NULL, 'DIS_0062', NULL, NULL, NULL),
('COMUR_0031', 'KELLE', NULL, NULL, 'DIS_0064', NULL, NULL, NULL),
('COMUR_0032', 'OKOYO', NULL, NULL, 'DIS_0067', NULL, NULL, NULL),
('COMUR_0033', 'MOKEKO', NULL, NULL, 'DIS_0069', NULL, NULL, NULL),
('COMUR_0034', 'SEMBE', NULL, NULL, 'DIS_0072', NULL, NULL, NULL),
('COMUR_0035', 'SOUANKE', NULL, NULL, 'DIS_0073', NULL, NULL, NULL),
('COMUR_0036', 'HINDA', NULL, NULL, 'DIS_0074', NULL, NULL, NULL),
('COMUR_0037', 'MADINGO-KAYES', NULL, NULL, 'DIS_0077', NULL, NULL, NULL),
('COMUR_0038', 'MVOUTI', NULL, NULL, 'DIS_0076', NULL, NULL, NULL),
('COMUR_0039', 'BOUANSA', NULL, NULL, 'DIS_0088', NULL, NULL, NULL),
('COMUR_0040', 'LOUDIMA', NULL, NULL, 'DIS_0084', NULL, NULL, NULL),
('COMUR_0041', 'LOUTETE', NULL, NULL, 'DIS_0088', NULL, NULL, NULL),
('COMUR_0042', 'MABOMBO', NULL, NULL, 'DIS_0085', NULL, NULL, NULL),
('COMUR_0043', 'MOUYONDZI', NULL, NULL, 'DIS_0088', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_commune`
--

CREATE TABLE `tr_commune` (
  `code_commune` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_commune` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sigle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude_commune` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude_commune` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_departement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_commune`
--

INSERT INTO `tr_commune` (`code_commune`, `lib_commune`, `sigle`, `longitude_commune`, `latitude_commune`, `code_departement`, `created_at`, `updated_at`, `deleted_at`) VALUES
('COM_0001', 'DOLISIE', NULL, NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0002', 'MOSSENDJO', NULL, NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0003', 'SIBITI', NULL, NULL, NULL, 'DPT_0009', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0004', 'POINTE-NOIRE', NULL, NULL, NULL, 'DPT_0002', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0005', 'DJAMBALA', NULL, NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0006', 'KINKALA', NULL, NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0007', 'KINTELE', NULL, NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0008', 'IMPFONDO', NULL, NULL, NULL, 'DPT_0003', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0009', 'OWANDO', NULL, NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0010', 'OYO', NULL, NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0011', 'EWO', NULL, NULL, NULL, 'DPT_0005', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0012', 'OUESSO', NULL, NULL, NULL, 'DPT_0004', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0013', 'LOANGO', NULL, NULL, NULL, 'DPT_0012', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0014', 'BRAZZAVILLE', NULL, NULL, NULL, 'DPT_0001', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0015', 'MADINGOU', NULL, NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0016', 'NKAYI', NULL, NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('COM_0017', 'POKOLA', NULL, NULL, NULL, 'DPT_0004', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_cour_appel`
--

CREATE TABLE `tr_cour_appel` (
  `code_cour_appel` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_cour_appel` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_departement`
--

CREATE TABLE `tr_departement` (
  `code_departement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_departement` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude_departement` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude_departement` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_departement`
--

INSERT INTO `tr_departement` (`code_departement`, `lib_departement`, `longitude_departement`, `latitude_departement`, `supprimer`, `created_at`, `updated_at`) VALUES
('DPT_0001', 'BRAZZAVILLE', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0002', 'POINTE-NOIRE', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0003', 'LIKOUALA', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0004', 'SANGHA', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0005', 'CUVETTE-OUEST', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0006', 'CUVETTE', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0007', 'PLATEAUX', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0008', 'POOL', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0009', 'LEKOUMOU', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0010', 'BOUENZA', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0011', 'NIARI', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('DPT_0012', 'KOUILOU', NULL, NULL, 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39');

-- --------------------------------------------------------

--
-- Table structure for table `tr_district`
--

CREATE TABLE `tr_district` (
  `code_district` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_district` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude_district` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude_district` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_departement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_district`
--

INSERT INTO `tr_district` (`code_district`, `lib_district`, `longitude_district`, `latitude_district`, `code_departement`, `created_at`, `updated_at`, `deleted_at`) VALUES
('DIS_0001', 'BANDA', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0002', 'DIVENIE', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0003', 'KIBANGOU', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0004', 'KIMONGO', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0005', 'LONDELA -KAYES', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0006', 'LOUVAKOU', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0007', 'MAKABANA', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0008', 'MAYOKO', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0009', 'MBINDA', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0010', 'MOUNGOUNDOU-NORD', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0011', 'MOUNGOUNDOU-SUD', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0012', 'MOUTAMBA', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0013', 'NYANGA', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0014', 'YAYA', NULL, NULL, 'DPT_0011', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0015', 'SIBITI', NULL, NULL, 'DPT_0009', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0016', 'KOMONO', NULL, NULL, 'DPT_0009', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0017', 'ZANAGA', NULL, NULL, 'DPT_0009', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0018', 'BAMBAMA', NULL, NULL, 'DPT_0009', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0019', 'MAYEYE', NULL, NULL, 'DPT_0009', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0020', 'TCHAMBA NZASSI', NULL, NULL, 'DPT_0002', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0021', 'ABALA', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0022', 'ALLEMBE', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0023', 'DJAMBALA', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0024', 'GAMBOMA', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0025', 'NGO', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0026', 'LEKANA', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0027', 'OLLOMBO', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0028', 'MAKOTIMPOKO', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0029', 'ONGOGNI', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0030', 'MBON', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0031', 'MPOUYA', NULL, NULL, 'DPT_0007', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0032', 'LOUINGUI', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0033', 'LOUMO', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0034', 'IGNE', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0035', 'MAYAMA', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0036', 'VINDZA', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0037', 'MBANZA-NDOUNGA', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0038', 'KIMBA', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0039', 'NGABE', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0040', 'MINDOULI', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0041', 'BOKO', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0042', 'KINKALA', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0043', 'KINDAMBA', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0044', 'GOMA TSE-TSE', NULL, NULL, 'DPT_0008', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0045', 'BETOU', NULL, NULL, 'DPT_0003', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0046', 'BOUANELA', NULL, NULL, 'DPT_0003', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0047', 'DONGOU', NULL, NULL, 'DPT_0003', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0048', 'ENYELLE', NULL, NULL, 'DPT_0003', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0049', 'EPENA', NULL, NULL, 'DPT_0003', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0050', 'IMPFONDO', NULL, NULL, 'DPT_0003', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0051', 'LIRANGA', NULL, NULL, 'DPT_0003', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0052', 'OWANDO', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0053', 'OYO', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0054', 'MAKOUA', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0055', 'TCHICAPIKA', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0056', 'NGOKO', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0057', 'NTOKOU', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0058', 'MOSSAKA', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0059', 'LOUKOLELA', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0060', 'BOUNDJI', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0061', 'BOKOMA', NULL, NULL, 'DPT_0006', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0062', 'ETOUMBI', NULL, NULL, 'DPT_0005', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0063', 'EWO', NULL, NULL, 'DPT_0005', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0064', 'KELLE', NULL, NULL, 'DPT_0005', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0065', 'MBAMA', NULL, NULL, 'DPT_0005', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0066', 'MBOMO', NULL, NULL, 'DPT_0005', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0067', 'OKOYO', NULL, NULL, 'DPT_0005', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0068', 'KABO', NULL, NULL, 'DPT_0004', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0069', 'MOKEKO', NULL, NULL, 'DPT_0004', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0070', 'NGBALA', NULL, NULL, 'DPT_0004', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0071', 'PIKOUNDA', NULL, NULL, 'DPT_0004', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0072', 'SEMBE', NULL, NULL, 'DPT_0004', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0073', 'SOUANKE', NULL, NULL, 'DPT_0004', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0074', 'HINDA', NULL, NULL, 'DPT_0012', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0075', 'KAKAMOEKA', NULL, NULL, 'DPT_0012', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0076', 'MVOUTI', NULL, NULL, 'DPT_0012', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0077', 'MADINGO-KAYES', NULL, NULL, 'DPT_0012', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0078', 'NZAMBI', NULL, NULL, 'DPT_0012', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0079', 'LOANGO', NULL, NULL, 'DPT_0012', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0080', 'ILE MBAMOU', NULL, NULL, 'DPT_0001', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0081', 'BOKO-SONGHO', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0082', 'KAYES', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0083', 'KINGOUE', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0084', 'LOUDIMA', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0085', 'MABOMBO', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0086', 'MADINGOU', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0087', 'MFOUATI', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0088', 'MOUYONDZI', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0089', 'TSIAKI', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL),
('DIS_0090', 'YAMBA', NULL, NULL, 'DPT_0010', '2023-04-23 09:21:55', '2023-04-23 09:21:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_ff`
--

CREATE TABLE `tr_ff` (
  `code_fonction` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_fonctionnalite` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_ff`
--

INSERT INTO `tr_ff` (`code_fonction`, `code_fonctionnalite`, `created_at`, `updated_at`) VALUES
('FONC_0002', 'FNC_0009', '2022-11-27 09:43:02', '2022-11-27 09:43:02'),
('FONC_0002', 'FNC_0015', '2022-11-27 10:40:05', '2022-11-27 10:40:05'),
('FONC_0002', 'FNC_0020', '2025-04-17 05:43:09', '2025-04-17 05:43:09'),
('FONC_0002', 'FNC_0031', '2023-11-09 12:34:37', '2023-11-09 12:34:37'),
('FONC_0002', 'FNC_0032', '2023-12-21 18:05:10', '2023-12-21 18:05:10'),
('FONC_0002', 'FNC_0033', '2023-12-21 18:05:10', '2023-12-21 18:05:10'),
('FONC_0002', 'FNC_0034', '2023-12-21 18:05:10', '2023-12-21 18:05:10'),
('FONC_0002', 'FNC_0042', '2025-03-24 14:05:14', '2025-03-24 14:05:14'),
('FONC_0003', 'FNC_0009', '2024-01-22 06:18:38', '2024-01-22 06:18:38'),
('FONC_0003', 'FNC_0015', '2024-01-22 06:18:38', '2024-01-22 06:18:38'),
('FONC_0003', 'FNC_0020', '2024-01-22 06:18:38', '2024-01-22 06:18:38'),
('FONC_0004', 'FNC_0009', '2022-11-27 09:43:18', '2022-11-27 09:43:18'),
('FONC_0004', 'FNC_0014', '2022-11-27 11:09:11', '2022-11-27 11:09:11'),
('FONC_0004', 'FNC_0023', '2024-09-05 19:01:49', '2024-09-05 19:01:49'),
('FONC_0004', 'FNC_0025', '2022-11-27 11:09:11', '2022-11-27 11:09:11'),
('FONC_0004', 'FNC_0028', '2022-11-27 11:09:11', '2022-11-27 11:09:11'),
('FONC_0004', 'FNC_0030', '2023-11-09 10:47:26', '2023-11-09 10:47:26'),
('FONC_0004', 'FNC_0032', '2023-12-21 18:04:46', '2023-12-21 18:04:46'),
('FONC_0004', 'FNC_0033', '2023-12-21 18:04:46', '2023-12-21 18:04:46'),
('FONC_0004', 'FNC_0041', '2025-03-21 12:12:10', '2025-03-21 12:12:10'),
('FONC_0005', 'FNC_0008', '2022-11-27 09:43:32', '2022-11-27 09:43:32'),
('FONC_0005', 'FNC_0016', '2023-11-27 07:03:39', '2023-11-27 07:03:39'),
('FONC_0005', 'FNC_0017', '2022-11-27 10:41:19', '2022-11-27 10:41:19'),
('FONC_0005', 'FNC_0019', '2023-11-27 07:03:39', '2023-11-27 07:03:39'),
('FONC_0005', 'FNC_0026', '2023-11-27 07:03:39', '2023-11-27 07:03:39'),
('FONC_0005', 'FNC_0027', '2023-11-27 07:03:39', '2023-11-27 07:03:39'),
('FONC_0005', 'FNC_0028', '2022-11-27 11:05:27', '2022-11-27 11:05:27'),
('FONC_0005', 'FNC_0034', '2023-12-21 18:05:28', '2023-12-21 18:05:28'),
('FONC_0006', 'FNC_0006', '2022-11-27 09:43:49', '2022-11-27 09:43:49'),
('FONC_0006', 'FNC_0012', '2022-11-27 09:51:12', '2022-11-27 09:51:12'),
('FONC_0006', 'FNC_0013', '2022-11-27 10:07:51', '2022-11-27 10:07:51'),
('FONC_0006', 'FNC_0016', '2022-11-27 10:08:08', '2022-11-27 10:08:08'),
('FONC_0006', 'FNC_0034', '2024-09-05 19:02:44', '2024-09-05 19:02:44'),
('FONC_0007', 'FNC_0007', '2022-11-27 09:44:13', '2022-11-27 09:44:13'),
('FONC_0007', 'FNC_0018', '2022-11-27 10:38:58', '2022-11-27 10:38:58'),
('FONC_0007', 'FNC_0026', '2022-11-27 10:38:58', '2022-11-27 10:38:58'),
('FONC_0009', 'FNC_0010', '2022-11-27 09:44:32', '2022-11-27 09:44:32'),
('FONC_0009', 'FNC_0021', '2022-12-02 17:09:11', '2022-12-02 17:09:11'),
('FONC_0009', 'FNC_0022', '2025-03-26 02:00:50', '2025-03-26 02:00:50'),
('FONC_0009', 'FNC_0044', '2025-04-18 01:57:21', '2025-04-18 01:57:21'),
('FONC_0010', 'FNC_0010', '2022-11-27 09:44:44', '2022-11-27 09:44:44'),
('FONC_0010', 'FNC_0022', '2022-12-02 17:01:51', '2022-12-02 17:01:51'),
('FONC_0011', 'FNC_0005', '2022-11-27 09:32:17', '2022-11-27 09:32:17'),
('FONC_0011', 'FNC_0006', '2022-11-27 09:41:58', '2022-11-27 09:41:58'),
('FONC_0011', 'FNC_0007', '2022-11-27 09:41:58', '2022-11-27 09:41:58'),
('FONC_0011', 'FNC_0008', '2022-11-27 09:41:58', '2022-11-27 09:41:58'),
('FONC_0011', 'FNC_0009', '2022-11-27 09:41:58', '2022-11-27 09:41:58'),
('FONC_0011', 'FNC_0010', '2022-11-27 09:41:58', '2022-11-27 09:41:58'),
('FONC_0011', 'FNC_0011', '2022-11-27 09:41:58', '2022-11-27 09:41:58'),
('FONC_0011', 'FNC_0021', '2022-11-27 10:50:17', '2022-11-27 10:50:17'),
('FONC_0011', 'FNC_0022', '2022-11-27 10:50:17', '2022-11-27 10:50:17'),
('FONC_0011', 'FNC_0028', '2024-01-26 05:41:19', '2024-01-26 05:41:19'),
('FONC_0011', 'FNC_0032', '2024-01-26 05:41:19', '2024-01-26 05:41:19'),
('FONC_0011', 'FNC_0033', '2024-01-26 05:41:19', '2024-01-26 05:41:19'),
('FONC_0011', 'FNC_0034', '2024-01-26 05:41:19', '2024-01-26 05:41:19'),
('FONC_0012', 'FNC_0008', '2022-11-27 09:45:28', '2022-11-27 09:45:28'),
('FONC_0012', 'FNC_0020', '2022-11-27 10:56:15', '2022-11-27 10:56:15'),
('FONC_0014', 'FNC_0014', '2023-02-12 05:47:15', '2023-02-12 05:47:15'),
('FONC_0014', 'FNC_0017', '2023-02-12 05:47:15', '2023-02-12 05:47:15'),
('FONC_0014', 'FNC_0024', '2023-02-12 05:47:15', '2023-02-12 05:47:15'),
('FONC_0014', 'FNC_0027', '2023-02-12 05:47:15', '2023-02-12 05:47:15'),
('FONC_0014', 'FNC_0028', '2023-02-12 05:47:15', '2023-02-12 05:47:15'),
('FONC_0014', 'FNC_0030', '2025-04-14 08:51:37', '2025-04-14 08:51:37'),
('FONC_0014', 'FNC_0042', '2025-04-14 08:51:37', '2025-04-14 08:51:37'),
('FONC_0014', 'FNC_0043', '2025-04-14 08:51:37', '2025-04-14 08:51:37'),
('FONC_0017', 'FNC_0009', '2024-01-26 07:44:11', '2024-01-26 07:44:11'),
('FONC_0017', 'FNC_0016', '2024-01-26 07:44:11', '2024-01-26 07:44:11'),
('FONC_0017', 'FNC_0017', '2024-01-26 07:44:11', '2024-01-26 07:44:11'),
('FONC_0017', 'FNC_0026', '2024-01-26 07:44:11', '2024-01-26 07:44:11'),
('FONC_0017', 'FNC_0034', '2024-01-26 07:44:11', '2024-01-26 07:44:11'),
('FONC_0018', 'FNC_0010', '2024-01-26 15:50:45', '2024-01-26 15:50:45'),
('FONC_0018', 'FNC_0016', '2024-01-26 15:50:45', '2024-01-26 15:50:45'),
('FONC_0018', 'FNC_0022', '2024-01-26 15:50:45', '2024-01-26 15:50:45'),
('FONC_0018', 'FNC_0034', '2024-01-26 15:52:19', '2024-01-26 15:52:19'),
('FONC_0019', 'FNC_0012', '2024-02-26 06:01:14', '2024-02-26 06:01:14'),
('FONC_0019', 'FNC_0013', '2024-02-26 06:01:14', '2024-02-26 06:01:14'),
('FONC_0019', 'FNC_0014', '2024-02-26 06:01:14', '2024-02-26 06:01:14'),
('FONC_0019', 'FNC_0016', '2024-02-26 06:01:14', '2024-02-26 06:01:14'),
('FONC_0019', 'FNC_0017', '2024-02-26 06:01:14', '2024-02-26 06:01:14'),
('FONC_0019', 'FNC_0019', '2024-02-26 06:01:14', '2024-02-26 06:01:14'),
('FONC_0019', 'FNC_0023', '2024-02-26 06:01:14', '2024-02-26 06:01:14'),
('FONC_0019', 'FNC_0026', '2024-02-26 06:01:14', '2024-02-26 06:01:14'),
('FONC_0019', 'FNC_0028', '2024-02-26 06:25:24', '2024-02-26 06:25:24'),
('FONC_0019', 'FNC_0032', '2024-01-26 05:19:01', '2024-01-26 05:19:01'),
('FONC_0019', 'FNC_0034', '2024-01-26 05:19:01', '2024-01-26 05:19:01'),
('FONC_0019', 'FNC_0038', '2024-02-26 06:10:49', '2024-02-26 06:10:49'),
('FONC_0020', 'FNC_0006', '2024-06-26 11:54:19', '2024-06-26 11:54:19'),
('FONC_0020', 'FNC_0039', '2024-08-26 19:37:01', '2024-08-26 19:37:01'),
('FONC_0021', 'FNC_0015', '2024-07-02 13:49:41', '2024-07-02 13:49:41'),
('FONC_0021', 'FNC_0020', '2024-07-02 13:49:41', '2024-07-02 13:49:41'),
('FONC_0021', 'FNC_0032', '2024-07-02 13:49:41', '2024-07-02 13:49:41'),
('FONC_0021', 'FNC_0034', '2024-07-02 13:49:41', '2024-07-02 13:49:41'),
('FONC_0021', 'FNC_0038', '2024-07-02 13:49:41', '2024-07-02 13:49:41'),
('FONC_0022', 'FNC_0009', '2024-11-04 12:15:07', '2024-11-04 12:15:07'),
('FONC_0023', 'FNC_0009', '2024-11-04 12:16:36', '2024-11-04 12:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `tr_filiation`
--

CREATE TABLE `tr_filiation` (
  `code_filiation` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_filiation` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_filiation`
--

INSERT INTO `tr_filiation` (`code_filiation`, `lib_filiation`, `supprimer`, `created_at`, `updated_at`) VALUES
('FIL_0001', 'PERE', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('FIL_0002', 'MERE', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('FIL_0003', 'TANTE PATERNELLE', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('FIL_0004', 'TANTE MATERNELLE', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('FIL_0005', 'FRERE', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('FIL_0006', 'SOEUR', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('FIL_0007', 'COUSIN (E)', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('FIL_0008', 'AUTRE', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('FIL_0009', 'ONCLE PATERNEL', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39');

-- --------------------------------------------------------

--
-- Table structure for table `tr_fonction`
--

CREATE TABLE `tr_fonction` (
  `code_fonction` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_fonction` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_fonction`
--

INSERT INTO `tr_fonction` (`code_fonction`, `lib_fonction`, `supprimer`, `created_at`, `updated_at`) VALUES
('FONC_0001', 'Sous préfet', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0002', 'Officier d\'état civil', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0003', 'Officier d\'état civil délégué', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0004', 'Agent mairie', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0005', 'Agent pompes funèbres', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0006', 'Agent formation sanitaire', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0007', 'Agent centre d\'hygiène', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0008', 'Agent tribunal', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0009', 'Président du tribunal', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0010', 'Procureur général', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0011', 'Super administrateur', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0012', 'Directeur pompes funèbres', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0013', 'DGAT', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0014', 'Agent mairie centrale', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0015', 'DEC', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0016', 'Chef de service', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0017', 'Agent bureau d\'enregistrement de décès', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0018', 'Procureur de la République', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0019', 'Agent ambassade', 0, '2024-02-20 06:24:52', '2024-02-20 06:24:52'),
('FONC_0020', 'Agent service de maternité', 0, '2024-06-26 12:51:32', '2024-06-26 12:51:32'),
('FONC_0021', 'Consule', 0, '2024-07-02 14:47:29', '2024-07-02 14:47:29'),
('FONC_0022', 'Gouverneur', 0, '2024-10-28 10:09:19', '2024-10-28 10:09:19'),
('FONC_0023', 'Ministre', 0, '2024-10-28 10:11:34', '2024-10-28 10:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `tr_fonctionnalite`
--

CREATE TABLE `tr_fonctionnalite` (
  `code_fonctionnalite` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_fonctionnalite` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lib_technique` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_fonctionnalite` text COLLATE utf8mb4_unicode_ci,
  `code_fonctionnalite_parent` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_module` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat_fonctionnalite` enum('Activé','Désactivé') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Désactivé',
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_fonctionnalite`
--

INSERT INTO `tr_fonctionnalite` (`code_fonctionnalite`, `lib_fonctionnalite`, `lib_technique`, `description_fonctionnalite`, `code_fonctionnalite_parent`, `code_module`, `etat_fonctionnalite`, `supprimer`, `created_at`, `updated_at`) VALUES
('FNC_0001', 'Gestion des menus', 'module.menus', 'Cette fonction permet à l\'utilisateur connecté de pouvoir administrer les menus si il en a le droit', NULL, 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0002', 'Gestion des actes de naissance', 'module.acteNaissance', 'Permet à un utilisateur d\'accéder aux données de naissance dans le système', NULL, 'MOD_0002', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0003', 'Gestion des actes de décès', 'module.acteDeces', 'Permet à un utilisateur d\'accéder aux données de décès dans le système', NULL, 'MOD_0003', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0004', 'Gestion des utilisateurs', 'module.users', 'Permet à un utilisateur d\'accéder aux données de utilisateurs dans le système', NULL, 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0005', 'Voir menu référentiel', 'module.menus.referentiel', 'Voir le menu référentiel', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0006', 'Voir menu formation sanitaire', 'module.menus.formationSanitaire', 'Voir le menu formation sanitaire', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0007', 'Voir menu centre d\'hygiène', 'module.menus.centreHygiene', 'Voir le menu centre d\'hygiène', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0008', 'Voir menu pompes funèbres', 'module.menus.pompesFunebres', 'Voir le menu pompes funèbres', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0009', 'Voir menu centre d\'état civil', 'module.menus.cec', 'Voir le menu centre d\'état civil', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0010', 'Voir menu tribunal', 'module.menus.tribunal', 'Voir le menu tribunal', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0011', 'Voir menu administration', 'module.menus.administration', 'Voir le menu administration', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0012', 'Créer une déclaration de naissance', 'module.ActeNaissance.declarationNaissance.create', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer une déclaration de naissance si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0013', 'Modifier une déclaration de naissance', 'module.ActeNaissance.declarationNaissance.edit', 'Cette fonction permet à l\'utilisateur connecté de pouvoir modifier une déclaration de naissance si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0014', 'générer un acte de naissance', 'module.acteNaissance.generate', 'Cette fonction permet à l\'utilisateur connecté de pouvoir générer un acte de naissance si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0015', 'Signer  un acte de naissance', 'module.acteNaissance.signature', 'Cette fonction permet à l\'utilisateur connecté de pouvoir signer un acte de naissance si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0016', 'Créer une déclaration de décès', 'module.acteDeces.declarationacteDeces.create', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer une déclaration de décès si il en a le droit', 'FNC_0003', 'MOD_0003', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0017', 'générer un acte de décès', 'module.acteDeces.generate', 'Cette fonction permet à l\'utilisateur connecté de pouvoir générer un acte de décès si il en a le droit', 'FNC_0003', 'MOD_0003', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0018', 'Créer un certificat de constatation de décès', 'module.acteDeces.CCDeces.create', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer un certificat de constatation de décès si il en a le droit', 'FNC_0003', 'MOD_0003', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0019', 'Modifier une déclaration de décès', 'module.acteDeces.declarationacteDeces.edit', 'Cette fonction permet à l\'utilisateur connecté de pouvoir modifier une déclaration de décès si il en a le droit', 'FNC_0003', 'MOD_0003', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0020', 'Signer  un acte de décès', 'module.acteDeces.signature', 'Cette fonction permet à l\'utilisateur connecté de pouvoir signer un acte de décès si il en a le droit', 'FNC_0003', 'MOD_0003', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0021', 'Parapher un registre', 'module.fonctionnalites.parapher', 'Permet à l\'utilisateur de parapher un registre si il en a le droit.', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0022', 'générer une réquisition', 'module.fonctionnalites.requisitions', 'Permet à l\'utilisateur de générer une réquisition si il en a le droit.', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0023', 'Créer un certificat de non inscription de naissance', 'module.acteNaissance.CNINaissance.create', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer un certificat de non inscription de naissance si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0024', 'Créer un certificat de transcription de naissance', 'module.acteNaissance.CTNaissance.create', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer un certificat de transcription de naissance si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0025', 'Créer un certificat de destruction de l\'acte de naissance', 'module.acteNaissance.CDANaissance.create', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer un certificat de destruction de l\'acte de naissance si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0026', 'Créer un certificat de non inscription de décès', 'module.acteDeces.CNIDeces.create', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer un certificat de non inscription de décès si il en a le droit', 'FNC_0003', 'MOD_0003', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0027', 'Créer un certificat de transcription de décès', 'module.acteDeces.CTDeces.create', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer un certificat de transcription de décès si il en a le droit', 'FNC_0003', 'MOD_0003', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0028', 'créer un registre', 'module.registre.create', 'Permet à l\'utilisateur de créer un registre si il en a le droit.', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-04-23 11:21:56', '2023-04-23 11:21:56'),
('FNC_0029', 'Gestion des actes de mariage', 'module.acteMariage', 'Permet à un utilisateur d\'accéder aux données de naissance dans le système', NULL, 'MOD_0004', 'Activé', 0, '2023-11-09 10:45:11', '2023-11-09 10:45:11'),
('FNC_0030', 'générer un acte de mariage', 'module.acteMariage.generate', 'Cette fonction permet à l\'utilisateur connecté de pouvoir générer un acte de mariage si il en a le droit.', 'FNC_0029', 'MOD_0004', 'Activé', 0, '2023-11-09 10:46:59', '2023-11-09 10:46:59'),
('FNC_0031', 'Signer  un acte de mariage', 'module.acteMariage.signature', 'Cette fonction permet à l\'utilisateur connecté de pouvoir signer un acte de mariage si il en a le droit', 'FNC_0029', 'MOD_0004', 'Activé', 0, '2023-11-09 12:34:15', '2023-11-09 12:34:15'),
('FNC_0032', 'Voir menu naissance', 'module.menus.naissance', 'Voir le menu naissance', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-12-21 17:38:34', '2023-12-21 17:38:34'),
('FNC_0033', 'Voir menu mariage', 'module.menus.mariage', 'Voir le menu mariage', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-12-21 17:40:01', '2023-12-21 17:40:01'),
('FNC_0034', 'Voir menu décès', 'module.menus.deces', 'Voir le menu décès', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2023-12-21 17:40:13', '2023-12-21 17:40:13'),
('FNC_0035', 'Viser un acte de naissance', 'module.acteNaissance.viser', 'Cette fonction permet à l\'utilisateur connecté de pouvoir viser un acte de naissance si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2024-01-26 05:15:57', '2024-01-26 05:15:57'),
('FNC_0036', 'viser un acte de décès', 'module.acteDeces.viser', 'Cette fonction permet à l\'utilisateur connecté de pouvoir viser un acte de naissance si il en a le droit', 'FNC_0003', 'MOD_0003', 'Activé', 0, '2024-01-26 05:16:17', '2024-01-26 05:16:17'),
('FNC_0037', 'Viser un acte de mariage', 'module.acteMariage.viser', 'Cette fonction permet à l\'utilisateur connecté de pouvoir viser un acte de naissance si il en a le droit', 'FNC_0029', 'MOD_0004', 'Activé', 0, '2024-01-26 05:16:32', '2024-01-26 05:16:32'),
('FNC_0038', 'Voir menu ambassade', 'module.menus.ambassade', 'Voir le menu d\'ambassade', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2024-02-26 06:08:25', '2024-02-26 06:10:05'),
('FNC_0039', 'Créer une fiche de maternité', 'module.ActeNaissance.declarationNaissance.create.fiche.maternite', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer une fiche de maternité si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2024-08-26 19:29:42', '2024-08-26 19:29:42'),
('FNC_0040', 'Créer une déclaration de naissance d\'un enfant abandonné', 'module.ActeNaissance.declarationNaissance.create.EnfantAbandonne', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer une déclaration de naissance d\'un enfant abandonné si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2024-08-26 19:32:08', '2024-08-26 19:32:08'),
('FNC_0041', 'Créer un certificat de non inscription à base d\'un jugement', 'module.ActeNaissance.certificatNonInscription.create', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer un certificat de non inscription à base d\'un jugement si il en a le droit', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2025-03-21 12:10:24', '2025-03-21 12:10:24'),
('FNC_0042', 'Annuler un acte de naissance à base d\'un jugement', 'module.menus.tribunal.requisition', 'Cette fonction permet à l\'utilisateur connecté de pouvoir créer une réquisition à base d\'un certificat si il en a le droit', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2025-03-24 14:04:47', '2025-03-30 07:55:44'),
('FNC_0043', 'Voir menus mairie centrale', 'module.menus.mairie_centrale', 'Permet à l\'utilisateur qui a l\'autorisation de voir le menus mairie centrale.', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2025-04-14 08:49:05', '2025-04-14 08:49:05'),
('FNC_0044', 'Créer  un jugement d\'autorisation à base d\'un certificat de non inscription', 'module.ActeNaissance.jugementAutorisation.create', 'Permet à l\'utilisateur connecté de créer un jugement', 'FNC_0002', 'MOD_0002', 'Activé', 0, '2025-04-18 01:55:31', '2025-04-18 01:55:31'),
('FNC_0045', 'modification d\'une requisition', 'module.fonctionnalites.requisition.edit', 'Permet à l\'utilisateur de modifier une réquisition si il en a le droit.', 'FNC_0001', 'MOD_0001', 'Activé', 0, '2025-06-01 09:33:35', '2025-06-01 09:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `tr_identification_personne`
--

CREATE TABLE `tr_identification_personne` (
  `code_personne` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sexe` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_localite` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` longtext COLLATE utf8mb4_unicode_ci,
  `telephone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone_parent` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau_instruction` enum('PRIMAIRE','SECONDAIRE NIVEAU I','SECONDAIRE NIVEAU II','SUPERIEUR','NON DECLARE') COLLATE utf8mb4_unicode_ci DEFAULT 'NON DECLARE',
  `code_nationalite` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_profession` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personne_string` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_adoption` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut_personne` enum('VIVANT','DECEDE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'VIVANT',
  `type_date_naissance` enum('EXACTE','ESTIME') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EXACTE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_identification_personne`
--

INSERT INTO `tr_identification_personne` (`code_personne`, `nom`, `prenom`, `sexe`, `date_naissance`, `lieu_naissance`, `code_localite`, `photo`, `telephone`, `telephone_parent`, `adresse`, `niveau_instruction`, `code_nationalite`, `code_profession`, `signature`, `personne_string`, `type_adoption`, `statut_personne`, `type_date_naissance`, `created_at`, `updated_at`) VALUES
('PRS_00000001', 'ACSI', 'Acsi', 'M', NULL, NULL, NULL, NULL, '066835335', NULL, '16,rue laptop Mpila', 'NON DECLARE', 'NAT_0009', 'PROF_0010', NULL, NULL, NULL, 'VIVANT', 'EXACTE', '2023-04-23 12:21:56', '2023-04-23 12:21:56'),
('PRS_00000002', 'ELENGA', 'OPALA', 'M', '1957-11-26', 'EWO', NULL, NULL, '066835338', NULL, '48,rue des combattants MOUNGALI', 'SUPERIEUR', 'NAT_0001', 'PROF_0006', 'signature/MHOxvx1TTmZUXEl0lzrr3HuW3ZxFIFubZXcnKRhU.png', 'BOUKAHENRI1957-11-26EWOMSUPERIEUR', NULL, 'VIVANT', 'EXACTE', '2022-11-27 11:59:48', '2024-01-17 06:36:18'),
('PRS_00000003', 'MBOUNGOU', 'Stéphanie', 'F', '1983-11-18', 'POINTE-NOIRE', NULL, NULL, NULL, NULL, '78,rue NGANGUI MAKELEKELE', 'SUPERIEUR', 'NAT_0001', 'PROF_0006', 'signature/gZdpfYQ56GkHkb8cc7WMruAcYBBuokIlmm4PtkG9.png', 'MBOUNGOUStéphanie1983-11-18POINTE-NOIREFSUPERIEUR', NULL, 'VIVANT', 'EXACTE', '2022-11-27 12:21:50', '2025-07-11 11:44:25'),
('PRS_00000004', 'MILEMBOLO', 'Prisca Nadège', 'F', '1976-11-25', 'BRAZZAVILLE', NULL, NULL, NULL, NULL, '145,rue de la corniche', 'SUPERIEUR', 'NAT_0001', 'PROF_0007', NULL, 'MILEMBOLOPrisca Nadège1976-11-25BRAZZAVILLEFSUPERIEUR', NULL, 'VIVANT', 'EXACTE', '2022-11-27 12:32:13', '2022-11-27 12:32:13'),
('PRS_00000005', 'HOUNOUNOU', 'Sandrine', 'F', '1991-11-25', 'BRAZZAVILLE', NULL, NULL, NULL, NULL, '14,rue LOUZALA', 'SUPERIEUR', 'NAT_0001', 'PROF_0005', 'signature/M9RcblkbrdmlUFDyhL8B3yRTx1VuqinQ4eNt9EP6.png', 'HOUNOUNOUSandrine1991-11-25BRAZZAVILLEFSUPERIEUR', NULL, 'VIVANT', 'EXACTE', '2022-11-27 12:39:57', '2025-05-28 09:17:11'),
('PRS_00000006', 'MAMPOUELE', 'Liane Marlise', 'F', '1957-11-01', 'EWO', NULL, NULL, '066835332', NULL, '35,rue du Marché', 'SUPERIEUR', 'NAT_0001', 'PROF_0006', 'signature/T8IjvjRHWOwD61v3gAwm6obMJq8bP3tDSszMjvzG.png', 'NGAKALA OKOAlbert1957-11-01EWOMSUPERIEUR', NULL, 'VIVANT', 'EXACTE', '2022-11-30 03:26:48', '2024-01-15 09:31:52'),
('PRS_00000007', 'BANTSIMBA', 'DIEUDONNE', 'M', '1956-02-14', 'NIARI', NULL, NULL, '066835334', NULL, '107 ,rue des beaux arts OUENZE', 'SUPERIEUR', 'NAT_0001', 'PROF_0008', 'signature/fxWXp1iG1GgCDmmpb2URhI6peKwNGXmbAKxgAGkn.png', 'BANTSIMBADIEUDONNÉ1956-02-14NIARIMSUPERIEUR', NULL, 'VIVANT', 'EXACTE', '2023-02-14 18:29:00', '2023-11-09 10:53:58'),
('PRS_00000009', 'BONGO', 'Armel', 'M', '1977-06-13', 'BRAZZAVILLE', 'LOC_0026', NULL, '045000000', NULL, '17 rue Massengo MAKELEKELE', NULL, 'NAT_0001', NULL, 'signature/jnGFEgP69LBDO7Sbm0OHO8VnSab2IsMR2YTbqNvo.png', 'BONGOArmelM1977-06-13BRAZZAVILLE', NULL, 'VIVANT', 'EXACTE', '2024-06-23 08:16:46', '2025-03-22 23:09:31'),
('PRS_00000019', 'MABIALA', 'Landry Gauthier', 'M', '1981-06-15', 'POINTE-NOIRE', 'LOC_0016', NULL, '064000000', NULL, '41 rue Massengo Brazzaville', NULL, 'NAT_0001', NULL, 'signature/jnGFEgP69LBDO7Sbm0OHO8VnSab2IsMR2YTbqNvo.png', 'DIRECTEURPOMPES FUNÈBRESM1981-06-15POINTE-NOIRE', NULL, 'VIVANT', 'EXACTE', '2024-06-23 13:18:41', '2025-03-26 05:19:39'),
('PRS_00000020', 'MAVOUNGOU', 'Ruth', 'F', '1989-04-25', 'BRAZZAVILLE', 'LOC_0026', NULL, '044152698', NULL, '120 rue des TATI', NULL, 'NAT_0001', NULL, NULL, 'MAVOUNGOURuthF1989-04-25BRAZZAVILLE', NULL, 'VIVANT', 'EXACTE', '2024-06-23 13:20:44', '2024-06-23 13:20:44'),
('PRS_00000021', 'DINGA OBA', 'sandresse', 'F', '1984-06-11', 'POINTE-NOIRE', 'LOC_0016', NULL, '044155339', NULL, '10 rue Loudima Brazzaville', NULL, 'NAT_0001', NULL, 'signature/zKFzojryYj65ehL9KNwfUUlZe8JDlsPKwOZEzYsd.png', 'BANZOUZIsandresseF1984-06-11POINTE-NOIRE', NULL, 'VIVANT', 'EXACTE', '2024-06-23 13:47:05', '2024-06-23 15:45:57'),
('PRS_00000022', 'PROCUREUR', 'BLAISE', 'M', '1980-06-20', 'BRAZZAVILLE', 'LOC_0026', NULL, '063000000', NULL, '10 rue Loudima OUENZE', NULL, 'NAT_0001', NULL, NULL, 'PRÉSIDENTBLAISEM1980-06-20BRAZZAVILLE', NULL, 'VIVANT', 'EXACTE', '2024-06-23 14:14:48', '2024-06-23 14:14:48'),
('PRS_00000041', 'MOBANDA', 'Armel', 'M', '1982-07-02', 'BRAZZAVILLE', 'LOC_0026', NULL, '065447896', NULL, '17 rue Kabou NGALIEMA', NULL, 'NAT_0001', NULL, NULL, 'MOBANDAArmelM1982-07-02BRAZZAVILLE', NULL, 'VIVANT', 'EXACTE', '2024-07-02 13:32:07', '2024-07-02 13:32:07'),
('PRS_00000045', 'MOKOKI', 'ALBERT', 'M', '1971-07-08', 'POINTE-NOIRE', 'LOC_0016', NULL, '044444444', NULL, '30 rue Kabou NGALIEMA', NULL, 'NAT_0001', NULL, 'signature/CePSyBl2YX0pOvKzCk2UVvxqJzCUTIf8otqTS8Qb.png', 'MOKOKIALBERTM1971-07-08POINTE-NOIRE', NULL, 'VIVANT', 'EXACTE', '2024-07-02 14:11:30', '2024-07-02 14:11:53'),
('PRS_00000087', 'MAYIBANZILUANGA', 'Dieumerci', 'M', '1978-04-11', 'DONGOU', 'LOC_0404', NULL, '060000000', NULL, '30 rue Kabou NGALIEMA', NULL, 'NAT_0001', NULL, 'signature/nWVOkEqEOBfv4Von9mdHtEvcMQ7XYt2kkPVhsBor.png', 'MAYIBANZILUANGADieumerciM1978-04-11DONGOU', NULL, 'VIVANT', 'EXACTE', '2024-10-21 09:40:36', '2024-10-22 21:04:36'),
('PRS_00000089', 'BUMA BASSELE', 'nadege', 'F', '1974-10-01', 'TCHICAPIKA', 'LOC_0424', NULL, '044000000', NULL, '10 rue Kabou NGALIEMA', NULL, 'NAT_0001', NULL, NULL, 'BUMA BASSELEnadegeF1974-10-01TCHICAPIKA', NULL, 'VIVANT', 'EXACTE', '2024-10-21 09:49:38', '2024-10-21 09:49:38'),
('PRS_00000118', 'MBON', 'Albert', 'M', '1959-01-13', 'EWO', 'LOC_0023', NULL, '044164337', NULL, 'Avenue de l’Intendance – Rue Mokeko, Ouénzé Derrière l’ex cinéma Ebina', NULL, 'NAT_0001', NULL, 'signature/mhCR692cv26dceWpNZwZge72QSp4LldGNyicPUES.png', 'MBONAlbertM1959-01-13EWO', NULL, 'VIVANT', 'EXACTE', '2025-01-20 10:14:34', '2025-01-20 10:42:26'),
('PRS_00000334', 'MALONGA', 'Alfonse', 'M', '1972-05-14', 'BRAZZAVILLE', 'LOC_0026', NULL, '044164337', NULL, 'Avenue de l’Intendance – Rue Mokeko, Ouénzé Derrière l’ex cinéma Ebina', NULL, 'NAT_0001', NULL, 'signature/jnGFEgP69LBDO7Sbm0OHO8VnSab2IsMR2YTbqNvo.png', 'MALONGAAlfonseM1972-05-14BRAZZAVILLE', NULL, 'VIVANT', 'EXACTE', '2025-07-01 12:20:18', '2025-07-01 12:20:18'),
('PRS_00000335', 'MAVOUNGOU', 'Romaric florent', 'M', '2002-01-11', 'BRAZZAVILLE', 'LOC_0026', NULL, '044164337', NULL, '25,rue Makoko MAYOMA MAKELEKELE BRAZZAVILLE', 'SUPERIEUR', 'NAT_0001', 'PROF_0006', NULL, 'MAVOUNGOUROMARICFLORENT2002-01-11BRAZZAVILLEM', '', 'VIVANT', 'ESTIME', '2026-01-15 16:13:39', '2026-01-15 16:13:39'),
('PRS_00000336', 'NKOUKA NGOMA', 'Sandrine lucie', 'F', '2002-06-17', 'POINTE-NOIRE', 'LOC_0016', NULL, '044114452', NULL, '25,rue Makoko MAYOMA MAKELEKELE BRAZZAVILLE', 'SUPERIEUR', 'NAT_0001', 'PROF_0083', NULL, 'NKOUKANGOMASANDRINELUCIE2002-06-17POINTE-NOIREF', '', 'VIVANT', 'ESTIME', '2026-01-15 16:13:39', '2026-01-15 16:13:39'),
('PRS_00000337', 'MAVOUNGOU', 'Princesse gloire', 'F', '2026-01-14', 'BRAZZAVILLE', 'LOC_0026', NULL, NULL, NULL, ',    ', 'NON DECLARE', 'NAT_0001', 'PROF_0010', NULL, 'MAVOUNGOUPRINCESSEGLOIRE2026-01-14BRAZZAVILLEF', '', 'VIVANT', 'EXACTE', '2026-01-15 16:13:39', '2026-01-15 16:13:39'),
('PRS_00000338', 'KOUBAKI', 'Laure marie', 'F', '1980-01-25', 'NKAYI', 'LOC_0028', NULL, NULL, NULL, '110,rue Ngobila MARCHE DE OUENZE OUENZE BRAZZAVILLE', 'SUPERIEUR', 'NAT_0001', 'PROF_0017', NULL, 'KOUBAKILAUREMARIE1980-01-25NKAYIF', '', 'DECEDE', 'EXACTE', '2026-01-15 18:18:04', '2026-01-15 18:18:04'),
('PRS_00000339', 'KOUBAKI', 'Bernard prince', 'M', '1951-06-17', 'OWANDO', 'LOC_0021', NULL, '066835332', NULL, '20,avenue Loukolela MAYI POUMA MBINDJO OUESSO', 'NON DECLARE', 'NAT_0001', 'PROF_0085', NULL, 'KOUBAKIBERNARDPRINCE1951-06-17OWANDOM', '', 'VIVANT', 'ESTIME', '2026-01-15 18:18:04', '2026-01-15 18:18:04'),
('PRS_00000340', 'MANIANGUI', 'Simone rose', 'F', '1953-02-25', 'IMPFONDO', 'LOC_0020', NULL, '044125689', NULL, '52,rue Mpika OCH MOUNGALI 3 POTO-POTO BRAZZAVILLE', 'SECONDAIRE NIVEAU I', 'NAT_0001', 'PROF_0024', NULL, 'MANIANGUISIMONEROSE1953-02-25IMPFONDOF', '', 'VIVANT', 'ESTIME', '2026-01-15 18:18:04', '2026-01-15 18:18:04');

-- --------------------------------------------------------

--
-- Table structure for table `tr_institution`
--

CREATE TABLE `tr_institution` (
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_institution` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude_institution` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude_institution` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_institution_parent` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_pompe_funebre` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_type_institution` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_localite` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '1',
  `sceau` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_institution`
--

INSERT INTO `tr_institution` (`code_institution`, `lib_institution`, `longitude_institution`, `latitude_institution`, `code_institution_parent`, `code_pompe_funebre`, `code_type_institution`, `code_localite`, `statut`, `sceau`, `created_at`, `updated_at`, `deleted_at`) VALUES
('INS_0001', 'COUR D\'APPEL DE BRAZZAVILLE', NULL, NULL, NULL, NULL, 'TPINS_0006', 'LOC_0053', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0002', 'COUR D\'APPEL DE POINTE NOIRE', NULL, NULL, NULL, NULL, 'TPINS_0006', 'LOC_0016', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0003', 'COUR D\'APPEL DE DOLISIE', NULL, NULL, NULL, NULL, 'TPINS_0006', 'LOC_0032', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0004', 'COUR D\'APPEL D\'OWANDO', NULL, NULL, NULL, NULL, 'TPINS_0006', 'LOC_0021', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0005', 'COUR D\'APPEL D\'OYO', NULL, NULL, NULL, NULL, 'TPINS_0006', 'LOC_0024', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0006', 'TRIBUNAL DE GRANDE INSTANCE DE BRAZZAVILLE', NULL, NULL, 'INS_0001', NULL, 'TPINS_0001', 'LOC_0053', 1, 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', NULL, NULL, NULL),
('INS_0007', 'TRIBUNAL DE GRANDE INSTANCE DE POINTE NOIRE', NULL, NULL, 'INS_0002', NULL, 'TPINS_0001', 'LOC_0016', 1, 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', NULL, NULL, NULL),
('INS_0008', 'TRIBUNAL DE GRANDE INSTANCE DE DOLISIE', NULL, NULL, 'INS_0003', NULL, 'TPINS_0001', 'LOC_0032', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0009', 'TRIBUNAL DE GRANDE INSTANCE D’OWANDO', NULL, NULL, 'INS_0004', NULL, 'TPINS_0001', 'LOC_0021', 1, 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0010', 'TRIBUNAL DE GRANDE INSTANCE DE MADINGOU', NULL, NULL, 'INS_0003', NULL, 'TPINS_0001', 'LOC_0020', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0011', 'TRIBUNAL DE GRANDE INSTANCE DE OUESSO', NULL, NULL, 'INS_0005', NULL, 'TPINS_0001', 'LOC_0024', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0012', 'TRIBUNAL DE GRANDE INSTANCE D’OYO', NULL, NULL, 'INS_0004', NULL, 'TPINS_0001', 'LOC_0022', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0013', 'TRIBUNAL DE GRANDE INSTANCE DE MOSSAKA', NULL, NULL, 'INS_0004', NULL, 'TPINS_0001', 'LOC_3975', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0014', 'TRIBUNAL DE GRANDE INSTANCE DE SIBITI', NULL, NULL, 'INS_0003', NULL, 'TPINS_0001', 'LOC_0015', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0015', 'TRIBUNAL DE GRANDE INSTANCE DE GAMBOMA', NULL, NULL, 'INS_0004', NULL, 'TPINS_0001', 'LOC_3957', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0016', 'TRIBUNAL DE GRANDE INSTANCE D’IMPFONDO', NULL, NULL, 'INS_0005', NULL, 'TPINS_0001', 'LOC_0020', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0017', 'TRIBUNAL DE GRANDE INSTANCE DE KINKALA', NULL, NULL, 'INS_0001', NULL, 'TPINS_0001', 'LOC_0018', 1, 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', NULL, NULL, NULL),
('INS_0018', 'TRIBUNAL DE GRANDE INSTANCE D\'EWO', NULL, NULL, 'INS_0004', NULL, 'TPINS_0001', 'LOC_0023', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0019', 'TRIBUNAL DE GRANDE INSTANCE DE KINDAMBA', NULL, NULL, 'INS_0001', NULL, 'TPINS_0001', 'LOC_3965', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0020', 'TRIBUNAL DE GRANDE INSTANCE DE MOUYONDZI', NULL, NULL, 'INS_0003', NULL, 'TPINS_0001', 'LOC_3990', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0021', 'TRIBUNAL DE GRANDE INSTANCE DE MOSSENDJO', NULL, NULL, 'INS_0003', NULL, 'TPINS_0001', 'LOC_0014', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0022', 'TRIBUNAL DE GRANDE INSTANCE DE DJAMBALA', NULL, NULL, 'INS_0004', NULL, 'TPINS_0001', 'LOC_0017', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0023', 'TRIBUNAL D’INSTANCE DE BACONGO-MAKELEKELE', NULL, NULL, 'INS_0006', NULL, 'TPINS_0008', 'LOC_0053', 1, 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', NULL, '2023-11-02 19:13:23', NULL),
('INS_0024', 'TRIBUNAL D’INSTANCE DE POTO-POTO-MOUNGALI', NULL, NULL, 'INS_0006', NULL, 'TPINS_0008', 'LOC_0054', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0025', 'TRIBUNAL D’INSTANCE DE OUENZE-TALANGAI', NULL, NULL, 'INS_0006', NULL, 'TPINS_0008', 'LOC_0057', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0026', 'TRIBUNAL D’INSTANCE D’IGNIE', NULL, NULL, 'INS_0017', NULL, 'TPINS_0008', 'LOC_3963', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0027', 'TRIBUNAL D’INSTANCE DE M’FILOU', NULL, NULL, 'INS_0006', NULL, 'TPINS_0008', 'LOC_0058', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0028', 'TRIBUNAL D’INSTANCE DE LISSANGA ILE M’BAMOU', NULL, NULL, 'INS_0006', NULL, 'TPINS_0008', 'LOC_0400', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0029', 'TRIBUNAL D’INSTANCE DE MAKOUA', NULL, NULL, 'INS_0009', NULL, 'TPINS_0008', 'LOC_3973', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0030', 'TRIBUNAL D’INSTANCE DE POKOLA', NULL, NULL, 'INS_0011', NULL, 'TPINS_0008', 'LOC_0064', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0031', 'TRIBUNAL D’INSTANCE DE TIE-TIE', NULL, NULL, 'INS_0002', NULL, 'TPINS_0008', 'LOC_0038', 1, 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', NULL, NULL, NULL),
('INS_0032', 'TRIBUNAL D’INSTANCE DE TCHINOUKA', NULL, NULL, 'INS_0007', NULL, 'TPINS_0008', 'LOC_0039', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0033', 'TRIBUNAL D’INSTANCE DE TCHIAMBA-NZASSI', NULL, NULL, 'INS_0007', NULL, 'TPINS_0008', 'LOC_3955', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0034', 'TRIBUNAL D’INSTANCE DE HINDA', NULL, NULL, 'INS_0007', NULL, 'TPINS_0008', 'LOC_3983', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0035', 'TRIBUNAL D’INSTANCE DE MVOUTI', NULL, NULL, 'INS_0007', NULL, 'TPINS_0008', 'LOC_3985', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0036', 'TRIBUNAL D’INSTANCE DE FOUNDOU FOUNDOU(DOLISIE)', NULL, NULL, 'INS_0008', NULL, 'TPINS_0008', 'LOC_0030', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0037', 'TRIBUNAL D’INSTANCE DE YOULOU POUNGUI (DOLISIE)', NULL, NULL, 'INS_0008', NULL, 'TPINS_0008', 'LOC_0031', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0038', 'TRIBUNAL D’INSTANCE DE ZANAGA', NULL, NULL, 'INS_0022', NULL, 'TPINS_0008', 'LOC_3954', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0039', 'TRIBUNAL D’INSTANCE DE NKAYI', NULL, NULL, 'INS_0010', NULL, 'TPINS_0008', 'LOC_0062', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0040', 'TRIBUNAL D’INSTANCE DE D’OKOYO', NULL, NULL, 'INS_0018', NULL, 'TPINS_0008', 'LOC_3979', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0041', 'TRIBUNAL D’INSTANCE DE MAKABANA', NULL, NULL, 'INS_0008', NULL, 'TPINS_0008', 'LOC_3951', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0042', 'TRIBUNAL D’INSTANCE D’ETOUMBI', NULL, NULL, 'INS_0018', NULL, 'TPINS_0008', 'LOC_3977', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0043', 'TRIBUNAL D’INSTANCE DE KELLE', NULL, NULL, 'INS_0018', NULL, 'TPINS_0008', 'LOC_3978', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0044', 'TRIBUNAL D’INSTANCE D’ABALA', NULL, NULL, 'INS_0022', NULL, 'TPINS_0008', 'LOC_3956', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0045', 'TRIBUNAL D’INSTANCE DE NGO', NULL, NULL, 'INS_0022', NULL, 'TPINS_0008', 'LOC_3959', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0046', 'MAIRIE CENTRALE DE  BRAZZAVILLE', NULL, NULL, 'INS_0006', NULL, 'TPINS_0002', 'LOC_0054', 1, 'sceau/Zzh9s5HAIBv8L6GBAb40vvzCNqjMoxvUNc7fUGRJ.png', NULL, '2023-12-22 04:42:49', NULL),
('INS_0047', 'MAIRIE DE MAKELEKELE', NULL, NULL, 'INS_0023', NULL, 'TPINS_0002', 'LOC_0052', 1, 'sceau/NbCoSavyhjpUIaqeoKmvnXNFgBkQUvOjLJZebFd5.png', NULL, '2023-12-22 04:43:14', NULL),
('INS_0048', 'MAIRIE DE BACONGO', NULL, NULL, 'INS_0023', NULL, 'TPINS_0002', 'LOC_0053', 1, 'sceau/IhExxNxFy7GvU90OmlY734DTiKaREAGTbUfTvziR.png', NULL, '2023-12-22 04:45:06', NULL),
('INS_0049', 'MAIRIE DE POTO-POTO', NULL, NULL, 'INS_0024', NULL, 'TPINS_0002', 'LOC_0054', 1, 'sceau/RD1jKXFB4C1WcN3Ra4BAu7Rxc0eSRs3d0Q4MB9MC.png', NULL, '2023-12-22 04:45:26', NULL),
('INS_0050', 'MAIRIE DE MOUNGALI', NULL, NULL, 'INS_0024', NULL, 'TPINS_0002', 'LOC_0055', 1, 'sceau/w3a09FM91tOMs5x1ytgzG3PUmwFrNDgIJpKEoh74.png', NULL, '2023-12-22 04:46:00', NULL),
('INS_0051', 'MAIRIE DE OUENZE', NULL, NULL, 'INS_0025', NULL, 'TPINS_0002', 'LOC_0056', 1, 'sceau/XtgQjgBTMXqoaaxlZX3O2fA57Py5aKmaKAllZjrF.png', NULL, '2023-12-22 04:46:21', NULL),
('INS_0052', 'MAIRIE DE TALANGAI', NULL, NULL, 'INS_0025', NULL, 'TPINS_0002', 'LOC_0057', 1, 'sceau/lZ5uqBiPPdOcK85pyfZDavgb5kSyaiDJFO0lNsAF.png', NULL, '2023-12-22 04:46:41', NULL),
('INS_0053', 'MAIRIE DE MFILOU', NULL, NULL, 'INS_0027', NULL, 'TPINS_0002', 'LOC_0058', 1, 'sceau/H4CHm80nTXVVWX21FUrMTnJK1J6RUuUM0fiJsqmH.png', NULL, '2023-12-22 04:46:54', NULL),
('INS_0054', 'MAIRIE DE MADIBOU', NULL, NULL, 'INS_0023', NULL, 'TPINS_0002', 'LOC_0059', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0055', 'MAIRIE DE DJIRI', NULL, NULL, 'INS_0025', NULL, 'TPINS_0002', 'LOC_0060', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0056', 'MAIRIE CENTRALE DE DOLISIE', NULL, NULL, 'INS_0008', NULL, 'TPINS_0002', 'LOC_0032', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0057', 'MAIRIE DE FOUNDOU-FOUNDOU', NULL, NULL, 'INS_0036', NULL, 'TPINS_0002', 'LOC_0030', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0058', 'MAIRIE DE YOULOU-POUNGUI', NULL, NULL, 'INS_0037', NULL, 'TPINS_0002', 'LOC_0031', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0059', 'MAIRIE CENTRALE DE MOSSENDJO', NULL, NULL, 'INS_0021', NULL, 'TPINS_0002', 'LOC_0033', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0060', 'MAIRIE DE BOUALI', NULL, NULL, 'INS_0021', NULL, 'TPINS_0002', 'LOC_0033', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0061', 'MAIRIE DE ITSIBOU', NULL, NULL, 'INS_0021', NULL, 'TPINS_0002', 'LOC_0034', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0062', 'MAIRIE CENTRALE DE NKAYI', NULL, NULL, 'INS_0039', NULL, 'TPINS_0002', 'LOC_0062', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0063', 'MAIRIE DE MWANA-NTO', NULL, NULL, 'INS_0039', NULL, 'TPINS_0002', 'LOC_0062', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0064', 'MAIRIE DE SOULOUKA', NULL, NULL, 'INS_0039', NULL, 'TPINS_0002', 'LOC_0063', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0065', 'MAIRIE CENTRALE DE OUESSO', NULL, NULL, 'INS_0011', NULL, 'TPINS_0002', 'LOC_0049', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0066', 'MAIRIE DE NZALANGOYE', NULL, NULL, 'INS_0011', NULL, 'TPINS_0002', 'LOC_0049', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0067', 'MAIRIE DE MBINDJO', NULL, NULL, 'INS_0011', NULL, 'TPINS_0002', 'LOC_0050', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0068', 'MAIRIE CENTRALE DE POINTE-NOIRE', NULL, NULL, 'INS_0007', NULL, 'TPINS_0002', 'LOC_0036', 1, 'sceau/zwEaiL9xqf2e5wtgch39lXSQlCn155NvbWoZUpYO.png', NULL, '2023-12-22 04:47:32', NULL),
('INS_0069', 'MAIRIE DE LUMUMBA', NULL, NULL, 'INS_0031', NULL, 'TPINS_0002', 'LOC_0036', 1, 'sceau/uffSgwXrZhyJTrnZ9GdxOIchuyknRJHUDoHdwa8V.png', NULL, '2023-12-22 04:47:55', NULL),
('INS_0070', 'MAIRIE DE MVOUMVOU', NULL, NULL, 'INS_0031', NULL, 'TPINS_0002', 'LOC_0037', 1, 'sceau/VQD46ZROJ5u70u7qQWdfTetXUzytQzEpITeLWIWG.png', NULL, '2023-12-22 04:48:10', NULL),
('INS_0071', 'MAIRIE DE TIE-TIE', NULL, NULL, 'INS_0031', NULL, 'TPINS_0002', 'LOC_0038', 1, 'sceau/zwEaiL9xqf2e5wtgch39lXSQlCn155NvbWoZUpYO.png', NULL, NULL, NULL),
('INS_0072', 'MAIRIE DE LOANDJILI', NULL, NULL, 'INS_0007', NULL, 'TPINS_0002', 'LOC_0039', 1, 'sceau/aZx1svNDjfSR75seMgRSwFqIcib6ksz82J8Otocg.png', NULL, '2023-12-22 04:49:54', NULL),
('INS_0073', 'MAIRIE DE MONGO MPOUKOU', NULL, NULL, 'INS_0032', NULL, 'TPINS_0002', 'LOC_0040', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0074', 'MAIRIE DE NGOYO', NULL, NULL, 'INS_0032', NULL, 'TPINS_0002', 'LOC_0041', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0075', 'MAIRIE DE SIBITI', NULL, NULL, 'INS_0014', NULL, 'TPINS_0002', 'LOC_0035', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0076', 'MAIRIE DE DJAMBALA', NULL, NULL, 'INS_0022', NULL, 'TPINS_0002', 'LOC_0042', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0077', 'MAIRIE DE KINKALA', NULL, NULL, 'INS_0017', NULL, 'TPINS_0002', 'LOC_0043', 1, 'sceau/eEoNc0KPFhT3zS9SJN13K8mfTKB6vnt8QNUafB0r.png', NULL, '2023-12-26 09:08:32', NULL),
('INS_0078', 'MAIRIE DE KINTELE', NULL, NULL, 'INS_0017', NULL, 'TPINS_0002', 'LOC_0044', 1, 'sceau/gVXy6mGET4kSVMGn7TsUTHZDoSGuDDGNGNSvj3N9.png', NULL, '2023-12-22 04:49:14', NULL),
('INS_0080', 'MAIRIE D\'IMPFONDO', NULL, NULL, 'INS_0016', NULL, 'TPINS_0002', 'LOC_0045', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0081', 'MAIRIE D\'OYO', NULL, NULL, 'INS_0012', NULL, 'TPINS_0002', 'LOC_0047', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0082', 'MAIRIE D\'EWO', NULL, NULL, 'INS_0018', NULL, 'TPINS_0002', 'LOC_0048', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0083', 'CENTRE HOSPITALIER UNIVERSITAIRE DE BRAZZAVILLE', NULL, NULL, 'INS_0049', NULL, 'TPINS_0010', 'LOC_0054', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0084', 'HOPITAL SPECIALISTE MERE ET ENFANT BLANCHE GOMEZ', NULL, NULL, 'INS_0049', NULL, 'TPINS_0011', 'LOC_0054', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0085', 'CLINIQUE MEDICALE SECUREX', NULL, NULL, 'INS_0049', NULL, 'TPINS_0009', 'LOC_0054', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0086', 'HOPITAL GENERAL DE LOANDJILI', NULL, NULL, 'INS_0072', NULL, 'TPINS_0012', 'LOC_0039', 1, 'sceau/7kJMRsqtxubkT0uRLsM5S4QXpUUthCu9lcfvNfkT.jpg', NULL, '2023-12-21 06:22:16', NULL),
('INS_0087', 'HOPITAL GENERAL ADOLPHE CISSE', NULL, NULL, 'INS_0069', NULL, 'TPINS_0012', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0088', 'POLYCLINIQUE MADELEINE GOMBES', NULL, NULL, NULL, NULL, 'TPINS_0013', 'LOC_0039', 1, NULL, '2023-02-14 17:35:05', '2026-01-15 07:57:54', '2026-01-15 07:57:54'),
('INS_0089', 'CENTRE MEDICAL SPECIALISE ENI CONGO', NULL, NULL, 'INS_0069', NULL, 'TPINS_0014', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0090', 'CLINIQUE NETCARE', NULL, NULL, 'INS_0069', NULL, 'TPINS_0009', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0091', 'CLINIQUE GUENIN', NULL, NULL, 'INS_0050', NULL, 'TPINS_0009', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0092', 'CENTRE MEDICAL SPECIALISE DE BRASCO BRAZZAVILLE', NULL, NULL, 'INS_0049', NULL, 'TPINS_0014', 'LOC_0054', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0093', 'HOPITAL DE BASE DE TIE-TIE', NULL, NULL, 'INS_0071', NULL, 'TPINS_0015', 'LOC_0038', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0094', 'HOPITAL DE BASE DE MAKELEKELE', NULL, NULL, 'INS_0047', 'INS_0192', 'TPINS_0015', 'LOC_0052', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0095', 'HOPITAL DE BASE DE M’PISSA BACONGO', NULL, NULL, 'INS_0048', NULL, 'TPINS_0015', 'LOC_0053', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0096', 'HOPITAL DE BASE DE M\'FILOU SINO-CONGOLAIS', NULL, NULL, 'INS_0053', NULL, 'TPINS_0015', 'LOC_0058', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0097', 'HOPITAL DE BASE DE TALANGAI', NULL, NULL, 'INS_0052', NULL, 'TPINS_0015', 'LOC_0057', 1, 'sceau/BEBD5IhY3JXrBPK7Hrj56MorsY1RtGqrhprvE1m5.png', NULL, '2023-12-21 06:21:48', NULL),
('INS_0098', 'CENTRE DE SANTE INTEGRE DE MOUKONDO', NULL, NULL, 'INS_0050', NULL, 'TPINS_0016', 'LOC_0055', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0099', 'CENTRE DE SANTE INTEGRE DE POTO POTO', NULL, NULL, 'INS_0049', NULL, 'TPINS_0016', 'LOC_0054', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0100', 'CENTRE DE SANTE INTEGRE DE OUENZE JANE VIALE', NULL, NULL, 'INS_0051', NULL, 'TPINS_0016', 'LOC_0056', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0101', 'CENTRE DE SANTE INTEGRE DE MADIBOU DJOUE', NULL, NULL, 'INS_0054', NULL, 'TPINS_0016', 'LOC_0059', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0102', 'CENTRE DE SANTE INTEGRE FULBERT YOULOU', NULL, NULL, 'INS_0054', NULL, 'TPINS_0016', 'LOC_0059', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0103', 'CENTRE DE SANTE INTEGRE DE DJIRI', NULL, NULL, 'INS_0055', NULL, 'TPINS_0016', 'LOC_0060', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0104', 'CENTRE DE SANTE INTEGRE D’ILE M’BAMOU LISSANGA', NULL, NULL, NULL, NULL, 'TPINS_0016', 'LOC_0400', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0105', 'HOPITAL CENTRAL DES ARMEES PIERRE MOBENGO', NULL, NULL, 'INS_0049', NULL, 'TPINS_0018', 'LOC_0054', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0106', 'CENTRE DE SANTE INTEGRE OFFIS GENERAL ET LOGISTIQUE', NULL, NULL, 'INS_0050', NULL, 'TPINS_0016', 'LOC_0055', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0107', 'CENTRE MEDICAL SPECIALISE BRASCO POINTE NOIRE', NULL, NULL, 'INS_0069', NULL, 'TPINS_0014', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0108', 'CENTRE DE SANTE INTEGRE NZADASSI', NULL, NULL, NULL, NULL, 'TPINS_0016', 'LOC_0401', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0109', 'CENTRE DE SANTE INTEGRE TANDOU BINZENZE', NULL, NULL, NULL, NULL, 'TPINS_0016', 'LOC_0401', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0110', 'CENTRE DE SANTE INTEGRE TCHINTANZI', NULL, NULL, 'INS_0072', NULL, 'TPINS_0016', 'LOC_0039', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0111', 'CENTRE DE SANTE INTEGRE FOUTA', NULL, NULL, NULL, NULL, 'TPINS_0016', 'LOC_0401', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0112', 'CENTRE DE SANTE INTEGRE MADELIENE MOUISSOU', NULL, NULL, 'INS_0071', NULL, 'TPINS_0016', 'LOC_0038', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0113', 'CENTRE DE SANTE INTEGRE TCHIMAGNI', NULL, NULL, 'INS_0074', NULL, 'TPINS_0016', 'LOC_0041', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0114', 'CENTRE DE SANTE INTEGRE NGOYO', NULL, NULL, 'INS_0074', NULL, 'TPINS_0016', 'LOC_0041', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0115', 'CENTRE DE SANTE INTEGRE JEAN DENIS TCHIMBAKALA', NULL, NULL, 'INS_0074', NULL, 'TPINS_0016', 'LOC_0041', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0116', 'CLINIQUE EVANGELIQUE DE MPAKA', NULL, NULL, 'INS_0074', NULL, 'TPINS_0009', 'LOC_0041', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0117', 'CENTRE DE SANTE INTEGRE DE TCHIMAGNI', NULL, NULL, 'INS_0074', NULL, 'TPINS_0016', 'LOC_0041', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0118', 'CENTRE DE SANTE INTEGRE SIAFOUMOU', NULL, NULL, 'INS_0073', NULL, 'TPINS_0016', 'LOC_0040', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0119', 'CENTRE DE SANTE INTEGRE MAKAYABOU', NULL, NULL, 'INS_0073', NULL, 'TPINS_0016', 'LOC_0040', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0120', 'CENTRE DE SANTE INTEGRE MONT KAMBA', NULL, NULL, 'INS_0072', NULL, 'TPINS_0016', 'LOC_0039', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0121', 'CENTRE DE SANTE INTEGRE MONT SINAI', NULL, NULL, 'INS_0072', NULL, 'TPINS_0016', 'LOC_0039', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0122', 'CENTRE DE SANTE INTEGRE MAHOUATA', NULL, NULL, 'INS_0070', NULL, 'TPINS_0016', 'LOC_0037', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0123', 'CENTRE DE SANTE INTEGRE HYGIENE SCOLAIRE', NULL, NULL, 'INS_0069', NULL, 'TPINS_0016', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0124', 'HOPITAL DE BASE DE DJAMBALA', NULL, NULL, 'INS_0076', NULL, 'TPINS_0015', 'LOC_0017', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0125', 'HOPITAL GENERAL DE DOLISIE', NULL, NULL, 'INS_0056', NULL, 'TPINS_0012', 'LOC_0032', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0126', 'HOPITALE DE REFERENCE DE DOLISIE', NULL, NULL, 'INS_0056', NULL, 'TPINS_0017', 'LOC_0032', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0127', 'CLINIQUE MONT SNAI', NULL, NULL, 'INS_0072', NULL, 'TPINS_0009', 'LOC_0039', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0128', 'CENTRE DE SANTE INTEGRE MOUPEPE', NULL, NULL, NULL, NULL, 'TPINS_0016', 'LOC_0027', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0129', 'CENTRE DE SANTE INTEGRE BACONGO 1', NULL, NULL, 'INS_0048', NULL, 'TPINS_0016', 'LOC_0053', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0130', 'HOPITAL DE BASE DE MADINGOU', NULL, NULL, 'INS_0048', NULL, 'TPINS_0016', 'LOC_0053', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0131', 'HOPITAL DE BASE DE MADINGOU', NULL, NULL, NULL, NULL, 'TPINS_0015', 'LOC_0020', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0132', 'HOPITAL GENERAL EDITH LUCIE BONGO ONDIMBA (CREPIT) OYO', NULL, NULL, NULL, NULL, 'TPINS_0012', 'LOC_0029', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0133', 'HOPITAL GENERAL DE DJIRI NKOMBO', NULL, NULL, 'INS_0055', NULL, 'TPINS_0012', 'LOC_0060', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0134', 'CLINIQUE ELIKIA', NULL, NULL, 'INS_0050', NULL, 'TPINS_0009', 'LOC_0055', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0135', 'CENTRE DE SANTE INTEGRE DE TERINKYO', NULL, NULL, 'INS_0047', NULL, 'TPINS_0016', 'LOC_0052', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0136', 'CLINIQUE MEDICO-CHIRURGICALE COGEMO', NULL, NULL, 'INS_0049', NULL, 'TPINS_0009', 'LOC_0054', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0137', 'CLINIQUE MUNICIPALE ALBERT LEYONO', NULL, NULL, 'INS_0051', NULL, 'TPINS_0009', 'LOC_0056', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0138', 'CENTRE DE SANTE INTEGRE MARIEN NGOUABI', NULL, NULL, 'INS_0052', NULL, 'TPINS_0016', 'LOC_0057', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0139', 'CENTRE DE SANTE INTEGRE MAMAN MBOUALE', NULL, NULL, 'INS_0052', NULL, 'TPINS_0016', 'LOC_0057', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0140', 'HOPITAL MILITAIRE DE POINTE-NOIRE', NULL, NULL, 'INS_0069', NULL, 'TPINS_0018', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0141', 'CENTRE DE SANTE INTEGRE DE LUMUMBA', NULL, NULL, 'INS_0069', NULL, 'TPINS_0016', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0142', 'POLYCLINIQUE  KITOKO', NULL, NULL, 'INS_0071', NULL, 'TPINS_0013', 'LOC_0038', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0143', 'HOPITAL GENERAL DE NGOYO PATRA', NULL, NULL, 'INS_0074', NULL, 'TPINS_0012', 'LOC_0041', 1, 'sceau/7PDJ2Z6TFVRAvJ5IqESJMwmBYsys8kEyJMpGVXHJ.jpg', NULL, '2023-12-21 06:21:04', NULL),
('INS_0144', 'CENTRE DE SANTE INTEGRE CARITAS', NULL, NULL, 'INS_0070', NULL, 'TPINS_0016', 'LOC_0037', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0145', 'CENTRE DE SANTE INTEGRE DE MVOUMVOU', NULL, NULL, 'INS_0070', NULL, 'TPINS_0016', 'LOC_0037', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0146', 'CENTRE DE SANTE INTEGRE CARITAS', NULL, NULL, 'INS_0072', NULL, 'TPINS_0016', 'LOC_0039', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0147', 'CENTRE DE SANTE INTEGRE DE MBOTA', NULL, NULL, 'INS_0072', NULL, 'TPINS_0016', 'LOC_0039', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0148', 'POLYCLINIQUE POTO DJEMBO', NULL, NULL, 'INS_0073', NULL, 'TPINS_0013', 'LOC_0040', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0149', 'POLYCLINIQUE RAFFINERIE', NULL, NULL, 'INS_0073', NULL, 'TPINS_0013', 'LOC_0040', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0150', 'CENTRE DE SANTE INTEGRE DE TCHIAMBA-NZASSI', NULL, NULL, NULL, NULL, 'TPINS_0016', 'LOC_0401', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0151', 'HOPITAL 4M', NULL, NULL, 'INS_0069', NULL, 'TPINS_0009', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0152', 'CLINIQUE LOUISE MICHEL', NULL, NULL, 'INS_0069', NULL, 'TPINS_0009', 'LOC_0036', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0153', 'AMBASSADE DU CONGO EN AFRIQUE DU SUD', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0154', 'AMBASSADE DU CONGO EN  REPUBLIQUE DEMOCRATIQUE ALGERIENNE ET POPULAIRE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0155', 'AMBASSADE DU CONGO EN ANGOLA', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0156', 'AMBASSADE DU CONGO AU CAMEROUN', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0157', 'CONSULAT GENERAL DU CONGO', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0158', 'AMBASSADE DU CONGO AU CENTRAFRIQUE', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0159', 'AMBASSADEUR GABRIEL ENTCHA-EBIA', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0160', 'AMBASSADE DU CONGO EN EGYPT', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0161', 'AMBASSADE DU CONGO EN REPUBLIQUE FEDERALE DEMOCRATIQUE D\'ETHIOPIE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0162', 'AMBASSADE DU CONGO AU GABON', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0163', 'AMBASSADE DU CONGO EN GUINEE EQUATORIALE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0164', 'AMBASSADE DU CONGO AU KENYA', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0165', 'AMBASSADE DU CONGO EN LIBYE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0166', 'AMBASSADE DU CONGO AU MALI', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0167', 'AMBASSADE DU CONGO AU MAROC', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0168', 'AMBASSADE DU CONGO EN NAMIBIE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0169', 'AMBASSADE DU CONGO AU NIGERIA', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0171', 'AMBASSADE DU CONGO AU SENEGAL', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', 'LOC_4247', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0172', 'AMBASSADE DU CONGO EN REPUBLIQUE DU TCHAD', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0173', 'CONSULAT DE LA REPUBLIQUE DU CONGO AU BENIN', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0174', 'AMBASSADE DE LA REPUBLIQUE DU CONGO AU RWANDA', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0175', 'AMBASSADE DE LA REPUBLIQUE DU CONGO AU CANADA', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0176', 'AMBASSADE DU CONGO AU BRESIL', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0177', 'AMBASSADE DU CONGO A CUBA', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0178', 'AMBASSADE DU CONGO AUPRES DES ETATS-UNIS D\'AMER', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0179', 'AMBASSADE DU CONGO EN CHINE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0180', 'AMBASSADE DU CONGO EN INDE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0181', 'AMBASSADE DU CONGO AUPRES DU ROYAUME DE BEL', NULL, NULL, 'INS_0006', NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0182', 'AMBASSADE DU CONGO EN ITALIE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0184', 'AMBASSADE DU CONGO EN RUSSIE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0185', 'AMBASSADE DU CONGO EN SUEDE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0187', 'AMBASSADE DU CONGO EN ALLEMAGNE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0188', 'AMBASSADE DE LA GRANDE BRETAGNE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0189', 'AMBASSADE DU ROYAUME D\'ESPAGNE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0190', 'AMBASSADE DU CONGO EN ISRAEL', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0191', 'AMBASSADE DU CONGO EN TURQUIE', NULL, NULL, NULL, NULL, 'TPINS_0005', NULL, 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0192', 'POMPES FUNEBRES MUNICIPALES DE BRAZZAVILLE', NULL, NULL, 'INS_0006', NULL, 'TPINS_0003', 'LOC_0054', 1, 'sceau/9xdMPP31wOpeQs4AFzxlt1h94w1w4Gs59M0IU3WL.png', NULL, '2023-12-22 08:15:52', NULL),
('INS_0193', 'BUREAU D\'ENREGISTREMENT DE DECES', NULL, NULL, 'INS_0007', NULL, 'TPINS_0003', 'LOC_0036', 1, 'sceau/zwEaiL9xqf2e5wtgch39lXSQlCn155NvbWoZUpYO.png', NULL, '2023-12-21 06:27:39', NULL),
('INS_0194', 'POMPES FUNEBRES D\"OYO', NULL, NULL, NULL, NULL, 'TPINS_0003', 'LOC_0022', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0195', 'COMMUNE D\'OWANDO', NULL, NULL, 'INS_0009', NULL, 'TPINS_0002', 'LOC_0046', 1, 'sceau/uffSgwXrZhyJTrnZ9GdxOIchuyknRJHUDoHdwa8V.png', '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0196', 'HÔPITAL 31 JUILLET 1968', NULL, NULL, 'INS_0195', NULL, 'TPINS_0012', 'LOC_0046', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0197', 'CENTRE D\'HYGIENE DE POINTE-NOIRE', NULL, NULL, 'INS_0193', NULL, 'TPINS_0003', 'LOC_0036', 1, 'sceau/9xdMPP31wOpeQs4AFzxlt1h94w1w4Gs59M0IU3WL.png', NULL, NULL, NULL),
('INS_0198', 'CENTRE D\'HYGIENE DE BRAZZAVILLE', NULL, NULL, 'INS_0192', 'INS_0192', 'TPINS_0019', 'LOC_0054', 1, NULL, '2023-02-14 17:35:05', '2023-12-01 07:19:43', NULL),
('INS_0199', 'HÔPITAL DE REFERENCE DE KINKALA', NULL, NULL, 'INS_0077', NULL, 'TPINS_0017', 'LOC_0043', 1, NULL, '2023-12-01 16:14:33', '2023-12-01 16:14:33', NULL),
('INST_0006', 'ALANGE TEST OPALA', NULL, NULL, 'INST_0006', NULL, 'TPINS_0002', 'LOC_0021', 1, 'sceau/kMZAnkD77LrGEIr4JCrPZZ7Q6b3NLJFEOMOQgpoQ.png', '2024-10-31 22:27:57', '2024-10-31 22:29:07', NULL),
('INST_0010', 'COMMUNE DE LIKASI', NULL, NULL, NULL, NULL, 'TPINS_0002', 'LOC_4272', 1, NULL, '2024-12-10 08:52:48', '2024-12-10 08:52:48', NULL),
('INST_0011', 'COMMUNE DE SHITURU', NULL, NULL, 'INST_0010', NULL, 'TPINS_0002', 'LOC_4280', 1, NULL, '2024-12-10 08:53:56', '2024-12-10 08:53:56', NULL),
('INST_0012', 'COMMUNE DE PANDA', NULL, NULL, 'INST_0010', NULL, 'TPINS_0002', 'LOC_4280', 1, NULL, '2024-12-10 08:55:22', '2026-01-14 22:59:38', '2026-01-14 22:59:38'),
('INST_0200', 'TITRE D\'EXEMPLE', NULL, NULL, 'INS_0091', NULL, 'TPINS_0011', 'LOC_0037', 1, 'sceau/jDEt4Ro2OFagQCuXIwWqwnstRajv3yPR5NflNJct.jpg', '2026-01-14 23:04:56', '2026-01-14 23:05:46', '2026-01-14 23:05:46');

-- --------------------------------------------------------

--
-- Table structure for table `tr_ins_user`
--

CREATE TABLE `tr_ins_user` (
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_user` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_fonction` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_ins_user`
--

INSERT INTO `tr_ins_user` (`cui`, `code_institution`, `code_user`, `code_fonction`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
('CUI_00000001', 'INS_0001', 'USR_00000001', 'FONC_0011', 1, '2023-04-23 12:21:57', '2023-04-23 12:21:57', NULL),
('CUI_00000004', 'INS_0047', 'USR_00000003', 'FONC_0004', 1, '2022-11-27 12:21:50', '2024-02-07 02:44:10', NULL),
('CUI_00000007', 'INS_0094', 'USR_00000005', 'FONC_0006', 1, '2022-11-27 12:39:57', '2024-02-07 02:45:18', NULL),
('CUI_00000008', 'INS_0023', 'USR_00000006', 'FONC_0009', 1, '2022-11-30 03:26:48', '2025-04-30 08:38:56', NULL),
('CUI_00000011', 'INS_0047', 'USR_00000009', 'FONC_0002', 1, '2024-06-23 08:16:46', '2024-06-23 08:16:46', NULL),
('CUI_00000012', 'INS_0006', 'USR_00000010', 'FONC_0009', 1, '2024-06-23 13:18:41', '2025-04-15 22:54:50', NULL),
('CUI_00000013', 'INS_0192', 'USR_00000011', 'FONC_0005', 1, '2024-06-23 13:20:44', '2024-06-23 13:20:44', NULL),
('CUI_00000015', 'INS_0046', 'USR_00000013', 'FONC_0014', 1, '2024-06-23 13:47:05', '2025-03-25 21:12:48', NULL),
('CUI_00000016', 'INS_0023', 'USR_00000014', 'FONC_0010', 1, '2024-06-23 14:14:48', '2024-11-19 19:55:53', NULL),
('CUI_00000021', 'INS_0196', 'USR_00000019', 'FONC_0006', 1, '2024-10-21 09:40:36', '2025-04-21 04:12:20', NULL),
('CUI_00000023', 'INS_0198', 'USR_00000021', 'FONC_0007', 1, '2024-10-21 09:49:38', '2025-04-10 19:08:24', NULL),
('CUI_00000031', 'INS_0192', 'USR_00000029', 'FONC_0012', 1, '2025-07-01 12:20:18', '2025-07-01 12:20:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_lieu_survenance`
--

CREATE TABLE `tr_lieu_survenance` (
  `code_lieu_survenance` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_lieu_survenance` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_lieu_survenance`
--

INSERT INTO `tr_lieu_survenance` (`code_lieu_survenance`, `lib_lieu_survenance`, `supprimer`, `created_at`, `updated_at`) VALUES
('LSURV_0001', 'Formation sanitaire', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('LSURV_0002', 'Centre carcéral', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('LSURV_0003', 'Domicile', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('LSURV_0004', 'Navire', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('LSURV_0005', 'Avion', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('LSURV_0006', 'Etranger', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39');

-- --------------------------------------------------------

--
-- Table structure for table `tr_localisation`
--

CREATE TABLE `tr_localisation` (
  `code_localisation` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_localisation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_departement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_localite`
--

CREATE TABLE `tr_localite` (
  `code_localite` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_localite` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_officel` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Codification officiel de département',
  `code_type_localite` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pompes_funebres` tinyint(1) NOT NULL DEFAULT '0',
  `code_localite_parent` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_localite`
--

INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_0001', 'BRAZZAVILLE', 'BZV', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0002', 'POINTE-NOIRE', 'PNR', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0003', 'LIKOUALA', 'LIK', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0004', 'SANGHA', 'SAN', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0005', 'CUVETTE-OUEST', 'CUO', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0006', 'CUVETTE', 'CUC', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0007', 'PLATEAUX', 'PLT', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0008', 'POOL', 'POO', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0009', 'LEKOUMOU', 'LKM', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0010', 'BOUENZA', 'BOZ', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0011', 'NIARI', 'NRI', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0012', 'KOUILOU', 'KOL', 'TPLOC_0001', 0, NULL, NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0013', 'DOLISIE', 'DOL', 'TPLOC_0003', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0014', 'MOSSENDJO', NULL, 'TPLOC_0003', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0015', 'SIBITI', NULL, 'TPLOC_0003', 0, 'LOC_0009', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0016', 'POINTE-NOIRE', 'PN', 'TPLOC_0003', 0, 'LOC_0002', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0017', 'DJAMBALA', NULL, 'TPLOC_0003', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0018', 'KINKALA', NULL, 'TPLOC_0003', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0019', 'KINTELE', NULL, 'TPLOC_0003', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0020', 'IMPFONDO', NULL, 'TPLOC_0003', 0, 'LOC_0003', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0021', 'OWANDO', 'OW', 'TPLOC_0003', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0022', 'OYO', NULL, 'TPLOC_0003', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0023', 'EWO', NULL, 'TPLOC_0003', 0, 'LOC_0005', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0024', 'OUESSO', NULL, 'TPLOC_0003', 0, 'LOC_0004', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0025', 'LOANGO', NULL, 'TPLOC_0003', 0, 'LOC_0012', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0026', 'BRAZZAVILLE', 'BV', 'TPLOC_0003', 1, 'LOC_0001', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0027', 'MADINGOU', NULL, 'TPLOC_0003', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0028', 'NKAYI', NULL, 'TPLOC_0003', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0029', 'POKOLA', NULL, 'TPLOC_0003', 0, 'LOC_0004', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0030', 'FOUNDOU-FOUNDOU', NULL, 'TPLOC_0004', 0, 'LOC_0013', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0031', 'YOULOU-POUNGUI', NULL, 'TPLOC_0004', 0, 'LOC_0013', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0032', 'DOLISIE', NULL, 'TPLOC_0004', 0, 'LOC_0013', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0033', 'BOUALI', NULL, 'TPLOC_0004', 0, 'LOC_0014', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0034', 'ITSIBOU', NULL, 'TPLOC_0004', 0, 'LOC_0014', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0035', 'SIBITI', NULL, 'TPLOC_0004', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0036', 'LUMUMBA', NULL, 'TPLOC_0004', 0, 'LOC_0016', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0037', 'MVOUMVOU', NULL, 'TPLOC_0004', 0, 'LOC_0016', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0038', 'TIE-TIE', NULL, 'TPLOC_0004', 0, 'LOC_0016', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0039', 'LOANDJILI', NULL, 'TPLOC_0004', 0, 'LOC_0016', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0040', 'MONGO-POUKOU', NULL, 'TPLOC_0004', 0, 'LOC_0016', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0041', 'NGOYO', NULL, 'TPLOC_0004', 0, 'LOC_0016', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0042', 'DJAMBALA', NULL, 'TPLOC_0004', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0043', 'KINKALA', NULL, 'TPLOC_0004', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0044', 'KINTELE', NULL, 'TPLOC_0004', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0045', 'IMPFONDO', NULL, 'TPLOC_0004', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0046', 'OWANDO', NULL, 'TPLOC_0004', 0, 'LOC_0021', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0047', 'OYO', NULL, 'TPLOC_0004', 0, 'LOC_0022', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0048', 'EWO', NULL, 'TPLOC_0004', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0049', 'NZALANGOYE', NULL, 'TPLOC_0004', 0, 'LOC_0024', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0050', 'MBINDJO', NULL, 'TPLOC_0004', 0, 'LOC_0024', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0051', 'LOANGO', NULL, 'TPLOC_0004', 0, 'LOC_0025', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0052', 'MAKELEKELE', NULL, 'TPLOC_0004', 0, 'LOC_0026', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0053', 'BACONGO', NULL, 'TPLOC_0004', 0, 'LOC_0026', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0054', 'POTO-POTO', NULL, 'TPLOC_0004', 0, 'LOC_0026', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0055', 'MOUNGALI', NULL, 'TPLOC_0004', 0, 'LOC_0026', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0056', 'OUENZE', NULL, 'TPLOC_0004', 0, 'LOC_0026', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0057', 'TALANGAI', NULL, 'TPLOC_0004', 0, 'LOC_0026', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0058', 'MFILOU', NULL, 'TPLOC_0004', 0, 'LOC_0026', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0059', 'MADIBOU', NULL, 'TPLOC_0004', 0, 'LOC_0026', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0060', 'DJIRI', NULL, 'TPLOC_0004', 0, 'LOC_0026', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0061', 'MADINGOU', NULL, 'TPLOC_0004', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0062', 'MWANA-NTO', NULL, 'TPLOC_0004', 0, 'LOC_0028', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0063', 'SOULOUKA', NULL, 'TPLOC_0004', 0, 'LOC_0028', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0064', 'POKOLA', NULL, 'TPLOC_0004', 0, 'LOC_0029', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0065', 'C.Q 101 CONGO-AFRICAIN', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0066', 'C.Q 102 CAPABLE', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0067', 'C.Q 103 AEROPORT', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0068', 'C.Q 104 BALOUMBOU', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0069', 'C.Q 105 GRAND MARCHE', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0070', 'C.Q 106 DES EGLISES', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0071', 'C.Q 107 BACONGO', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0072', 'C.Q 108 HERVÉ DELLOT', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0073', 'C.Q 109 BAKOUGNI', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0074', 'C.Q 110 MBOUKOU', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0075', 'C.Q 111 DIMEBEKO', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0076', 'C.Q 112 PINARE', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0077', 'C.Q 113 TAHITI', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0078', 'C.Q.114 MPASSI-NGOLO', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0079', 'C.Q.115 MANGANDZI 1', NULL, 'TPLOC_0007', 0, 'LOC_0030', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0080', 'C.Q 201 MOUPEPE', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0081', 'C.Q 202 GAÏA', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0082', 'C.Q 203 LAC GAÏA', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0083', 'C.Q 204 CENTRE VILLE', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0084', 'C.Q 205 PETIT ZANAGA', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0085', 'C.Q 206 TSILA 1', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0086', 'C.Q 207 TSILA 2', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0087', 'C.Q 208 LISSANGA', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0088', 'C.Q 209 TELEVISION', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0089', 'C.Q 201 MANGANDZI 2', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0090', 'C.Q 211 PADI', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0091', 'C.Q 212 MONT FLEURY', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0092', 'C.Q 213 UNITE', NULL, 'TPLOC_0007', 0, 'LOC_0031', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0093', 'MBAMBA', NULL, 'TPLOC_0007', 0, 'LOC_0033', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0094', 'PLANCHE', NULL, 'TPLOC_0007', 0, 'LOC_0033', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0095', 'MAKENGUE', NULL, 'TPLOC_0007', 0, 'LOC_0033', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0096', 'ITOMBE', NULL, 'TPLOC_0007', 0, 'LOC_0033', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0097', 'MOUKAMBA', NULL, 'TPLOC_0007', 0, 'LOC_0033', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0098', 'MOZARD', NULL, 'TPLOC_0007', 0, 'LOC_0034', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0099', 'YOMBO', NULL, 'TPLOC_0007', 0, 'LOC_0034', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0100', 'MALEMBO', NULL, 'TPLOC_0007', 0, 'LOC_0034', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0101', 'LIMBAMBA', NULL, 'TPLOC_0007', 0, 'LOC_0034', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0102', 'NYANGA', NULL, 'TPLOC_0007', 0, 'LOC_0034', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0103', 'LOUMINGOU', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0104', 'MATIBI', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0105', 'MAPINDI', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0106', 'MVOUBA', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0107', 'MOUSSANDA', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0108', 'INDO', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0109', 'MALIMBA', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0110', 'MOUSSIEHOU', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0111', 'MIKAMBA', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0112', 'LOSSO', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0113', 'MOUKANDA ', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0114', 'MONGO', NULL, 'TPLOC_0007', 0, 'LOC_0035', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0115', 'CENTRE-VILLE (GARE CENTRALE)', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0116', 'CENTRE- VILLE STADE F.A', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0117', 'KM4 ATELIERS C.F.C.O', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0118', 'KM4 CEG 5 FEVRIER', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0119', 'EGLISE SAINT PIERRE', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0120', 'BASE AERIENNE', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0121', 'S.I.C TCHICHELLE', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0122', 'MARCHE CENTRAL', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0123', 'GRAND MOSQUEE', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0124', 'AUBERGE DE LA JUSTICE', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0125', 'JOIE DU CONGO', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0126', 'CEG O.C.H KWAME NKRUMA', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0127', 'O.C.H STATION X-OIL', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0128', 'O.C.H BAGUETTE', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0129', 'MBEMBA COUTE CHER', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0130', 'M’ PITA MAISON D’ARRET', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0131', 'M’PITA USINE PLASCO', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0132', 'TCHIMBAMBA AVIATION', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0133', 'TCHIMBAMBA MARCHE', NULL, 'TPLOC_0007', 0, 'LOC_0036', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0134', 'PLAGE,BASE AGIP', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0135', 'EGLISE SAINT CHRISTOPHE', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0136', 'MARCHE MVOUMVOU', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0137', 'MARCHE MVOUMVOU', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0138', 'KIF- KIF', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0139', 'HOTEL DE LA PONTENEGRINE', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0140', 'EGLISE SAINTE BERNADETTE', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0141', 'CINEMA ROY', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0142', 'CINEMA REX', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0143', 'EGLISE KIMBAGUISTE', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0144', 'ECOLE NDENDE NIENGO', NULL, 'TPLOC_0007', 0, 'LOC_0037', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0146', 'EGLISE SAINT FRANCOIS', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0147', 'MARCHE TIE-TIE', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0148', 'DUO', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0149', 'MOULEMBO', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0150', 'NDAKA SOUSSOU', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0151', 'MARCHE LIBERTE', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0152', 'MBOUKOU', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0153', 'MVOUNGOU 1 MARCHE', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0154', 'MVOUNGOU 2TERMINUS', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0155', 'MVOUNGOU 3 ECOLE', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0156', 'MVOUNGOU 4 LA DIANGA', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0157', 'LOUSSALA 1 MARCHE', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0158', 'LOUSSALA 2 ECOLE', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0159', 'CSI DU 15 OCTOBRE 1997 MPAKA 1 KM 8', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0160', 'MPAKA 2 MATERNITE MOUISSOU MADELEINE', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0161', 'MPAKA 3 EGLISE SAINT-ESPRIT', NULL, 'TPLOC_0007', 0, 'LOC_0038', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0162', 'LOANDILI 1 ECOLE BALOU CONSTANT', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0163', 'LOANDJILI 2 CEG MOE POATY', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0164', 'NKOUIKOU MARCHE', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0165', 'TCHINIAMBI ECOLE B.M MAVOUNGOU', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0166', 'TCHINIAMBI 2 ECOLE 15 AOUT1963', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0167', 'MONGO-KAMBA MARCHE DU PEUPLE', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0168', 'MONGO KAMBA MOVIS', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0169', 'TCHIBATI', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0170', 'TCHINIAMBI MBOTA 1 ECOLE', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0171', 'TCHINIAMBI MBOTA 2 CARLOS', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0172', 'MARCHE 1 MBOTA', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0173', 'MBOTA 2 ECOLE 8 FEVRIER1964', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0174', 'BISSONGO', NULL, 'TPLOC_0007', 0, 'LOC_0039', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0175', 'MONGO- MPOUKOU ECOLE', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0176', 'SIAFOUMOU CSI', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0177', 'MBOTA RAFFINERIE', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0178', 'MBOTA RAFFINERIE OCEAN', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0179', 'SONGOLO', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0180', 'FAUBOURG', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0181', 'LOANDILI', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0182', 'TCHIALI', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0183', 'MAKAYABOU ZEPHIRIN', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0184', 'TANDOU- SOUKOU', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0185', 'ECOLE NKOUNGA MAKOSSO', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0186', 'TCHICAYA ELOI', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0187', 'NKOUIKOU', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0188', 'NKOUIKOU TRABEC', NULL, 'TPLOC_0007', 0, 'LOC_0040', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0189', 'NGOYO 1 ECOLE', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0190', 'NGOYO 2 C.M.A', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0191', 'TCHIMBABOUKA', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0192', 'TCHIMANI', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0193', 'COTE MATEVE', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0194', 'NANGA', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0195', 'MPAKA 120', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0196', 'MPAKA 2 MARCHE', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0197', 'MPAKA 3 5 CHEMINS', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0198', 'DJENO', NULL, 'TPLOC_0007', 0, 'LOC_0041', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0199', 'MONGOKO', NULL, 'TPLOC_0007', 0, 'LOC_0049', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0200', 'FAISCEAUX', NULL, 'TPLOC_0007', 0, 'LOC_0049', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0201', 'MINDONGO', NULL, 'TPLOC_0007', 0, 'LOC_0049', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0202', 'POTO-POTO', NULL, 'TPLOC_0007', 0, 'LOC_0049', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0203', 'CHATEAU D’EAU', NULL, 'TPLOC_0007', 0, 'LOC_0049', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0204', 'MOMETA', NULL, 'TPLOC_0007', 0, 'LOC_0049', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0205', 'CENTRE SPORTIF', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0206', 'DIATA', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0207', 'KINGOUARI', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0208', 'KINSOUNDI(BARRAGE)', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0209', 'MAMBA ( BIFOUITI)', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0210', 'MAYOMA', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0211', 'METEO', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0212', 'MOUNKOUNZI-NGONAKA', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0213', 'NGANGOUONI', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0214', 'NIANIA (SITA DIA TSOLO)', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0215', 'NGASSA (MATOUR)', NULL, 'TPLOC_0007', 0, 'LOC_0052', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0216', 'DAHOMEY', NULL, 'TPLOC_0007', 0, 'LOC_0053', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0217', 'GLACIERE', NULL, 'TPLOC_0007', 0, 'LOC_0053', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0218', 'MBAMA', NULL, 'TPLOC_0007', 0, 'LOC_0053', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0219', 'MPISSA', NULL, 'TPLOC_0007', 0, 'LOC_0053', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0220', 'NKEOUA', NULL, 'TPLOC_0007', 0, 'LOC_0053', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0221', 'NIMBI', NULL, 'TPLOC_0007', 0, 'LOC_0053', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0222', 'RIO', NULL, 'TPLOC_0007', 0, 'LOC_0053', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0223', 'SAINT – PIERRE', NULL, 'TPLOC_0007', 0, 'LOC_0053', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0224', 'TAHITI', NULL, 'TPLOC_0007', 0, 'LOC_0053', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0225', 'AVENUE DE FRANCE', NULL, 'TPLOC_0007', 0, 'LOC_0054', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0226', 'AVENUE DE DE PARIS', NULL, 'TPLOC_0007', 0, 'LOC_0054', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0227', 'CAMPS 15 AOUT', NULL, 'TPLOC_0007', 0, 'LOC_0054', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0228', 'OCH MOUNGALI 3', NULL, 'TPLOC_0007', 0, 'LOC_0054', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0229', '5 FEVRIER', NULL, 'TPLOC_0007', 0, 'LOC_0054', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0230', 'ANCIENS COMBATTANTS', NULL, 'TPLOC_0007', 0, 'LOC_0055', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0231', 'CEG DE LA PAIX', NULL, 'TPLOC_0007', 0, 'LOC_0055', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0232', 'CEG MATSOUA', NULL, 'TPLOC_0007', 0, 'LOC_0055', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0233', 'DIX MAISONS', NULL, 'TPLOC_0007', 0, 'LOC_0055', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0234', 'ECOLE DES BEAUX-ARTS', NULL, 'TPLOC_0007', 0, 'LOC_0055', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0235', 'LA POUDRIERE', NULL, 'TPLOC_0007', 0, 'LOC_0055', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0236', 'MARCHE DE 10 FRANCS', NULL, 'TPLOC_0007', 0, 'LOC_0055', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0237', 'MOUKONDO', NULL, 'TPLOC_0007', 0, 'LOC_0055', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0238', 'PLAITEAU DES 15 ANS', NULL, 'TPLOC_0007', 0, 'LOC_0055', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0239', 'BONEMBA', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0240', 'KIMBANGOU – MIKALOU', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0241', 'MARCHE DE OUENZE', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0242', 'MASSAMBA RAPHAEL', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0243', 'MOULEKE', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0244', 'MOUKONDO', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0245', 'MPILA CENT FILS', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0246', 'MPIERE – MPIERE', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0247', 'OUENZE MANDZADZA', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0248', 'PEYRE PIERRE', NULL, 'TPLOC_0007', 0, 'LOC_0056', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0249', 'CHAMP DE TIR', NULL, 'TPLOC_0007', 0, 'LOC_0057', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0250', 'FLEUVE CONGO', NULL, 'TPLOC_0007', 0, 'LOC_0057', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0251', 'GASTON LENDA', NULL, 'TPLOC_0007', 0, 'LOC_0057', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0252', 'INTENDANCE', NULL, 'TPLOC_0007', 0, 'LOC_0057', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0253', 'JOSEPH NGOLALI', NULL, 'TPLOC_0007', 0, 'LOC_0057', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0254', 'MAMAN MBOUALE', NULL, 'TPLOC_0007', 0, 'LOC_0057', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0255', 'MPILA', NULL, 'TPLOC_0007', 0, 'LOC_0057', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0256', 'TEXACO TSIEME', NULL, 'TPLOC_0007', 0, 'LOC_0057', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0257', 'CASE BARNIER', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0258', 'CITE DES 17', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0259', 'INDZOULI', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0260', 'ITSALI', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0261', 'KAHOUNGA', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0262', 'KIBOUENDE', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0263', 'KIELE THENARD', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0264', 'MASSINA', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0265', 'MOUTABALA', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0266', 'MPIERE – MPIERE', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0267', 'NZOKO MBIMI', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0268', 'NGAMBIO', NULL, 'TPLOC_0007', 0, 'LOC_0058', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0269', 'KOMBE', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0270', 'KIBINA', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0271', 'MADIBOU', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0272', 'MAFOUTA', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0273', 'MANSIMOU', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0274', 'MASSISSIA', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0275', 'MAYANGA', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0276', 'MBOUONO', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0277', 'MOUSSOSSO', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0278', 'POTO – POTO DJOUE(SANGOLO)', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0279', 'TSANGAMANI', NULL, 'TPLOC_0007', 0, 'LOC_0059', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0280', 'IMPOH MANIANGA', NULL, 'TPLOC_0007', 0, 'LOC_0060', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0281', 'ITATOLO', NULL, 'TPLOC_0007', 0, 'LOC_0060', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0282', 'JACQUES OPANGAULT', NULL, 'TPLOC_0007', 0, 'LOC_0060', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0283', 'MAKABANDILOU', NULL, 'TPLOC_0007', 0, 'LOC_0060', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0284', 'MIKALOU MADZOUNA', NULL, 'TPLOC_0007', 0, 'LOC_0060', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0285', 'MATARI', NULL, 'TPLOC_0007', 0, 'LOC_0060', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0286', 'NKOMBO', NULL, 'TPLOC_0007', 0, 'LOC_0060', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0287', 'MWANA-NTO', NULL, 'TPLOC_0007', 0, 'LOC_0062', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0288', 'DIVOUBA', NULL, 'TPLOC_0007', 0, 'LOC_0062', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0289', 'MARCHE CENTRAL', NULL, 'TPLOC_0007', 0, 'LOC_0062', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0290', 'MASSALA-NGOUALA', NULL, 'TPLOC_0007', 0, 'LOC_0062', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0291', 'KIVIDI', NULL, 'TPLOC_0007', 0, 'LOC_0062', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0292', 'CAPABLE', NULL, 'TPLOC_0007', 0, 'LOC_0062', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0293', 'KINTOUADI', NULL, 'TPLOC_0007', 0, 'LOC_0063', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0294', '15 AOUT 1960', NULL, 'TPLOC_0007', 0, 'LOC_0063', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0295', 'MOUKOUKOULOU', NULL, 'TPLOC_0007', 0, 'LOC_0063', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0296', 'SOULOUKA', NULL, 'TPLOC_0007', 0, 'LOC_0063', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0297', 'AQUARIUM', NULL, 'TPLOC_0007', 0, 'LOC_0063', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0298', 'SANS FIL', NULL, 'TPLOC_0007', 0, 'LOC_0050', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0299', 'BIRANKEIM', NULL, 'TPLOC_0007', 0, 'LOC_0050', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0300', 'DOA', NULL, 'TPLOC_0007', 0, 'LOC_0050', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0301', 'JUSTIE', NULL, 'TPLOC_0007', 0, 'LOC_0050', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0302', 'MAYI POUMA', NULL, 'TPLOC_0007', 0, 'LOC_0050', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0303', 'MBOMA', NULL, 'TPLOC_0007', 0, 'LOC_0050', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0304', 'NGONGO', NULL, 'TPLOC_0007', 0, 'LOC_0050', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0305', 'ABALA-NDOLO', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0306', 'AKOU', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0307', 'CENTRE-VILLE', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0308', 'AKOUNOU', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0309', 'MFOA', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0310', 'MPOUANGNA', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0311', 'NGAMBAO', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0312', 'NGANTSUENE', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0313', 'NGOULAYO', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0314', 'OUENZE', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0315', 'NYONFOULA', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0316', 'TALANGAI', NULL, 'TPLOC_0007', 0, 'LOC_0017', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0317', 'MADIBA', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0318', 'MAKOUMBOU MA MPOMBO', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0319', 'MASSOMBO', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0320', 'NTARI-NGOUARI', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0321', 'LOUBIKOU', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0322', 'BANZIEMO', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0323', 'VINZA', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0324', 'MBANZA-NKANDI', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0325', 'WAYAKO', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0326', 'MABI', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0327', 'NGAMBARI', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0328', 'MOULOUANGOU', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0329', 'YALAVOUNGA', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0330', 'MADIDI', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0331', 'MOUKOMBO-YALALA', NULL, 'TPLOC_0007', 0, 'LOC_0018', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0332', 'PAUL NGAMBI', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0333', 'NGAMBIO', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0334', 'GASTON MPOUILILOU', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0335', 'NGABANOU', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0336', 'LIFOULA', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0337', 'MANTENSAMA-SOUH', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0338', 'ISSOULI', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0339', 'FLORENT TSIBA', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0340', 'UNIVERSITE DENIS SASSOU NGUESSO', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0341', 'MILLE LOGEMENT', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0342', 'IHOUEME', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0343', 'STADE DE LA CONCORDE', NULL, 'TPLOC_0007', 0, 'LOC_0019', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0344', 'MOUNGOUNGUI', NULL, 'TPLOC_0007', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0345', 'TOLINGANA', NULL, 'TPLOC_0007', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0346', 'BAKANDI', NULL, 'TPLOC_0007', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0347', 'CENTRE VILLE', NULL, 'TPLOC_0007', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0348', 'ANGOLA LIBRE', NULL, 'TPLOC_0007', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0349', 'TOSSANGANA', NULL, 'TPLOC_0007', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0350', 'BOHONA', NULL, 'TPLOC_0007', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0351', 'GANGANIA', NULL, 'TPLOC_0007', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0352', 'KOMBOLA', NULL, 'TPLOC_0007', 0, 'LOC_0020', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0353', 'KINDA-ODZOHO', NULL, 'TPLOC_0007', 0, 'LOC_0021', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0354', 'YENGO', NULL, 'TPLOC_0007', 0, 'LOC_0021', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0355', 'IKOUMOU', NULL, 'TPLOC_0007', 0, 'LOC_0021', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0356', 'OHOKO', NULL, 'TPLOC_0007', 0, 'LOC_0021', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0357', 'OMBOUMA', NULL, 'TPLOC_0007', 0, 'LOC_0021', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0358', 'LINNENGUE', NULL, 'TPLOC_0007', 0, 'LOC_0021', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0359', 'KANGUINI', NULL, 'TPLOC_0007', 0, 'LOC_0021', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0360', 'OBANGUI', NULL, 'TPLOC_0007', 0, 'LOC_0022', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0361', 'OYAH', NULL, 'TPLOC_0007', 0, 'LOC_0022', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0362', 'OKONGO', NULL, 'TPLOC_0007', 0, 'LOC_0022', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0363', 'BOUTA', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0364', 'CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0365', 'KANGAMITEMA', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0366', 'OUENZE', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0367', 'EWO VILLAGE', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0368', 'EX VILLAGE KEMVANI', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0369', 'EX VILLAGE MBOU', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0370', 'EX VILLAGE OKA', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0371', 'EX VILLAGE OLLOU', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0372', 'EX VILLAGE ONDOUNA', NULL, 'TPLOC_0007', 0, 'LOC_0023', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0373', 'BACONGO', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0374', 'CAPABLE', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0375', 'DAKAR', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0376', 'KIMPAMBOU', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0377', 'LOUBOTA', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0378', 'MANDZAOU', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0379', 'MBOUKOUDOU', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0380', 'MOUKOKOTADI', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0381', 'MPOUMA', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0382', 'NSATOU- MEYA', NULL, 'TPLOC_0007', 0, 'LOC_0027', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0383', 'PETE', NULL, 'TPLOC_0007', 0, 'LOC_0029', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0384', 'STOOL', NULL, 'TPLOC_0007', 0, 'LOC_0029', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0385', 'CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_0029', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0386', 'MEVELLEC', NULL, 'TPLOC_0007', 0, 'LOC_0029', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0387', 'BONDZOKOU', NULL, 'TPLOC_0007', 0, 'LOC_0029', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0388', 'LOUMINGOU', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0389', 'MATIBI', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0390', 'MAPINDI', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0391', 'MVOUBA', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0392', 'MOUSSANDA', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0393', 'INDO', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0394', 'MALIMBA', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0395', 'MOUSSIEHOU', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0396', 'MIKAMBA', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0397', 'LOSSO', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0398', 'MOUKANDA ', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0399', 'MONGO', NULL, 'TPLOC_0007', 0, 'LOC_0015', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0400', 'ILE MBAMOU', NULL, 'TPLOC_0002', 0, 'LOC_0001', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0401', 'TCHAMBA NZASSI', NULL, 'TPLOC_0002', 0, 'LOC_0002', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0402', 'BETOU', NULL, 'TPLOC_0002', 0, 'LOC_0003', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0403', 'BOUANELA', NULL, 'TPLOC_0002', 0, 'LOC_0003', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0404', 'DONGOU', NULL, 'TPLOC_0002', 0, 'LOC_0003', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0405', 'ENYELLE', NULL, 'TPLOC_0002', 0, 'LOC_0003', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0406', 'EPENA', NULL, 'TPLOC_0002', 0, 'LOC_0003', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0407', 'IMPFONDO', NULL, 'TPLOC_0002', 0, 'LOC_0003', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0408', 'LIRANGA', NULL, 'TPLOC_0002', 0, 'LOC_0003', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0409', 'KABO', NULL, 'TPLOC_0002', 0, 'LOC_0004', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0410', 'MOKEKO', NULL, 'TPLOC_0002', 0, 'LOC_0004', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0411', 'NGBALA', NULL, 'TPLOC_0002', 0, 'LOC_0004', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0412', 'PIKOUNDA', NULL, 'TPLOC_0002', 0, 'LOC_0004', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0413', 'SEMBE', NULL, 'TPLOC_0002', 0, 'LOC_0004', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0414', 'SOUANKE', NULL, 'TPLOC_0002', 0, 'LOC_0004', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0415', 'ETOUMBI', NULL, 'TPLOC_0002', 0, 'LOC_0005', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0416', 'EWO', NULL, 'TPLOC_0002', 0, 'LOC_0005', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0417', 'KELLE', NULL, 'TPLOC_0002', 0, 'LOC_0005', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0418', 'MBAMA', NULL, 'TPLOC_0002', 0, 'LOC_0005', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0419', 'MBOMO', NULL, 'TPLOC_0002', 0, 'LOC_0005', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0420', 'OKOYO', NULL, 'TPLOC_0002', 0, 'LOC_0005', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0421', 'OWANDO', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0422', 'OYO', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0423', 'MAKOUA', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0424', 'TCHICAPIKA', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0425', 'NGOKO', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0426', 'NTOKOU', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0427', 'MOSSAKA', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0428', 'LOUKOLELA', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0429', 'BOUNDJI', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0430', 'BOKOMA', NULL, 'TPLOC_0002', 0, 'LOC_0006', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0431', 'ABALA', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0432', 'ALLEMBE', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0433', 'DJAMBALA', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0434', 'GAMBOMA', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0435', 'NGO', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0436', 'LEKANA', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0437', 'OLLOMBO', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0438', 'MAKOTIMPOKO', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0439', 'ONGOGNI', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00');
INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_0440', 'MBON', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0441', 'MPOUYA', NULL, 'TPLOC_0002', 0, 'LOC_0007', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0442', 'LOUINGUI', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0443', 'LOUMO', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0444', 'IGNE', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0445', 'MAYAMA', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0446', 'VINDZA', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0447', 'MBANZA-NDOUNGA', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0448', 'KIMBA', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0449', 'NGABE', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0450', 'MINDOULI', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0451', 'BOKO', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0452', 'KINKALA', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0453', 'KINDAMBA', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0454', 'GOMA TSE-TSE', NULL, 'TPLOC_0002', 0, 'LOC_0008', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0455', 'SIBITI', NULL, 'TPLOC_0002', 0, 'LOC_0009', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0456', 'KOMONO', NULL, 'TPLOC_0002', 0, 'LOC_0009', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0457', 'ZANAGA', NULL, 'TPLOC_0002', 0, 'LOC_0009', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0458', 'BAMBAMA', NULL, 'TPLOC_0002', 0, 'LOC_0009', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0459', 'MAYEYE', NULL, 'TPLOC_0002', 0, 'LOC_0009', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0460', 'BOKO-SONGHO', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0461', 'KAYES', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0462', 'KINGOUE', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0463', 'LOUDIMA', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0464', 'MABOMBO', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0465', 'MADINGOU', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0466', 'MFOUATI', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0467', 'MOUYONDZI', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0468', 'TSIAKI', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0469', 'YAMBA', NULL, 'TPLOC_0002', 0, 'LOC_0010', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0470', 'BANDA', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0471', 'DIVENIE', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0472', 'KIBANGOU', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0473', 'KIMONGO', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0474', 'LONDELA -KAYES', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0475', 'LOUVAKOU', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0476', 'MAKABANA', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0477', 'MAYOKO', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0478', 'MBINDA', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0479', 'MOUNGOUNDOU-NORD', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0480', 'MOUNGOUNDOU-SUD', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0481', 'MOUTAMBA', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0482', 'NYANGA', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0483', 'YAYA', NULL, 'TPLOC_0002', 0, 'LOC_0011', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0484', 'HINDA', NULL, 'TPLOC_0002', 0, 'LOC_0012', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0485', 'KAKAMOEKA', NULL, 'TPLOC_0002', 0, 'LOC_0012', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0486', 'MVOUTI', NULL, 'TPLOC_0002', 0, 'LOC_0012', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0487', 'MADINGO-KAYES', NULL, 'TPLOC_0002', 0, 'LOC_0012', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0488', 'NZAMBI', NULL, 'TPLOC_0002', 0, 'LOC_0012', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0489', 'LOANGO', NULL, 'TPLOC_0002', 0, 'LOC_0012', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0490', 'BAKALA', NULL, 'TPLOC_0007', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0491', 'NIOUNVOU', NULL, 'TPLOC_0007', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0492', 'POUMBOU', NULL, 'TPLOC_0007', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0493', 'TEMPS-PASSE', NULL, 'TPLOC_0007', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0494', 'TANDOU-PETSO', NULL, 'TPLOC_0007', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0495', 'NZAMBI-SOUNDA', NULL, 'TPLOC_0007', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0496', 'KONGO', NULL, 'TPLOC_0007', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0497', 'NGANGOU ', NULL, 'TPLOC_0007', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0498', 'LOUBAMBA', NULL, 'TPLOC_0007', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0499', 'LOUBELA-NGANGA', NULL, 'TPLOC_0007', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0500', 'NSEMI-BOUEYA', NULL, 'TPLOC_0007', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0501', 'MAMBOMA', NULL, 'TPLOC_0007', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0502', 'MANKOUDI', NULL, 'TPLOC_0007', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0503', 'AEROPORT', NULL, 'TPLOC_0007', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0504', 'CHATEAU D’EAU', NULL, 'TPLOC_0007', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0505', 'MOUELA', NULL, 'TPLOC_0007', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0506', 'CARRE', NULL, 'TPLOC_0007', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0507', 'CONGO BOIS', NULL, 'TPLOC_0007', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0508', 'CAMP INDUSTRIEL', NULL, 'TPLOC_0007', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0509', 'MOUKONDO ', NULL, 'TPLOC_0007', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0510', 'CITE VIGOR', NULL, 'TPLOC_0007', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0511', 'BOUMOU', NULL, 'TPLOC_0007', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0512', 'MALATA', NULL, 'TPLOC_0007', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0513', 'BAKOULOU', NULL, 'TPLOC_0007', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0514', 'AUBEVILLE', NULL, 'TPLOC_0007', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0515', 'BOUBALA', NULL, 'TPLOC_0007', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0516', 'KOBE', NULL, 'TPLOC_0007', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0517', 'MASSOHO', NULL, 'TPLOC_0007', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0518', '(CQ TSIMBA) CAPABLE', NULL, 'TPLOC_0007', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0519', '15 ANS', NULL, 'TPLOC_0007', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0520', 'ITSIBOU', NULL, 'TPLOC_0007', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0521', 'TALANGAI', NULL, 'TPLOC_0007', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0522', 'YELLO', NULL, 'TPLOC_0007', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0523', 'MINZIKIDI', NULL, 'TPLOC_0007', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0524', 'BIALLA', NULL, 'TPLOC_0007', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0525', 'MADIDI', NULL, 'TPLOC_0007', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0526', 'TSOTSOLI ', NULL, 'TPLOC_0007', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0527', 'DOUNGILA', NULL, 'TPLOC_0007', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0528', 'VOUKA', NULL, 'TPLOC_0007', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0529', 'MOUBILI', NULL, 'TPLOC_0007', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0530', 'MAPIEME', NULL, 'TPLOC_0007', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0531', 'VOUKA', NULL, 'TPLOC_0007', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0532', 'MALETA', NULL, 'TPLOC_0007', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0533', 'OUANZI', NULL, 'TPLOC_0007', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0534', 'NTEMENE', NULL, 'TPLOC_0007', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0535', 'IKOLOLO', NULL, 'TPLOC_0007', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0536', 'MALEKE', NULL, 'TPLOC_0007', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0537', 'INDOUNGOU', NULL, 'TPLOC_0007', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0538', 'ALLEMBE VILLAGE', NULL, 'TPLOC_0007', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0539', 'MARIEN NGOUABI', NULL, 'TPLOC_0007', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0540', 'THOMAS SANKARA', NULL, 'TPLOC_0007', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0541', 'ABONGO', NULL, 'TPLOC_0007', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0542', 'MAKOUANGO', NULL, 'TPLOC_0007', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0543', 'MBEKA', NULL, 'TPLOC_0007', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0544', 'MOKOUABEKA', NULL, 'TPLOC_0007', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0545', 'ABILI', NULL, 'TPLOC_0007', 0, 'LOC_0440', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0546', 'MBEHENTSIO', NULL, 'TPLOC_0007', 0, 'LOC_0440', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0547', 'ASSINA', NULL, 'TPLOC_0007', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0548', 'MOLEDOU', NULL, 'TPLOC_0007', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0549', 'NGAEKE', NULL, 'TPLOC_0007', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0550', 'KIYINDA', NULL, 'TPLOC_0007', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0551', 'LOUINGUI-CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0552', 'MAHOUPA', NULL, 'TPLOC_0007', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0553', 'MANDOUNDOU', NULL, 'TPLOC_0007', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0554', 'MANTENSAMA', NULL, 'TPLOC_0007', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0555', 'MOUNKOUNKOU', NULL, 'TPLOC_0007', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0556', 'NKANA', NULL, 'TPLOC_0007', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0557', 'NSOUINI', NULL, 'TPLOC_0007', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0558', 'LOUMO-CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0559', 'N’SAKA', NULL, 'TPLOC_0007', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0560', 'MBANZA-MPOUDI 1', NULL, 'TPLOC_0007', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0561', 'MBANZA-MPOUDI 2', NULL, 'TPLOC_0007', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0562', 'MATSOUA', NULL, 'TPLOC_0007', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0563', 'BITELEMONO', NULL, 'TPLOC_0007', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0564', 'LOUKIMI', NULL, 'TPLOC_0007', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0565', 'MIENANZAMBI', NULL, 'TPLOC_0007', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0566', 'KONGO', NULL, 'TPLOC_0007', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0567', 'MOUTESSI', NULL, 'TPLOC_0007', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0568', 'MOUSSELE', NULL, 'TPLOC_0007', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0569', 'MBANDZA-NDOUNGA 1', NULL, 'TPLOC_0007', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0570', 'MBANDZA-NDOUNGA 2', NULL, 'TPLOC_0007', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0571', 'MAKAH', NULL, 'TPLOC_0007', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0572', 'MASSA', NULL, 'TPLOC_0007', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0573', 'MBANDAKA', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0574', 'PIERRE MATINGOU', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0575', 'FERIE', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0576', 'MADZIA', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0577', 'MAKANA', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0578', 'MATE 2', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0579', 'YETELA', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0580', 'YENGO NTSANA', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0581', 'MBILOU', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0582', 'NKOULOU', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0583', 'MAKANI', NULL, 'TPLOC_0007', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0584', 'MIHETE', NULL, 'TPLOC_0007', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0585', 'MBEMBA MAKOUEZI', NULL, 'TPLOC_0007', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0586', 'NDELA', NULL, 'TPLOC_0007', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0587', 'MIHOUMI', NULL, 'TPLOC_0007', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0588', 'NTSAMPOUKA', NULL, 'TPLOC_0007', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0589', 'BITALA 2', NULL, 'TPLOC_0007', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0590', 'MOUANA-NSANA', NULL, 'TPLOC_0007', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0591', 'MIYINDOU', NULL, 'TPLOC_0007', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0592', 'ODIBA', NULL, 'TPLOC_0007', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0593', 'EYOHO', NULL, 'TPLOC_0007', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0594', 'MBANDZA', NULL, 'TPLOC_0007', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0595', 'KONDA', NULL, 'TPLOC_0007', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0596', 'OSSONGUI', NULL, 'TPLOC_0007', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0597', 'ONANGA', NULL, 'TPLOC_0007', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0598', 'BOKONONGO', NULL, 'TPLOC_0007', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0599', 'LA PLAINE', NULL, 'TPLOC_0007', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0600', 'NTSINTSEYI', NULL, 'TPLOC_0007', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0601', 'VOUO', NULL, 'TPLOC_0007', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0602', 'AMBEYA', NULL, 'TPLOC_0007', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0603', 'ATSIANDZA', NULL, 'TPLOC_0007', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0604', 'MOUANANDZO', NULL, 'TPLOC_0007', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0605', 'ODOUMA', NULL, 'TPLOC_0007', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0606', 'SCIERIE', NULL, 'TPLOC_0007', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0607', 'PARADIS', NULL, 'TPLOC_0007', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0608', 'QUARTIER 1', NULL, 'TPLOC_0007', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0609', 'QUARTIER 2', NULL, 'TPLOC_0007', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0610', 'QUARTIER 3', NULL, 'TPLOC_0007', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0611', 'QUARTIER 4', NULL, 'TPLOC_0007', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0612', 'QUARTIER 5', NULL, 'TPLOC_0007', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0613', 'NGOMBE-CARREFOUR', NULL, 'TPLOC_0007', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0614', 'NGOMBE-KETTA', NULL, 'TPLOC_0007', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0615', 'NGOMBE-CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0616', 'NGOMBE-MOLILI 1', NULL, 'TPLOC_0007', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0617', 'NGOMBE-MOLILI 2', NULL, 'TPLOC_0007', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0618', 'LEMBA-LOUBOU SOULOU', NULL, 'TPLOC_0007', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0619', 'MABINDOU', NULL, 'TPLOC_0007', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0620', 'BOKO SONGHO CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0621', 'BOKO SONGHO CITE', NULL, 'TPLOC_0007', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0622', 'KIMPANDZOU', NULL, 'TPLOC_0007', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0623', 'KAYES MADI 1', NULL, 'TPLOC_0007', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0624', 'KAYES MADI 2', NULL, 'TPLOC_0007', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0625', 'KAYES OTTINO', NULL, 'TPLOC_0007', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0626', 'IMPOUMBOUIN', NULL, 'TPLOC_0007', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0627', 'KINGOUE VILLAGE', NULL, 'TPLOC_0007', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0628', 'MITOKO', NULL, 'TPLOC_0007', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0629', 'MPINI', NULL, 'TPLOC_0007', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0630', 'NKAH', NULL, 'TPLOC_0007', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0631', 'NTSIAKI', NULL, 'TPLOC_0007', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0632', 'DAHOMEY', NULL, 'TPLOC_0007', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0633', 'NOUFOUMA', NULL, 'TPLOC_0007', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0634', 'DZOUA', NULL, 'TPLOC_0007', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0635', 'KIMALOU II', NULL, 'TPLOC_0007', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0636', 'MAWATINA', NULL, 'TPLOC_0007', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0637', 'MBATERA', NULL, 'TPLOC_0007', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0638', 'MIAMI II', NULL, 'TPLOC_0007', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0639', 'NGAMIKALA', NULL, 'TPLOC_0007', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0640', 'NDZALIBATOUNGOU', NULL, 'TPLOC_0007', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0641', 'TSIAKI I', NULL, 'TPLOC_0007', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0642', 'TSIAKI NGOLO', NULL, 'TPLOC_0007', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0643', 'DIANGALA', NULL, 'TPLOC_0007', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0644', 'KIMPOKO', NULL, 'TPLOC_0007', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0645', 'LOUANGOU', NULL, 'TPLOC_0007', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0646', 'MAMBONDO', NULL, 'TPLOC_0007', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0647', 'MBOUNOU', NULL, 'TPLOC_0007', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0648', 'NTEMBELE', NULL, 'TPLOC_0007', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0649', 'TOUNGOUNA', NULL, 'TPLOC_0007', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0650', 'YAMBA CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0651', 'CQ 1 POSTE', NULL, 'TPLOC_0007', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0652', 'CQ 2 BANGALA', NULL, 'TPLOC_0007', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0653', 'CQ 3 CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0654', 'CQ 4 BOPENGOLA', NULL, 'TPLOC_0007', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0655', 'KAÏBINDA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0656', 'NGONDZO', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0657', 'IBONGA-MOUYINA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0658', 'IBANGOU', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0659', 'MOUROUNZOUENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0660', 'NGOUANGA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0661', 'NGUETSI', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0662', 'NGOUDOU', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0663', 'VOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0664', 'TSEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0665', 'BIKA-BITSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0666', 'MAMBA-NA YILOU', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0667', 'NGOKOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0668', 'KOLLA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0669', 'MILENGA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0670', 'KOUEDIKA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0671', 'PMD', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0672', 'NDILOU-MAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0673', 'BOUALA-BATOU', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0674', 'MBINZIALOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0675', 'DOUFOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0676', 'BIFOUFOU', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0677', 'MBOTA', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0678', 'MBIRIBI', NULL, 'TPLOC_0008', 0, 'LOC_0470', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0679', 'BOUALI', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0680', 'CARREFOUR', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0681', 'DIBOLA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0682', 'DIKOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0683', 'DIMANI', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0684', 'DIVENIE VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0685', 'DOUBILA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0686', 'IGNOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0687', 'ILOUMBOUSSIAWEKA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0688', 'INGUEBI', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0689', 'PEMO KB', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0690', 'INGUELI', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0691', 'ITABI-MOUKONDO', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0692', 'KALLA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0693', 'KOUYI', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0694', 'LEMBOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0695', 'MAMBOUANA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0696', 'BILOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0697', 'MIDOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0698', 'MOLLO', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0699', 'MOUDOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0700', 'MOUDOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0701', 'MOUFOUBOU', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0702', 'MOUHALA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0703', 'MOUHINGOU-BENGO', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0704', 'MOUKONDO', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0705', 'MOULOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0706', 'MOUNGOUTOU', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0707', 'MOUPITOU', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0708', 'MOUSSAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0709', 'MOUTSENGANI', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0710', 'NGONGO-VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0711', 'NYANGA-PAYSANNAT', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0712', 'NZAMBI-KALLA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0713', 'NZANZA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0714', 'SOUANGUI-1', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0715', 'SOUANGUI-2', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0716', 'VANGA', NULL, 'TPLOC_0008', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0717', 'LOUBANDILA', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0718', 'MOUKATSOU', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0719', 'PONT DU NIARI', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0720', 'MATOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0721', 'NGOKANGO', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0722', 'LEBOULOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0723', 'LEBOULOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0724', 'LEBOULOU 3', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0725', 'KAMBALA 1', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0726', 'KAMBALA 2', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0727', 'KIBOUTOU', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0728', 'KAYES', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0729', 'MABOUDOU', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0730', 'LOUBETSI', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0731', 'BANGONDO 1', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0732', 'BANGONDO 2', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0733', 'PANGA 1', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0734', 'PANGA 2', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0735', 'KOUSSOU', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0736', 'NOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0737', 'MAROUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0738', 'MOUYOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0739', 'DIMBOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0740', 'MALLEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0741', 'MOUKABA', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0742', 'MOUSSONDJI', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0743', 'NYANGA-DILEMBI', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0744', 'PORO', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0745', 'LOANGO', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0746', 'BITIBA', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0747', 'CAMP SFIB', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0748', 'NGOUA 2', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0749', 'LOUFOULA PONT', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0750', 'LOUFOULA CENTRE', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0751', 'NGONGO', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0752', 'KARINZOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0753', 'MBADI', NULL, 'TPLOC_0008', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0754', 'KIKASSA 1', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0755', 'KIKASSA 2', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0756', 'MANGA-KALA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0757', 'MOUSSINGA- KIKOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0758', 'ILOU PANGA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0759', 'TSATOU-MBENGOU', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0760', 'YANDZA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0761', 'PANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0762', 'BOUSSAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0763', 'NGANDA-MBINDA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0764', 'MOUKONDZI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0765', 'MOUKEKE', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0766', 'KOUMINA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0767', 'MOUBANGOU', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0768', 'KIFOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0769', 'MOUKOMO- KADI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0770', 'KALA-MOUINI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0771', 'TSOUNDI- KITSESSI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0772', 'KITAMBA 1', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0773', 'KITAMBA 2', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0774', 'BOUKOU-MOUKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0775', 'KONGO', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0776', 'NGONGO', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0777', 'KIPANDZOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0778', 'KIPANDZOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0779', 'HOUDIN', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0780', 'KILEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0781', 'KOUNGOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0782', 'KOUNGOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0783', 'MATEMBO 1', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0784', 'MATEMBO 2', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0785', 'KIMBENZA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0786', 'KITSOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0787', 'PAKA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0788', 'BAZIKA- BAZEBI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0789', 'MANGOLA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0790', 'MBOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0791', 'KITSAKA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0792', 'LOUILA 1', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0793', 'LOUILA 2', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0794', 'KISSISSI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0795', 'FOUMVOU', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0796', 'KITSINDI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0797', 'KIKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0798', 'MAYINGUI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0799', 'NDINGUI-MALEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0800', 'BONDIKA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0801', 'MBEKI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0802', 'YENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0803', 'MOUSSOKI PE', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0804', 'KAYES', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0805', 'KIDOUBI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0806', 'KITSOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0807', 'MIDIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0808', 'KIKIONGA', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0809', 'KINDOUNGA 2', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0810', 'POUSSIDI', NULL, 'TPLOC_0008', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0811', 'BANDA-KAYES', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0812', 'BANDE-MOUANDA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0813', 'BISSIASSIA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0814', 'BOUISSA-DIELA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0815', 'DIAMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0816', 'DUBLIN', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0817', 'HINDA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0818', 'JOLY-SOIR', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0819', 'KAYES-BONGA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0820', 'KAYES-BAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0821', 'KAYES-YOKA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0822', 'KIKONDI', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0823', 'KIMBAOKA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0824', 'KIMBEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0825', 'KIMOUANDA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0826', 'KINDAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0827', 'KINDAMBA-KIANGOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0828', 'KINDAMBA-MBOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0829', 'KINDAMBA-SIKILA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0830', 'KINDAMBA-YENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0831', 'KIDIANGOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0832', 'KINDOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0833', 'KINGOUA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0834', 'KINGOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0835', 'KINIANGOUNA- MBOUNZOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0836', 'KINIATI 1', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0837', 'KINIATI 2', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0838', 'KINTAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0839', 'KINZOLANI 1', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0840', 'KINZOLANI 2', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0841', 'KIPANDA 1', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0842', 'KIPANDA 2', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0843', 'KIPANGALA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0844', 'KIPONGUI', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0845', 'KITSAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0846', 'KITSEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0847', 'KIVOUANDA-TSOUSSOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0848', 'LONDELA–POUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0849', 'MABOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0850', 'MAMPOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0851', 'MBIONGO', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0852', 'MBOTA-MAKOKO', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0853', 'MIKINDOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0854', 'MIKONDO 1', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0855', 'MIKONDO 2', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0856', 'MOUKONDZI-NONGO', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0857', 'MOULOUNDOU- KONDI', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0858', 'MOUTOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0859', 'NGOUNGOUTI', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0860', 'NKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0861', 'NZO-PANDZOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0862', 'TOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0863', 'TSANDA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0864', 'TSANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0865', 'TSONGO-NGOMA', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0866', 'TSOUKOU-MALELE', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0867', 'YANDZA-MBAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0474', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0868', 'AMERIQUE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0869', 'BAMANGA-NIENZE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0870', 'BANDZABI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0871', 'BATEKE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0872', 'BITOUSSI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0873', 'BOLO', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0874', 'CARLOS 1', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0875', 'CARLOS 2', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0876', 'CITE DE DAVID', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0877', 'DEUX MANGUIERS', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0878', 'DEUXIEME PONT', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0879', 'DIAMBOU-FOUANA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0880', 'DIBENIE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0881', 'DIBINDOU', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0882', 'DITADI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0883', 'GABRIEL', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0884', 'ILOU-KAYES(PLM)', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00');
INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_0885', 'KAMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0886', 'KAYES-MBOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0887', 'KEKELE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0888', 'KIABA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0889', 'KIBINDOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0890', 'KIKANOU', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0891', 'KIPELE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0892', 'KIYALA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0893', 'KONDA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0894', 'KOTOKIPESSI 1', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0895', 'KOTOKIPESSI 2', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0896', 'KOUKEBESSENE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0897', 'KOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0898', 'KOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0899', 'LES BANDAS', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0900', 'LOUVAKOU-BATEKE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0901', 'LOUVAKOU-MOUNIONDZI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0902', 'LOUVAKOU-PAYSANNAT', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0903', 'MABEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0904', 'MABIDI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0905', 'MABOKO-CENTRE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0906', 'MABOKO-NGUENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0907', 'MABOKO RETRAITE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0908', 'MABOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0909', 'MALANGA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0910', 'MALOLO 1', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0911', 'MALOLO 2', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0912', 'MATSELIKA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0913', 'MBIMI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0914', 'MBOUKOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0915', 'MBOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0916', 'MIKIZOU', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0917', 'MIKOUMBI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0918', 'MILA-MILA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0919', 'MILONDO', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0920', 'MOUBANDI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0921', 'MOUBEYI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0922', 'MOUKANDA-NGO', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0923', 'MOUKONDO-LEPROSERIE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0924', 'MOUKONDO VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0925', 'MOUKONDZI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0926', 'MOUKOUASSI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0927', 'MOULENDE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0928', 'MOUNDOUNDOU-MBAYA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0929', 'MOUSSEMI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0930', 'MOUTENDE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0931', 'MOYEN DE VIVRE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0932', 'NDEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0933', 'NDOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0934', 'NGANDA-SIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0935', 'NGONGUI ZOLA- PANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0936', 'NGOYO-MATSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0937', 'NZAOU', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0938', 'NZOUNGO-KIBANGOU', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0939', 'PASSI- PASS', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0940', 'SIX MANGUIERS', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0941', 'SOSSI', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0942', 'TAO-TAO', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0943', 'TATY', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0944', 'TROIS MANGUIERS', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0945', 'YANGA', NULL, 'TPLOC_0008', 0, 'LOC_0475', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0946', 'MINGOUENGUELE VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0947', 'MOUKONDO-YAMA', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0948', 'SABOUKOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0949', 'MIVEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0950', 'NGOBISSALA', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0951', 'MATALILA 1', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0952', 'MATALILA 2', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0953', 'MATALILA CENTRE', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0954', 'FORALAC', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0955', 'MABOUADA', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0956', 'LEWANDA', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0957', 'POUNDZA', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0958', 'SENGUELE', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0959', 'NGOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0960', 'LEGANDA', NULL, 'TPLOC_0008', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0961', 'BANDZOKO', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0962', 'DOUMANI', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0963', 'KOUANGA', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0964', 'LESSOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0965', 'MAKOKO', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0966', 'MATOTO', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0967', 'MAYALA', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0968', 'MAYOKO GARE', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0969', 'M’BAKA', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0970', 'M’BAYA', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0971', 'MINGANANGA', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0972', 'NIE-NIE', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0973', 'SIEBA', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0974', 'SIMBA LEHALA', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0975', 'SIMBA-MIHANDA', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0976', 'TSINGUIDI', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0977', 'VOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0477', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0978', 'REVOLUTION', NULL, 'TPLOC_0008', 0, 'LOC_0478', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0979', 'LEBOLO', NULL, 'TPLOC_0008', 0, 'LOC_0478', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0980', 'NGONGO-NGOUBOU', NULL, 'TPLOC_0008', 0, 'LOC_0478', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0981', 'BOUDINGA-NZALAMOYI', NULL, 'TPLOC_0008', 0, 'LOC_0478', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0982', 'MOUNGOUNDOU-NORD VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0983', 'BINANGA', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0984', 'NGOUBOU-NGOUBOU', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0985', 'BALI', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0986', 'MOUSSESSE', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0987', 'MOUPATA', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0988', 'MBIHA', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0989', 'POUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0990', 'LEBAGNIE', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0991', 'SINDALA', NULL, 'TPLOC_0008', 0, 'LOC_0479', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0992', 'BAKELE', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0993', 'KISSIELE', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0994', 'BOUPANDA', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0995', 'MAYAMA', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0996', 'NZIMA', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0997', 'DIBA-DIBA', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0998', 'MABALA', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_0999', 'MOULAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1000', 'INDZENDI', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1001', 'MABAMOUBOU', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1002', 'POUDI', NULL, 'TPLOC_0008', 0, 'LOC_0480', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1003', 'BOUDIANGA GARE', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1004', 'BOUDIANGA VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1005', 'MBOUDJI PK 106', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1006', 'MAYITOULA 2 PK 109', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1007', 'MAYITOULA 1 PK 110', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1008', 'MABAFI', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1009', 'TITI', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1010', 'ITSOTSO', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1011', 'NGALA', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1012', 'LEBOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1013', 'KANGA', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1014', 'KOUMOUTSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1015', 'MASSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1016', 'NGONO', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1017', 'PORO', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1018', 'SIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1019', 'KAMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1020', 'NGOUA 2', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1021', 'MOUVENZE', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1022', 'MOURALA', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1023', 'SALA', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1024', 'LITSANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1025', 'MITELE', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1026', 'NGOUELE', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1027', 'MOUTEBE', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1028', 'MARALA', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1029', 'OUBOUESSE', NULL, 'TPLOC_0008', 0, 'LOC_0481', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1030', 'NZEDE', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1031', 'NDENDE- CONGO', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1032', 'BATSENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1033', 'MOUNAMA', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1034', 'IVAROU', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1035', 'LONGO', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1036', 'MBENGUI-MOUDJAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1037', 'LONGANA', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1038', 'KANA- NYANGA', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1039', 'PEMO- KAND', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1040', 'BIHONGO', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1041', 'KAMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1042', 'MOUNGOUDI', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1043', 'MOURANI', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1044', 'MIHOUMBI', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1045', 'DOUKANGA', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1046', 'MOUSSOGO', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1047', 'DITSANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1048', 'OUDJONGO', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1049', 'NGONGO', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1050', 'NGOUANGA', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1051', 'MOUKOUNZA', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1052', 'IROGO', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1053', 'POUKA', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1054', 'MOUYOMBI', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1055', 'BILENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1056', 'ASIA- VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0482', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1057', 'MATADI', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1058', 'NGONAKA', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1059', 'MOUTAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1060', 'MOUSSANA', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1061', 'BIBAYI', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1062', 'MIKOUBOU', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1063', 'NYANGA', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1064', 'BOUDZOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1065', 'MINGAYA', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1066', 'IPINI', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1067', 'MOUYALA', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1068', 'BITOLO', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1069', 'OMOYE', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1070', 'BIBAKA', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1071', 'NZABI', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1072', 'KIKOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1073', 'MOUMBILI', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1074', 'BIKELELE', NULL, 'TPLOC_0008', 0, 'LOC_0483', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1075', 'KOLO', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1076', 'MADZALA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1077', 'TALA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1078', 'MOUDANBA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1079', 'MVOUARA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1080', 'YANGA- MANGORI', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1081', 'KENDI', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1082', 'KISSIAMBI', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1083', 'NZEBE', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1084', 'KIMANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1085', 'KINKOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1086', 'MANENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1087', 'KINGOUAMA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1088', 'MOUTOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1089', 'MAHAMANA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1090', 'OSSIBA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1091', 'BIHOUA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1092', 'MAKOUBI', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1093', 'KILEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1094', 'KOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1095', 'KIKO NDE', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1096', 'CONGO- MALEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1097', 'LISSIEMI', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1098', 'MOUNGOMO', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1099', 'BEKOL', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1100', 'MISSAMA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1101', 'MAPATI', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1102', 'MAMBOUANA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1103', 'MBAKA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1104', 'OUANDZI', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1105', 'LEKOLI', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1106', 'LOYO', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1107', 'BENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1108', 'BIDOUA', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1109', 'BIKIE', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1110', 'MOUKASSI', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1111', 'IBE', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1112', 'SATOU', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1113', 'MASSIENDZI- MANOMO', NULL, 'TPLOC_0008', 0, 'LOC_0455', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1114', 'MBILA', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1115', 'MAKAGA', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1116', 'MITOKO', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1117', 'VOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1118', 'KINGANI', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1119', 'DOUAKANI', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1120', 'MOUSSAHOU', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1121', 'LEFOUTOU', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1122', 'MOUTOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1123', 'MVAKALA', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1124', 'MBAYA', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1125', 'MOKINA', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1126', 'NGONAKA', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1127', 'MOETCHE', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1128', 'OMOY', NULL, 'TPLOC_0008', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1129', 'MOUKILA', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1130', 'NDOUO', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1131', 'BANDZIE', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1132', 'MALIMA', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1133', 'OGOOUE', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1134', 'INGOUMINA', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1135', 'ONKOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1136', 'OLOUO', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1137', 'OBILI', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1138', 'KEINKELE', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1139', 'INGOUATSINI', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1140', 'MAKELE', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1141', 'BOUKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1142', 'ONDAMA', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1143', 'LIKOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1144', 'MADZOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1145', 'KENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1146', 'SIESSE', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1147', 'YOMI', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1148', 'KIMBOTO', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1149', 'INGA', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1150', 'TONGO', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1151', 'MBOMO', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1152', 'SALA- MBAMA', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1153', 'MOUKOGO- MITIENE', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1154', 'INGOLO 2', NULL, 'TPLOC_0008', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1155', 'SIMOMBONDO', NULL, 'TPLOC_0008', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1156', 'MAVOUNOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1157', 'BANDOYE', NULL, 'TPLOC_0008', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1158', 'DZANGA', NULL, 'TPLOC_0008', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1159', 'LOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1160', 'LEBAYI', NULL, 'TPLOC_0008', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1161', 'LEWALA', NULL, 'TPLOC_0008', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1162', 'LEWEME', NULL, 'TPLOC_0008', 0, 'LOC_0458', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1163', 'MINGUELE', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1164', 'OUAKA', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1165', 'MOUSSOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1166', 'MIMBASSI', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1167', 'IKAYA', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1168', 'NDZIEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1169', 'LILENDE', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1170', 'INDZIERI', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1171', 'MAKANDA', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1172', 'MOUFILOU', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1173', 'IDOUBI', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1174', 'PANDA', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1175', 'BOUDOUHOU', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1176', 'IHOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1177', 'MAKOTO', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1178', 'LIKOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1179', 'MIKAKAYA', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1180', 'IMBEYE', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1181', 'DOUDOU', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1182', 'MATOTO', NULL, 'TPLOC_0008', 0, 'LOC_0459', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1183', 'NKOTCHI- FOUTA', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1184', 'FOUTA', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1185', 'SEKA', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1186', 'SANZA', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1187', 'TCHIANDA', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1188', 'MBOUL’TOMBI', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1189', 'TANDOU BINZENZE', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1190', 'TANDOU MBOMA', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1191', 'MBOUSSOU', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1192', 'CAYO', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1193', 'BANGA CAYO', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1194', 'TCHIZOMONGO', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1195', 'TCHITANZI', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1196', 'TCHIMINZI', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1197', 'KOULOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1198', 'BILOLO', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1199', 'MANENGA', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1200', 'MBOUSEMI', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1201', 'DJEBBA', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1202', 'TCHISSAKATA', NULL, 'TPLOC_0008', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1203', 'ABAH', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1204', 'AKABI', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1205', 'AKOU', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1206', 'ALLIEME', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1207', 'ATIE-ATIE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1208', 'BALA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1209', 'BALI', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1210', 'COMO', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1211', 'EBONGO 1', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1212', 'EBONGO 2', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1213', 'EBOU', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1214', 'EBOYO 1', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1215', 'EBOYO 2', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1216', 'EBVOURA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1217', 'EKO', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1218', 'EKOLO KONGALEKOU', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1219', 'EKOUASSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1220', 'ELIE-ELIE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1221', 'ENDZOLO et ETORO', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1222', 'ENTA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1223', 'ENTSIELI', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1224', 'EPOH', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1225', 'ESSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1226', 'EWABA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1227', 'EYOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1228', 'IBEA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1229', 'IBONGA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1230', 'ILOLO', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1231', 'IBEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1232', 'ITOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1233', 'KENDOUO', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1234', 'KINGUIEN', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1235', 'KOSSONA 1', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1236', 'KOSSONA 2', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1237', 'LEBOALEKOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1238', 'MBANZA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1239', 'MBANGOUONI', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1240', 'MBE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1241', 'MBE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1242', 'MBOUBEE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1243', 'MOUAN', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1244', 'MOUONI', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1245', 'MVIE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1246', 'NDOUOMI', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1247', 'NGAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1248', 'NKAN', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1249', 'NKANA-KELLIEME', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1250', 'NKOUE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1251', 'NKOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1252', 'NTOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1253', 'NTOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1254', 'OBAN', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1255', 'OBELENGO', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1256', 'OBEME', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1257', 'ODJOULI', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1258', 'OKAGA 1', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1259', 'OKAGA 2', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1260', 'OKAMAMOUE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1261', 'OKEKE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1262', 'OKEKE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1263', 'OKIEMOUE-ALLAH', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1264', 'OKONDO', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1265', 'OKONGA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1266', 'OKOUEBE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1267', 'ODZALA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1268', 'ONGUENDE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1269', 'OSSAH(OOliongo-atsono)', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1270', 'OSSAO', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1271', 'OSSELE 1', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1272', 'OSSELE 2', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1273', 'OSSELE POSTE', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1274', 'OTALI', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1275', 'OTSOUANVA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1276', 'OYABA', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1277', 'OYALI', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1278', 'OYO', NULL, 'TPLOC_0008', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1279', 'ABAH', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1280', 'ABELA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1281', 'AKOU', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1282', 'ALLEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1283', 'ANGUIEME', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1284', 'BOKA 1', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1285', 'BOKA 2', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1286', 'BOKA 3', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1287', 'EKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1288', 'ETSELE', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1289', 'KADZOUNI-ONGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1290', 'KENGOUONI', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1291', 'MBONGA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1292', 'NGAMBOKO', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1293', 'NGANKOMA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1294', 'NGANTALA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1295', 'NKANA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1296', 'OBEA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1297', 'OKALI', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1298', 'OKAYA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1299', 'OKOGO', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1300', 'OKONDA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1301', 'OLLEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1302', 'ONGOLI', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1303', 'OSSANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1304', 'OTSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1305', 'POSSI', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1306', 'TSANI', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1307', 'YAMA', NULL, 'TPLOC_0008', 0, 'LOC_0432', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1308', 'ABBA 1', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1309', 'ABBA 2', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1310', 'ANKARI', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1311', 'ANTORO', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1312', 'EBALA', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1313', 'EPARI', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1314', 'KAON', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1315', 'KIALE', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1316', 'MBALI', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1317', 'MPOUANDZIO', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1318', 'NKAMBA-MOWE', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1319', 'OMBION', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1320', 'OMPAO', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1321', 'OTSOUANKE', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1322', 'OSSA', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1323', 'VONO', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1324', 'YONO', NULL, 'TPLOC_0008', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1325', 'ACKOR', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1326', 'AFFO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1327', 'AKANA-LETO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1328', 'AKANA(Obaba)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1329', 'AKIELE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1330', 'AKOU(Etoro)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1331', 'AKOU(Embounou)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1332', 'ALLIEN', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1333', 'AMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00');
INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_1334', 'AMBOUE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1335', 'ANDOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1336', 'ANDZION', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1337', 'ANGOUE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1338', 'ANGOUERE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1339', 'ANGOULOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1340', 'ANGOULOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1341', 'ANKI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1342', 'ANKONO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1343', 'ASSENE( Etoro)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1344', 'ASSENE LETO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1345', 'ASSENE OTTUI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1346', 'ASSIELE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1347', 'ASSIENE EKO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1348', 'BENE ENIAMA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1349', 'BOMABOUROU', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1350', 'BOUANGA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1351', 'BOUNDJI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1352', 'BOUEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1353', 'DAMAS', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1354', 'EBIESSE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1355', 'EBOUI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1356', 'EBOUI (Mbaya)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1357', 'EDZOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1358', 'EKO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1359', 'ELION', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1360', 'ELOUO 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1361', 'ELOUO 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1362', 'EMBANA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1363', 'EMBOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1364', 'EMOUA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1365', 'EMPORO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1366', 'ENGAKOUN', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1367', 'ENIAMA-NGAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1368', 'ENTA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1369', 'EPOUENE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1370', 'ESSIALA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1371', 'ETOLO OBABA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1372', 'ETOLO PLAINE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1373', 'ETONTON', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1374', 'ETORO 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1375', 'ETORO 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1376', 'FOBO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1377', 'GENEVE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1378', 'IMPINI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1379', 'INDO 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1380', 'INDO 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1381', 'INGA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1382', 'INGOUELE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1383', 'INGOUENE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1384', 'INKAN', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1385', 'INKANTSIANA 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1386', 'INKANTSIANA 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1387', 'INKOUELE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1388', 'INTALA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1389', 'INTSESSE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1390', 'INTSIALA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1391', 'INTSIANA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1392', 'INTSIANVA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1393', 'INTSIELE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1394', 'ITALIE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1395', 'KIAKOUO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1396', 'KIRI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1397', 'KOMO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1398', 'KONGA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1399', 'KONO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1400', 'LEBOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1401', 'LEBOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1402', 'LEFOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1403', 'LEKANA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1404', 'LESSIA-NTSOU', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1405', 'LETO 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1406', 'LETO 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1407', 'MASSALI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1408', 'MATADI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1409', 'MATADI(Ngakielé)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1410', 'MBAN', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1411', 'MBAYA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1412', 'MBEMPO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1413', 'MBESSOH', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1414', 'MBOBI 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1415', 'MBOBI 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1416', 'MBOLOMON 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1417', 'MBOLOMON 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1418', 'MFOU', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1419', 'MOLENDE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1420', 'MOTEMA-PEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1421', 'MPAN (Mbaya)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1422', 'MPAN LETO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1423', 'MPANA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1424', 'MPOUAYAN-INGOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1425', 'MPOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1426', 'NDABA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1427', 'NDENDABA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1428', 'NDOLO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1429', 'NDZABA 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1430', 'NDZABA 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1431', 'NGAFOULA 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1432', 'NGAFOULA 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1433', 'NGAKIELE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1434', 'NGOBANA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1435', 'NGOUI 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1436', 'NGOUI 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1437', 'NGUIEN 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1438', 'NGUIEN 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1439', 'NKAN', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1440', 'NTSOU(Ecole)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1441', 'NTSOU (Ingouele)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1442', 'OBABA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1443', 'OBALA (ETORO)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1444', 'OBALA (EKO)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1445', 'OBAN', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1446', 'OBELE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1447', 'OBILAMBOMA 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1448', 'OBILAMBOMA 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1449', 'OBILAMBOMO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1450', 'OBO 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1451', 'OBO 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1452', 'ODZIO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1453', 'ODZISSAN', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1454', 'OKAMOUE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1455', 'OKIELE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1456', 'OKIENE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1457', 'OKOMOUE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1458', 'OKOO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1459', 'OKOUAMPI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1460', 'OKOUNGA (BORDEAUX )', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1461', 'OKOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1462', 'OKOUON', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1463', 'OLI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1464', 'OLLOUO 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1465', 'OLLOUO 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1466', 'OLONTSIO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1467', 'OMPHOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1468', 'ONDABA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1469', 'ONGALA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1470', 'ONGALI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1471', 'ONGOYE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1472', 'ONKEYI (SANGOLO)', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1473', 'ONNAN', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1474', 'ONSI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1475', 'OSSIO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1476', 'OSSO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1477', 'OSSONDO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1478', 'OTALA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1479', 'OTSOUAMPAIRE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1480', 'OTTUI', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1481', 'OYALA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1482', 'OYONTSOUO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1483', 'SIONA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1484', 'TALA-NA-MISO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1485', 'TALANGAI GENTIL', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1486', 'TANGA-NORD', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1487', 'TSAMPOKO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1488', 'TSOUANKA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1489', 'TSOUO', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1490', 'VILLE PROPRE', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1491', 'YABA', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1492', 'YALA 1', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1493', 'YALA 2', NULL, 'TPLOC_0008', 0, 'LOC_0434', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1494', 'ABBI', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1495', 'ABILI', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1496', 'ADZI', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1497', 'AKOUI', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1498', 'ALLION', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1499', 'ANDZOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1500', 'AYAMA MPOUROU', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1501', 'AYO', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1502', 'EBOU', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1503', 'EDZOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1504', 'EKONO', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1505', 'EKOROKORO', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1506', 'ELOUNA', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1507', 'ESSOUA', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1508', 'ETSOUALI', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1509', 'GAMPION', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1510', 'IMPAN', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1511', 'IMPE', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1512', 'IMPOH 1', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1513', 'IMPOH 2', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1514', 'LOME', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1515', 'MBEOKALA', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1516', 'MPE', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1517', 'MPOH', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1518', 'NGO 2', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1519', 'NKOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1520', 'NSAH', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1521', 'OKAH', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1522', 'OKIENE', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1523', 'OKOMOUE', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1524', 'OLONO', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1525', 'OMBIMA', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1526', 'ONIANVA', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1527', 'ONTCHOUO', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1528', 'OYONFOULA', NULL, 'TPLOC_0008', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1529', 'AKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1530', 'AKOU', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1531', 'AMPAKA', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1532', 'ANGAMA', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1533', 'ANGUIEME', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1534', 'EMOMPIBI', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1535', 'INGA-NTALI', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1536', 'IMPINI', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1537', 'KEBARA', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1538', 'LAGUE', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1539', 'LEKANA- BIRI', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1540', 'NGOULONKILA', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1541', 'NKOUA', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1542', 'NKOUTY', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1543', 'NTSAMA', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1544', 'OLELE', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1545', 'ONTOUROU', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1546', 'OTSALAKA', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1547', 'SOUO', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1548', 'TCHOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1549', 'ABATSAMBE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1550', 'ABOH', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1551', 'AKANA-BOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1552', 'AKIELE ANGOUENE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1553', 'AKIELE ATSONGO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1554', 'AKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1555', 'AKONGO-OKOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1556', 'AKOUSSIKA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1557', 'AMBOMBONGO 1', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1558', 'ASSENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1559', 'ASSONILABAME', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1560', 'BENE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1561', 'BOLLO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1562', 'BOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1563', 'COMO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1564', 'EBAH', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1565', 'EKASSA-ONDINGA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1566', 'EKASSA BOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1567', 'EKOLAYOA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1568', 'ELLO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1569', 'ELONDI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1570', 'EMBOULI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1571', 'ENDOLO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1572', 'ENGA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1573', 'EPAH-NGAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1574', 'EPOUGNOU', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1575', 'EPOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1576', 'ESSEBILI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1577', 'ETALA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1578', 'GOUENE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1579', 'IBABI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1580', 'IBANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1581', 'IFILIFOA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1582', 'IKARE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1583', 'IKOUELE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1584', 'INGONDO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1585', 'IPOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1586', 'ITANDI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1587', 'KABA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1588', 'KALA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1589', 'KANI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1590', 'KOLI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1591', 'KONDZI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1592', 'MABIROU', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1593', 'MAPEME', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1594', 'MASSALI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1595', 'MBANDZA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1596', 'MBOBO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1597', 'MBOMA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1598', 'MOLOMO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1599', 'NDIMI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1600', 'NDJOUH', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1601', 'NDONGO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1602', 'NDONGO-OBINDI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1603', 'NDONGO OMIO 1', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1604', 'NDONGO OMIO 2', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1605', 'NGAMAKOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1606', 'NGANIA OSSEBI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1607', 'NGANIA TSONGO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1608', 'NGOSSI-NGOSSI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1609', 'NGUELOKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1610', 'NGUIELE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1611', 'NGUIELOKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1612', 'OBATSENE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1613', 'OBOYA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1614', 'ODZANDONGO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1615', 'ODZOLO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1616', 'OKA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1617', 'OKAMAMOUE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1618', 'OKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1619', 'OKIBA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1620', 'OKOO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1621', 'OKOO-TSAGNI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1622', 'OLANA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1623', 'ONGOLI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1624', 'OSSAH', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1625', 'OTALI 1', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1626', 'OTALI 2', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1627', 'OTALI 3', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1628', 'OTSINI', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1629', 'OYAH', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1630', 'OYONGO 1', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1631', 'OYONGO 2', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1632', 'POMBO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1633', 'PONAMOUE', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1634', 'POUSSOU', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1635', 'TALANAMISSO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1636', 'TORO', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1637', 'TSODZOU', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1638', 'TSOKIA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1639', 'TSOLOKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1640', 'YABA', NULL, 'TPLOC_0008', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1641', 'BABOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1642', 'BOBANA 1', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1643', 'BOBANA 2', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1644', 'BOBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1645', 'BOBI', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1646', 'BOBOUANKOSSO)', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1647', 'BODOUANGO 1', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1648', 'BODOUANGO 2', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1649', 'BODZEKA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1650', 'BOKEKA- DINOU', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1651', 'BOKONONGO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1652', 'BOKOUAMBOLO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1653', 'BOLAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1654', 'BOSSOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1655', 'BONTSONGO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1656', 'BOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1657', 'BOUNDJI', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1658', 'BOYEYE', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1659', 'DZONGO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1660', 'EBAH', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1661', 'EBANA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1662', 'EHERE', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1663', 'ELONDI', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1664', 'IBOULI', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1665', 'KONGA 1', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1666', 'KONGA 2', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1667', 'KONGO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1668', 'LEBOULA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1669', 'LEKOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1670', 'LELOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1671', 'MABOUA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1672', 'MASSOMINA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1673', 'MBANTSE', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1674', 'MEKALA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1675', 'MELANGA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1676', 'MIBOTO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1677', 'MOBEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1678', 'MOPOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1679', 'MOPONGO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1680', 'MOTOKO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1681', 'MOTOKOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1682', 'MOUAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1683', 'NDZALOKONDZO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1684', 'NGUEMBOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1685', 'NKALA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1686', 'NKANGA-NKANGA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1687', 'NKOGNI- NKOGNI', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1688', 'OBENDZA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1689', 'OBETSENE', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1690', 'OKIELE', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1691', 'OSSA 2', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1692', 'OSSELE', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1693', 'TANGA- LENGA', NULL, 'TPLOC_0008', 0, 'LOC_0438', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1694', 'AKANA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1695', 'ALLA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1696', 'BANGASSOU', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1697', 'BELLET', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1698', 'EKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1699', 'ENDOLO', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1700', 'ENDZEI', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1701', 'IFFOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1702', 'ILONGA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1703', 'INGUINA 1 et 2', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1704', 'IPOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1705', 'ITOH', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1706', 'KAH-ONGALE', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1707', 'KANA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1708', 'KAYES 1', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1709', 'KAYES 2', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1710', 'KOMO', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1711', 'KONOSSO 1', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1712', 'KONOSSO 2', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1713', 'LESSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1714', 'MBANDZA IKIE', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1715', 'MBANDZA KOLIMA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1716', 'MBE CARREFOUR', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1717', 'MBEKANGA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1718', 'MBENGUEKO', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1719', 'MBOBI', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1720', 'MBOBI-OBILAMBOMO', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1721', 'MBOLEMON', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1722', 'MOKILI PASSI', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1723', 'MOSSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1724', 'NDONGO', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1725', 'NGAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1726', 'NGAMBA AKIESSE', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1727', 'NGUELAKOMO', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1728', 'OBALA 1', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1729', 'OBALA 2', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1730', 'OBANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1731', 'ODZATONI', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1732', 'OKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1733', 'OKAYE', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1734', 'OKONGA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1735', 'OLLEME', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1736', 'ONGO', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1737', 'ONGOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1738', 'ONGOYE', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1739', 'OTONA KONONO', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1740', 'OWE', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1741', 'OYALE', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1742', 'OYANI', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1743', 'PASSA', NULL, 'TPLOC_0008', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1744', 'ANDZIEME', NULL, 'TPLOC_0008', 0, 'LOC_0440', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1745', 'INGOUONI- MOKE', NULL, 'TPLOC_0008', 0, 'LOC_0440', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1746', 'MAH', NULL, 'TPLOC_0008', 0, 'LOC_0440', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1747', 'MINGO', NULL, 'TPLOC_0008', 0, 'LOC_0440', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1748', 'OBOLI', NULL, 'TPLOC_0008', 0, 'LOC_0440', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1749', 'OLOUNOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0440', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1750', 'OLOUNOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0440', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1751', 'ATTENDEZ', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1752', 'BALEMON', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1753', 'BOBAKAMPONDZA', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1754', 'BOBILAMBOMO', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1755', 'BOUALANGA', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1756', 'BOWANDO 1', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1757', 'BOWANDO 2', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1758', 'IMBOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1759', 'LENIONGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1760', 'LILANGALA', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1761', 'MAPE 1', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1762', 'MAPE 2', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1763', 'MIBE', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1764', 'MONGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1765', 'MPALA LEFINI 1', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1766', 'MPALA- LEFINI 2', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1767', 'MPOUYA- MOKE', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1768', 'NGANTSIAKIE', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1769', 'NKASSA NDZIBA', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1770', 'NKASSA VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1771', 'NTOTO- MOTANI', NULL, 'TPLOC_0008', 0, 'LOC_0441', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1772', 'BANDA- MBANZA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1773', 'BIDI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1774', 'BINKONGA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1775', 'BITSIKA-MAYINAMA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1776', 'BOUDZOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1777', 'KIAZI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1778', 'KIBOUENDE', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1779', 'KIMBELE', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1780', 'KIMBETI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1781', 'KINGOMA-DIBENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1782', 'KINIMBI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1783', 'KINKAKASA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00');
INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_1784', 'KINSELE', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1785', 'KINZEZA-KIMBOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1786', 'KISSENGUELE', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1787', 'LOUBEBEZI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1788', 'LOUGHEMO', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1789', 'LOUKOKO', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1790', 'LOUKOUNGA-KIMPOLO', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1791', 'MAFOUSSI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1792', 'MALABA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1793', 'MANGALA-KOUBOLA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1794', 'MBAMA MALODI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1795', 'MANKONDI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1796', 'MASSANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1797', 'MATAKA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1798', 'MAZI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1799', 'MBIEDI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1800', 'MIFOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1801', 'MISSAFOU (MBANZA-TIBI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1802', 'MITSINDOU', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1803', 'MOULENDA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1804', 'MOUTEMBESSA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1805', 'MOUNTSOUELE', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1806', 'MOUNZENZE', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1807', 'NGAMBOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1808', 'NGAMIBAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1809', 'NGOLIBA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1810', 'NGOMBE-MPATI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1811', 'NKAMA MBANGALA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1812', 'NKOUKA MPASSI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1813', 'NZAZA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1814', 'OUANDA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1815', 'SAKAMESSO', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1816', 'SESSE-DIA-BANTOU', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1817', 'TADI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1818', 'VOUNGOUTA', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1819', 'ZOULOU NKABI', NULL, 'TPLOC_0008', 0, 'LOC_0442', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1820', 'MPANGOU', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1821', 'MAKONKO', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1822', 'KIMBOUTA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1823', 'MBADZA-NTOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1824', 'MBANZA-NKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1825', 'NKALA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1826', 'NKOKI', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1827', 'MAYOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1828', 'MANKOUSSOU', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1829', 'NSANGA-MVIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1830', 'KIMBASSI', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1831', 'MFOUMBOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1832', 'MFOUMBOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1833', 'MBANDZA-MBEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1834', 'KINZOUANA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1835', 'KIMBANDA 1', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1836', 'KIMBANDA 2', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1837', 'LEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1838', 'NGOYO', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1839', 'NTOMBO 1', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1840', 'NTOMBO 2', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1841', 'KIMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1842', 'MBANDZA- KIMBOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1843', 'NTOMBOKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1844', 'MBANDZA-BAKA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1845', 'MBANDZA-NGANGA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1846', 'BADIMOSSI', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1847', 'MBANDZA-HOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1848', 'NGOUDIANZA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1849', 'KILOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1850', 'KIKAMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1851', 'LOUSSEKA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1852', 'MOULENGO', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1853', 'YANGA-MOUKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1854', 'MPETE', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1855', 'KELEKELE', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1856', 'KIMPALALA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1857', 'BIBOUA', NULL, 'TPLOC_0008', 0, 'LOC_0443', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1858', 'MOUTOH', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1859', 'YEH', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1860', 'MINGALA BAMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1861', 'MBAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1862', 'IMBIMI', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1863', 'MASSA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1864', 'NKOUO', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1865', 'MALOUKOU BAMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1866', 'MALOUKOU MALEZE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1867', 'MALOUKOU KRAKASSINE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1868', 'MALOUKOU-TRECHOT', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1869', 'MALOUKOU GOMEZ', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1870', 'MABAYA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1871', 'BAKOULA-CENTRE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1872', 'BAKOULA-NGOUATABA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1873', 'LINGOLI 2', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1874', 'LINGOLO 1', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1875', 'KANGA MBANZI', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1876', 'LESHIA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1877', 'BANDOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1878', 'KINGANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1879', 'KILOUAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1880', 'MATY CV1', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1881', 'MATY CV2', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1882', 'ESSIO', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1883', 'BOULANKIO', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1884', 'IMPOUNI', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1885', 'NGOUOMI', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1886', 'OKAH ECOLE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1887', 'INGOUO', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1888', 'OKAH SERVICE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1889', 'IMPHONO', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1890', 'IMPANI', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1891', 'DZOULOU-MPIERE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1892', 'IKIONO (KITSANINGA)', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1893', 'MANDIELE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1894', 'KOUOMI', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1895', 'LIKO', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1896', 'EDZOUEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1897', 'KIDZOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1898', 'ANKOUA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1899', 'MATY SOURCE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1900', 'MABORIA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1901', 'WAYAKO', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1902', 'BONGOVILLE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1903', 'KINTOUARI', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1904', 'MATY CENTRE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1905', 'IMBALI SEA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1906', 'IMPE', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1907', 'INGAFINI', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1908', 'IKOURI', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1909', 'IFOUROU', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1910', 'BISSA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1911', 'IMPANA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1912', 'ZOLA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1913', 'MADIBOU', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1914', 'BINOKENA', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1915', 'MANGOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1916', 'NKOUE', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1917', 'KINGANDOU-WAMBO', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1918', 'NGOUA', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1919', 'NSOUNDI', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1920', 'KINDOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1921', 'KINIMBI-BOLOKO', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1922', 'NGANTOKO', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1923', 'MOUKOUAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1924', 'KAHOUNGA- BOUDZOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1925', 'KAHOUNGA-MOUTSILA', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1926', 'KINDAMBA POTA', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1927', 'MOUVIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1928', 'NGANDOU-MASSOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1929', 'RENEVILLE', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1930', 'KINTAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1931', 'KINGANDOU-NGUIMBI', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1932', 'LOUKOUANGOU', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1933', 'NKAMA', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1934', 'MISSONIA', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1935', 'KIYA', NULL, 'TPLOC_0008', 0, 'LOC_0445', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1936', 'KIKOUIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1937', 'MISSAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1938', 'MOYEN', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1939', 'M’PANGALA', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1940', 'M’PANGALA', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1941', 'MADZAKA', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1942', 'MBOULOU ECOLE', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1943', 'MIENI', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1944', 'MBAKOU-MABOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1945', 'MIHETE-MBAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1946', 'IMPOUO', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1947', 'IKOMO', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1948', 'BITAMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1949', 'KIBILOU', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1950', 'KIBILOU', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1951', 'N’KO', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1952', 'MILOU', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1953', 'KINKONONGO', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1954', 'MIHETE-MALOUO', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1955', 'MANGUIRI', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1956', 'BAC-DJOUEKE', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1957', 'MINZERE', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1958', 'KOUMOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1959', 'N’KO 2', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1960', 'KINKOUA-MIAMI', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1961', 'MAMBIO', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1962', 'MOUNOKO', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1963', 'N’DOUO-YOULA', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1964', 'MALELA-BOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0446', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1965', 'BOKOKO', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1966', 'DIBA', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1967', 'KAHOUNGA-LOUKARI', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1968', 'KIMBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1969', 'KIMPANZOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1970', 'KINDOUTA-LOUOMO', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1971', 'KIYALA-LOUOMO', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1972', 'LOUBOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1973', 'LOUKAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1974', 'LOUKOKO', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1975', 'LOUKAMI-KOUTA', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1976', 'LOUYAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1977', 'MABASSA', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1978', 'MAHOUTA', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1979', 'MAKAYA-HOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1980', 'MANTABA 1', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1981', 'MANTABA 2', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1982', 'MANTEKE', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1983', 'MASSAMBA-MBIKI', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1984', 'MAYANOU 1', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1985', 'MAYANOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1986', 'MBANZA-KOUBATIKA', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1987', 'MBINZA', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1988', 'MBONZA 1', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1989', 'MBONZA 2', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1990', 'MONGO-MOUSSAKI', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1991', 'MOUNKOUONO', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1992', 'MOUTAMPA 1', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1993', 'MOUTAMPA 2', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1994', 'M’PELO', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1995', 'NKANKATA', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1996', 'NZITA-NZOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1997', 'SANGUI-DIAYAKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0447', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1998', 'BISSAMI', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_1999', 'DZOKOTRO', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2000', 'EFANGNOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2001', 'IKOMI', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2002', 'INGA', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2003', 'KIDZOUA', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2004', 'KIMBEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2005', 'KIMBETI', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2006', 'KOUON', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2007', 'MAH', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2008', 'MAKAKA 2', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2009', 'MAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2010', 'MASSINA', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2011', 'MINGOUI', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2012', 'MOUNKOMO', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2013', 'MOUNTSENE', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2014', 'MOUYALI', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2015', 'MPASSA', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2016', 'MPOUOMO', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2017', 'NDZOMO', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2018', 'NGANDOU-MAYALA', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2019', 'NTSOUO', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2020', 'DZOUANA', NULL, 'TPLOC_0008', 0, 'LOC_0448', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2021', 'MBE', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2022', 'OKIENE', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2023', 'MBOKA-LEFINI', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2024', 'MBOKA-SERIEUX', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2025', 'BRICK 2', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2026', 'BRICK 1', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2027', 'MOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2028', 'BOKABA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2029', 'NGOLIBA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2030', 'MAFAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2031', 'KIMPOKO', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2032', 'SEDECK', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2033', 'MOSSENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2034', 'NDINGA-MAWA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2035', 'EDOUANI', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2036', 'KOUNZOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2037', 'KABA-NGOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2038', 'KABA-ECOLE', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2039', 'MOLEBO', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2040', 'LIBANGA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2041', 'MAYI-PILI', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2042', 'DIEU-LE-VEUT', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2043', 'ODZIBA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2044', 'SAH', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2045', 'IMBAMA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2046', 'MASSESSE', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2047', 'BELLE-VILLE', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2048', 'INGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2049', 'IMPOH', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2050', 'NDZION-NDZION', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2051', 'MAH', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2052', 'INGAH', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2053', 'INKAON', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2054', 'ITABA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2055', 'NOUVEAU VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2056', 'IMVOUBA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2057', 'OWE-MASSINA', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2058', 'NGOULAYO', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2059', 'KIANI-YONO', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2060', 'NKIELLE', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2061', 'KISSANGANI', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2062', 'MPOUMAKO', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2063', 'INONI-PLATEAU', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2064', 'INONI-FALAISE', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2065', 'MBOUAMBE-LEFINI', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2066', 'IMBOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2067', 'BANIONZI', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2068', 'KIKOUIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2069', 'KILOUBI', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2070', 'KIMANIKA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2071', 'KIMBEDI', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2072', 'KIMPONDZI', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2073', 'KINDAMBA-NGOUEDI', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2074', 'KINGOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2075', 'KINGOYI', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2076', 'KINKANDA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2077', 'KINKEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2078', 'KINKOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2079', 'KISSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2080', 'LOUENGO', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2081', 'LOUHANGA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2082', 'LOUILA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2083', 'LOULOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2084', 'LOUTEHETE', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2085', 'MANGOUNGO U', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2086', 'MASSEMBO-LOUBAKI', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2087', 'MASSENGO-NGOMA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2088', 'MIMPALA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2089', 'MISSAFOU', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2090', 'MISSANDA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2091', 'MOUALOU', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2092', 'MOULANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2093', 'MOUNSANGOULA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2094', 'MPASSA-MINES', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2095', 'MPEHOLA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2096', 'MPOUKOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2097', 'NGANDOU-BOUDZOUA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2098', 'NGONGO', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2099', 'NKAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2100', 'NSENGO', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2101', 'NTARI', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2102', 'NZINZI', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2103', 'PARIS-SALA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2104', 'TABA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2105', 'TONATO', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2106', 'TOUNGADIAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2107', 'YANGANZALA', NULL, 'TPLOC_0008', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2108', 'BELA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2109', 'BONDO', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2110', 'BOUENDE', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2111', 'DIKOKELE', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2112', 'DOUKA-BAKOUTALA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2113', 'FOUOTA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2114', 'KAMOU(KITELE)', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2115', 'KAMBA MAKONKO', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2116', 'KENGUELE', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2117', 'KIMBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2118', 'KILEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2119', 'KIMAZA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2120', 'KIMPANDZOU', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2121', 'KIMPENGA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2122', 'KIMPILA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2123', 'KIMPOUNGUI', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2124', 'KINDOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2125', 'KINGOUDI', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2126', 'KINIANGUI (NZABI MAYAMBOULA)', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2127', 'KINKADA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2128', 'KINSAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2129', 'MANSUINI (LOUBITOUKOU)', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2130', 'LOUKOUAKOUA (MANTEKE)', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2131', 'LOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2132', 'MABABA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2133', 'MALELA NDOKI', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2134', 'MANDOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2135', 'MANKAMI', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2136', 'MANKONKO', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2137', 'MANKONONGO', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2138', 'MANTABA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2139', 'MASSESSE', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2140', 'MATADI', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2141', 'MANDOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2142', 'MBANZA GALA GALA (NGUIENGUIE)', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2143', 'MBANZA LOUKAYA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2144', 'MBANZA MAKONDI', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2145', 'MBANZA MBEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2146', 'MBANZA MINGUENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2147', 'MBANZA NKAKA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2148', 'MBANZA NSANDA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2149', 'MBANZA VOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2150', 'MBELO (BOUKAKA)', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2151', 'MBENSEKE (BISSOUDI)', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2152', 'MBOUNDA 1', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2153', 'MBOUNDA 2', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2154', 'MOUNSOUNSOU', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2155', 'MPAKA MATADI', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2156', 'MPIKA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2157', 'MPOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2158', 'N’DIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2159', 'NGAMA LOUBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2160', 'NKANDOU BINSOUNSOU', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2161', 'NKANDOU A MPOUTOU', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2162', 'NKANZI', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2163', 'NSAKAMBILOU', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2164', 'NSANTOU NTOTO', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2165', 'NSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2166', 'NSOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2167', 'NZELEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2168', 'NZIETO', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2169', 'PARIS', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2170', 'SINGA BANANA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2171', 'TAABA-LOUENGA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2172', 'TENTA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2173', 'VOKA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2174', 'VOULOUKIKAYES', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2175', 'YALALA-NKISSI', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2176', 'YANGA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2177', 'ZAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2178', 'ZINGA', NULL, 'TPLOC_0008', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2179', 'KARI-KARI', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2180', 'KIBOSSI NGUETANI', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2181', 'KIMBAMA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2182', 'KIMBELE', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2183', 'KIMBETI', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2184', 'KIMPILA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2185', 'KINGANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2186', 'KINSSASSA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2187', 'KINTAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2188', 'KILOLO', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2189', 'MABOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2190', 'MADZIA-CENTRE', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2191', 'MALENGO', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2192', 'MBANZA-MBOMA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2193', 'MANIETO', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2194', 'MANSIEDI', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2195', 'MATOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2196', 'MAYONGONGO', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2197', 'MBAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2198', 'MOUMBANZA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2199', 'MIHETE', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2200', 'MOUBIRI', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2201', 'MOUSSENONGO', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2202', 'MOUTAMPA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2203', 'MOUYAMI', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2204', 'MOUZIETO', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2205', 'MPAYAKA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2206', 'M’VOULOUMAMBA-NORD', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2207', 'M’VOULOUMAMBA SUD', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2208', 'NGAMALIE', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2209', 'NGAMAMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2210', 'NGABOULOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2211', 'NGAMIKOLE', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2212', 'NGAMINDOKO', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2213', 'NGAMISSAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2214', 'NGUELA TSETSEKE', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2215', 'NSAMOUNA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2216', 'NSOMO', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2217', 'OUA-OUA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2218', 'PK LOUALOU', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2219', 'POTO-POTO LAURENT', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2220', 'SIASSIA NTONKAMA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2221', 'SOUMOUNA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2222', 'VOULA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2223', 'YANGA SERIEUX', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2224', 'YANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2225', 'YOKAMA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2226', 'ZANDOU DIA BAKALA', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00');
INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_2227', 'KISENGUELE', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2228', 'KIKOMBOLO', NULL, 'TPLOC_0008', 0, 'LOC_0452', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2229', 'BANDA MISSAMVI', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2230', 'BANGOU MPOUETE', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2231', 'BATOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2232', 'HERELA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2233', 'INKALA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2234', 'JEUDI KIBOKA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2235', 'KILEBE', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2236', 'KINDZAKA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2237', 'KINKAKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2238', 'LOUBEHOUA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2239', 'LOUFOURI', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2240', 'LOUKAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2241', 'LOUKOUO', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2242', 'LOUO-MINGALI', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2243', 'MAKANA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2244', 'MALALA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2245', 'MANTENSAMA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2246', 'MATIBA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2247', 'MATOURIDI', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2248', 'MBANZA NKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2249', 'MBOUALA-MAHOUOLO', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2250', 'MIBOUORI', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2251', 'MOUANGA-NGOUBA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2252', 'MOUKOLA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2253', 'MOUMENI', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2254', 'MOUNTEMBESSA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2255', 'MOUSSOLO-DAKAR', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2256', 'MOUTELE', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2257', 'MOUTOUSSALA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2258', 'MPASSA ECOLE', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2259', 'MPASSA VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2260', 'NGAMANZAMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2261', 'NGOUNGOU CARREFOUR', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2262', 'NGOUNOUNKOUTOU', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2263', 'NZEKI-MISSONSA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2264', 'NZEKI-MOUDILOU', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2265', 'SALABIAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2266', 'SANGA-VILLE', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2267', 'TSIEMO', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2268', 'VOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2269', 'NGANGA LINGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2270', 'LOUKANGA I', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2271', 'LOUKANGA II', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2272', 'DZOUMOUNA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2273', 'NTOULA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2274', 'MABENGA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2275', 'BISSINZA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2276', 'MOUMBOUANISSA (KIMBEMBE)', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2277', 'MAYALA (KINSOUNDI)', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2278', 'LINZOLO', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2279', 'MAKANA I', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2280', 'MAKANA II', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2281', 'MAYITOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2282', 'MABAYA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2283', 'KOUBOLA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2284', 'MVOUANGA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2285', 'TABA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2286', 'MOULILA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2287', 'NSAYI MAMBA (PTT)', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2288', 'POMPI', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2289', 'MVOUZA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2290', 'BOUKONZO BUA LAMI', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2291', 'MANIETO', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2292', 'GOMA TSE-TSE', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2293', 'MOULABAKA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2294', 'MAZINGA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2295', 'MAKAKA I', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2296', 'MAKAKA II', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2297', 'MIKATOU BAMBOMA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2298', 'BANTABA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2299', 'NGOYI (KIVIMBA 21)', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2300', 'MITOKO', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2301', 'KIBITI NSAYI', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2302', 'KIBOSSI', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2303', 'KIBOUENDE 10', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2304', 'KIBOUENDE 11 (NTOBA YILELE)', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2305', 'KOSSA', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2306', 'KIVIMBA 10', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2307', 'MBANZA-NGUERI 10 (MATINGOU MA MVOURI)', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2308', 'KINGANDOU 10 (NTOUOMI)', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2309', 'LOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2310', 'NGUIRI (LOUWETO)', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2311', 'LENGO', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2312', 'KAHOUNGA 3 (HAUTE NGUIRI)', NULL, 'TPLOC_0008', 0, 'LOC_0454', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2313', 'AFRIQUE DU SUD', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2314', 'BETIKOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2315', 'BONGOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2316', 'BOYELE 1', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2317', 'BOYELE 2', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2318', 'BOYELE 3', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2319', 'BOYELE 4', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2320', 'BOYELE PORT', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2321', 'CAMP FULGENCE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2322', 'CONGOMALEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2323', 'EBALABALA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2324', 'EBOKO', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2325', 'GAGA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2326', 'GNAMOBA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2327', 'GOUGA FLEUVE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2328', 'GOUGA ROUTE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2329', 'IKPENGBELE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2330', 'ITE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2331', 'ITELE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2332', 'KELLE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2333', 'KEKENZE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2334', 'KPAKAYA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2335', 'KPETA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2336', 'LANDZA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2337', 'LIBOKO', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2338', 'LOBAGNY', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2339', 'LOUMBE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2340', 'MABELOU', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2341', 'MALEBO', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2342', 'MAWANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2343', 'MBOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2344', 'MOBANGZA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2345', 'MOGBALA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2346', 'MOKINDA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2347', 'MOPKENGBA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2348', 'MOPKETENE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2349', 'MOSCOU', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2350', 'NGBONDO', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2351', 'NGONDIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2352', 'NGONGO', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2353', 'PANGALEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2354', 'TALANGAI', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2355', 'WAKENDZE', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2356', 'WOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2357', 'WONGO NORD', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2358', 'WONGO OUEST', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2359', 'YAKO', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2360', 'YANGATONDO', NULL, 'TPLOC_0008', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2361', 'BONDOKI', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2362', 'BOTONGO', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2363', 'EBAMBE', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2364', 'EDZAMA', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2365', 'KINAMI', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2366', 'LIKONDA', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2367', 'MOBAYE', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2368', 'MOUNGOUMA-BAILLY', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2369', 'MONGOUMA-MOKE', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2370', 'MOSSENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0403', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2371', 'BANGUI MOTABA', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2372', 'BOKPENDE', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2373', 'BONDZANDA', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2374', 'BONDZALE', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2375', 'BONGBOKO', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2376', 'BONGOYE', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2377', 'BOUCY-BOUCY', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2378', 'BOSSENGUE-DOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2379', 'CAMP-SOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2380', 'DJOUMBE', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2381', 'IKOUANGALA', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2382', 'INYANGA-NKAKE', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2383', 'KPETA', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2384', 'LIKOMBO 1', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2385', 'LIKOMBO 2', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2386', 'LOUNDOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2387', 'LOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2388', 'LOSSO -IPENDJA', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2389', 'MACAO', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2390', 'MANKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2391', 'MANFOUETE', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2392', 'M BONDA-MAKO', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2393', 'MOTABA', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2394', 'MOUMBELOU', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2395', 'MOUNGOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2396', 'NDZOKOU', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2397', 'TALANAMISSO', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2398', 'TALANGAI', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2399', 'WOULIZE', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2400', 'ZINGO', NULL, 'TPLOC_0008', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2401', 'AKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2402', 'AMINA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2403', 'BAI-BAPOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2404', 'BERANDZOKOU', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2405', 'BIRAO', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2406', 'BISSAMBI', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2407', 'BODZAMA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2408', 'BOKOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2409', 'BOLOMO', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2410', 'BOMOLE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2411', 'BOPOUNI', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2412', 'GOMA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2413', 'IBENGA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2414', 'LIKENZE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2415', 'LIMITE 1', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2416', 'LIMITE 2', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2417', 'LOBANDZI', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2418', 'LOBI', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2419', 'LOKOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2420', 'LOKOUA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2421', 'LOLA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2422', 'LOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2423', 'LONGA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2424', 'LOPOLA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2425', 'LOSSETI-MOBELOU', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2426', 'LOUMBONGA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2427', 'MAPELA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2428', 'MATAMA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2429', 'MIMBELLY', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2430', 'MIMPOUTOU', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2431', 'MINDZOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2432', 'MOKABI-BARRIERE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2433', 'MOKABI BOKO', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2434', 'MAKABI-LOLA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2435', 'MOKABI-VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2436', 'MOKOLO RIVIERE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2437', 'MOKOLO ROUTE-BOMBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2438', 'MOKOBO', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2439', 'MONGOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2440', 'MOUALE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2441', 'MOUNGOUNGUI', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2442', 'MOUNOKOBOLI', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2443', 'MOTALA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2444', 'MOYOKO', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2445', 'NDONGO 1', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2446', 'NDONGO 2', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2447', 'NDOLE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2448', 'NGOMBAGOYE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2449', 'NGOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2450', 'NGOUANDIKA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2451', 'TINGAMA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2452', 'WANDZA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2453', 'WOMBO-LIBOKO', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2454', 'ZANGA', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2455', 'ZONGO-LIBANDI', NULL, 'TPLOC_0008', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2456', 'ATTENTION', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2457', 'BEN', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2458', 'BOHA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2459', 'BOKATOLA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2460', 'BOLEKE', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2461', 'BONDEKO', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2462', 'BONGANDZI', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2463', 'BOSSEKA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2464', 'BOSSELA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2465', 'BOSSIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2466', 'BOTALA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2467', 'DZEKE', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2468', 'EDZELE', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2469', 'IBAKI', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2470', 'IBANGA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2471', 'IBOLO', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2472', 'IPONGUI', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2473', 'ITANGA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2474', 'ITOUZI', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2475', 'IYAHOU', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2476', 'KANIO', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2477', 'KOUNDOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2478', 'LIOUESSO', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2479', 'MABONGOKOTO', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2480', 'MAKENGO', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2481', 'MATOKO', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2482', 'MBANDZA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2483', 'MBETI', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2484', 'MBILI', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2485', 'MBIMBO', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2486', 'MBOUA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2487', 'MINGANGA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2488', 'MBOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2489', 'MOBAKA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2490', 'MOBANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2491', 'MAHOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2492', 'MOKENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2493', 'MOLEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2494', 'MOSSOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2495', 'TOUKOULAKA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2496', 'YEKOLA', NULL, 'TPLOC_0008', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2497', 'BETHLEM', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2498', 'BIKOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2499', 'BOBELE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2500', 'BOBOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2501', 'BOKATA', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2502', 'BOKOZI', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2503', 'BOLEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2504', 'BOMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2505', 'BONDOKO', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2506', 'BONGUENDE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2507', 'BONGUENGOU', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2508', 'BOPKOKOTO', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2509', 'BOTANGA', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2510', 'DJEMBA BROUSSE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2511', 'DJEMBA RIVIERE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2512', 'IPENDE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2513', 'KOMBOLA', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2514', 'MALALA', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2515', 'MAKOLONGOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2516', 'MODZAKA', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2517', 'MOHITO', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2518', 'MOUMBENZELE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2519', 'NGANGANIA-BROUSSE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2520', 'NGOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2521', 'NGOUNDZA 1', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2522', 'NGOUNDZA 2', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2523', 'NIANGUE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2524', 'NKOKO', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2525', 'TONGOBOI', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2526', 'VENDZA', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2527', 'YOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2528', 'YOI NA YOI', NULL, 'TPLOC_0008', 0, 'LOC_0407', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2529', 'BOBANGA', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2530', 'BOUNGA BAYA', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2531', 'CONGO-MALEBE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2532', 'DJOUMBELE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2533', 'DJOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2534', 'ETIMA', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2535', 'EPELE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2536', 'IKOLONGO', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2537', 'LIKOUANGOLA', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2538', 'LIGNETE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2539', 'LIGOYI', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2540', 'LISSALANGOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2541', 'LONGO', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2542', 'LOKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2543', 'MAKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2544', 'MAMBANGUE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2545', 'MBALA', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2546', 'MBOSSIMBE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2547', 'MOLEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2548', 'MOLIAMBAMBE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2549', 'MONGOMBETE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2550', 'MOYENGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2551', 'NGONDOLA', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2552', 'NKENKE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2553', 'NONGA', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2554', 'TALANGAI BALLOYS', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2555', 'YOMBA BALLOYS', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2556', 'YABA-YELEYELE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2557', 'YELEYELE', NULL, 'TPLOC_0008', 0, 'LOC_0408', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2558', 'ABEYA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2559', 'IBEKE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2560', 'POUERE 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2561', 'POUERE 2', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2562', 'OKOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2563', 'OYEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2564', 'ONOUENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2565', 'OBEYA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2566', 'ODIKANGO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2567', 'MOUETSOU', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2568', 'OWANDO-TANDA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2569', 'MBOMA-OLLEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2570', 'MOUENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2571', 'OBENGA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2572', 'IHIMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2573', 'MBOMA-ESSENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2574', 'ASSOKO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2575', 'ENGANDA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2576', 'OYEBA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2577', 'SABA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2578', 'ALLEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2579', 'EBONDZI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2580', 'BOBONGO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2581', 'OYOMI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2582', 'IKOUELE 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2583', 'IKOUELE 2', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2584', 'EKIENABOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2585', 'OKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2586', 'NGOUAKANDI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2587', 'IBEA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2588', 'ONGUIDA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2589', 'ENDEKE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2590', 'ALLEBOU', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2591', 'DZABAKA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2592', 'EBOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2593', 'KATSOKO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2594', 'ODZEA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2595', 'ISSEKI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2596', 'AMBOKANGUIE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2597', 'OKANDA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2598', 'OYONGO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2599', 'IKONGONO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2600', 'EDIKANGOUE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2601', 'ISSAMI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2602', 'OMBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2603', 'OLLENDE-MBOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2604', 'IBONGA LEHOLI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2605', 'OMBOGNO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2606', 'OKOUASSE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2607', 'OKONDZI 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2608', 'OKONDZI 2', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2609', 'OKONDZI 3', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2610', 'OKONDZI 4', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2611', 'BOGNA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2612', 'BOUA 3', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2613', 'KOUYOU NGANDZA -', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2614', 'LIBOUNA 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2615', 'ELONGO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2616', 'LIBOUNA 2', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2617', 'MOYO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2618', 'BOKANDA 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2619', 'BOKANDA 2 (pallet)', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2620', 'LOKAKOUA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2621', 'LOBOMO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2622', 'EKAGNA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2623', 'NDZIKOU', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2624', 'OYONGO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2625', 'INGUIE NA OYAKO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2626', 'BOYAKA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2627', 'BOUI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2628', 'POUERE ISSENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2629', 'NGOUENE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2630', 'OKONA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2631', 'ANDO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2632', 'KOUBOU', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2633', 'IBOUNA 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2634', 'IBOUNA 2', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2635', 'ILLANGA-DZONGO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2636', 'OSSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2637', 'OKONGA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2638', 'ILLANGA NDONGO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2639', 'OBANGA 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2640', 'OBANGA 2', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2641', 'EMBANDA 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2642', 'EMBANDA 2', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2643', 'ENGANGA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2644', 'MOUNDZELI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2645', 'BOUA 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2646', 'ISSABI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2647', 'PENDA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2648', 'OPAKA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2649', 'PAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2650', 'APORO 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2651', 'APORO 2', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2652', 'ABONDZI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2653', 'ENGOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2654', 'MOSHOUE-ILLEBE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2655', 'OYONGO 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2656', 'EKONDZO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2657', 'KIAMBI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2658', 'MOH 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2659', 'MOH 2', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2660', 'DZEKEME', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2661', 'ODZEMA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2662', 'OSSONGA 1', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2663', 'ELINGOSSAYO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2664', 'OKONDA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2665', 'OLLOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2666', 'OTSENDO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2667', 'POURI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2668', 'OSSANGOU-KOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2669', 'OSSANGOU-OMBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2670', 'OSSANGOU-OBOYA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2671', 'BOYA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2672', 'ELIMA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00');
INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_2673', 'OTOKO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2674', 'MANGA-BOKO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2675', 'MANGA-KOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2676', 'MANGA-DZO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2677', 'MANGA-DIKI', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2678', 'MANGA-OUEME', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2679', 'MANGA-SIA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2680', 'MANGA-BOUA', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2681', 'MANGA-MANGUE', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2682', 'MANGA-MIONGO', NULL, 'TPLOC_0008', 0, 'LOC_0421', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2683', 'ABO 1-ABO 2', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2684', 'PAMA-OKOH', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2685', 'NGOUENE', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2686', 'EDOU-ILLANGA', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2687', 'MBARA', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2688', 'KOUEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2689', 'OPOKANIA', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2690', 'OLENGA', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2691', 'OTSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2692', 'MBOBO', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2693', 'ENGOUEME', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2694', 'IGNUIE', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2695', 'OBOUYA-EMBAMI', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2696', 'OKONDO', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2697', 'OKOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2698', 'OBELE', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2699', 'OLEBE', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2700', 'ODOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2701', 'OKONA', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2702', 'NDOUENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2703', 'ISSO', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2704', 'ELONDZI', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2705', 'EKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2706', 'MIABI', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2707', 'KOYO-ILLANGA 1', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2708', 'ABONGO', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2709', 'IKONDA 1-IKONDA 2', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2710', 'OSSAH 1-OSSAH 2', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2711', 'EKOH', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2712', 'KALI OTOKO', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2713', 'LIBOKA', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2714', 'EKAGNI', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2715', 'IKOUANGO', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2716', 'ITOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2717', 'OKOUGNOU', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2718', 'AHOSSO-ILLANGA 2', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2719', 'IBOH', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2720', 'ONDEBE', NULL, 'TPLOC_0008', 0, 'LOC_0422', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2721', 'YOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2722', 'EBALOYEKE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2723', 'EBONGUI', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2724', 'EMBOLI', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2725', 'OMBELE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2726', 'MOUALO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2727', 'DOUMA NA NDZOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2728', 'ADINGA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2729', 'TALANGAI', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2730', 'ATALI', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2731', 'NDONGO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2732', 'OYOUE 2', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2733', 'ISSONGO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2734', 'BOKAGNA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2735', 'ABELA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2736', 'ONDZA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2737', 'OTAMBOHOKO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2738', 'IKEMOU', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2739', 'ELOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2740', 'MBESSE N’OBAMBI', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2741', 'MBENDZE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2742', 'VOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2743', 'OTAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2744', 'BORA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2745', 'IFOUNDAKA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2746', 'VOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2747', 'IKOSSA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2748', 'OKIA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2749', 'NGUIO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2750', 'OSSAKE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2751', 'OBONDJO VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2752', 'OBONDJO CENTRE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2753', 'ODINGA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2754', 'LANGO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2755', 'ONGOMO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2756', 'PAMBA ODZAKA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2757', 'APOKO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2758', 'LEBANGO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2759', 'NDZAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2760', 'EPOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2761', 'ILEBOU ONDONGO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2762', 'NIETEBOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2763', 'IKOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2764', 'OKOKOKO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2765', 'ABENGA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2766', 'OTSOUEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2767', 'ATEKOU', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2768', 'ADIBA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2769', 'MOTETE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2770', 'LOMBIA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2771', 'OLONGO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2772', 'CITE DE DIEU', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2773', 'IMANIA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2774', 'OBE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2775', 'MANGO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2776', 'ONANGA 1', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2777', 'TSONGO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2778', 'OTEMA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2779', 'ONANGA 2', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2780', 'ISSENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2781', 'TSIAKO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2782', 'OTALA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2783', 'DOUA OFOU', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2784', 'MVOULA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2785', 'ASSAKI', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2786', 'MOHALI (EPERE)', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2787', 'ENGUIDI 2', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2788', 'ANGALE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2789', 'IHOURA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2790', 'OHOURI', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2791', 'ASSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2792', 'EKANIA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2793', 'ABOUA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2794', 'ENGUIDI 1', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2795', 'DOUA OSSENDO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2796', 'OSSOUANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2797', 'IKINGAWASSA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2798', 'INGUEKE', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2799', 'ODZALA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2800', 'IBONIMA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2801', 'NTOKOU OTOLO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2802', 'EBOKO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2803', 'KOUNDOUTABA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2804', 'MOUANDZO', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2805', 'BOYA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2806', 'OKAGNA', NULL, 'TPLOC_0008', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2807', 'BOKOUELE', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2808', 'EHOTA', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2809', 'LIPOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2810', 'MOKONDA', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2811', 'ILLANGA', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2812', 'BOMBOKOUTA', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2813', 'INGONDO', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2814', 'OMBELA', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2815', 'OYO-AKONDO', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2816', 'EKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2817', 'ITOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2818', 'OBELE', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2819', 'BOUNDJI-ATSE', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2820', 'OBESSI', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2821', 'BOKOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2822', 'TSONO', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2823', 'BOMIOKO', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2824', 'EBOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2825', 'ELONDJI', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2826', 'LITOMBI', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2827', 'TONGO', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2828', 'MAKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2829', 'BENE', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2830', 'ESSASSAKA', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2831', 'ENGOUELE', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2832', 'BOYOKO', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2833', 'NGANIA-MOUAKE', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2834', 'OBOUYA', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2835', 'MOUEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2836', 'ONDIAPINDZA', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2837', 'VOULANGOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2838', 'MBOMA', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2839', 'EGNONGO', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2840', 'OSSANGUI-OUENZE', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2841', 'OYENDZE', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2842', 'TSAMA', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2843', 'OPAGUI', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2844', 'OKIA', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2845', 'IBONGA', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2846', 'MOUANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0425', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2847', 'LOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2848', 'OKOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2849', 'OBOKO-NGOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2850', 'OKO', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2851', 'EKOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2852', 'MANDA', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2853', 'EBAKA-MALALA', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2854', 'IKOU', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2855', 'YOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2856', 'IBONGO', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2857', 'OMBEBO', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2858', 'ONIAMBE', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2859', 'OYOUE', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2860', 'TSABOU-ODIO', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2861', 'IFOUTA', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2862', 'NDONGONIAMA', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2863', 'PANGO', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2864', 'NTOKOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2865', 'NTOKOU 3', NULL, 'TPLOC_0008', 0, 'LOC_0426', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2866', 'BONIALA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2867', 'MOPIKA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2868', 'BOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2869', 'BOUNDJI-LAGUNE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2870', 'BOKA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2871', 'MOKOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2872', 'BOUAYA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2873', 'TCHAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2874', 'MIKONGONGOLI', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2875', 'MATABOKE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2876', 'TSE-TSE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2877', 'KONDZOLO', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2878', 'KONDA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2879', 'BOHOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2880', 'SENGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2881', 'MOTOUMONODE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2882', 'YENGOLA-LOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2883', 'BOMBONGO', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2884', 'BOYOKO-BIRI', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2885', 'LIBOMA-MOBIYA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2886', 'BOSSALO', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2887', 'MAKENENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2888', 'YOMBE-BOUENGNI', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2889', 'LIBALA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2890', 'BOKOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2891', 'EKOULI-POURI', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2892', 'MOTANDO-MOPONGA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2893', 'MANGA-MABOKO', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2894', 'NDOLLET', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2895', 'LOBOKO', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2896', 'BOUETA-BANDEKE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2897', 'BOTOUALI 2', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2898', 'BOKANIA-BOKANDA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2899', 'BOBOUELA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2900', 'MOUANDZAKALA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2901', 'LIBOUNA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2902', 'BONGOMA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2903', 'KOUANDZA-EKANDA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2904', 'ESSOMBI', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2905', 'LITHE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2906', 'BOSSAKE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2907', 'NGANDA 2', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2908', 'BONGA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2909', 'EKOUNOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2910', 'LIKENDZE', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2911', 'KOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2912', 'MAKOLOMAKA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2913', 'LIKENDZI', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2914', 'MATIKO', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2915', 'NKASSA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2916', 'BOSSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2917', 'IREBOU(fleuve congo)', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2918', 'BOTOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2919', 'MOTEMOBIONGO', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2920', 'BOUETA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2921', 'LOKONGO', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2922', 'MONGOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2923', 'BOKOUANGO 2', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2924', 'IREBOU (Likouala aux herbes)', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2925', 'MONGO MONE', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2926', 'MAKABANA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2927', 'LOMBET', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2928', 'MILEMBET', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2929', 'BOMIONDO', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2930', 'SOSSOLO', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2931', 'MITSIENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2932', 'MONDZOLOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2933', 'CONGO-MAKAMBO', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2934', 'MBONDO', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2935', 'MOBOLA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2936', 'MOKOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2937', 'EPELE', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2938', 'LOBOKO-SANGHA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2939', 'ESSOBE', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2940', 'BISSANGA-SAMBO', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2941', 'MAGNOLI', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2942', 'MOKONONGO', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2943', 'INIENGUE-AMERIQUE', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2944', 'BOMA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2945', 'BONDZANDA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2946', 'MITOULA', NULL, 'TPLOC_0008', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2947', 'MBESSE', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2948', 'OKOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2949', 'ISSERE', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2950', 'ONGONDZA', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2951', 'TOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2952', 'OBONGUI', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2953', 'ENGONDO', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2954', 'ODIKANGO', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2955', 'OTSEGNE', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2956', 'ATELY', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2957', 'ONDINGUI', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2958', 'OKOUESSE', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2959', 'OKOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2960', 'IYONGO', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2961', 'TSEKA', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2962', 'IKO', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2963', 'IPFOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2964', 'ENGANA', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2965', 'ADZOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2966', 'OLERI', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2967', 'ABOLO', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2968', 'EKIEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2969', 'NGOUONI', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2970', 'EKAMI', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2971', 'ABELA', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2972', 'TSONGO', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2973', 'ENDAGUI', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2974', 'OLAYOKO', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2975', 'FOURA', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2976', 'ANTOGO', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2977', 'OLEBI', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2978', 'OLONGONE', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2979', 'ETOUOMI', NULL, 'TPLOC_0008', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2980', 'BOLOUAKA', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2981', 'NGOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2982', 'BOLEKO', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2983', 'BOKOUANGO', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2984', 'NGULO', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2985', 'BONONGO', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2986', 'DESERT', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2987', 'BOTOUALI', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2988', 'MISSEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2989', 'MAKOTIPOKO', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2990', 'IREBOU', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2991', 'MISSONGO', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2992', 'YOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2993', 'EKONDZO', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2994', 'BODZENDZO', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2995', 'BABELA', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2996', 'BONGO', NULL, 'TPLOC_0008', 0, 'LOC_0430', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2997', 'ADINGA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2998', 'ALIENI', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_2999', 'AKANA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3000', 'AMBOMI', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3001', 'ENGOBE', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3002', 'KOUI', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3003', 'LEBAMI', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3004', 'LESSIA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3005', 'MBOLEPAKA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3006', 'MVOUOMA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3007', 'NGOUA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3008', 'OBAKO', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3009', 'OBALA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3010', 'OKOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3011', 'OLOLI1', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3012', 'OPONGA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3013', 'OPORI', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3014', 'OWANDO', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3015', 'PALABAKA', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3016', 'PAPAYE', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3017', 'TCHERRE', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3018', 'TSAMA 1', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3019', 'TSAMA 2', NULL, 'TPLOC_0008', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3020', 'ABEKE', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3021', 'AKOU', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3022', 'ALLEME', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3023', 'AYANDZA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3024', 'BAYA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3025', 'BIA 1', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3026', 'DZOUAMA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3027', 'EKA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3028', 'EKEYI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3029', 'EMBIMI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3030', 'ETI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3031', 'ESSANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3032', 'KEBILI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3033', 'KEBOUYA 1', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3034', 'KEMVOUOMO', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3035', 'KESSALA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3036', 'KOUYA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3037', 'LEBILI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3038', 'LEMBESS', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3039', 'LEMVOURI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3040', 'LETOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3041', 'NDOUMBI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3042', 'NGAMI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3043', 'NGAYI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3044', 'NKORI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3045', 'NTCHOUO', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3046', 'NTSAYI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3047', 'NDZO', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3048', 'OBA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3049', 'OBANA 1', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3050', 'OBANA 2', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3051', 'OBILI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3052', 'OKOGO', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3053', 'OKONDO', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3054', 'OKOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3055', 'OLLOUA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3056', 'OMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3057', 'OMOYI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3058', 'ONGUIA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3059', 'OPIGUI', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3060', 'OSSELE', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3061', 'OYENDZE', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3062', 'SERRE', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3063', 'VAGA', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3064', 'YABA LA BELLE', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3065', 'YABA-MBETIS', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3066', 'YOULOKOYO', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3067', 'KEBOUYA 2', NULL, 'TPLOC_0008', 0, 'LOC_0416', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3068', 'ABOLO', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3069', 'ABOUNDJI-ODOUMINA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3070', 'AKAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3071', 'AKANA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3072', 'AKOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3073', 'AMBOMI', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3074', 'ANGOMO', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3075', 'ANTSOKO', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3076', 'BELLA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3077', 'EBANDZA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3078', 'ENTSIAMI', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3079', 'ETABA 1', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3080', 'ETABA 2', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3081', 'KABA NIAMA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3082', 'KOUOMI', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3083', 'LEBAYI', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3084', 'MBOMOBAKOTA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3085', 'MVOUOMA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3086', 'NDOUBA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3087', 'NDJOUONO', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3088', 'NDZOUKOU-NGOYEBOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3089', 'NGUIMA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3090', 'OBELI', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3091', 'OBOKO 1', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3092', 'OBOKO 2', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3093', 'ODIA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3094', 'OKA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3095', 'OLLOUA', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3096', 'OBOYE CARREFOUR', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3097', 'OBOYE FRONTIERE', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3098', 'ONIENGA 1', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3099', 'ONIENGA 2', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3100', 'ONTCHOUANDZOKO', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3101', 'ONTCHOUOMO', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3102', 'OSSELE', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3103', 'OYABI', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3104', 'SCIERIE', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3105', 'YEMBELENGOYE', NULL, 'TPLOC_0008', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3106', 'ABANA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3107', 'AKOUA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3108', 'AMBELA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3109', 'AOURA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3110', 'BEYI-MBOLO', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3111', 'DOUBANZO', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3112', 'EDIGUI', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3113', 'ENDEKE', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3114', 'ENDOUO', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3115', 'ENKEYA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3116', 'ESSOURA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3117', 'KEMPAKA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3118', 'KERI-KERI', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3119', 'MBOMA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3120', 'MINA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3121', 'MOULA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3122', 'NGOMA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00');
INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_3123', 'OBELE', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3124', 'ODIA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3125', 'OKA-BAMBO', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3126', 'OKEKA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3127', 'OKELATAKA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3128', 'OKOBA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3129', 'ONGOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3130', 'OSSERANTSIENE', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3131', 'OWOGUI', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3132', 'TALAS', NULL, 'TPLOC_0008', 0, 'LOC_0418', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3133', 'AKELE', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3134', 'BOSSOUAKA', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3135', 'DIBA', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3136', 'EBANA', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3137', 'IBEYI', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3138', 'KEBA', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3139', 'KEKELE', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3140', 'LEBANGO', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3141', 'LENGUI-LENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3142', 'LISSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3143', 'MANGOME', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3144', 'MBANDZA', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3145', 'MBENDE', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3146', 'MBOMANDZOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3147', 'MOUANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3148', 'NGANGUE-BONGUI', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3149', 'NTOLO', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3150', 'OLLEME', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3151', 'OLOBA', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3152', 'OMBO', NULL, 'TPLOC_0008', 0, 'LOC_0419', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3153', 'ADZIE', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3154', 'AMINA-DZOUAMA', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3155', 'AMBIMI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3156', 'ASSIENE', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3157', 'ASSIGUI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3158', 'AYINA', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3159', 'BOGUI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3160', 'BOULIGUI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3161', 'DZOGO 2', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3162', 'EDZOUGA', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3163', 'KELLE-OYONGO', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3164', 'KEMOUAMI 1', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3165', 'KEMOUAMI 2', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3166', 'KENTSELE', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3167', 'KEPOUYA 1', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3168', 'KEPOUYA 2', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3169', 'LEKETY', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3170', 'LEKOLI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3171', 'MBIE', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3172', 'MBOUMA-LEBALA', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3173', 'MAGUI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3174', 'NKOUA-MFAGUI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3175', 'NGOUMBI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3176', 'OBERE', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3177', 'ODZIA', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3178', 'OKOUYA 1', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3179', 'OKOUYA 2', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3180', 'OLLIEMI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3181', 'OLLEBI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3182', 'ONGALI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3183', 'ONGUIA', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3184', 'PORI', NULL, 'TPLOC_0008', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3185', 'DJAKA', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3186', 'MATOTO', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3187', 'IKELEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3188', 'NDOKI 1', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3189', 'NDOKI 2', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3190', 'NGATONGO', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3191', 'NGANDZIKOLO', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3192', 'KONDA', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3193', 'LEME', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3194', 'BOMASSA', NULL, 'TPLOC_0008', 0, 'LOC_0409', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3195', 'MBALOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3196', 'KETTA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3197', 'KANDEKO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3198', 'ELONGE', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3199', 'SANGHA PALM', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3200', 'MAHOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3201', 'MBODZAMONGO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3202', 'ATTENTION', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3203', 'MIELELEKE', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3204', 'MOMBANGA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3205', 'OUESSO MBILA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3206', 'LIOUESSO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3207', 'IBONGA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3208', 'LOUAME', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3209', 'MOYOYE', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3210', 'MOBANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3211', 'MOKOUANGONDA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3212', 'LANGO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3213', 'MONDEKO IGNOLI', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3214', 'EPOMA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3215', 'YENGO MAMBILI', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3216', 'KEREMBEL', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3217', 'KANDEKO 40', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3218', 'NGANDA MESSOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3219', 'PARIS VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3220', 'ABOYAMAKAMBO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3221', 'ZOULABOUTH', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3222', 'SECKA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3223', 'NGOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3224', 'MABOKO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3225', 'MALALA KINSAHASSA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3226', 'PANDAMA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3227', 'MANGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3228', 'TEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3229', 'EPONGO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3230', 'POUNGA CHANTIER', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3231', 'NGOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3232', 'BOUAKANDZOTO', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3233', 'MBAYA', NULL, 'TPLOC_0008', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3234', 'KINSHASA', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3235', 'SOKO', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3236', 'ZOUOBA', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3237', 'KEREMBEL', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3238', 'ELOLOGA', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3239', 'NEMEYONG', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3240', 'MBEA-MBEA', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3241', 'MINGUIAM', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3242', 'MINGUILA', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3243', 'MOULET', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3244', 'ALANGONG', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3245', 'BOUDEL', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3246', 'KOMO', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3247', 'BOLOZO', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3248', 'TALA-TALA 1 (CHANTIER)', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3249', 'TALA-TALA 2 (VILLAGE)', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3250', 'EGABA', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3251', 'NOLA MOKE', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3252', 'NAMOPORO', NULL, 'TPLOC_0008', 0, 'LOC_0411', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3253', 'BILLY', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3254', 'BODZATA', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3255', 'BOMBONGO', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3256', 'BOTOBO', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3257', 'EKONDZO', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3258', 'EKOUOMOU', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3259', 'IBELE', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3260', 'IKASSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3261', 'IKOLOMOYE', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3262', 'MATALI', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3263', 'MATELE', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3264', 'MOKOUANGO', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3265', 'MOLANDA', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3266', 'MOLANGUENDZALATOUA', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3267', 'MOSSENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3268', 'NGANGASSA', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3269', 'NGOMBE 1', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3270', 'NGOMBE 2', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3271', 'NTOKOU SANGHA', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3272', 'SASSAMBO', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3273', 'TEBALE', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3274', 'IPOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0412', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3275', 'KOKOUA', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3276', 'DOUMA 1', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3277', 'DOUMA 2', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3278', 'MIELEKOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3279', 'GOA', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3280', 'DOUO-DINA', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3281', 'BIESSI 1', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3282', 'BOUTAZABE', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3283', 'MENDJADJAH', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3284', 'BAD', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3285', 'SEKA KOUDOU', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3286', 'BATEKOK', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3287', 'DEMEYONG', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3288', 'BESSIE', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3289', 'EGNABI', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3290', 'NAKOUAKA', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3291', 'GAMA', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3292', 'ZOULABOUTH', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3293', 'ZALAPOUMBE', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3294', 'BOUDEL', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3295', 'ALONGONG', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3296', 'MIKEL', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3297', 'SANGHA', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3298', 'DIA', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3299', 'MOUTOUMAYECK', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3300', 'ADIALA 1', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3301', 'ADIALA 2', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3302', 'ASSOMDELE', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3303', 'MEDJONG', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3304', 'LOPO', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3305', 'BOUAM', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3306', 'MOGNOLO', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3307', 'MAZINGO', NULL, 'TPLOC_0008', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3308', 'BOMALINGA 1', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3309', 'BOMALINGA 2', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3310', 'MEDIAO', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3311', 'KOKO', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3312', 'ZOULA', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3313', 'GOLA', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3314', 'EBALAD', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3315', 'BAMEGOD', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3316', 'ELOGO 1', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3317', 'ELOGO 2', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3318', 'MESSOCK', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3319', 'MINGUELAKOUM', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3320', 'BENDAMA', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3321', 'NTONGO', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3322', 'ELONDJO', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3323', 'DJAMPOUO', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3324', 'SANS FIL', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3325', 'DONE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3326', 'LONGA SEIZE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3327', 'LOBOCK', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3328', 'ELENE 1', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3329', 'ELENE 2', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3330', 'POUMBA ETSIOCK', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3331', 'GOLMELENE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3332', 'EBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3333', 'POUMBA EKOB', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3334', 'EZOLOUM', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3335', 'GARABIZAM', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3336', 'MEGOBE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3337', 'ELERE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3338', 'EKOKOLA', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3339', 'BIDOUMO 1', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3340', 'BIDOUMO 2', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3341', 'MEKOUA-ALLATH', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3342', 'GOL', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3343', 'BELLE VUE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3344', 'PEH', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3345', 'CABOSSE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3346', 'AZOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3347', 'ASSOUMOUNDELE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3348', 'MBALLAM CONGO', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3349', 'MISSOMISSOM', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3350', 'J’ AIME L’ AMOUR', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3351', 'NTAM', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3352', 'MAKA', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3353', 'MEYOSSE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3354', 'AVIMA', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3355', 'MINDJONE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3356', 'EDJOH', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3357', 'BATAPOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3358', 'BEFAME-AYINA', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3359', 'MAMA', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3360', 'SUPPORTER LA PEINE', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3361', 'METOTO', NULL, 'TPLOC_0008', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3362', 'BAMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3363', 'BONDI', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3364', 'LOEME-NANGAMA', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3365', 'MAKOLA', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3366', 'MBOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3367', 'MBOUBISSI', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3368', 'NDEMBOUANOU', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3369', 'NKONDI-MBAKA', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3370', 'NTOMBO-PONT', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3371', 'NTOTO-SIALA', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3372', 'SIALA', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3373', 'NTANDOU-MILOMBA', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3374', 'TCHICANOU', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3375', 'TCHIKA-TANGA', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3376', 'TCHINIAMBI-LOEME', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3377', 'TCHITONDI', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3378', 'TCHISSOKO', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3379', 'TCHIVOULA', NULL, 'TPLOC_0008', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3380', 'BADA', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3381', 'BISSIDJI', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3382', 'BOUNGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3383', 'DOUMANGA 3', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3384', 'KAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3385', 'KINANGA', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3386', 'LOAKA', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3387', 'LOUBA-SABLE', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3388', 'LOUKOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3389', 'LOUVOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3390', 'MAGNE', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3391', 'MANZI', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3392', 'M’BOUKOU (CAMP MAB)', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3393', 'MFILOU', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3394', 'MOULA', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3395', 'NDINGA', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3396', 'NGOUNGUI', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3397', 'SEXO', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3398', 'SOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3399', 'TCHISSAFOU', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3400', 'YEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3401', 'ZIBATI', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3402', 'MINDOU', NULL, 'TPLOC_0008', 0, 'LOC_0485', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3403', 'BANGA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3404', 'BILALA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3405', 'BILINGA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3406', 'DIMONIKA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3407', 'DOUMANGA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3408', 'KIPESSI', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3409', 'KOULILA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3410', 'LES-SARAS', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3411', 'LOUVENZA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3412', 'MAKABA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3413', 'MALELE', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3414', 'MALEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3415', 'MASSABI', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3416', 'MFOUBOU', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3417', 'MVOUGOUNTI', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3418', 'NEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3419', 'NKOUGNI', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3420', 'NTAKA-KIPESSI', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3421', 'NZOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3422', 'POUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3423', 'TCHIVALA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3424', 'TSESSI', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3425', 'TSOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3426', 'YANGA', NULL, 'TPLOC_0008', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3427', 'BAS KOUILOU', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3428', 'BELLELO', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3429', 'BIOKO', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3430', 'BIVELA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3431', 'COTOVINDOU', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3432', 'IKALO', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3433', 'KABOUNE', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3434', 'KANGA-TCHIZONDI', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3435', 'KOUANI', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3436', 'KOUANNI-DIGUEMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3437', 'KONONGO', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3438', 'KM', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3439', 'KOUBOTCHI', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3440', 'KOUTOU', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3441', 'LONGO-BONDI', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3442', 'LOUKOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3443', 'MANENGA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3444', 'MBAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3445', 'MBENA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3446', 'MBOUKOU-MASSI', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3447', 'MBOUYOU', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3448', 'MONGO-BISSAFI', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3449', 'NKOLA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3450', 'NGOMA-TCHILOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3451', 'NGOUNDOU-MAKANDA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3452', 'SINTOU-NKOLA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3453', 'TANDOU-YOUMBI', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3454', 'TCHIESSA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3455', 'TCHIONZO', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3456', 'TCHISSEKA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3457', 'TCHIZALAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3458', 'YANGA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3459', 'YANIKA', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3460', 'YOUBI', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3461', 'YOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3462', 'WOLLO', NULL, 'TPLOC_0008', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3463', 'BONDI', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3464', 'KONDI', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3465', 'M’PELLA', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3466', 'MVANDJI', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3467', 'NGOUMBI', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3468', 'NOMBI-LOUSSALA PARIS', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3469', 'NTIE-TIE', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3470', 'TANDOU-NGOMA', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3471', 'TCHIBOTA', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3472', 'TCHILOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3473', 'TSIELLA', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3474', 'SIALIVAKOU', NULL, 'TPLOC_0008', 0, 'LOC_0488', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3475', 'BAS KOUILOU 2', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3476', 'DIOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3477', 'OLL-MOGNI', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3478', 'LIAMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3479', 'MATOMBI', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3480', 'MBOULOUVOKA', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3481', 'MENGO', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3482', 'MONGO-TANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3483', 'M’PILI', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3484', 'MVILANI', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3485', 'NGONDJI', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3486', 'NKOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3487', 'TCHIBEBE', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3488', 'TCHIKOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3489', 'TCHINGOLI', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3490', 'TCHISSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0489', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3491', 'DIX MAISONS', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3492', 'IMVOULI', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3493', 'KITENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3494', 'KONDA', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3495', 'LISSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3496', 'LOUBASSA', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3497', 'MANGUENGUENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3498', 'MATABA – TABA', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3499', 'MBAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3500', 'MOUDONGA', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3501', 'MOUTOU YA NGOMBE', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3502', 'NZETE – MOKO', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3503', 'OYAPI', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3504', 'SINOA LA BELLE', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3505', 'ASSEMBLEE', NULL, 'TPLOC_0008', 0, 'LOC_0400', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3506', 'KINSAKA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3507', 'MAYANAMA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3508', 'MPANGA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3509', 'NKENGUE MANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3510', 'TANDA MOULOKO', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3511', 'KINANGA NKENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3512', 'MADIADIA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3513', 'YENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3514', 'KIMBENZA KOLA II', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3515', 'KALA BOUTOTO', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3516', 'MAYANGA I', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3517', 'MAYANGA II', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3518', 'KIMBOAKA KONGO', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3519', 'MASSISSIA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3520', 'KINGOYO NTELA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3521', 'KINGODALA NSEMI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3522', 'MATENTA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3523', 'KIMBOAKA TEBA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3524', 'KINGOYO LOUKALA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3525', 'MAKELA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3526', 'KANANA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3527', 'LOMBOLO', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3528', 'KINANGA NSOMPI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3529', 'KIBINDA KIKOUANGOU', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3530', 'KINKENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3531', 'KINDZOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3532', 'KINTAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3533', 'MISSASSA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3534', 'KINANGA MBEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3535', 'KINGUILA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3536', 'KIMOUANDA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3537', 'MASSINDA NTADI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3538', 'KINANGA- GANDA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3539', 'LOUBINDOU', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3540', 'KIMBEZA BOUADI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3541', 'BANDA MAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3542', 'KIMPALANGA LOUDIMA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3543', 'MANKALA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3544', 'HIDI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3545', 'MINGA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3546', 'MONGO YANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3547', 'KIBOUESSA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3548', 'LOUKOSSI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3549', 'KIBANGOU', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3550', 'PETIT MANZAOU', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3551', 'GRAND MANDZAOU', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3552', 'KITIDI TOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3553', 'MIDIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3554', 'MBENGO', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3555', 'NZANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3556', 'KEMBASSANI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3557', 'NTOTO WOLA I', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3558', 'KINZAMBI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3559', 'KABADISSOU', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3560', 'MANDZAKALA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3561', 'BOUA BOUA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3562', 'KINGOMA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3563', 'KISSENGA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3564', 'NSOUKOU BOUADI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3565', 'MBANDZA KINIATI', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3566', 'NTOTO WOLA II', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3567', 'KIKOUAMBA MAVOUANDA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3568', 'MAHOUHA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3569', 'SONEL LOUAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0460', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00');
INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_3570', 'DAKAR', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3571', 'KIMBONGA LOUAMBA I', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3572', 'KIMBONGA LOUAMBA II', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3573', 'VOKA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3574', 'MBANDZA KINIATI', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3575', 'KIMPALANGA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3576', 'KINGOUALA KOLA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3577', 'KIMPAMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3578', 'MOUYONDZI MVOUANDZI', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3579', 'KILOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3580', 'KINGOUALA KIMENI', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3581', 'BODISSA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3582', 'KINSOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3583', 'MANSIEDI', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3584', 'LOUBANGALA MONT ALBERT MOUAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3585', 'KINDOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3586', 'LAKA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3587', 'MANGOUFOU MIKAKATI', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3588', 'KINTOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3589', 'KIOSSI', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3590', 'MOUTELA LOUADI', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3591', 'MOUTELA I MPILA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3592', 'MOUTELA II', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3593', 'NGOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3594', 'MOUDIKOULA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3595', 'SAMOUSSOUALOU', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3596', 'YOULOU NKOYI', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3597', 'KIMBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3598', 'LOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3599', 'MOULOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3600', 'KIMENI', NULL, 'TPLOC_0008', 0, 'LOC_0461', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3601', 'MOUNKOMO', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3602', 'KIMPOLO', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3603', 'MAYAMA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3604', 'KINGOMO', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3605', 'KINTSOKO', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3606', 'KIBITI II', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3607', 'KINDZOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3608', 'KINGAH', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3609', 'KINDZOUA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3610', 'NGOLE', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3611', 'KINDZELE', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3612', 'NZANZA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3613', 'MATAMOU', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3614', 'MOUANZENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3615', 'BANTSIELISSI', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3616', 'MATSITI', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3617', 'MOUBIRI', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3618', 'KIBAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3619', 'ZABATA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3620', 'TABA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3621', 'KINTOUARI', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3622', 'KIBITI II', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3623', 'KIMBOUSSI', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3624', 'NKAMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3625', 'KINKOUAMBALA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3626', 'MATADI', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3627', 'MINGALI', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3628', 'WALA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3629', 'KITOUCH', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3630', 'MISSAH', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3631', 'MIKATA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3632', 'KINKOULA', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3633', 'MPINI VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3634', 'MAWOBO', NULL, 'TPLOC_0008', 0, 'LOC_0462', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3635', 'PIKA SONGHO', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3636', 'SOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3637', 'MBOULOUMOUNOUA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3638', 'MOULANGA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3639', 'PASSIPE BAC', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3640', 'MIKASSOU', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3641', 'KINGOMA LOUHALA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3642', 'YOMBE TSATSA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3643', 'MALELA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3644', 'DITADI', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3645', 'TSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3646', 'MOUBOTSI', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3647', 'KIMANDZA PANGA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3648', 'MAYEKO', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3649', 'MBOMO CENTRE', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3650', 'MBOMO I', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3651', 'MBOMO II', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3652', 'MANDZATSI', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3653', 'KIBOUBA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3654', 'KINGUIMBI KILAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3655', 'MOUINDI', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3656', 'SINDA', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3657', 'DIHESSE', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3658', 'NDOLO', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3659', 'MONT BELO', NULL, 'TPLOC_0008', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3660', 'BAMBOUYOU', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3661', 'BOUENZA I', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3662', 'BOUENZA II', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3663', 'KIBATI', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3664', 'KIBOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3665', 'KIÉLÉ I', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3666', 'KIÉLÉ II', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3667', 'KIKANGA- MAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3668', 'KILOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3669', 'KIMANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3670', 'KIMBOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3671', 'KIMFIKOU', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3672', 'KIVÉMBÉ', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3673', 'KINIANGUI I', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3674', 'KINIANGUI II', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3675', 'LOUBOULOU I', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3676', 'LOUBOULOU II', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3677', 'MAKALA', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3678', 'MAKOTO', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3679', 'MAKOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3680', 'MAYOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3681', 'MBILA NZAMBI', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3682', 'MBISSI- MPATI', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3683', 'MBOUNOU I', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3684', 'MBOUNOU II', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3685', 'MIDIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3686', 'MISSALA CARREFOUR', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3687', 'MOUBOUBA', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3688', 'MOUSSANDA I', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3689', 'MOUSSANDA II', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3690', 'MOUTELE', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3691', 'NGUIRI I', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3692', 'NGUIRI II', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3693', 'NGANDA', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3694', 'NKILA- NTARI', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3695', 'NTIRI', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3696', 'NZASSI I', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3697', 'NZAOU I', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3698', 'NZAOU II', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3699', 'NZASSI II', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3700', 'SEKE NENE', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3701', 'SEKE PEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3702', 'TAMOUNOUA', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3703', 'MALOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3704', 'NDINGUI', NULL, 'TPLOC_0008', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3705', 'MPIKA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3706', 'MPALOU I', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3707', 'KISSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3708', 'KINIADI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3709', 'KIMBOAKA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3710', 'KINGEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3711', 'NGOMA BITORI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3712', 'MBOUKI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3713', 'IBA DOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3714', 'KIMBANDA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3715', 'KIKIMOU', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3716', 'MPASSA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3717', 'BITOTO', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3718', 'MBAYE LOANGO', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3719', 'MANDOU MOUNIONDZI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3720', 'MAYIMISSA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3721', 'KIKONDO', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3722', 'YORI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3723', 'BANTIÉTIÉ', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3724', 'BIFOUNDIKA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3725', 'MANDOU III', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3726', 'KINGOMA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3727', 'YOULOU NKOUTOU', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3728', 'BIYOKI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3729', 'KINDAMBA BISSOUAKI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3730', 'NKIÉNI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3731', 'MOUSSÉNÉNGUÉ', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3732', 'AUBEVILLE', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3733', 'KINGOYE MOUDOKO', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3734', 'MBINDA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3735', 'KISIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3736', 'KIMPAMBOU KAYES- GARE', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3737', 'KIMPAMBOU KAYES – VILLAGE', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3738', 'KIBOUNDA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3739', 'KIHOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3740', 'MATEMBO', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3741', 'NSANGA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3742', 'KIMBENZA NDIBA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3743', 'MFOUNDOU', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3744', 'MOUPEPE', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3745', 'MIENGUE MIENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3746', 'NKOYI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3747', 'MANTSOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3748', 'NTENZI', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3749', 'MINGUENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3750', 'NGAMBA MONASTERE', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3751', 'KIMBEDI CARREFOUR', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3752', 'MPALOU II', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3753', 'KIMBEDI BAC', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3754', 'NGANDA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3755', 'NGANDOU', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3756', 'MBOMA', NULL, 'TPLOC_0008', 0, 'LOC_0465', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3757', 'NGOLONGA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3758', 'BIKOTI II', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3759', 'KIMBENZA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3760', 'KINGOYI MOUHANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3761', 'MPEMO', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3762', 'MPETE', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3763', 'KINGOUMA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3764', 'MANGOLA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3765', 'KINANGA PETIT', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3766', 'MOUTIYAKA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3767', 'KINSIMBA TEMPE', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3768', 'BOUENDE MPINDA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3769', 'KINGOULA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3770', 'KINGOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3771', 'KINGOUALA MINGUENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3772', 'KILEMBA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3773', 'KINGONDA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3774', 'BIKOUMBI', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3775', 'TALA NKOYI', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3776', 'KINDAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3777', 'KINANAGA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3778', 'TABOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3779', 'NZANGATA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3780', 'YALALA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3781', 'BISSINDZA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3782', 'SIKA NINGA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3783', 'BIDZOUA NGOMA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3784', 'MISSAFOU', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3785', 'MAYANGA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3786', 'MPANGA YANGOLA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3787', 'SAINT MICHEL', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3788', 'KINKANGA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3789', 'MAKILA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3790', 'KIMBIMBI', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3791', 'LOUTETE CARREFOUR', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3792', 'KIMOUANDA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3793', 'KINDZABA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3794', 'NGOYO', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3795', 'BIBONDO PONT', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3796', 'NGOUEDI', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3797', 'KINDALA BIKOTI', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3798', 'KINGOUALA MBOUESSE', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3799', 'KINKOUMBA NKAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0466', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3800', 'BOUMOYO', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3801', 'NKOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3802', 'MBELLO', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3803', 'BIBOURI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3804', 'MOUSSENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3805', 'KOLO', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3806', 'MPANGA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3807', 'MOULEKE', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3808', 'KIMPELE', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3809', 'NZABI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3810', 'NGUIRI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3811', 'MAMBOUYOU', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3812', 'MBOUNOU I MISSIA MISSIA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3813', 'BOUSSOUMOUNA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3814', 'MOUKONDO', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3815', 'SOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3816', 'MOUSSANDA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3817', 'PANDII', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3818', 'PANDIII', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3819', 'PANDI. III', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3820', 'PANDI MAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3821', 'KIMPONGUI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3822', 'KINTOUARI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3823', 'MAKALA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3824', 'NTSOUMBOU I', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3825', 'NTSOUMBOU II', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3826', 'KIMBIMI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3827', 'MANGANDZA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3828', 'KIELE', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3829', 'KINTSOUARI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3830', 'MPENGUI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3831', 'NSAOU', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3832', 'MAYALAMA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3833', 'NTARI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3834', 'KIPÉNI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3835', 'BAZANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3836', 'MADINGA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3837', 'NGANDA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3838', 'MADOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3839', 'KINKABOU', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3840', 'MOUTAMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3841', 'BOUANSI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3842', 'KINGOUALA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3843', 'KINGOYE I', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3844', 'KINGOYE II', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3845', 'LOUBOTOECOLE', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3846', 'MOUABI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3847', 'NTENDE', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3848', 'NTIRI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3849', 'MOUIRII', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3850', 'MOUBIRIII', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3851', 'MAKOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3852', 'KINIANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3853', 'KIMFIKOU', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3854', 'MPANGALA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3855', 'KENGUE', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3856', 'MOUNDI MPIKA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3857', 'MOUANDIMOUKELO', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3858', 'MPANDIBISSA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3859', 'MOUZANGA ZAKETE ZAKETE', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3860', 'ZANGA', NULL, 'TPLOC_0008', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3861', 'BOSSO I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3862', 'BOSSO II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3863', 'KIFOULOU I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3864', 'KIFOULOU II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3865', 'KIKAYA I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3866', 'KIKAYA II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3867', 'KILOUNGOU', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3868', 'KIMALOU I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3869', 'KIMBANA', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3870', 'KIMBIMI I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3871', 'KIMBIMI II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3872', 'KIMBIMI II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3873', 'KIMBOTO', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3874', 'KIMPORI', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3875', 'KINDELE', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3876', 'KINDZOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3877', 'KINGOLO', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3878', 'KINGOUÉLÉ', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3879', 'KINKAYA', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3880', 'KINAIANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3881', 'KISSIELE', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3882', 'KOUYOU', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3883', 'MADOUNGOU I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3884', 'MADOUNGOU II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3885', 'MADZOUNOU', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3886', 'MAKAKA', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3887', 'MALIMA', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3888', 'MASSIA', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3889', 'MAYOULOU', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3890', 'MBOUMOU', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3891', 'MIAMA', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3892', 'MINGALI I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3893', 'MINGALI II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3894', 'MOULIENE', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3895', 'NGUENGUE I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3896', 'NGUENGUE II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3897', 'NKAA', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3898', 'NKOYI', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3899', 'PONO I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3900', 'PONO II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3901', 'TETEBE', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3902', 'TSIAKI II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3903', 'TSIAKI III', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3904', 'TSOMONO I', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3905', 'TSOMONO II', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3906', 'YAMA', NULL, 'TPLOC_0008', 0, 'LOC_0468', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3907', 'MBOUMBOU', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3908', 'NGAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3909', 'KINKAMA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3910', 'MANDA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3911', 'MVOUARA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3912', 'MPEBME', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3913', 'NSENDE', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3914', 'MALOUENDE', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3915', 'MASSANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3916', 'NZAOU', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3917', 'KIMBA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3918', 'SONEL', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3919', 'BOUMBI', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3920', 'BAMBEMBE', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3921', 'MOUTEMBESSE', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3922', 'BOUSSOUMOUNA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3923', 'MOUTIYAKA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3924', 'BIKOUKA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3925', 'MPETOU', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3926', 'NKILA NTARI', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3927', 'MOUDZANGA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3928', 'PARIS', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3929', 'KINIANGUI', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3930', 'NDOUNGA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3931', 'NTEBELE', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3932', 'MFILA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3933', 'MOUKOSSO', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3934', 'NDINGUI', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3935', 'KINDZAKA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3936', 'KINTSAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3937', 'KIMPOMBO', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3938', 'MOUTÉLÉ', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3939', 'KINDZOUMBA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3940', 'MIYAMBA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3941', 'MABA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3942', 'BIGNIOGNIO', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3943', 'KIBITI', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3944', 'KIMBOULA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3945', 'KINDOLO', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3946', 'MAKOUKOU', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3947', 'NTALA', NULL, 'TPLOC_0008', 0, 'LOC_0469', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3948', 'DIVENIE', NULL, 'TPLOC_0005', 0, 'LOC_0471', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3949', 'KIBANGOU', NULL, 'TPLOC_0005', 0, 'LOC_0472', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3950', 'KIMONGO', NULL, 'TPLOC_0005', 0, 'LOC_0473', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3951', 'MAKABANA', NULL, 'TPLOC_0005', 0, 'LOC_0476', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3952', 'MBINDA', NULL, 'TPLOC_0005', 0, 'LOC_0478', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3953', 'KOMONO', NULL, 'TPLOC_0005', 0, 'LOC_0456', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3954', 'ZANAGA', NULL, 'TPLOC_0005', 0, 'LOC_0457', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3955', 'TCHIAMBA NZASSI', NULL, 'TPLOC_0005', 0, 'LOC_0401', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3956', 'ABALA', NULL, 'TPLOC_0005', 0, 'LOC_0431', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3957', 'GAMBOMA', NULL, 'TPLOC_0005', 0, 'LOC_0433', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3958', 'LEKANA', NULL, 'TPLOC_0005', 0, 'LOC_0436', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3959', 'NGO', NULL, 'TPLOC_0005', 0, 'LOC_0435', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3960', 'OLLOMBO', NULL, 'TPLOC_0005', 0, 'LOC_0437', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3961', 'ONGONGNI', NULL, 'TPLOC_0005', 0, 'LOC_0439', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3962', 'BOKO', NULL, 'TPLOC_0005', 0, 'LOC_0451', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3963', 'IGNIE', NULL, 'TPLOC_0005', 0, 'LOC_0444', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3964', 'KIBOUENDE', NULL, 'TPLOC_0005', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3965', 'KINDAMBA', NULL, 'TPLOC_0005', 0, 'LOC_0453', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3966', 'MINDOULI', NULL, 'TPLOC_0005', 0, 'LOC_0450', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3967', 'NGABE', NULL, 'TPLOC_0005', 0, 'LOC_0449', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3968', 'BETOU', NULL, 'TPLOC_0005', 0, 'LOC_0402', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3969', 'DONGOU', NULL, 'TPLOC_0005', 0, 'LOC_0404', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3970', 'ENYELLE', NULL, 'TPLOC_0005', 0, 'LOC_0405', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3971', 'EPENA', NULL, 'TPLOC_0005', 0, 'LOC_0406', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3972', 'BOUNDJI', NULL, 'TPLOC_0005', 0, 'LOC_0429', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3973', 'MAKOUA', NULL, 'TPLOC_0005', 0, 'LOC_0423', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3974', 'TCHICAPIKA', NULL, 'TPLOC_0005', 0, 'LOC_0424', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3975', 'MOSSAKA', NULL, 'TPLOC_0005', 0, 'LOC_0427', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3976', 'LOUKOLELA', NULL, 'TPLOC_0005', 0, 'LOC_0428', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3977', 'ETOUMBI', NULL, 'TPLOC_0005', 0, 'LOC_0415', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3978', 'KELLE', NULL, 'TPLOC_0005', 0, 'LOC_0417', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3979', 'OKOYO', NULL, 'TPLOC_0005', 0, 'LOC_0420', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3980', 'MOKEKO', NULL, 'TPLOC_0005', 0, 'LOC_0410', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3981', 'SEMBE', NULL, 'TPLOC_0005', 0, 'LOC_0413', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3982', 'SOUANKE', NULL, 'TPLOC_0005', 0, 'LOC_0414', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3983', 'HINDA', NULL, 'TPLOC_0005', 0, 'LOC_0484', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3984', 'MADINGO-KAYES', NULL, 'TPLOC_0005', 0, 'LOC_0487', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3985', 'MVOUTI', NULL, 'TPLOC_0005', 0, 'LOC_0486', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3986', 'BOUANSA', NULL, 'TPLOC_0005', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3987', 'LOUDIMA', NULL, 'TPLOC_0005', 0, 'LOC_0463', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3988', 'LOUTETE', NULL, 'TPLOC_0005', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3989', 'MABOMBO', NULL, 'TPLOC_0005', 0, 'LOC_0464', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3990', 'MOUYONDZI', NULL, 'TPLOC_0005', 0, 'LOC_0467', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3991', 'BIBAKA', NULL, 'TPLOC_0007', 0, 'LOC_3948', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3992', 'YALA', NULL, 'TPLOC_0007', 0, 'LOC_3948', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3993', 'BOUNDZA', NULL, 'TPLOC_0007', 0, 'LOC_3948', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3994', 'HOPITAL', NULL, 'TPLOC_0007', 0, 'LOC_3948', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3995', 'MILIMBA', NULL, 'TPLOC_0007', 0, 'LOC_3948', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3996', 'MAYOUKOU', NULL, 'TPLOC_0007', 0, 'LOC_3948', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3997', '4 CHEMINS', NULL, 'TPLOC_0007', 0, 'LOC_3948', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3998', 'MAKAYA LAZARD', NULL, 'TPLOC_0007', 0, 'LOC_3949', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_3999', 'CAMP 12', NULL, 'TPLOC_0007', 0, 'LOC_3949', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4000', 'LINGUALA', NULL, 'TPLOC_0007', 0, 'LOC_3949', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4001', 'CARREFOUR', NULL, 'TPLOC_0007', 0, 'LOC_3949', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4002', 'MOUNZEO ', NULL, 'TPLOC_0007', 0, 'LOC_3949', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4003', 'NZAKOUA', NULL, 'TPLOC_0007', 0, 'LOC_3949', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4004', 'KOUNI', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4005', 'KIMONGO-VILLAGE', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4006', 'TSATOU-KIMONGO', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4007', 'KIMOUELE', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4008', 'INDIGENE', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4009', 'KONGO-KIVOUNDA ', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4010', 'KIBOUANDI', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4011', 'BOUKOU-PAKA', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4012', 'KINZIETE', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4013', 'KIMONGO-POSTE', NULL, 'TPLOC_0007', 0, 'LOC_3950', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4014', 'AEROPORT', NULL, 'TPLOC_0007', 0, 'LOC_3951', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4015', 'CHATEAU D’EAU', NULL, 'TPLOC_0007', 0, 'LOC_3951', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00');
INSERT INTO `tr_localite` (`code_localite`, `lib_localite`, `code_officel`, `code_type_localite`, `pompes_funebres`, `code_localite_parent`, `deleted_at`, `created_at`, `updated_at`) VALUES
('LOC_4016', 'MUELLA', NULL, 'TPLOC_0007', 0, 'LOC_3951', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4017', 'CARRE', NULL, 'TPLOC_0007', 0, 'LOC_3951', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4018', 'CONGO BOIS', NULL, 'TPLOC_0007', 0, 'LOC_3951', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4019', 'CAMP INDUSTRIEL', NULL, 'TPLOC_0007', 0, 'LOC_3951', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4020', 'MOUKONDO ', NULL, 'TPLOC_0007', 0, 'LOC_3951', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4021', 'CITE VIGOR', NULL, 'TPLOC_0007', 0, 'LOC_3951', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4022', 'CQ 1 15 AOUT', NULL, 'TPLOC_0007', 0, 'LOC_3952', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4023', 'CQ 2 AVIATION', NULL, 'TPLOC_0007', 0, 'LOC_3952', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4024', 'CQ 3 PASSIA', NULL, 'TPLOC_0007', 0, 'LOC_3952', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4025', 'CQ 4 NGANI-NGANI', NULL, 'TPLOC_0007', 0, 'LOC_3952', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4026', 'CQ 5 KM 4', NULL, 'TPLOC_0007', 0, 'LOC_3952', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4027', 'CQ 6 NIOLO', NULL, 'TPLOC_0007', 0, 'LOC_3952', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4028', 'CQ 7 AMITIE', NULL, 'TPLOC_0007', 0, 'LOC_3952', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4029', 'CQ 8 POTO-POTO', NULL, 'TPLOC_0007', 0, 'LOC_3952', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4030', 'CENTRE-VILLE', NULL, 'TPLOC_0007', 0, 'LOC_3953', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4031', 'GNIMI', NULL, 'TPLOC_0007', 0, 'LOC_3953', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4032', 'MAKELE', NULL, 'TPLOC_0007', 0, 'LOC_3953', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4033', 'FOULA', NULL, 'TPLOC_0007', 0, 'LOC_3953', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4034', 'MOUALA', NULL, 'TPLOC_0007', 0, 'LOC_3953', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4035', 'LISSENGUE', NULL, 'TPLOC_0007', 0, 'LOC_3953', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4036', 'SAMA', NULL, 'TPLOC_0007', 0, 'LOC_3954', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4037', 'DJOUELI', NULL, 'TPLOC_0007', 0, 'LOC_3954', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4038', 'SOCIAL', NULL, 'TPLOC_0007', 0, 'LOC_3954', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4039', 'POTO - POTO', NULL, 'TPLOC_0007', 0, 'LOC_3954', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4040', 'TCHIAMBA', NULL, 'TPLOC_0007', 0, 'LOC_3955', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4041', 'NZASSI', NULL, 'TPLOC_0007', 0, 'LOC_3955', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4042', 'CHIC', NULL, 'TPLOC_0007', 0, 'LOC_3956', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4043', 'FOUBA', NULL, 'TPLOC_0007', 0, 'LOC_3956', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4044', '23 mars', NULL, 'TPLOC_0007', 0, 'LOC_3956', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4045', 'YELLE', NULL, 'TPLOC_0007', 0, 'LOC_3956', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4046', 'AGNIE', NULL, 'TPLOC_0007', 0, 'LOC_3957', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4047', 'BENES', NULL, 'TPLOC_0007', 0, 'LOC_3957', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4048', 'KOMO', NULL, 'TPLOC_0007', 0, 'LOC_3957', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4049', 'LOUARA', NULL, 'TPLOC_0007', 0, 'LOC_3957', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4050', 'MBAMBI', NULL, 'TPLOC_0007', 0, 'LOC_3957', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4051', 'MPAIRE 1', NULL, 'TPLOC_0007', 0, 'LOC_3957', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4052', 'MPAIRE 2', NULL, 'TPLOC_0007', 0, 'LOC_3957', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4053', 'NKENI', NULL, 'TPLOC_0007', 0, 'LOC_3957', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4054', 'EBONGO', NULL, 'TPLOC_0007', 0, 'LOC_3958', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4055', 'MFOA', NULL, 'TPLOC_0007', 0, 'LOC_3958', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4056', 'LEFOUROU', NULL, 'TPLOC_0007', 0, 'LOC_3958', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4057', 'ITOLI', NULL, 'TPLOC_0007', 0, 'LOC_3958', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4058', 'KONO', NULL, 'TPLOC_0007', 0, 'LOC_3959', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4059', 'MBESSALA', NULL, 'TPLOC_0007', 0, 'LOC_3959', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4060', 'MPALA', NULL, 'TPLOC_0007', 0, 'LOC_3959', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4061', 'YANA –YANI', NULL, 'TPLOC_0007', 0, 'LOC_3959', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4062', 'COMO', NULL, 'TPLOC_0007', 0, 'LOC_3960', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4063', 'EMERY PATRICE LUMUMBA', NULL, 'TPLOC_0007', 0, 'LOC_3960', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4064', 'MARIEN NGOUABI', NULL, 'TPLOC_0007', 0, 'LOC_3960', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4065', 'OLLEME', NULL, 'TPLOC_0007', 0, 'LOC_3960', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4066', 'OTTO MBONGO', NULL, 'TPLOC_0007', 0, 'LOC_3960', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4067', 'BONGHO DIDYME', NULL, 'TPLOC_0007', 0, 'LOC_3961', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4068', 'EMINA', NULL, 'TPLOC_0007', 0, 'LOC_3961', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4069', 'ENGHA', NULL, 'TPLOC_0007', 0, 'LOC_3961', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4070', 'ETALE', NULL, 'TPLOC_0007', 0, 'LOC_3961', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4071', 'KIELI- KIELI', NULL, 'TPLOC_0007', 0, 'LOC_3961', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4072', 'BIZA', NULL, 'TPLOC_0007', 0, 'LOC_3962', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4073', 'MBONGO', NULL, 'TPLOC_0007', 0, 'LOC_3962', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4074', 'SAMBA-NDONGO', NULL, 'TPLOC_0007', 0, 'LOC_3962', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4075', 'BOKO VILLAGE', NULL, 'TPLOC_0007', 0, 'LOC_3962', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4076', 'YONGO', NULL, 'TPLOC_0007', 0, 'LOC_3962', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4077', 'KITADI', NULL, 'TPLOC_0007', 0, 'LOC_3962', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4078', 'CAMPEMENT', NULL, 'TPLOC_0007', 0, 'LOC_3963', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4079', 'NDIBOU', NULL, 'TPLOC_0007', 0, 'LOC_3963', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4080', 'NGAKOUBA', NULL, 'TPLOC_0007', 0, 'LOC_3963', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4081', 'BOKO-MAYAKA', NULL, 'TPLOC_0007', 0, 'LOC_3964', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4082', 'KINKOKOSSO', NULL, 'TPLOC_0007', 0, 'LOC_3964', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4083', 'SAINT PAUL', NULL, 'TPLOC_0007', 0, 'LOC_3964', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4084', 'MONGO MARIE', NULL, 'TPLOC_0007', 0, 'LOC_3964', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4085', 'MIKOLE', NULL, 'TPLOC_0007', 0, 'LOC_3964', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4086', 'PAUL MOUDIMBA', NULL, 'TPLOC_0007', 0, 'LOC_3964', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4087', 'PIKA-MBANA', NULL, 'TPLOC_0007', 0, 'LOC_3964', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4088', 'NKAMA-MAYETELA', NULL, 'TPLOC_0007', 0, 'LOC_3964', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4089', 'FRANCKA', NULL, 'TPLOC_0007', 0, 'LOC_3964', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4090', 'CAMP MACON', NULL, 'TPLOC_0007', 0, 'LOC_3965', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4091', 'KINDAMBA NKOUNKOU', NULL, 'TPLOC_0007', 0, 'LOC_3965', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4092', 'KINGOMA', NULL, 'TPLOC_0007', 0, 'LOC_3965', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4093', 'MATAKA', NULL, 'TPLOC_0007', 0, 'LOC_3965', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4094', 'MOULALA', NULL, 'TPLOC_0007', 0, 'LOC_3965', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4095', 'PAROISSE', NULL, 'TPLOC_0007', 0, 'LOC_3965', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4096', 'SAINT JOSEPH', NULL, 'TPLOC_0007', 0, 'LOC_3965', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4097', 'CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4098', 'MOUTESSI', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4099', 'MATENSAMA', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4100', 'LA CITE', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4101', 'SANS-FIL 1', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4102', 'SANS-FIL 2', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4103', 'HOPITAL', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4104', 'C.P.R', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4105', 'YOULOUBIENGUE', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4106', 'TRAVERS BANK', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4107', 'BANSELELE', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4108', 'MOUBOULOU', NULL, 'TPLOC_0007', 0, 'LOC_3966', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4109', 'MPUTU', NULL, 'TPLOC_0007', 0, 'LOC_3967', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4110', 'NZILA', NULL, 'TPLOC_0007', 0, 'LOC_3967', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4111', 'NGALIFOUROU', NULL, 'TPLOC_0007', 0, 'LOC_3967', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4112', 'NDZION', NULL, 'TPLOC_0007', 0, 'LOC_3967', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4113', 'NGAYO', NULL, 'TPLOC_0007', 0, 'LOC_3967', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4114', 'CQ-1 ÇA METRE 1', NULL, 'TPLOC_0007', 0, 'LOC_3968', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4115', 'CQ-2 ÇA METRE 2', NULL, 'TPLOC_0007', 0, 'LOC_3968', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4116', 'CQ-3 MOUNDZOMBO 1', NULL, 'TPLOC_0007', 0, 'LOC_3968', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4117', 'CQ-4 MOUNDZOMBO 2', NULL, 'TPLOC_0007', 0, 'LOC_3968', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4118', 'CQ-5, YENDE 1', NULL, 'TPLOC_0007', 0, 'LOC_3968', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4119', 'CQ-6, YENDE 2', NULL, 'TPLOC_0007', 0, 'LOC_3968', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4120', 'QUARTIER 1', NULL, 'TPLOC_0007', 0, 'LOC_3970', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4121', 'QUARTIER 2', NULL, 'TPLOC_0007', 0, 'LOC_3970', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4122', 'QUARTIER 3', NULL, 'TPLOC_0007', 0, 'LOC_3970', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4123', 'QUARTIER 4', NULL, 'TPLOC_0007', 0, 'LOC_3970', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4124', 'QUARTIER 5', NULL, 'TPLOC_0007', 0, 'LOC_3970', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4125', 'QUARTIER 6', NULL, 'TPLOC_0007', 0, 'LOC_3970', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4126', 'EKOLA', NULL, 'TPLOC_0007', 0, 'LOC_3972', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4127', 'MATADI', NULL, 'TPLOC_0007', 0, 'LOC_3972', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4128', 'CHATEAU D’EAU', NULL, 'TPLOC_0007', 0, 'LOC_3972', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4129', 'OYOA', NULL, 'TPLOC_0007', 0, 'LOC_3972', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4130', 'EBONGATSE', NULL, 'TPLOC_0007', 0, 'LOC_3972', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4131', 'TSAMBITSO', NULL, 'TPLOC_0007', 0, 'LOC_3972', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4132', 'EKOLAKE', NULL, 'TPLOC_0007', 0, 'LOC_3973', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4133', 'MBATAKA', NULL, 'TPLOC_0007', 0, 'LOC_3973', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4134', 'MOSSA-KETA', NULL, 'TPLOC_0007', 0, 'LOC_3973', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4135', 'OHADE', NULL, 'TPLOC_0007', 0, 'LOC_3973', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4136', 'BONGA', NULL, 'TPLOC_0007', 0, 'LOC_3973', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4137', 'ENIMBANINDZA', NULL, 'TPLOC_0007', 0, 'LOC_3973', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4138', 'MBAYE', NULL, 'TPLOC_0007', 0, 'LOC_3974', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4139', 'NGUIEMBI', NULL, 'TPLOC_0007', 0, 'LOC_3974', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4140', 'BIANGALA', NULL, 'TPLOC_0007', 0, 'LOC_3975', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4141', 'MALEBOU', NULL, 'TPLOC_0007', 0, 'LOC_3975', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4142', 'BABOMBO', NULL, 'TPLOC_0007', 0, 'LOC_3975', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4143', 'CONGO YA SIKA', NULL, 'TPLOC_0007', 0, 'LOC_3975', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4144', 'BOKANDO', NULL, 'TPLOC_0007', 0, 'LOC_3975', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4145', 'MOBAKA', NULL, 'TPLOC_0007', 0, 'LOC_3975', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4146', 'LIBELE', NULL, 'TPLOC_0007', 0, 'LOC_3975', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4147', 'MOTENDI', NULL, 'TPLOC_0007', 0, 'LOC_3975', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4148', 'BONGONDA', NULL, 'TPLOC_0007', 0, 'LOC_3976', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4149', '15 ANS', NULL, 'TPLOC_0007', 0, 'LOC_3976', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4150', 'COMPAGNIE', NULL, 'TPLOC_0007', 0, 'LOC_3976', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4151', 'NGOMBA', NULL, 'TPLOC_0007', 0, 'LOC_3976', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4152', 'BIANGALA', NULL, 'TPLOC_0007', 0, 'LOC_3976', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4153', 'AMVOUA', NULL, 'TPLOC_0007', 0, 'LOC_3977', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4154', 'APENGUE', NULL, 'TPLOC_0007', 0, 'LOC_3977', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4155', 'KOSSOLOBA', NULL, 'TPLOC_0007', 0, 'LOC_3977', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4156', 'OMONDJO', NULL, 'TPLOC_0007', 0, 'LOC_3977', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4157', 'YONGOLO', NULL, 'TPLOC_0007', 0, 'LOC_3977', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4158', 'BOMI', NULL, 'TPLOC_0007', 0, 'LOC_3978', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4159', 'ELELY', NULL, 'TPLOC_0007', 0, 'LOC_3978', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4160', 'KELLE VILLAGE', NULL, 'TPLOC_0007', 0, 'LOC_3978', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4161', 'LEMBELET', NULL, 'TPLOC_0007', 0, 'LOC_3978', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4162', 'MAKOLO', NULL, 'TPLOC_0007', 0, 'LOC_3978', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4163', 'OYABI', NULL, 'TPLOC_0007', 0, 'LOC_3978', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4164', 'ADZHE', NULL, 'TPLOC_0007', 0, 'LOC_3979', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4165', 'ASSALI', NULL, 'TPLOC_0007', 0, 'LOC_3979', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4166', 'BOULIGUI', NULL, 'TPLOC_0007', 0, 'LOC_3979', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4167', 'NDOUMOU', NULL, 'TPLOC_0007', 0, 'LOC_3979', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4168', 'NTSOULOU', NULL, 'TPLOC_0007', 0, 'LOC_3979', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4169', 'CAMP BOUVIER', NULL, 'TPLOC_0007', 0, 'LOC_3980', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4170', 'COMMISIMPEX', NULL, 'TPLOC_0007', 0, 'LOC_3980', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4171', 'KANGATEMA', NULL, 'TPLOC_0007', 0, 'LOC_3980', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4172', 'MAKOLA', NULL, 'TPLOC_0007', 0, 'LOC_3980', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4173', 'NOUVEAU VIILAGE', NULL, 'TPLOC_0007', 0, 'LOC_3980', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4174', 'PAKO', NULL, 'TPLOC_0007', 0, 'LOC_3980', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4175', 'PETIT VILAGE', NULL, 'TPLOC_0007', 0, 'LOC_3980', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4176', 'ZOULA BODINGO', NULL, 'TPLOC_0007', 0, 'LOC_3980', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4177', 'ZOULA KEKA', NULL, 'TPLOC_0007', 0, 'LOC_3980', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4178', 'CENTRE-VILLE', NULL, 'TPLOC_0007', 0, 'LOC_3981', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4179', 'COPAYER', NULL, 'TPLOC_0007', 0, 'LOC_3981', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4180', 'YENGA', NULL, 'TPLOC_0007', 0, 'LOC_3981', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4181', 'EGOUOLGOUOL', NULL, 'TPLOC_0007', 0, 'LOC_3981', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4182', 'NAMOPORO', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4183', 'PELEMBA', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4184', 'MBAFANE', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4185', 'MOBEMPA', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4186', 'SCIERIE', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4187', 'BAM 1', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4188', 'BAM 2', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4189', 'POTO-POTO', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4190', 'MPALA-NGOMAN', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4191', 'NOMBAKELE', NULL, 'TPLOC_0007', 0, 'LOC_3982', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4192', 'SIC', NULL, 'TPLOC_0007', 0, 'LOC_3983', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4193', 'TCHIENNDJILI', NULL, 'TPLOC_0007', 0, 'LOC_3983', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4194', 'TCHILOUMBOU-LIAMBOU', NULL, 'TPLOC_0007', 0, 'LOC_3983', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4195', 'KAYES-POSTE', NULL, 'TPLOC_0007', 0, 'LOC_3984', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4196', 'SAINTE MARIE', NULL, 'TPLOC_0007', 0, 'LOC_3984', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4197', 'MONT LIBERE', NULL, 'TPLOC_0007', 0, 'LOC_3985', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4198', 'LA GARE', NULL, 'TPLOC_0007', 0, 'LOC_3985', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4199', 'MASSAMVOU', NULL, 'TPLOC_0007', 0, 'LOC_3985', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4200', 'Q1', NULL, 'TPLOC_0007', 0, 'LOC_3986', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4201', 'Q2', NULL, 'TPLOC_0007', 0, 'LOC_3986', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4202', 'Q3', NULL, 'TPLOC_0007', 0, 'LOC_3986', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4203', 'Q4', NULL, 'TPLOC_0007', 0, 'LOC_3986', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4204', 'Q5', NULL, 'TPLOC_0007', 0, 'LOC_3986', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4205', 'Q6', NULL, 'TPLOC_0007', 0, 'LOC_3986', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4206', 'Q7', NULL, 'TPLOC_0007', 0, 'LOC_3986', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4207', 'Q8', NULL, 'TPLOC_0007', 0, 'LOC_3986', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4208', 'CENTRAL', NULL, 'TPLOC_0007', 0, 'LOC_3987', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4209', 'LOUVILA', NULL, 'TPLOC_0007', 0, 'LOC_3987', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4210', 'MOUKONDO', NULL, 'TPLOC_0007', 0, 'LOC_3987', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4211', 'PONT', NULL, 'TPLOC_0007', 0, 'LOC_3987', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4212', 'POSTE 1', NULL, 'TPLOC_0007', 0, 'LOC_3987', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4213', 'POSTE 2', NULL, 'TPLOC_0007', 0, 'LOC_3987', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4214', 'POUNGOU', NULL, 'TPLOC_0007', 0, 'LOC_3987', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4215', 'POUNGOU 2', NULL, 'TPLOC_0007', 0, 'LOC_3987', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4216', 'C.E.G', NULL, 'TPLOC_0007', 0, 'LOC_3988', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4217', 'CENTRE', NULL, 'TPLOC_0007', 0, 'LOC_3988', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4218', 'KINKOUMBA TANGA', NULL, 'TPLOC_0007', 0, 'LOC_3988', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4219', 'KISSENDE', NULL, 'TPLOC_0007', 0, 'LOC_3988', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4220', 'LOUIMBI', NULL, 'TPLOC_0007', 0, 'LOC_3988', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4221', 'MATADI', NULL, 'TPLOC_0007', 0, 'LOC_3988', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4222', 'O.C.H', NULL, 'TPLOC_0007', 0, 'LOC_3988', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4223', '31 juillet', NULL, 'TPLOC_0007', 0, 'LOC_3988', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4224', 'LOUKANGOU', NULL, 'TPLOC_0007', 0, 'LOC_3989', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4225', 'MABIALA', NULL, 'TPLOC_0007', 0, 'LOC_3989', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4226', 'MBOUMA', NULL, 'TPLOC_0007', 0, 'LOC_3989', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4227', 'MOUELE', NULL, 'TPLOC_0007', 0, 'LOC_3989', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4228', 'MOUDIBOU', NULL, 'TPLOC_0007', 0, 'LOC_3989', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4229', 'MOUKOMBO', NULL, 'TPLOC_0007', 0, 'LOC_3989', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4230', 'AEROPORT', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4231', 'BANGUEMBO', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4232', 'CAMP SIC', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4233', 'HAOUSSA', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4234', 'KAYES I', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4235', 'KAYES II', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4236', 'KINTOUARI', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4237', 'MANGUEMBO', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4238', 'MATOLO I', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4239', 'MATOLO II', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4240', 'MOUBOMBO', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4241', 'MOUKALA VILLAGE', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4242', 'MOUYONDZI VILLAGE', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4243', 'NKILA', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4244', 'NTSANGOU', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4245', 'ZONGO', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4246', '31 juillet', NULL, 'TPLOC_0007', 0, 'LOC_3990', NULL, '2023-08-15 18:00:00', '2023-08-15 18:00:00'),
('LOC_4247', 'ETRANGER', NULL, 'TPLOC_0003', 0, NULL, NULL, '2024-02-20 08:11:09', '2023-08-15 18:00:00'),
('LOC_4250', 'NON DECLARE', NULL, 'TPLOC_0009', 0, NULL, NULL, '2024-08-27 07:07:53', '2024-08-27 07:07:53'),
('LOC_4251', 'MOUNDZELI', NULL, 'TPLOC_0007', 0, 'LOC_0421', NULL, '2024-10-21 08:57:21', '2024-11-04 11:07:54'),
('LOC_4272', 'ISSABI', NULL, 'TPLOC_0007', 0, 'LOC_0421', NULL, '2024-12-09 08:09:55', '2024-12-09 08:09:55'),
('LOC_4273', 'MOUENGUE', NULL, 'TPLOC_0007', 0, 'LOC_0421', NULL, '2024-12-09 08:11:59', '2024-12-09 08:11:59'),
('LOC_4279', 'KIKULA', NULL, 'TPLOC_0004', 0, 'LOC_4272', NULL, '2024-12-09 08:15:37', '2024-12-09 08:15:37'),
('LOC_4280', 'BOUA', NULL, 'TPLOC_0007', 0, 'LOC_0421', NULL, '2024-12-09 08:16:01', '2024-12-09 08:16:01'),
('LOC_4281', 'APORO 1', NULL, 'TPLOC_0007', 0, 'LOC_0421', NULL, '2024-12-09 08:16:44', '2024-12-09 08:16:44'),
('LOC_4282', 'SHITURU', NULL, 'TPLOC_0004', 0, 'LOC_4272', '2026-01-14 18:31:26', '2024-12-09 08:17:21', '2026-01-14 18:31:26'),
('LOC_4283', 'OPALA', NULL, 'TPLOC_0008', 0, 'LOC_3956', '2026-01-15 07:46:18', '2026-01-14 21:17:39', '2026-01-15 07:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `tr_module`
--

CREATE TABLE `tr_module` (
  `code_module` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_module` text COLLATE utf8mb4_unicode_ci,
  `etat_module` enum('Activé','Désactivé') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Désactivé',
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_module`
--

INSERT INTO `tr_module` (`code_module`, `lib_module`, `description_module`, `etat_module`, `supprimer`, `created_at`, `updated_at`) VALUES
('MOD_0001', 'Gestion d\'accès au Système', 'Module qui permet à un utilisateur d\'accéder au système', 'Activé', 0, '2023-04-23 11:21:57', '2023-04-23 11:21:57'),
('MOD_0002', 'Gestion des naissances', 'Permet à un utilisateur d\'accéder aux données des naissance dans le système', 'Activé', 0, '2023-04-23 11:21:57', '2023-04-23 11:21:57'),
('MOD_0003', 'Gestion des décès', 'Permet à un utilisateur d\'accéder aux données des décès dans le système', 'Activé', 0, '2023-04-23 11:21:57', '2023-04-23 11:21:57'),
('MOD_0004', 'Gestion des mariages', 'Permet à un utilisateur d\'accéder aux données des mariages dans le système', 'Activé', 0, '2023-04-23 11:21:57', '2023-04-23 11:21:57'),
('MOD_0005', 'Gestion des divorces', 'Permet à un utilisateur d\'accéder aux données des divorces dans le système', 'Activé', 0, '2023-04-23 11:21:57', '2023-04-23 11:21:57');

-- --------------------------------------------------------

--
-- Table structure for table `tr_mouvement`
--

CREATE TABLE `tr_mouvement` (
  `code_mouvement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_mouvement` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_mouvement`
--

INSERT INTO `tr_mouvement` (`code_mouvement`, `lib_mouvement`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
('MOUV_0001', 'Déclaration de naissance envoyée', 'La formation sanitaire envoie une déclaration de naissance au centre d\'état civil', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0002', 'Déclaration de décès envoyée', 'La formation sanitaire envoie une déclaration de décès au centre d\'état civil', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0003', 'Déclaration reçue et traitée par le centre d\'état civil', 'Le centre d\'état civil reçoit et traite le dossier envoyé par la formation sanitaire', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0004', 'Dossier renvoyé', 'Une institution peut renvoyer le dossier après traitement', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0005', 'Acte généré et envoyé à la signature', 'Après génération, l\'acte est envoyé à la signature de l\'officier d\'état civil', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0006', 'Certificat envoyé au tribunal', 'Le centre d\'état civil envoie le certificat de non inscription au tribunal', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0009', 'Réquisition envoyée au centre d\'état civil', 'Le tribunal envoie une réquisition au centre d\'état civil pour transcription', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0010', 'Jugement envoyé au centre d\'état civil', 'Le tribunal envoie un jugement au centre d\'état civil pour transcription', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0011', 'Document transmis au centre d\'état civil', 'Le tribunal transmet le document importé au centre d\'état civil', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0012', 'Document reçu par le centre d\'état civil', 'Le centre d\'état civil reçoit le document du tribunal', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0013', 'En attente de transcription de l\'acte', 'L\'acte est en attente de transcription au centre d\'état civil.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0014', 'Acte produit et en attente d\'approbation de l\'officier d\'état civil', 'L\'acte est produit et attend l\'approbation de l\'officier d\'état civil.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0015', 'Acte produit non rétiré', 'L\'acte a été produit mais n\'a pas encore été rétiré par le demandeur.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0016', 'Acte rétiré', 'L\'acte a été rétiré par le demandeur.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0017', 'Acte annulé', 'L\'acte a été annulé.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0018', 'Dossier archivé', 'Le dossier est archivé pour la traçabilité finale', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0019', 'Dossier confirmé par le centre d\'état civil', 'Le centre d\'état civil confirme la conformité du dossier et le prépare pour la génération de l\'acte.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0020', 'Dossier validé par l\'officier d\'état civil', 'l\'officier d\'état civil valide le dossier après vérification.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0021', 'Dossier validé', 'La mairie valide le dossier pour finaliser la procédure.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0022', 'Dossier validé par le tribunal', 'Le tribunal valide le dossier dans le cadre d\'une procédure judiciaire.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0023', 'Acte rectifié', 'L\'acte a fait l\'objet d\'une rectification officielle.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0024', 'Déclaration de naissance enregistrée', 'La formation sanitaire enregistre une déclaration de naissance', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0025', 'Fiche de maternité enregistrée', 'La fiche de maternité est enregistrée dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0026', 'Certificat de non inscription enregistré', 'Le certificat de non inscription est enregistré dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0027', 'Certificat de destruction enregistré', 'Le certificat de destruction est enregistré dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0028', 'Jugement d\'homologation enregistré', 'Le jugement d\'homologation est enregistré dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0029', 'Jugement d\'adoption enregistré', 'Le jugement d\'adoption est enregistré dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0030', 'Jugement supplétif enregistré', 'Le jugement supplétif est enregistré dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0031', 'Fiche de transcription enregistrée', 'La fiche de transcription est enregistrée dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0032', 'Déclaration de décès enregistrée', 'La formation sanitaire enregistre une déclaration de décès', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0109', 'Décès confirmé par le tribunal', 'Le tribunal confirme la déclaration de décès.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0113', 'Acte de décès signé et non rétiré', 'L\'acte de décès a été signé mais n\'a pas encore été rétiré par le demandeur.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0114', 'Acte de décès rétiré', 'L\'acte de décès a été rétiré par le demandeur.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_0212', 'Acte de mariage signé et rétiré', 'L\'acte de mariage a été signé et rétiré par le demandeur.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_1001', 'Réquisition importée par le tribunal', 'Le tribunal a importé une réquisition pour la déclaration concernée.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_1002', 'Jugement importé par le tribunal', 'Le tribunal a importé un jugement pour la déclaration concernée.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_1019', 'Dossier confirmé par le tribunal', 'Le tribunal confirme la conformité du dossier et le prépare pour la suite du traitement.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2001', 'Fiche de rectification envoyée au tribunal', 'Une fiche de rectification a été transmise au tribunal pour traitement.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2002', 'Fiche de rectification validée par le tribunal', 'Le tribunal a validé la fiche de rectification.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2003', 'Fiche de rectification rejetée par le tribunal', 'Le tribunal a rejeté la fiche de rectification.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2004', 'Fiche de rectification créée', 'Une nouvelle fiche de rectification a été créée dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2005', 'Certificat de constatation de décès enregistré', 'Le certificat de constatation de décès est enregistré dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2006', 'Certificat de constatation de décès envoyé', 'Le certificat de constatation de décès est envoyé au centre d\'état civil.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2007', 'Formulaire enregistré', 'La demande d\'enregistrement de mariage effectuée.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2008', 'Formulaire type envoyé au tribunal', 'la demande de dispense est envoyée au tribunal.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2009', 'Publication de ban de mariage effectuée', 'La publication des bans de mariage a été effectuée conformément à la procédure légale.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2010', 'Célébration de mariage effectuée', 'La cérémonie de mariage a été célébrée officiellement par l\'officier d\'état civil.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34'),
('MOUV_2011', 'Certificat de transcription enregistré', 'Le certificat de transcription est enregistré dans le système.', NULL, '2025-09-08 05:43:34', '2025-09-08 05:43:34');

-- --------------------------------------------------------

--
-- Table structure for table `tr_nationalite`
--

CREATE TABLE `tr_nationalite` (
  `code_nationalite` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_nationalite` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_nationalite`
--

INSERT INTO `tr_nationalite` (`code_nationalite`, `lib_nationalite`, `created_at`, `updated_at`, `deleted_at`) VALUES
('NAT_0001', 'CONGOLAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0002', 'GABONAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0003', 'CAMEROUNAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0004', 'CENTRAFRICAIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0005', 'TCHADIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0006', 'EQUATO-GUINEEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0007', 'SAO-TOME-ET-PRINCIPIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0008', 'SENEGALAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0009', 'IVOIRIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0010', 'MALIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0011', 'BURKINABE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0012', 'NIGERIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0013', 'BENINOIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0014', 'TOGOLAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0015', 'GUINEEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0016', 'SIERRA-LEONAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0017', 'LIBERIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0018', 'GHANEEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0019', 'GAMBIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0020', 'GUINEEN(NE) - BISSAU', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0021', 'CAP-VERDIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0022', 'MAURITANIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0023', 'NIGERIAN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0024', 'ETHIOPIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0025', 'KENYAN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0026', 'TANZANIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0027', 'OUGANDAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0028', 'RWANDAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0029', 'BURUNDAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0030', 'SOUDANAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0031', 'SOUDANAIS(E) - DU SUD', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0032', 'DJIBOUTIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0033', 'ERYTHREEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0034', 'SOMALIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0035', 'ALGERIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0036', 'MAROCAIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0037', 'TUNISIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0038', 'LYBIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0039', 'EGYPTIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0040', 'SUD-AFRICAIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0041', 'ANGOLAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0042', 'MOZAMBICAIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0043', 'ZAMBIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0044', 'ZIMBABWEEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0045', 'BOTSWANAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0046', 'NAMIBIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0047', 'MALAWIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0048', 'LESOTHAN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0049', 'SWAZILAND(IS)(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0050', 'MALGACHE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0051', 'CONGOLAIS(E) - RDC', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0052', 'COMORIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0053', 'MAURICIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0054', 'SEYCHELLOIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0055', 'FRANCAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0056', 'BELGE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0057', 'SUISSE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0058', 'ALLEMAND(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0059', 'ESPAGNOL(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0060', 'ITALIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0061', 'PORTUGAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0062', 'BRITANNIQUE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0063', 'IRLANDAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0064', 'NEERLANDAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0065', 'LUXEMBOURGEOIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0066', 'AUTRICHIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0067', 'POLONAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0068', 'ROUMAIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0069', 'GREC(QUE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0070', 'RUSSE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0071', 'UKRAINIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0072', 'SERBE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0073', 'CROATE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0074', 'TURC(QUE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0075', 'AMERICAIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0076', 'CANADIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0077', 'BRESILIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0078', 'ARGENTIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0079', 'MEXICAIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0080', 'COLOMBIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0081', 'CHILIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0082', 'PERUVIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0083', 'VENEZUELIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0084', 'CUBAIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0085', 'HAITIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0086', 'CHINOIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0087', 'INDIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0088', 'JAPONAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0089', 'COREN(NE) - DU SUD', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0090', 'VIETNAMIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0091', 'THAILANDAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0092', 'INDOENESIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0093', 'MALAYSIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0094', 'PHILIPPIN(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0095', 'PAKISTANAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0096', 'BANGLADAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0097', 'SRI-LANKAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0098', 'IRANIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0099', 'IRAKIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0100', 'SAOUDIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0101', 'EMIRATI(E) - ARABES UNIS', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0102', 'LIBANAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0103', 'SYRIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0104', 'JORDANIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0105', 'ISRAELIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0106', 'PALESTINIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0107', 'AUSTRALIEN(NE)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0108', 'NEO-ZELANDAIS(E)', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0109', 'APATRIDE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL),
('NAT_0110', 'NON DECLARE', '2026-01-15 15:58:38', '2026-01-15 15:58:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_option_mariage`
--

CREATE TABLE `tr_option_mariage` (
  `code_option_mariage` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_option_mariage` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_option_mariage`
--

INSERT INTO `tr_option_mariage` (`code_option_mariage`, `lib_option_mariage`, `created_at`, `updated_at`, `deleted_at`) VALUES
('OPM_0001', 'Monogamie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('OPM_0002', 'Polygamie', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_pompes_funebres`
--

CREATE TABLE `tr_pompes_funebres` (
  `code_pompes_funebres` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_pompes_funebres` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_profession`
--

CREATE TABLE `tr_profession` (
  `code_profession` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_profession`
--

INSERT INTO `tr_profession` (`code_profession`, `lib_profession`, `created_at`, `updated_at`, `deleted_at`) VALUES
('PROF_0001', 'Ingénieur pétrolier', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0002', 'Technicien de forage', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0003', 'Logisticien industriel', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0004', 'Opérateur de production pétrolière', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0005', 'Architecte', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0006', 'Ingénieur en génie civil', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0007', 'Entrepreneur en construction', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0008', 'Maçon', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0009', 'Menuisier', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0010', 'Plombier', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0011', 'Électricien', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0012', 'Peintre en bâtiment', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0013', 'Médecin', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0014', 'Médecin spécialiste', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0015', 'Infirmier(ère)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0016', 'Pharmacien(ne)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0017', 'Sage-femme', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0018', 'Technicien de laboratoire médical', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0019', 'Aide-soignant(e)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0020', 'Informaticien(ne)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0021', 'Développeur logiciel', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0022', 'Technicien en maintenance informatique', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0023', 'Webmaster', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0024', 'Commerçant(e)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0025', 'Commerçant ambulant', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0026', 'Vendeur(euse)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0027', 'Négociant(e)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0028', 'Importateur/Exportateur', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0029', 'Juge', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0030', 'Avocat(e)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0031', 'Greffier(ère)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0032', 'Huissier de justice', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0033', 'Enseignant(e)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0034', 'Professeur', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0035', 'Directeur d\'école', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0036', 'Formateur(trice)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0037', 'Fonctionnaire', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0038', 'Administrateur civil', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0039', 'Agent administratif', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0040', 'Secrétaire', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0041', 'Chauffeur', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0042', 'Mécanicien(ne)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0043', 'Transporteur', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0044', 'Agent de fret', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0045', 'Comptable', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0046', 'Banquier(ère)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0047', 'Agent d\'assurance', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0048', 'Caissier(ère)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0049', 'Journaliste', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0050', 'Animateur radio/télévision', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0051', 'Photographe', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0052', 'Cameraman', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0053', 'Agriculteur(trice)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0054', 'Éleveur(euse)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0055', 'Pêcheur(euse)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0056', 'Transformateur de produits agricoles', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0057', 'Artisan', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0058', 'Sculpteur(euse)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0059', 'Musicien(ne)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0060', 'Artiste plasticien(ne)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0061', 'Tailleur(euse)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0062', 'Cordonnier(ère)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0063', 'Chef cuisinier', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0064', 'Serveur(euse)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0065', 'Gestionnaire d\'hôtel', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0066', 'Guide touristique', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0067', 'Policier(ère)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0068', 'Militaire', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0069', 'Agent de sécurité', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0070', 'Garde du corps', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0071', 'Travailleur social', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0072', 'Psychologue', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0073', 'Éducateur spécialisé', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0074', 'Coiffeur(euse)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0075', 'Esthéticien(ne)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0076', 'Couturier(ère)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0077', 'Réparateur de téléphones', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0078', 'Réparateur d\'électroménager', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0079', 'Consultant(e)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0080', 'Expert-comptable', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0081', 'Entraîneur sportif', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0082', 'Animateur socioculturel', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0083', 'Étudiant(e)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0084', 'Sans emploi', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0085', 'Retraité(e)', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL),
('PROF_0086', 'Non déclaré', '2026-01-15 15:16:49', '2026-01-15 15:16:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_regime`
--

CREATE TABLE `tr_regime` (
  `code_regime` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_regime` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_regime`
--

INSERT INTO `tr_regime` (`code_regime`, `lib_regime`, `supprimer`, `created_at`, `updated_at`) VALUES
('RGIM_0001', 'Régime de la communauté réduite aux acquêts(RCA)', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('RGIM_0002', 'Régime de la séparation de biens(RSB)', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('RGIM_0003', 'Régime de la communauté conventionnelle(RCC)', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39');

-- --------------------------------------------------------

--
-- Table structure for table `tr_registre`
--

CREATE TABLE `tr_registre` (
  `code_registre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_registre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_ouverture` date DEFAULT NULL,
  `date_fermeture` date DEFAULT NULL,
  `nombre_acte_prevu` int NOT NULL DEFAULT '50',
  `nombre_acte_transcrit` smallint NOT NULL DEFAULT '0',
  `code_type_registre` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '1',
  `sceau` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_paraphage` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identifiant_registre` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approbation_tribunal` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_tribunal` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cloture_cec` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_cloture_cec` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_registre`
--

INSERT INTO `tr_registre` (`code_registre`, `lib_registre`, `date_ouverture`, `date_fermeture`, `nombre_acte_prevu`, `nombre_acte_transcrit`, `code_type_registre`, `cui`, `statut`, `sceau`, `otp_paraphage`, `identifiant_registre`, `approbation_tribunal`, `signature_tribunal`, `cloture_cec`, `signature_cloture_cec`, `created_at`, `updated_at`, `deleted_at`) VALUES
('REG_01', 'REGISTRE DE NAISSANCE', '2026-01-07', '2026-12-31', 20, 1, 'TPRG_0001', 'CUI_00000004', 1, 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', '68221423', 'R.A.N_INS_004707012026150656', 'CUI_00000008', 'signature/T8IjvjRHWOwD61v3gAwm6obMJq8bP3tDSszMjvzG.png', NULL, NULL, '2026-01-07 14:06:56', '2026-01-15 18:06:20', NULL),
('REG_02', 'REGISTRE DE DECES', '2026-01-16', '2026-12-31', 30, 1, 'TPRG_0004', 'CUI_00000013', 1, 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', '68543467', 'R.A.D_INS_019216012026060159', 'CUI_00000012', 'signature/jnGFEgP69LBDO7Sbm0OHO8VnSab2IsMR2YTbqNvo.png', NULL, NULL, '2026-01-16 05:01:59', '2026-01-16 05:05:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_religion`
--

CREATE TABLE `tr_religion` (
  `code_religion` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_religion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_religion`
--

INSERT INTO `tr_religion` (`code_religion`, `lib_religion`, `created_at`, `updated_at`, `deleted_at`) VALUES
('RELI_0001', 'CHRISTIANISME', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('RELI_0002', 'ISLAMIQUE', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('RELI_0003', 'AUTRE', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_rubrique`
--

CREATE TABLE `tr_rubrique` (
  `code_rubrique` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_rubrique` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'eple:nom,prenom,sexe,date de naissance,nationalite,etc',
  `entite_rubrique` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Exple: enfant,père,mère,époux,épouse,defunt,etc',
  `code_type_acte` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_situation_matrimoniale`
--

CREATE TABLE `tr_situation_matrimoniale` (
  `code_situation_matrimoniale` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_situation_matrimoniale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_situation_matrimoniale`
--

INSERT INTO `tr_situation_matrimoniale` (`code_situation_matrimoniale`, `lib_situation_matrimoniale`, `created_at`, `updated_at`, `deleted_at`) VALUES
('SMAT_0001', 'Mariage état civil', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('SMAT_0002', 'Pré mariage', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('SMAT_0003', 'Célibataire', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('SMAT_0004', 'Union libre', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('SMAT_0005', 'Divorcé(e)', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('SMAT_0006', 'Veuf(ve)', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_sms_providers`
--

CREATE TABLE `tr_sms_providers` (
  `code_providers` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_provider` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_type` enum('JSON','XML','TEXT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_tribunal`
--

CREATE TABLE `tr_tribunal` (
  `code_tribunal` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_tribunal` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_cour_appel` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '1',
  `sceau` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_acte`
--

CREATE TABLE `tr_type_acte` (
  `code_type_acte` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_acte` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_categorie_ins`
--

CREATE TABLE `tr_type_categorie_ins` (
  `code_type_categorie_ins` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_categorie_institution` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_illustrative` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_type_categorie_ins`
--

INSERT INTO `tr_type_categorie_ins` (`code_type_categorie_ins`, `lib_type_categorie_institution`, `image_illustrative`, `created_at`, `updated_at`, `deleted_at`) VALUES
('TCINS_0001', 'centre d\'état civil', 'img-bkg-accueil/cec.jpg', NULL, NULL, NULL),
('TCINS_0002', 'Tribunal', 'img-bkg-accueil/tribunal.jpg', NULL, NULL, NULL),
('TCINS_0003', 'Formation sanitaire', 'img-bkg-accueil/fs.jpg', NULL, NULL, NULL),
('TCINS_0004', 'Ambassade', 'img-bkg-accueil/cec.jpg', '2024-10-30 19:52:27', '2024-10-30 19:52:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_cec`
--

CREATE TABLE `tr_type_cec` (
  `code_type_cec` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_cec` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_document`
--

CREATE TABLE `tr_type_document` (
  `code_type_document` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_document` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_type_document`
--

INSERT INTO `tr_type_document` (`code_type_document`, `lib_type_document`, `supprimer`, `created_at`, `updated_at`) VALUES
('TDOC_0001', 'Carte nationale d\'identité', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('TDOC_0002', 'Passport', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('TDOC_0003', 'Permis de conduire', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('TDOC_0004', 'Carte d\'étudiant', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('TDOC_0005', 'Carte scolaire', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39'),
('TDOC_0006', 'Carte consulaire', 0, '2026-01-05 13:38:39', '2026-01-05 13:38:39');

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_document_demande`
--

CREATE TABLE `tr_type_document_demande` (
  `code_type_document_demande` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_document_demande` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_extrait`
--

CREATE TABLE `tr_type_extrait` (
  `code_type_extrait` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_extrait` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_type_extrait`
--

INSERT INTO `tr_type_extrait` (`code_type_extrait`, `lib_type_extrait`, `created_at`, `updated_at`, `deleted_at`) VALUES
('TEX_0001', 'Extrait d\'acte de naissance', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('TEX_0002', 'Extrait d\'acte de mariage', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL),
('TEX_0003', 'Extrait d\'acte de décès', '2026-01-05 13:38:40', '2026-01-05 13:38:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_institution`
--

CREATE TABLE `tr_type_institution` (
  `code_type_institution` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_institution` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_type_categorie_ins` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_type_institution`
--

INSERT INTO `tr_type_institution` (`code_type_institution`, `lib_type_institution`, `code_type_categorie_ins`, `created_at`, `updated_at`, `deleted_at`) VALUES
('TPINS_0001', 'Tribunal de grande instance', 'TCINS_0002', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0002', 'Mairie', 'TCINS_0001', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0003', 'Pompes funèbres', 'TCINS_0001', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0005', 'Ambassade', 'TCINS_0001', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0006', 'Cour d\'appel', 'TCINS_0002', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0008', 'Tribunal d\'instance', 'TCINS_0002', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0009', 'Clinique', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0010', 'Centre Hospitalier Universitaire', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0011', 'Hôpital spécialisé', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0012', 'Hôpital général', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0013', 'Polyclinique', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0014', 'Centre Médical Spécialisé', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0015', 'Hôpital de base', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0016', 'Centre de santé intégré', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0017', 'Hôpital de référence', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0018', 'Hopital Militaire', 'TCINS_0003', '2023-04-23 08:21:56', '2023-04-23 08:21:56', NULL),
('TPINS_0019', 'Centre D\"hygiène', 'TCINS_0001', '2023-04-23 07:21:56', '2023-04-23 07:21:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_jugement`
--

CREATE TABLE `tr_type_jugement` (
  `code_type_jugement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_jugement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_localite`
--

CREATE TABLE `tr_type_localite` (
  `code_type_localite` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_localite` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_cec` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Codification officiel du centre d''état civil',
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_type_localite`
--

INSERT INTO `tr_type_localite` (`code_type_localite`, `lib_type_localite`, `type_cec`, `supprimer`, `created_at`, `updated_at`) VALUES
('TPLOC_0001', 'DEPARTEMENT', NULL, 0, '2023-04-23 11:21:56', '2023-08-15 19:00:00'),
('TPLOC_0002', 'DISTRICT', 'DST', 0, '2023-04-23 11:21:56', '2023-08-15 19:00:00'),
('TPLOC_0003', 'COMMUNE', 'COM', 0, '2023-04-23 11:21:56', '2023-08-15 19:00:00'),
('TPLOC_0004', 'ARRONDISSEMENT', 'ARR', 0, '2023-04-23 11:21:56', '2023-08-15 19:00:00'),
('TPLOC_0005', 'COMMUNAUTE URBAINE', 'COU', 0, '2023-04-23 11:21:56', '2023-08-15 19:00:00'),
('TPLOC_0006', 'COMMUNAUTE RURALE', 'COR', 0, '2023-04-23 11:21:56', '2023-08-15 19:00:00'),
('TPLOC_0007', 'QUARTIER', NULL, 0, '2023-04-23 11:21:56', '2023-08-15 19:00:00'),
('TPLOC_0008', 'VILLAGE', NULL, 0, '2023-04-23 11:21:56', '2023-08-15 19:00:00'),
('TPLOC_0009', 'NON DECLARE', NULL, 0, '2024-02-20 08:54:27', '2023-08-15 19:00:00'),
('TPLOC_0010', 'Alange', NULL, 1, '2026-01-13 13:29:45', '2026-01-14 14:52:38');

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_registre`
--

CREATE TABLE `tr_type_registre` (
  `code_type_registre` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_registre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_type_registre`
--

INSERT INTO `tr_type_registre` (`code_type_registre`, `lib_type_registre`, `supprimer`, `created_at`, `updated_at`) VALUES
('TPRG_0001', 'NAISSANCE', 0, '2026-01-05 13:38:40', '2026-01-05 13:38:40'),
('TPRG_0002', 'MARIAGE', 0, '2026-01-05 13:38:40', '2026-01-05 13:38:40'),
('TPRG_0003', 'DIVORCE', 0, '2026-01-05 13:38:40', '2026-01-05 13:38:40'),
('TPRG_0004', 'DECES', 0, '2026-01-05 13:38:40', '2026-01-05 13:38:40');

-- --------------------------------------------------------

--
-- Table structure for table `tr_type_requisition`
--

CREATE TABLE `tr_type_requisition` (
  `code_type_requisition` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_type_requisition` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_type_requisition`
--

INSERT INTO `tr_type_requisition` (`code_type_requisition`, `lib_type_requisition`, `deleted_at`, `created_at`, `updated_at`) VALUES
('TPRG_0001', 'requisition aux fins d\'inscription à la déclaration tardive', NULL, '2025-09-10 19:34:26', '2025-09-10 19:34:26'),
('TPRG_0002', 'requisition aux fins de reconstitution de l\'acte', NULL, '2025-09-10 19:34:26', '2025-09-10 19:34:26'),
('TPRG_0003', 'requisition aux fins de transcription de l\'acte', NULL, '2025-09-10 19:34:26', '2025-09-10 19:34:26'),
('TPRG_0004', 'requisition aux fins de rectification de l\'acte', NULL, '2025-09-10 19:34:26', '2025-09-10 19:34:26'),
('TPRG_0005', 'dispense aux fins de lieu de célébration du mariage', NULL, '2025-09-10 19:34:26', '2025-09-10 19:34:26'),
('TPRG_0006', 'dispense aux fins de délai de célébration du mariage', NULL, '2025-09-10 19:34:26', '2025-09-10 19:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `tr_uf`
--

CREATE TABLE `tr_uf` (
  `code_user` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_fonctionnalite` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_user`
--

CREATE TABLE `tr_user` (
  `code_user` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `pseudo_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google2fa_secret` text COLLATE utf8mb4_unicode_ci COMMENT 'Secret chiffré pour l''authentification 2FA',
  `google2fa_enabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indique si la 2FA est activée pour cet utilisateur',
  `recovery_codes` text COLLATE utf8mb4_unicode_ci COMMENT 'Codes de récupération chiffrés (8 codes)',
  `two_factor_verified_at` timestamp NULL DEFAULT NULL COMMENT 'Date et heure de la dernière vérification 2FA réussie',
  `two_factor_method` enum('totp','sms','email') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'totp' COMMENT 'Méthode de 2FA utilisée (TOTP par défaut)',
  `two_factor_required` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si true, l''utilisateur doit activer la 2FA',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `code_personne` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_user`
--

INSERT INTO `tr_user` (`code_user`, `pseudo`, `email`, `email_verified_at`, `pseudo_verified_at`, `password`, `google2fa_secret`, `google2fa_enabled`, `recovery_codes`, `two_factor_verified_at`, `two_factor_method`, `two_factor_required`, `remember_token`, `status`, `code_personne`, `created_at`, `updated_at`, `deleted_at`) VALUES
('USR_00000001', '066835335', 'acsi@gmail.com', NULL, NULL, '$2y$10$fZ6Oa.9hDPEu4aXsiUL59eBCA.wsvrcUJw6HgqHOUz5.wH6EngBsO', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000001', '2023-04-23 12:21:56', '2023-04-23 12:21:56', NULL),
('USR_00000003', '068554413', 'stephanie@gmail.com', NULL, NULL, '$2y$10$gxXDvZ6FRV54/MwFEvP47OdPWAiYteOk2PbLUGX8mh83duzWxClxa', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000003', '2022-11-27 12:21:50', '2025-10-16 21:36:44', NULL),
('USR_00000005', '058856935', 'sandrine@gmail.com', NULL, NULL, '$2y$10$75v7FL3k.RDOfhkLMAb/1.9ARW1akjePH6Yh0XkAii3GEYKonrEoK', 'eyJpdiI6ImRaSEUzYVRLRStmc25FN1dmVXNRdmc9PSIsInZhbHVlIjoiNWQ0d2haSE5kd1NsaFFwamdhRG8xeEpyZWJFZHc2QmppQ2ozMENpcGNTdz0iLCJtYWMiOiJhYWU5YzRhMDRmZWNkNTY3NDM3NDM3OTliNDVlZDM2YmY4YmI1ZWZkYjc0Yjg1NjAxZWVkZDA4YjIyNjZhZTY4IiwidGFnIjoiIn0=', 1, 'eyJpdiI6ImE4Y3FHd0R5UERleXJ5dWpKYWMvMlE9PSIsInZhbHVlIjoiK05HUTRob0Z0U3V3RlpmQUY0dzBjYitrdHN0dGMxblR6OWFZYmJ6V2pEbDF1UmVISzJXV21tdFJiQTlNZENFK29sMHJIM0lJRTFQbVZxT0lHT1NwdTlxdGl4OGJob3R6d2JXSkpxa2Z0VGs2TDNxNSsvNG82emFtZXg5bVl4K3dEWVIzY1RiZGxhTlJUbGFoOEFTL3hnPT0iLCJtYWMiOiI5YTM1ZjkwN2E0MGJlZGEwN2IxZGM2ZDQwZGM5NzYzYWEyM2MwYzE3OTQ1NWJjMjZkNzM1MDdlZTIzMmFjYzNhIiwidGFnIjoiIn0=', '2026-01-15 15:41:54', 'totp', 0, NULL, 1, 'PRS_00000005', '2022-11-27 12:39:57', '2026-01-15 15:41:54', NULL),
('USR_00000006', '066835333', 'preztribuna@gmail.com', NULL, NULL, '$2y$10$iVLUfF9BrAt2z5E2Qqu/Pu3fFI0HSaAbsAWPUwkD44B9Jp231ax8q', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000006', '2022-11-30 03:26:48', '2025-04-30 08:38:56', NULL),
('USR_00000009', '044164355', 'mairemakelekele@gmail.com', NULL, NULL, '$2y$10$NLZ2UGhphyYJXKtD4bcNPOkqNPFgz.Av2SKGp9QoVv4pMuQhmklRS', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000009', '2024-06-23 08:16:46', '2024-06-23 08:16:46', NULL),
('USR_00000010', '066835350', 'tgibrazzaville@gmail.com', NULL, NULL, '$2y$10$2lM/s5bfumPZPOPBMZMZdO1VReST3I08JiriXOjGKXMyW6lXdJTbW', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000019', '2024-06-23 13:18:41', '2025-04-15 22:54:50', NULL),
('USR_00000011', '044152698', 'agentpfbz@gmail.com', NULL, NULL, '$2y$10$NGrFe4m2GtECDbCaYsfc7eqfBr4UY0AYxGKkVvxeT5w3T1x9l/0my', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000020', '2024-06-23 13:20:44', '2024-06-23 13:20:44', NULL),
('USR_00000013', '044155339', 'agentmairiecentralebz@gmail.com', NULL, NULL, '$2y$10$SfoJR10gbkQPGuj2Z3c0OumcMUW/h3Em66qrB9wMXcGjbVlrn0jza', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000021', '2024-06-23 13:47:05', '2025-03-25 21:12:48', NULL),
('USR_00000014', '044164350', 'procureurmakelekele@gmail.com', NULL, NULL, '$2y$10$qbaMIUs0Xe24e9.YZgjQZ.IZJmg1nfKzCvBvbrd3pNF4gsUexzFUW', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000022', '2024-06-23 14:14:48', '2024-11-19 19:55:53', NULL),
('USR_00000019', '060000000', 'agentformationsanitaire@gmail.com', NULL, NULL, '$2y$10$tvC6FiWK0VxlSS1NnZehuOCupU6oq3aMW7X5U5gSLbPqHdrli2Mj6', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000087', '2024-10-21 09:40:36', '2025-04-10 19:06:14', NULL),
('USR_00000021', '044000000', 'centrehygiene@gmail.com', NULL, NULL, '$2y$10$hBFKn8bnzwTY3YDAaDRyJeqJJoLyLFLUa25aip7SBSCZ4fphRum6S', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000089', '2024-10-21 09:49:38', '2025-03-25 21:44:20', NULL),
('USR_00000029', '044164337', 'directeurpf@gmail.com', NULL, NULL, '$2y$10$1GOG9FypsZdnG8Ri9KA3S.i8v7v0WPeF//1nrO6DLhw6pVdUT3gqG', NULL, 0, NULL, NULL, 'totp', 0, NULL, 1, 'PRS_00000334', '2025-07-01 12:20:18', '2025-07-01 12:20:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_user_audit_trail`
--

CREATE TABLE `tr_user_audit_trail` (
  `id` bigint UNSIGNED NOT NULL,
  `code_user` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tr_user_audit_trail`
--

INSERT INTO `tr_user_audit_trail` (`id`, `code_user`, `action`, `description`, `old_values`, `new_values`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(1, 'USR_00000003', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'uGh7a2Mofs2paRulXX0bWdJGT3bUbefZyFP7I4cd', '2026-01-06 07:32:12'),
(2, 'USR_00000003', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'zhqDr38RALbFjOMEZJZcmERbYzO62amuzxdX70TX', '2026-01-07 13:34:34'),
(3, 'USR_00000006', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '5g1wqW0zmDC4FfOqeO6IK0p03SYxTaO3SWvydTSv', '2026-01-07 14:07:46'),
(4, 'USR_00000005', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'aumlXnIWlvCGkbhD4BBIF7pezFCesILqoBc2eObS', '2026-01-12 11:20:28'),
(5, 'USR_00000003', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'PfWepvtEiM01ckhvpkDzJR1jzO0fmGZvHBtDaEvO', '2026-01-12 11:20:57'),
(6, 'USR_00000005', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'bfCjVkpK0LQxqC85tlAfrZJH1SQiPbp8lvo97AvJ', '2026-01-12 11:21:25'),
(7, 'USR_00000005', '2fa_enabled', 'Double authentification activée', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'bfCjVkpK0LQxqC85tlAfrZJH1SQiPbp8lvo97AvJ', '2026-01-12 11:23:29'),
(8, 'USR_00000003', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'wASX4gbKlvNiZmXnBSRuHHrjaRIZgm9gQtJH19Dh', '2026-01-12 11:29:42'),
(9, 'USR_00000006', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'eOF7j9vGH5NE8hE4ccdQ0axomrcdW3g4ymysbE1w', '2026-01-12 11:36:21'),
(10, 'USR_00000001', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YDhqa60gP0ZSJkgiefz5smucYH7zFBG7TTmObjY6', '2026-01-12 14:22:51'),
(11, 'USR_00000001', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'FHvYhQLfgy230LcqJalNN02UNiQikhIY6i3cQ6SO', '2026-01-13 10:02:34'),
(12, 'USR_00000001', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'CQDP7plVOjtjw1t7CXSl4kGBDvhznPkJiDYsBw4O', '2026-01-13 12:10:41'),
(13, 'USR_00000001', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'Nuxh5KEg4LaDtNpulxPMF5MCZ7LjKHhDpEBmTL0n', '2026-01-14 14:25:52'),
(14, 'USR_00000001', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'b3tzxD3UjFnZ09ZlneQhzwwznlP95kSsB064leya', '2026-01-15 07:09:45'),
(15, 'USR_00000001', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '20Vy7ixuISoRJnTFoGStjRyuXzYVP4hWBj4BRTSe', '2026-01-15 13:51:06'),
(16, 'USR_00000003', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'bwzyzpwJt4qXF7KkJicSnLPyC7j3AjlNjtbLNXm7', '2026-01-15 16:18:05'),
(17, 'USR_00000011', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'HKmMJ4hu86B8YTQPlHJkFVCGYr2PleTT3Kh78zO9', '2026-01-15 18:28:19'),
(18, 'USR_00000011', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'a4TU81cijIZSQpV2mV0NMA6P9JCtVPxG28Elr8sq', '2026-01-15 19:12:43'),
(19, 'USR_00000011', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'ofxAOazOdxlmdbRyFbumEna73vcNiUSGZ3XPwGPa', '2026-01-16 04:57:24'),
(20, 'USR_00000010', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'y4UmSNfknyB1YM2xSCapfzskIGn6Ok7eILgvpjQw', '2026-01-16 05:03:21'),
(21, 'USR_00000029', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'fkzN8hs5VFeuNB5pRhGNmHpccz2Io0sbltfswcsp', '2026-01-16 05:11:28'),
(22, 'USR_00000011', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'MPqFvoe77KhzwuYWQVHTfmDOzwTgxkJLRWRFO8nY', '2026-01-16 20:24:09'),
(23, 'USR_00000003', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'sw0QKbQAhWs0hlz1jclvoIwmS0uxbCLsMixFJOmW', '2026-01-16 20:41:12'),
(24, 'USR_00000029', 'login', 'Connexion réussie', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'vKR5cbcIVMkGt5skgk6xb8uZXfedIR9ATMymY3nJ', '2026-01-17 01:23:26');

-- --------------------------------------------------------

--
-- Table structure for table `t_acte_deces`
--

CREATE TABLE `t_acte_deces` (
  `code_acte_deces` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_emission` datetime DEFAULT NULL,
  `code_registre` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_declaration_deces` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_heure_approbation_pompe_funebre` datetime DEFAULT NULL,
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code de l''institution qui a généré l''acte',
  `retirer` tinyint(1) NOT NULL DEFAULT '0',
  `approbation_tribunal` tinyint(1) NOT NULL DEFAULT '0',
  `approbation_pompe_funebre` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_approbation_pompe_funebre` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_pompe_funebre` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sceau_tribunal` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'permet de savoir si acte est annule ou pas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_acte_deces`
--

INSERT INTO `t_acte_deces` (`code_acte_deces`, `date_emission`, `code_registre`, `code_declaration_deces`, `cui`, `date_heure_approbation_pompe_funebre`, `code_institution`, `retirer`, `approbation_tribunal`, `approbation_pompe_funebre`, `otp_approbation_pompe_funebre`, `signature_pompe_funebre`, `sceau_tribunal`, `statut`, `created_at`, `updated_at`, `deleted_at`) VALUES
('AD_00000001', '2026-01-16 06:05:49', 'REG_02', 'CDD_00000001', 'CUI_00000013', '2026-01-16 06:13:51', 'INS_0192', 0, 1, 'CUI_00000031', '68543997', 'signature/jnGFEgP69LBDO7Sbm0OHO8VnSab2IsMR2YTbqNvo.png', 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', 0, '2026-01-16 05:05:49', '2026-01-16 05:13:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_acte_mariage`
--

CREATE TABLE `t_acte_mariage` (
  `code_acte_mariage` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_emission` datetime DEFAULT NULL,
  `code_registre` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_declaration_mariage` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'institution à qui appartient cette declaration',
  `approbation_tribunal` tinyint(1) NOT NULL DEFAULT '0',
  `approbation_mairie` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_heure_approbation_mairie` timestamp NULL DEFAULT NULL,
  `otp_approbation_mairie` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_maire` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sceau_tribunal` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `retirer` tinyint(1) NOT NULL DEFAULT '0',
  `statut` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'permet de savoir si acte est annule ou pas',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_acte_naissance`
--

CREATE TABLE `t_acte_naissance` (
  `niupp` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_emission` datetime DEFAULT NULL,
  `code_declaration_naissance` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_registre` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code de l''institution qui a généré l''acte',
  `approbation_tribunal` tinyint(1) NOT NULL DEFAULT '0',
  `approbation_mairie` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_mairie` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sceau_tribunal` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_heure_approbation_mairie` timestamp NULL DEFAULT NULL,
  `otp_approbation_mairie` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `retirer` tinyint(1) NOT NULL DEFAULT '0',
  `statut` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'permet de savoir si acte est annule ou pas',
  `motif_annulation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_acte_naissance`
--

INSERT INTO `t_acte_naissance` (`niupp`, `date_emission`, `code_declaration_naissance`, `code_registre`, `cui`, `code_institution`, `approbation_tribunal`, `approbation_mairie`, `signature_mairie`, `sceau_tribunal`, `date_heure_approbation_mairie`, `otp_approbation_mairie`, `retirer`, `statut`, `motif_annulation`, `created_at`, `updated_at`, `deleted_at`) VALUES
('2202601BZVBVCOM0001', '2026-01-15 19:06:20', 'CDN_00000001', 'REG_01', 'CUI_00000004', 'INS_0047', 1, NULL, NULL, 'sceau/hdWslfnMMHwTvbDz2o0rtI3rc1Le6Qw1bo4p8XCc.png', NULL, NULL, 0, 0, NULL, '2026-01-15 18:06:20', '2026-01-15 18:06:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_action`
--

CREATE TABLE `t_action` (
  `code_action` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `envoye_au` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_api_headers`
--

CREATE TABLE `t_api_headers` (
  `code_api_headers` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `header_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `header_value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_providers` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_api_params`
--

CREATE TABLE `t_api_params` (
  `code_api_params` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `params_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `params_value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_providers` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_contact_personne`
--

CREATE TABLE `t_contact_personne` (
  `id` bigint UNSIGNED NOT NULL,
  `indicatif` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_personnelle` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_professionnelle` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_personne` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_contact_personne`
--

INSERT INTO `t_contact_personne` (`id`, `indicatif`, `telephone`, `email_personnelle`, `email_professionnelle`, `code_personne`, `created_at`, `updated_at`, `deleted_at`) VALUES
(13, '+242', '066835332', NULL, NULL, 'PRS_00000006', '2025-04-13 11:07:04', '2025-04-13 11:07:04', NULL),
(14, '+242', '044164337', NULL, NULL, 'PRS_00000009', '2025-07-01 12:58:01', '2025-07-01 12:58:01', NULL),
(15, '+242', '066835332', NULL, NULL, 'PRS_00000019', '2025-04-13 04:33:00', '2025-04-13 04:33:00', NULL),
(16, '+242', '066835332', NULL, NULL, 'PRS_00000022', '2025-04-13 04:34:00', '2025-04-13 04:34:00', NULL),
(17, '+242', '066835332', NULL, NULL, 'PRS_00000334', '2025-07-04 13:11:32', '2025-07-04 13:11:32', NULL),
(24, '+242', '044164337', NULL, NULL, 'PRS_00000335', '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
(25, '+242', '044114452', NULL, NULL, 'PRS_00000336', '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
(26, NULL, NULL, NULL, NULL, 'PRS_00000337', '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
(27, NULL, NULL, NULL, NULL, 'PRS_00000338', '2026-01-15 18:18:04', '2026-01-15 18:18:04', NULL),
(28, NULL, '066835332', NULL, NULL, 'PRS_00000339', '2026-01-15 18:18:04', '2026-01-15 18:18:04', NULL),
(29, NULL, '044125689', NULL, NULL, 'PRS_00000340', '2026-01-15 18:18:04', '2026-01-15 18:18:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_copie`
--

CREATE TABLE `t_copie` (
  `id` bigint UNSIGNED NOT NULL,
  `numero_acte` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_nom` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_prenom` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_date_naissance` date DEFAULT NULL,
  `reference_document` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_document` date DEFAULT NULL,
  `libelle_document` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lieu_delivrance_document` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_officier` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_officier` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_ddecescause`
--

CREATE TABLE `t_ddecescause` (
  `code_declaration_deces` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_cause_deces` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_ddecescause`
--

INSERT INTO `t_ddecescause` (`code_declaration_deces`, `code_cause_deces`, `created_at`, `updated_at`) VALUES
('CDD_00000001', 'CD_0002', '2026-01-15 18:18:04', '2026-01-15 18:18:04'),
('CDD_00000001', 'CD_0003', '2026-01-15 18:18:04', '2026-01-15 18:18:04');

-- --------------------------------------------------------

--
-- Table structure for table `t_declaration_deces`
--

CREATE TABLE `t_declaration_deces` (
  `code_declaration_deces` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_heure_declaration` timestamp NULL DEFAULT NULL,
  `date_heure_deces` timestamp NULL DEFAULT NULL,
  `num_acte_naissance` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_mariage` date DEFAULT NULL,
  `cec_naissance` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_situation_matrimoniale` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domicile_defunt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cec_mariage` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_acte_mariage` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_declarant` enum('Personne morale','Personne physique') COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_regime` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_religion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_lieu_survenance` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_user_institution` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lieu_deces` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `top_requisition` tinyint(1) NOT NULL DEFAULT '0',
  `numero_req` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_certificat` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_declaration` enum('DECLARATION DE DECES','DECLARATION TARDIVE','CERTIFICAT DE CONSTATATION DE DECES','CERTIFICAT DE NON INSCRIPTION','CERTIFICAT DE DESTRUCTION DE L''ACTE','FICHE DE TRANSCRIPTION') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fonction_medecin` enum('Medécin','Infirmier(e)','Autre personne de la santé') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_medecin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_conjoint` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_filiation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_declarant` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_defunt` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_cause_deces` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_pere` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_mere` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cec_approuver` enum('OUI','NON') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON' COMMENT 'permet de savoir si la declaration est prête ou pas pour la transcription de l''acte',
  `cec_approuve_par` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tribunal_approuver` enum('NON','OUI') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON',
  `tribunal_approuve_par` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cec_approuve_le` timestamp NULL DEFAULT NULL,
  `tribunal_approuve_le` timestamp NULL DEFAULT NULL,
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'institution à qui appartient cette declaration',
  `code_institution_destinataire` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'institution destinataire de la déclaration',
  `numero_ancien_acte` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_declarant` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_defunt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_conjoint` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_pere` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_mere` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `code_jugement` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_requisition` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `declarant_approuver` enum('OUI','NON') COLLATE utf8mb4_unicode_ci DEFAULT 'NON' COMMENT 'Permet de savoir si le docuement a été lu et approuvé par le déclarant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_declaration_deces`
--

INSERT INTO `t_declaration_deces` (`code_declaration_deces`, `date_heure_declaration`, `date_heure_deces`, `num_acte_naissance`, `date_mariage`, `cec_naissance`, `code_situation_matrimoniale`, `domicile_defunt`, `cec_mariage`, `num_acte_mariage`, `type_declarant`, `code_regime`, `code_religion`, `code_lieu_survenance`, `code_document`, `code_user_institution`, `lieu_deces`, `top_requisition`, `numero_req`, `numero_certificat`, `type_declaration`, `fonction_medecin`, `nom_medecin`, `code_conjoint`, `code_filiation`, `code_declarant`, `code_defunt`, `code_cause_deces`, `code_pere`, `code_mere`, `cec_approuver`, `cec_approuve_par`, `tribunal_approuver`, `tribunal_approuve_par`, `cec_approuve_le`, `tribunal_approuve_le`, `code_institution`, `code_institution_destinataire`, `numero_ancien_acte`, `piece_declarant`, `piece_defunt`, `piece_conjoint`, `piece_pere`, `piece_mere`, `created_at`, `updated_at`, `deleted_at`, `code_jugement`, `code_requisition`, `declarant_approuver`) VALUES
('CDD_00000001', '2026-01-15 18:18:04', '2026-01-15 09:30:00', '552ZEFAEZZRFEZE', NULL, 'MAIRIE DE MWANA-NTO', 'SMAT_0004', NULL, NULL, NULL, 'Personne physique', NULL, 'RELI_0001', 'LSURV_0001', NULL, 'CUI_00000007', 'BRAZZAVILLE', 0, NULL, NULL, 'DECLARATION DE DECES', NULL, NULL, NULL, 'FIL_0001', 'PRS_00000339', 'PRS_00000338', 'CD_0002', 'PRS_00000339', 'PRS_00000340', 'OUI', 'CUI_00000013', 'NON', NULL, NULL, NULL, 'INS_0094', 'INS_0192', NULL, 'app/pieces/CDD_00000001_declarant_1768504714.png', 'app/pieces/CDD_00000001_defunt_1768504722.png', NULL, 'app/pieces/CDD_00000001_pere_1768504941.png', 'app/pieces/CDD_00000001_mere_1768504947.png', '2026-01-15 18:18:04', '2026-01-15 19:19:20', NULL, NULL, NULL, 'OUI');

-- --------------------------------------------------------

--
-- Table structure for table `t_declaration_mariage`
--

CREATE TABLE `t_declaration_mariage` (
  `code_declaration_mariage` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_declaration_mariage` date NOT NULL,
  `date_prevue_mariage` date NOT NULL,
  `lieu_ceremonie_mariage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_celebration_mariage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autorisation_ambassade_epoux` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_autorisation_ambassade_epoux` date DEFAULT NULL,
  `autorisation_ambassade_epouse` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_autorisation_ambassade_epouse` date DEFAULT NULL,
  `cec_naissance_epouse` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cec_naissance_epoux` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificat_residence_epoux` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_emission_certificat_residence_epoux` date DEFAULT NULL,
  `certificat_residence_epouse` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_emission_certificat_residence_epouse` date DEFAULT NULL,
  `code_epouse` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_epoux` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nbre_enfant` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_temoin_homme_epouse` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_temoin_femme_epouse` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_temoin_homme_epoux` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_temoin_femme_epoux` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_filiation_chef_famille` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chef_famille` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pere_epoux` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mere_epoux` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pere_epouse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mere_epouse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_emission_acte_naissance_epouse` date DEFAULT NULL,
  `date_emission_acte_naissance_epoux` date DEFAULT NULL,
  `numero_acte_naissance_epouse` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_acte_naissance_epoux` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_jugement_divorce_epoux` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avis_epouse` tinyint(1) NOT NULL DEFAULT '0',
  `reference_avis_epouse` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_pre_mariage_epoux` date DEFAULT NULL,
  `parent_paternel_epoux` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_maternel_epoux` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant_dot` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `examens_prenuptiaux` tinyint(1) NOT NULL DEFAULT '0',
  `persister_marier_epoux` tinyint(1) NOT NULL DEFAULT '0',
  `persister_marier_epouse` tinyint(1) NOT NULL DEFAULT '0',
  `date_pre_mariage_epouse` date DEFAULT NULL,
  `parent_paternel_epouse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_maternel_epouse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_jugement_divorce_epouse` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_acte_mariage_epoux` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_acte_mariage_epouse` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_acte_deces_epoux` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_acte_deces_epouse` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_option_mariage` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_regime` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_situation_mat_epouse` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_situation_mat_epoux` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_declaration` enum('DECLARATION DE MARIAGE','DISPENSE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_mariage` enum('NORMAL','POSTHUME','PROCURATION') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_dispense` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_profession_epoux` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_profession_epouse` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_profession_temoin_h_epoux` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_profession_temoin_f_epoux` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_profession_temoin_h_epouse` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_profession_temoin_f_epouse` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_prenom_mandant_epoux` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_prenom_mandant_epouse` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approuver` enum('OUI','NON') COLLATE utf8mb4_unicode_ci DEFAULT 'NON' COMMENT 'Permet de savoir si le docuement a été lu et approuvé par le déclarant',
  `cec_approuver` enum('OUI','NON') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON' COMMENT 'permet de savoir si la declaration est prête ou pas pour la transcription de l''acte',
  `cec_approuve_par` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tribunal_approuver` enum('NON','OUI') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON',
  `tribunal_approuve_par` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cec_approuve_le` timestamp NULL DEFAULT NULL,
  `tribunal_approuve_le` timestamp NULL DEFAULT NULL,
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'institution à qui appartient cette declaration',
  `code_institution_destinataire` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'institution destinataire de la déclaration',
  `epoux_approuver` enum('OUI','NON') COLLATE utf8mb4_unicode_ci DEFAULT 'NON' COMMENT 'Permet de savoir si le document a été lu et approuvé par le future époux',
  `epouse_approuver` enum('OUI','NON') COLLATE utf8mb4_unicode_ci DEFAULT 'NON' COMMENT 'Permet de savoir si le document a été lu et approuvé par la future épouse',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `piece_epoux` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chemin vers la pièce d''identité de l''époux',
  `piece_epouse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chemin vers la pièce d''identité de l''épouse',
  `piece_temoins` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chemin vers les pièces d''identité des témoins'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_declaration_naissance`
--

CREATE TABLE `t_declaration_naissance` (
  `code_declaration_naissance` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_enfant` int NOT NULL DEFAULT '0',
  `date_heure_declaration` timestamp NOT NULL,
  `type_declarant` enum('Personne morale','Personne physique') COLLATE utf8mb4_unicode_ci NOT NULL,
  `personne_morale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personne_declaree` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cec_naissance` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Centre d''état civil de naissance,autre que ce qui est dans le referentiel',
  `pays_naissance_enfant` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_declarant` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_adoptant` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_enfant` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_pere` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_mere` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_filiation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_user_institution` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'utilisateur à qui appartient cet enregistrement',
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'institution à qui appartient cette declaration',
  `code_lieu_survenance` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_situation_mat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_heure_naissance` timestamp NULL DEFAULT NULL,
  `numero_req` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_certificat` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_declaration` enum('DECLARATION DE NAISSANCE','CERTIFICAT DE NON INSCRIPTION','CERTIFICAT DE DESTRUCTION DE L''ACTE','FICHE DE MATERNITE','FICHE DE TRANSCRIPTION') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formation_sanitaire_naissance` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cec_approuver` enum('OUI','NON') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON' COMMENT 'permet de savoir si la declaration est prête ou pas pour la transcription de l''acte',
  `cec_approuve_par` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tribunal_approuver` enum('NON','OUI') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON',
  `tribunal_approuve_par` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cec_approuve_le` timestamp NULL DEFAULT NULL,
  `tribunal_approuve_le` timestamp NULL DEFAULT NULL,
  `declarant_approuver` enum('OUI','NON') COLLATE utf8mb4_unicode_ci DEFAULT 'NON' COMMENT 'Permet de savoir si le docuement a été lu et approuvé par le déclarant',
  `code_institution_destinataire` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'institution destinataire de la déclaration',
  `numero_ancien_acte` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_jugement` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_declarant` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_pere` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_mere` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lieu_placement` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'qui permet de renseigner la structure au quel l’enfant trouvé ou abandonné a été placé',
  `piece_extrait_main_courante` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'qui permet de renseigner la structure au quel l’enfant trouvé ou abandonné a été placé',
  `num_jugement_placement_provisoir` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'qui permet de renseigner la structure au quel l''enfant trouvé ou abandonné a été placé',
  `num_fiche_placement` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'qui permet de renseigner la structure au quel l''enfant trouvé ou abandonné a été placé',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_declaration_naissance`
--

INSERT INTO `t_declaration_naissance` (`code_declaration_naissance`, `nombre_enfant`, `date_heure_declaration`, `type_declarant`, `personne_morale`, `personne_declaree`, `cec_naissance`, `pays_naissance_enfant`, `code_declarant`, `code_adoptant`, `code_enfant`, `code_pere`, `code_mere`, `code_filiation`, `code_user_institution`, `code_institution`, `code_lieu_survenance`, `code_situation_mat`, `date_heure_naissance`, `numero_req`, `numero_certificat`, `type_declaration`, `formation_sanitaire_naissance`, `cec_approuver`, `cec_approuve_par`, `tribunal_approuver`, `tribunal_approuve_par`, `cec_approuve_le`, `tribunal_approuve_le`, `declarant_approuver`, `code_institution_destinataire`, `numero_ancien_acte`, `code_jugement`, `piece_declarant`, `piece_pere`, `piece_mere`, `lieu_placement`, `piece_extrait_main_courante`, `num_jugement_placement_provisoir`, `num_fiche_placement`, `created_at`, `updated_at`, `deleted_at`) VALUES
('CDN_00000001', 1, '2026-01-14 23:00:00', 'Personne physique', NULL, 'Enfant normal', NULL, NULL, 'PRS_00000335', NULL, 'PRS_00000337', 'PRS_00000335', 'PRS_00000336', 'FIL_0001', 'CUI_00000007', 'INS_0094', 'LSURV_0001', 'SMAT_0002', '2026-01-14 09:25:00', NULL, NULL, 'DECLARATION DE NAISSANCE', NULL, 'OUI', 'CUI_00000004', 'NON', NULL, NULL, NULL, 'OUI', 'INS_0047', NULL, NULL, 'app/pieces/CDN_00000001_declarant_1768497388.png', 'app/pieces/CDN_00000001_pere_1768497396.png', 'app/pieces/CDN_00000001_mere_1768497403.png', NULL, NULL, NULL, NULL, '2026-01-15 16:13:39', '2026-01-15 17:59:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_demande_document`
--

CREATE TABLE `t_demande_document` (
  `code_demande_document` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_demandeur` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_demander` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sexe_demander` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone_demander` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_demandeur` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_type_document_demande` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('En traitement','Réjeté','Traité','Livré') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En traitement',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_detail_livret`
--

CREATE TABLE `t_detail_livret` (
  `code_detail_livret` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_livret_famille` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_enfant` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_type_extrait` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_extrait` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_detail_rectification`
--

CREATE TABLE `t_detail_rectification` (
  `code_detail_rectification` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_rectification` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_rubrique` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ancienne_valeur` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nouvelle_valeur` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_document`
--

CREATE TABLE `t_document` (
  `code_document` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_document` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_type_document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_personne` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_document` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_document`
--

INSERT INTO `t_document` (`code_document`, `numero_document`, `code_type_document`, `code_personne`, `image_document`, `created_at`, `updated_at`, `deleted_at`) VALUES
('DOC_00000001', 'DGVZRGZRGZR5203', 'TDOC_0002', 'PRS_00000335', NULL, '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
('DOC_00000002', 'SFBZEBZEB302', 'TDOC_0001', 'PRS_00000336', NULL, '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
('DOC_00000003', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'TDOC_0001', 'PRS_00000337', NULL, '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
('DOC_00000004', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'TDOC_0001', 'PRS_00000339', NULL, '2026-01-15 18:18:04', '2026-01-15 18:18:04', NULL),
('DOC_00000005', 'DFBEZRBZER65', 'TDOC_0001', 'PRS_00000340', NULL, '2026-01-15 18:18:04', '2026-01-15 18:18:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_extrait`
--

CREATE TABLE `t_extrait` (
  `id` bigint UNSIGNED NOT NULL,
  `numero_acte` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_extrait` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lieu_delivrance_extrait` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_officier` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_officier` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_feuillet_registre`
--

CREATE TABLE `t_feuillet_registre` (
  `code_feuillet_registre` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_acte` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_acte` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_feuillet_registre`
--

INSERT INTO `t_feuillet_registre` (`code_feuillet_registre`, `code_acte`, `numero_acte`, `created_at`, `updated_at`, `deleted_at`) VALUES
('FRE_0001', '2202601BZVBVCOM0001', 'RAN01202601', '2026-01-15 18:06:20', '2026-01-15 18:06:20', NULL),
('FRE_0002', 'AD_00000001', 'RAD02202601', '2026-01-16 05:05:49', '2026-01-16 05:05:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_ins_user_localite`
--

CREATE TABLE `t_ins_user_localite` (
  `id` int UNSIGNED NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_localite` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_ins_user_localite`
--

INSERT INTO `t_ins_user_localite` (`id`, `cui`, `code_localite`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CUI_00000013', 'LOC_0052', '2026-01-15 18:40:51', '2026-01-15 18:40:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_jugement`
--

CREATE TABLE `t_jugement` (
  `code_jugement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_jugement` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_jugement` date DEFAULT NULL,
  `document_jugement` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_declaration` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_type_jugement` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('importée','envoyée') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'importée',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_livret_famille`
--

CREATE TABLE `t_livret_famille` (
  `code_livret_famille` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_declaration_mariage` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_mouvement_deces`
--

CREATE TABLE `t_mouvement_deces` (
  `code_mouvement_deces` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_mouvement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_mouvement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_declaration_deces` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'utilisateur qui a effectué le mouvement',
  `code_institution_destinataire` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pour renvoyer à l''institution d''origine',
  `motif_renvoi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('En cours','Envoyée','Renvoyée','Actif','Importé','Confirmée') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En cours',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_mouvement_deces`
--

INSERT INTO `t_mouvement_deces` (`code_mouvement_deces`, `code_mouvement`, `lib_mouvement`, `code_declaration_deces`, `cui`, `code_institution_destinataire`, `motif_renvoi`, `observation`, `statut`, `created_at`, `updated_at`, `deleted_at`) VALUES
('MDC_0001', 'MOUV_0032', 'Déclaration de décès enregistrée', 'CDD_00000001', 'CUI_00000007', NULL, NULL, NULL, 'En cours', '2026-01-15 18:18:04', '2026-01-15 18:18:04', NULL),
('MDC_0002', 'MOUV_0002', 'Déclaration de décès envoyée', 'CDD_00000001', 'CUI_00000007', 'INS_0192', NULL, NULL, 'Envoyée', '2026-01-15 19:10:34', '2026-01-15 19:10:34', NULL),
('MDC_0003', 'MOUV_0019', 'Dossier confirmé par le centre d\'état civil', 'CDD_00000001', 'CUI_00000013', NULL, NULL, 'Dossier confirmé et prêt pour la génération de l\'acte', 'Confirmée', '2026-01-15 19:19:20', '2026-01-15 19:19:20', NULL),
('MDC_0004', 'MOUV_0014', 'Acte produit et en attente d\'approbation de l\'officier d\'état civil', 'CDD_00000001', NULL, NULL, NULL, NULL, 'Actif', '2026-01-16 05:05:49', '2026-01-16 05:05:49', NULL),
('MDC_0005', 'MOUV_0015', 'Acte produit non rétiré', 'CDD_00000001', NULL, NULL, NULL, NULL, 'Actif', '2026-01-16 05:13:51', '2026-01-16 05:13:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_mouvement_dossier`
--

CREATE TABLE `t_mouvement_dossier` (
  `code_mouvement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_dossier` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Peut être code_declaration_naissance ou code_declaration_deces',
  `module` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'naissance ou deces ou autre',
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'l’auteur représente l’utilisateur ou l’entité qui a effectué l’action (le mouvement).',
  `observation` text COLLATE utf8mb4_unicode_ci,
  `date_mouvement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_mouvement_mariage`
--

CREATE TABLE `t_mouvement_mariage` (
  `code_mouvement_mariage` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_mouvement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_mouvement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_declaration_mariage` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'utilisateur qui a effectué le mouvement',
  `code_institution_destinataire` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pour renvoyer à l''institution d''origine',
  `motif_renvoi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('En cours','Envoyée','Renvoyée','Actif','Importé','Confirmée') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En cours',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_mouvement_naissance`
--

CREATE TABLE `t_mouvement_naissance` (
  `code_mouvement_naissance` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_mouvement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_mouvement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_declaration_naissance` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'utilisateur qui a effectué le mouvement',
  `code_institution_destinataire` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pour renvoyer à l''institution d''origine',
  `motif_renvoi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('En cours','Envoyée','Renvoyée','Actif','Importé','Confirmée') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En cours',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_mouvement_naissance`
--

INSERT INTO `t_mouvement_naissance` (`code_mouvement_naissance`, `code_mouvement`, `lib_mouvement`, `code_declaration_naissance`, `cui`, `code_institution_destinataire`, `motif_renvoi`, `observation`, `statut`, `created_at`, `updated_at`, `deleted_at`) VALUES
('MDN_0001', 'MOUV_0024', 'Déclaration de naissance enregistrée', 'CDN_00000001', 'CUI_00000007', NULL, NULL, NULL, 'En cours', '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
('MDN_0002', 'MOUV_0001', 'Déclaration de naissance envoyée', 'CDN_00000001', 'CUI_00000007', 'INS_0047', NULL, NULL, 'Envoyée', '2026-01-15 16:17:02', '2026-01-15 16:17:02', NULL),
('MDN_0003', 'MOUV_0019', 'Dossier confirmé par le centre d\'état civil', 'CDN_00000001', 'CUI_00000004', NULL, NULL, 'Dossier confirmé et prêt pour la génération de l\'acte', 'Confirmée', '2026-01-15 17:59:49', '2026-01-15 17:59:49', NULL),
('MDN_0004', 'MOUV_0014', 'Acte produit et en attente d\'approbation de l\'officier d\'état civil', 'CDN_00000001', NULL, NULL, NULL, NULL, 'Actif', '2026-01-15 18:06:20', '2026-01-15 18:06:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_mouvement_rectification`
--

CREATE TABLE `t_mouvement_rectification` (
  `code_mouvement_rectification` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_mouvement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lib_mouvement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_rectification` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'utilisateur qui a effectué le mouvement',
  `code_institution_destinataire` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pour renvoyer à l''institution d''origine',
  `motif_renvoi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('En cours','Envoyée','Renvoyée','Actif','Importé') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En cours',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_paiement_document`
--

CREATE TABLE `t_paiement_document` (
  `code_paiement_document` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_demande_document` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `canal_paiement` enum('MOMO','AIRTEL','OTHER') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_paiement` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut_payment` enum('success','failed','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_personne_sitmat`
--

CREATE TABLE `t_personne_sitmat` (
  `code_personne_sitMat` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_personne` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_situation_matrimoniale` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supprimer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_rectification`
--

CREATE TABLE `t_rectification` (
  `code_rectification` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CUI de l''utilisateur qui a fait la rectification',
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Centre état civil où vient la rectification',
  `code_institution_destinataire` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Centre état civil où va la rectification',
  `numero_rectification` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro de la rectification',
  `code_type_acte` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_requisition` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_prenom_requerant` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_requerant` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone_requerant` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_rectification` date DEFAULT NULL,
  `code_filiation` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code de la filiation du requérant, exple: père, mère, époux, épouse, etc.',
  `numero_acte` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numero de l''acte à rectifier',
  `statut` enum('En cours de traitement','Envoyé au tribunal','Validé','Annulé') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En cours de traitement' COMMENT 'Statut de la rectification',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_requisition`
--

CREATE TABLE `t_requisition` (
  `code_requisition` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_requisition` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_requisition` date DEFAULT NULL,
  `document_requisition` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_declaration` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_type_requisition` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_institution` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('importée','envoyée') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'importée',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_residence_personne`
--

CREATE TABLE `t_residence_personne` (
  `id` bigint UNSIGNED NOT NULL,
  `lib_pays` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lib_ville` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_voie` varchar(175) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_voie` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_rue` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_localite` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_personne` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_residence_personne`
--

INSERT INTO `t_residence_personne` (`id`, `lib_pays`, `lib_ville`, `type_voie`, `nom_voie`, `numero_rue`, `code_localite`, `code_personne`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, 'Congo', 'BRAZZAVILLE', 'rue', 'Makoko', '25', 'LOC_0026', 'PRS_00000335', '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
(7, 'Congo', 'BRAZZAVILLE', 'rue', 'Makoko', '25', 'LOC_0026', 'PRS_00000336', '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
(8, 'Congo', NULL, NULL, NULL, NULL, NULL, 'PRS_00000337', '2026-01-15 16:13:39', '2026-01-15 16:13:39', NULL),
(9, 'Congo', 'BRAZZAVILLE', 'rue', 'Ngobila', '110', 'LOC_0026', 'PRS_00000338', '2026-01-15 18:18:04', '2026-01-15 18:18:04', NULL),
(10, 'Congo', 'OUESSO', 'avenue', 'Loukolela', '20', 'LOC_0024', 'PRS_00000339', '2026-01-15 18:18:04', '2026-01-15 18:18:04', NULL),
(11, 'Congo', 'BRAZZAVILLE', 'rue', 'Mpika', '52', 'LOC_0026', 'PRS_00000340', '2026-01-15 18:18:04', '2026-01-15 18:18:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_retrait_acte`
--

CREATE TABLE `t_retrait_acte` (
  `code_retrait_acte` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_acte` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `retirer_par` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `piece_identite` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_piece_identite` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observations` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_signature_mariage`
--

CREATE TABLE `t_signature_mariage` (
  `code_signature_mariage` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_declaration_mariage` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature_epoux` longtext COLLATE utf8mb4_unicode_ci,
  `signature_epouse` longtext COLLATE utf8mb4_unicode_ci,
  `signature_temoin_premier_epoux` longtext COLLATE utf8mb4_unicode_ci,
  `signature_temoin_deuxieme_epoux` longtext COLLATE utf8mb4_unicode_ci,
  `signature_temoin_premier_epouse` longtext COLLATE utf8mb4_unicode_ci,
  `signature_temoin_deuxieme_epouse` longtext COLLATE utf8mb4_unicode_ci,
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_sms_templates`
--

CREATE TABLE `t_sms_templates` (
  `code_template` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_action` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_tarification`
--

CREATE TABLE `t_tarification` (
  `code_tarification` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_type_document_demande` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_user_arrondissement`
--

CREATE TABLE `t_user_arrondissement` (
  `cui` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_arrondissement` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_demande_document`
--
ALTER TABLE `detail_demande_document`
  ADD PRIMARY KEY (`code_detail_demande_document`),
  ADD KEY `detail_demande_document_code_demande_document_foreign` (`code_demande_document`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_money_transaction_details`
--
ALTER TABLE `mobile_money_transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile_money_transaction_details_invoice_number_unique` (`invoice_number`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_id_notifiable_type_index` (`notifiable_id`,`notifiable_type`),
  ADD KEY `notifications_cui_foreign` (`cui`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `paiement_details`
--
ALTER TABLE `paiement_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paiement_details_code_demande_document_foreign` (`code_demande_document`);

--
-- Indexes for table `paiement_documents`
--
ALTER TABLE `paiement_documents`
  ADD PRIMARY KEY (`code_paiement_document`),
  ADD KEY `paiement_documents_cui_foreign` (`cui`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `tr_arrondissement`
--
ALTER TABLE `tr_arrondissement`
  ADD PRIMARY KEY (`code_arrondissement`),
  ADD KEY `tr_arrondissement_code_commune_foreign` (`code_commune`);

--
-- Indexes for table `tr_canal`
--
ALTER TABLE `tr_canal`
  ADD PRIMARY KEY (`code_canal`);

--
-- Indexes for table `tr_cause_deces`
--
ALTER TABLE `tr_cause_deces`
  ADD PRIMARY KEY (`code_cause_deces`),
  ADD UNIQUE KEY `tr_cause_deces_lib_cause_deces_unique` (`lib_cause_deces`);

--
-- Indexes for table `tr_communaute_urbaine`
--
ALTER TABLE `tr_communaute_urbaine`
  ADD PRIMARY KEY (`code_communaute_urbaine`),
  ADD KEY `tr_communaute_urbaine_code_district_foreign` (`code_district`);

--
-- Indexes for table `tr_commune`
--
ALTER TABLE `tr_commune`
  ADD PRIMARY KEY (`code_commune`),
  ADD KEY `tr_commune_code_departement_foreign` (`code_departement`);

--
-- Indexes for table `tr_cour_appel`
--
ALTER TABLE `tr_cour_appel`
  ADD PRIMARY KEY (`code_cour_appel`);

--
-- Indexes for table `tr_departement`
--
ALTER TABLE `tr_departement`
  ADD PRIMARY KEY (`code_departement`);

--
-- Indexes for table `tr_district`
--
ALTER TABLE `tr_district`
  ADD PRIMARY KEY (`code_district`),
  ADD KEY `tr_district_code_departement_foreign` (`code_departement`);

--
-- Indexes for table `tr_ff`
--
ALTER TABLE `tr_ff`
  ADD PRIMARY KEY (`code_fonction`,`code_fonctionnalite`),
  ADD KEY `tr_ff_code_fonctionnalite_foreign` (`code_fonctionnalite`);

--
-- Indexes for table `tr_filiation`
--
ALTER TABLE `tr_filiation`
  ADD PRIMARY KEY (`code_filiation`),
  ADD UNIQUE KEY `tr_filiation_lib_filiation_unique` (`lib_filiation`);

--
-- Indexes for table `tr_fonction`
--
ALTER TABLE `tr_fonction`
  ADD PRIMARY KEY (`code_fonction`),
  ADD UNIQUE KEY `tr_fonction_lib_fonction_unique` (`lib_fonction`);

--
-- Indexes for table `tr_fonctionnalite`
--
ALTER TABLE `tr_fonctionnalite`
  ADD PRIMARY KEY (`code_fonctionnalite`),
  ADD UNIQUE KEY `tr_fonctionnalite_lib_fonctionnalite_unique` (`lib_fonctionnalite`),
  ADD UNIQUE KEY `tr_fonctionnalite_lib_technique_unique` (`lib_technique`),
  ADD KEY `tr_fonctionnalite_code_fonctionnalite_parent_foreign` (`code_fonctionnalite_parent`),
  ADD KEY `tr_fonctionnalite_code_module_foreign` (`code_module`);

--
-- Indexes for table `tr_identification_personne`
--
ALTER TABLE `tr_identification_personne`
  ADD PRIMARY KEY (`code_personne`),
  ADD UNIQUE KEY `tr_identification_personne_personne_string_unique` (`personne_string`),
  ADD KEY `tr_identification_personne_code_localite_foreign` (`code_localite`),
  ADD KEY `tr_identification_personne_code_nationalite_foreign` (`code_nationalite`),
  ADD KEY `tr_identification_personne_code_profession_foreign` (`code_profession`);

--
-- Indexes for table `tr_institution`
--
ALTER TABLE `tr_institution`
  ADD PRIMARY KEY (`code_institution`),
  ADD KEY `tr_institution_code_institution_parent_foreign` (`code_institution_parent`),
  ADD KEY `tr_institution_code_pompe_funebre_foreign` (`code_pompe_funebre`),
  ADD KEY `tr_institution_code_type_institution_foreign` (`code_type_institution`),
  ADD KEY `tr_institution_code_localite_foreign` (`code_localite`);

--
-- Indexes for table `tr_ins_user`
--
ALTER TABLE `tr_ins_user`
  ADD PRIMARY KEY (`cui`,`code_institution`,`code_user`),
  ADD KEY `tr_ins_user_code_institution_foreign` (`code_institution`),
  ADD KEY `tr_ins_user_code_user_foreign` (`code_user`),
  ADD KEY `tr_ins_user_code_fonction_foreign` (`code_fonction`);

--
-- Indexes for table `tr_lieu_survenance`
--
ALTER TABLE `tr_lieu_survenance`
  ADD PRIMARY KEY (`code_lieu_survenance`),
  ADD UNIQUE KEY `tr_lieu_survenance_lib_lieu_survenance_unique` (`lib_lieu_survenance`);

--
-- Indexes for table `tr_localisation`
--
ALTER TABLE `tr_localisation`
  ADD PRIMARY KEY (`code_localisation`),
  ADD KEY `tr_localisation_code_departement_foreign` (`code_departement`);

--
-- Indexes for table `tr_localite`
--
ALTER TABLE `tr_localite`
  ADD PRIMARY KEY (`code_localite`),
  ADD KEY `tr_localite_code_type_localite_foreign` (`code_type_localite`),
  ADD KEY `tr_localite_code_localite_parent_foreign` (`code_localite_parent`);

--
-- Indexes for table `tr_module`
--
ALTER TABLE `tr_module`
  ADD PRIMARY KEY (`code_module`),
  ADD UNIQUE KEY `tr_module_lib_module_unique` (`lib_module`);

--
-- Indexes for table `tr_mouvement`
--
ALTER TABLE `tr_mouvement`
  ADD PRIMARY KEY (`code_mouvement`),
  ADD UNIQUE KEY `tr_mouvement_lib_mouvement_unique` (`lib_mouvement`);

--
-- Indexes for table `tr_nationalite`
--
ALTER TABLE `tr_nationalite`
  ADD PRIMARY KEY (`code_nationalite`),
  ADD UNIQUE KEY `tr_nationalite_lib_nationalite_unique` (`lib_nationalite`);

--
-- Indexes for table `tr_option_mariage`
--
ALTER TABLE `tr_option_mariage`
  ADD PRIMARY KEY (`code_option_mariage`);

--
-- Indexes for table `tr_pompes_funebres`
--
ALTER TABLE `tr_pompes_funebres`
  ADD PRIMARY KEY (`code_pompes_funebres`);

--
-- Indexes for table `tr_profession`
--
ALTER TABLE `tr_profession`
  ADD PRIMARY KEY (`code_profession`),
  ADD UNIQUE KEY `tr_profession_lib_profession_unique` (`lib_profession`);

--
-- Indexes for table `tr_regime`
--
ALTER TABLE `tr_regime`
  ADD PRIMARY KEY (`code_regime`),
  ADD UNIQUE KEY `tr_regime_lib_regime_unique` (`lib_regime`);

--
-- Indexes for table `tr_registre`
--
ALTER TABLE `tr_registre`
  ADD PRIMARY KEY (`code_registre`),
  ADD KEY `tr_registre_approbation_tribunal_foreign` (`approbation_tribunal`),
  ADD KEY `tr_registre_cloture_cec_foreign` (`cloture_cec`),
  ADD KEY `tr_registre_code_type_registre_foreign` (`code_type_registre`),
  ADD KEY `tr_registre_cui_foreign` (`cui`);

--
-- Indexes for table `tr_religion`
--
ALTER TABLE `tr_religion`
  ADD PRIMARY KEY (`code_religion`),
  ADD UNIQUE KEY `tr_religion_lib_religion_unique` (`lib_religion`);

--
-- Indexes for table `tr_rubrique`
--
ALTER TABLE `tr_rubrique`
  ADD PRIMARY KEY (`code_rubrique`),
  ADD KEY `tr_rubrique_code_type_acte_foreign` (`code_type_acte`);

--
-- Indexes for table `tr_situation_matrimoniale`
--
ALTER TABLE `tr_situation_matrimoniale`
  ADD PRIMARY KEY (`code_situation_matrimoniale`),
  ADD UNIQUE KEY `tr_situation_matrimoniale_lib_situation_matrimoniale_unique` (`lib_situation_matrimoniale`);

--
-- Indexes for table `tr_sms_providers`
--
ALTER TABLE `tr_sms_providers`
  ADD PRIMARY KEY (`code_providers`),
  ADD UNIQUE KEY `tr_sms_providers_lib_provider_unique` (`lib_provider`);

--
-- Indexes for table `tr_tribunal`
--
ALTER TABLE `tr_tribunal`
  ADD PRIMARY KEY (`code_tribunal`),
  ADD KEY `tr_tribunal_code_cour_appel_foreign` (`code_cour_appel`);

--
-- Indexes for table `tr_type_acte`
--
ALTER TABLE `tr_type_acte`
  ADD PRIMARY KEY (`code_type_acte`);

--
-- Indexes for table `tr_type_categorie_ins`
--
ALTER TABLE `tr_type_categorie_ins`
  ADD PRIMARY KEY (`code_type_categorie_ins`);

--
-- Indexes for table `tr_type_cec`
--
ALTER TABLE `tr_type_cec`
  ADD PRIMARY KEY (`code_type_cec`);

--
-- Indexes for table `tr_type_document`
--
ALTER TABLE `tr_type_document`
  ADD PRIMARY KEY (`code_type_document`),
  ADD UNIQUE KEY `tr_type_document_lib_type_document_unique` (`lib_type_document`);

--
-- Indexes for table `tr_type_document_demande`
--
ALTER TABLE `tr_type_document_demande`
  ADD PRIMARY KEY (`code_type_document_demande`);

--
-- Indexes for table `tr_type_extrait`
--
ALTER TABLE `tr_type_extrait`
  ADD PRIMARY KEY (`code_type_extrait`);

--
-- Indexes for table `tr_type_institution`
--
ALTER TABLE `tr_type_institution`
  ADD PRIMARY KEY (`code_type_institution`),
  ADD KEY `tr_type_institution_code_type_categorie_ins_foreign` (`code_type_categorie_ins`);

--
-- Indexes for table `tr_type_jugement`
--
ALTER TABLE `tr_type_jugement`
  ADD PRIMARY KEY (`code_type_jugement`);

--
-- Indexes for table `tr_type_localite`
--
ALTER TABLE `tr_type_localite`
  ADD PRIMARY KEY (`code_type_localite`),
  ADD UNIQUE KEY `tr_type_localite_lib_type_localite_unique` (`lib_type_localite`);

--
-- Indexes for table `tr_type_registre`
--
ALTER TABLE `tr_type_registre`
  ADD PRIMARY KEY (`code_type_registre`),
  ADD UNIQUE KEY `tr_type_registre_lib_type_registre_unique` (`lib_type_registre`);

--
-- Indexes for table `tr_type_requisition`
--
ALTER TABLE `tr_type_requisition`
  ADD PRIMARY KEY (`code_type_requisition`);

--
-- Indexes for table `tr_uf`
--
ALTER TABLE `tr_uf`
  ADD PRIMARY KEY (`code_user`,`code_fonctionnalite`),
  ADD KEY `tr_uf_code_fonctionnalite_foreign` (`code_fonctionnalite`);

--
-- Indexes for table `tr_user`
--
ALTER TABLE `tr_user`
  ADD PRIMARY KEY (`code_user`),
  ADD UNIQUE KEY `tr_user_email_unique` (`email`),
  ADD UNIQUE KEY `tr_user_pseudo_unique` (`pseudo`),
  ADD KEY `tr_user_code_personne_foreign` (`code_personne`);

--
-- Indexes for table `tr_user_audit_trail`
--
ALTER TABLE `tr_user_audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tr_user_audit_trail_code_user_created_at_index` (`code_user`,`created_at`),
  ADD KEY `tr_user_audit_trail_action_created_at_index` (`action`,`created_at`),
  ADD KEY `tr_user_audit_trail_ip_address_index` (`ip_address`);

--
-- Indexes for table `t_acte_deces`
--
ALTER TABLE `t_acte_deces`
  ADD PRIMARY KEY (`code_acte_deces`),
  ADD KEY `t_acte_deces_code_declaration_deces_foreign` (`code_declaration_deces`),
  ADD KEY `t_acte_deces_code_registre_foreign` (`code_registre`),
  ADD KEY `t_acte_deces_cui_foreign` (`cui`),
  ADD KEY `t_acte_deces_approbation_pompe_funebre_foreign` (`approbation_pompe_funebre`),
  ADD KEY `t_acte_deces_code_institution_foreign` (`code_institution`);

--
-- Indexes for table `t_acte_mariage`
--
ALTER TABLE `t_acte_mariage`
  ADD PRIMARY KEY (`code_acte_mariage`),
  ADD KEY `t_acte_mariage_code_institution_foreign` (`code_institution`),
  ADD KEY `t_acte_mariage_approbation_mairie_foreign` (`approbation_mairie`),
  ADD KEY `t_acte_mariage_code_declaration_mariage_foreign` (`code_declaration_mariage`),
  ADD KEY `t_acte_mariage_code_registre_foreign` (`code_registre`),
  ADD KEY `t_acte_mariage_cui_foreign` (`cui`);

--
-- Indexes for table `t_acte_naissance`
--
ALTER TABLE `t_acte_naissance`
  ADD PRIMARY KEY (`niupp`),
  ADD KEY `t_acte_naissance_code_declaration_naissance_foreign` (`code_declaration_naissance`),
  ADD KEY `t_acte_naissance_code_registre_foreign` (`code_registre`),
  ADD KEY `t_acte_naissance_cui_foreign` (`cui`),
  ADD KEY `t_acte_naissance_approbation_mairie_foreign` (`approbation_mairie`),
  ADD KEY `t_acte_naissance_code_institution_foreign` (`code_institution`);

--
-- Indexes for table `t_action`
--
ALTER TABLE `t_action`
  ADD PRIMARY KEY (`code_action`),
  ADD UNIQUE KEY `t_action_lib_action_unique` (`lib_action`);

--
-- Indexes for table `t_api_headers`
--
ALTER TABLE `t_api_headers`
  ADD PRIMARY KEY (`code_api_headers`),
  ADD KEY `t_api_headers_code_providers_foreign` (`code_providers`);

--
-- Indexes for table `t_api_params`
--
ALTER TABLE `t_api_params`
  ADD PRIMARY KEY (`code_api_params`),
  ADD KEY `t_api_params_code_providers_foreign` (`code_providers`);

--
-- Indexes for table `t_contact_personne`
--
ALTER TABLE `t_contact_personne`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_contact_personne_code_personne_foreign` (`code_personne`);

--
-- Indexes for table `t_copie`
--
ALTER TABLE `t_copie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_copie_cui_foreign` (`cui`);

--
-- Indexes for table `t_ddecescause`
--
ALTER TABLE `t_ddecescause`
  ADD PRIMARY KEY (`code_declaration_deces`,`code_cause_deces`),
  ADD KEY `t_ddecescause_code_cause_deces_foreign` (`code_cause_deces`);

--
-- Indexes for table `t_declaration_deces`
--
ALTER TABLE `t_declaration_deces`
  ADD PRIMARY KEY (`code_declaration_deces`),
  ADD KEY `t_declaration_deces_cec_approuve_par_foreign` (`cec_approuve_par`),
  ADD KEY `t_declaration_deces_tribunal_approuve_par_foreign` (`tribunal_approuve_par`),
  ADD KEY `t_declaration_deces_code_cause_deces_foreign` (`code_cause_deces`),
  ADD KEY `t_declaration_deces_code_situation_matrimoniale_foreign` (`code_situation_matrimoniale`),
  ADD KEY `t_declaration_deces_code_regime_foreign` (`code_regime`),
  ADD KEY `t_declaration_deces_code_lieu_survenance_foreign` (`code_lieu_survenance`),
  ADD KEY `t_declaration_deces_code_religion_foreign` (`code_religion`),
  ADD KEY `t_declaration_deces_code_document_foreign` (`code_document`),
  ADD KEY `t_declaration_deces_code_user_institution_foreign` (`code_user_institution`),
  ADD KEY `t_declaration_deces_code_filiation_foreign` (`code_filiation`),
  ADD KEY `t_declaration_deces_code_declarant_foreign` (`code_declarant`),
  ADD KEY `t_declaration_deces_code_defunt_foreign` (`code_defunt`),
  ADD KEY `t_declaration_deces_code_conjoint_foreign` (`code_conjoint`),
  ADD KEY `t_declaration_deces_code_pere_foreign` (`code_pere`),
  ADD KEY `t_declaration_deces_code_mere_foreign` (`code_mere`),
  ADD KEY `t_declaration_deces_code_institution_destinataire_foreign` (`code_institution_destinataire`),
  ADD KEY `t_declaration_deces_code_institution_foreign` (`code_institution`),
  ADD KEY `t_declaration_deces_code_jugement_foreign` (`code_jugement`),
  ADD KEY `t_declaration_deces_code_requisition_foreign` (`code_requisition`);

--
-- Indexes for table `t_declaration_mariage`
--
ALTER TABLE `t_declaration_mariage`
  ADD PRIMARY KEY (`code_declaration_mariage`),
  ADD KEY `t_declaration_mariage_cec_approuve_par_foreign` (`cec_approuve_par`),
  ADD KEY `t_declaration_mariage_tribunal_approuve_par_foreign` (`tribunal_approuve_par`),
  ADD KEY `t_declaration_mariage_code_epouse_foreign` (`code_epouse`),
  ADD KEY `t_declaration_mariage_code_epoux_foreign` (`code_epoux`),
  ADD KEY `t_declaration_mariage_code_temoin_homme_epouse_foreign` (`code_temoin_homme_epouse`),
  ADD KEY `t_declaration_mariage_code_temoin_femme_epouse_foreign` (`code_temoin_femme_epouse`),
  ADD KEY `t_declaration_mariage_code_temoin_homme_epoux_foreign` (`code_temoin_homme_epoux`),
  ADD KEY `t_declaration_mariage_code_temoin_femme_epoux_foreign` (`code_temoin_femme_epoux`),
  ADD KEY `t_declaration_mariage_code_filiation_chef_famille_foreign` (`code_filiation_chef_famille`),
  ADD KEY `t_declaration_mariage_code_option_mariage_foreign` (`code_option_mariage`),
  ADD KEY `t_declaration_mariage_code_regime_foreign` (`code_regime`),
  ADD KEY `t_declaration_mariage_code_situation_mat_epouse_foreign` (`code_situation_mat_epouse`),
  ADD KEY `t_declaration_mariage_code_situation_mat_epoux_foreign` (`code_situation_mat_epoux`),
  ADD KEY `t_declaration_mariage_cui_foreign` (`cui`),
  ADD KEY `t_declaration_mariage_code_institution_foreign` (`code_institution`),
  ADD KEY `t_declaration_mariage_code_institution_destinataire_foreign` (`code_institution_destinataire`),
  ADD KEY `t_declaration_mariage_code_profession_epoux_foreign` (`code_profession_epoux`),
  ADD KEY `t_declaration_mariage_code_profession_epouse_foreign` (`code_profession_epouse`),
  ADD KEY `t_declaration_mariage_code_profession_temoin_h_epoux_foreign` (`code_profession_temoin_h_epoux`),
  ADD KEY `t_declaration_mariage_code_profession_temoin_f_epoux_foreign` (`code_profession_temoin_f_epoux`),
  ADD KEY `t_declaration_mariage_code_profession_temoin_h_epouse_foreign` (`code_profession_temoin_h_epouse`),
  ADD KEY `t_declaration_mariage_code_profession_temoin_f_epouse_foreign` (`code_profession_temoin_f_epouse`);

--
-- Indexes for table `t_declaration_naissance`
--
ALTER TABLE `t_declaration_naissance`
  ADD PRIMARY KEY (`code_declaration_naissance`),
  ADD KEY `t_declaration_naissance_code_adoptant_foreign` (`code_adoptant`),
  ADD KEY `t_declaration_naissance_code_declarant_foreign` (`code_declarant`),
  ADD KEY `t_declaration_naissance_code_enfant_foreign` (`code_enfant`),
  ADD KEY `t_declaration_naissance_code_pere_foreign` (`code_pere`),
  ADD KEY `t_declaration_naissance_code_mere_foreign` (`code_mere`),
  ADD KEY `t_declaration_naissance_code_filiation_foreign` (`code_filiation`),
  ADD KEY `t_declaration_naissance_code_user_institution_foreign` (`code_user_institution`),
  ADD KEY `t_declaration_naissance_code_lieu_survenance_foreign` (`code_lieu_survenance`),
  ADD KEY `t_declaration_naissance_code_situation_mat_foreign` (`code_situation_mat`),
  ADD KEY `t_declaration_naissance_code_institution_destinataire_foreign` (`code_institution_destinataire`),
  ADD KEY `t_declaration_naissance_cec_approuve_par_foreign` (`cec_approuve_par`),
  ADD KEY `t_declaration_naissance_tribunal_approuve_par_foreign` (`tribunal_approuve_par`),
  ADD KEY `t_declaration_naissance_code_jugement_foreign` (`code_jugement`);

--
-- Indexes for table `t_demande_document`
--
ALTER TABLE `t_demande_document`
  ADD PRIMARY KEY (`code_demande_document`),
  ADD KEY `t_demande_document_code_type_document_demande_foreign` (`code_type_document_demande`);

--
-- Indexes for table `t_detail_livret`
--
ALTER TABLE `t_detail_livret`
  ADD PRIMARY KEY (`code_detail_livret`),
  ADD KEY `t_detail_livret_code_livret_famille_foreign` (`code_livret_famille`),
  ADD KEY `t_detail_livret_code_enfant_foreign` (`code_enfant`),
  ADD KEY `t_detail_livret_code_type_extrait_foreign` (`code_type_extrait`);

--
-- Indexes for table `t_detail_rectification`
--
ALTER TABLE `t_detail_rectification`
  ADD PRIMARY KEY (`code_detail_rectification`),
  ADD KEY `t_detail_rectification_code_rectification_foreign` (`code_rectification`),
  ADD KEY `t_detail_rectification_code_rubrique_foreign` (`code_rubrique`);

--
-- Indexes for table `t_document`
--
ALTER TABLE `t_document`
  ADD PRIMARY KEY (`code_document`),
  ADD KEY `t_document_code_type_document_foreign` (`code_type_document`),
  ADD KEY `t_document_code_personne_foreign` (`code_personne`);

--
-- Indexes for table `t_extrait`
--
ALTER TABLE `t_extrait`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_extrait_cui_foreign` (`cui`);

--
-- Indexes for table `t_feuillet_registre`
--
ALTER TABLE `t_feuillet_registre`
  ADD PRIMARY KEY (`code_feuillet_registre`);

--
-- Indexes for table `t_ins_user_localite`
--
ALTER TABLE `t_ins_user_localite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_ins_user_localite_cui_foreign` (`cui`),
  ADD KEY `t_ins_user_localite_code_localite_foreign` (`code_localite`);

--
-- Indexes for table `t_jugement`
--
ALTER TABLE `t_jugement`
  ADD PRIMARY KEY (`code_jugement`),
  ADD KEY `t_jugement_cui_foreign` (`cui`),
  ADD KEY `t_jugement_code_type_jugement_foreign` (`code_type_jugement`),
  ADD KEY `t_jugement_code_institution_foreign` (`code_institution`);

--
-- Indexes for table `t_livret_famille`
--
ALTER TABLE `t_livret_famille`
  ADD PRIMARY KEY (`code_livret_famille`),
  ADD KEY `t_livret_famille_code_declaration_mariage_foreign` (`code_declaration_mariage`);

--
-- Indexes for table `t_mouvement_deces`
--
ALTER TABLE `t_mouvement_deces`
  ADD PRIMARY KEY (`code_mouvement_deces`),
  ADD KEY `t_mouvement_deces_code_mouvement_foreign` (`code_mouvement`),
  ADD KEY `t_mouvement_deces_code_declaration_deces_foreign` (`code_declaration_deces`),
  ADD KEY `t_mouvement_deces_cui_foreign` (`cui`);

--
-- Indexes for table `t_mouvement_dossier`
--
ALTER TABLE `t_mouvement_dossier`
  ADD PRIMARY KEY (`code_mouvement`),
  ADD KEY `t_mouvement_dossier_cui_foreign` (`cui`),
  ADD KEY `t_mouvement_dossier_code_dossier_module_index` (`code_dossier`,`module`);

--
-- Indexes for table `t_mouvement_mariage`
--
ALTER TABLE `t_mouvement_mariage`
  ADD PRIMARY KEY (`code_mouvement_mariage`),
  ADD KEY `t_mouvement_mariage_code_mouvement_foreign` (`code_mouvement`),
  ADD KEY `t_mouvement_mariage_code_declaration_mariage_foreign` (`code_declaration_mariage`),
  ADD KEY `t_mouvement_mariage_cui_foreign` (`cui`),
  ADD KEY `t_mouvement_mariage_code_institution_destinataire_foreign` (`code_institution_destinataire`);

--
-- Indexes for table `t_mouvement_naissance`
--
ALTER TABLE `t_mouvement_naissance`
  ADD PRIMARY KEY (`code_mouvement_naissance`),
  ADD KEY `t_mouvement_naissance_code_mouvement_foreign` (`code_mouvement`),
  ADD KEY `t_mouvement_naissance_code_declaration_naissance_foreign` (`code_declaration_naissance`),
  ADD KEY `t_mouvement_naissance_cui_foreign` (`cui`),
  ADD KEY `t_mouvement_naissance_code_institution_destinataire_foreign` (`code_institution_destinataire`);

--
-- Indexes for table `t_mouvement_rectification`
--
ALTER TABLE `t_mouvement_rectification`
  ADD PRIMARY KEY (`code_mouvement_rectification`),
  ADD KEY `t_mouvement_rectification_code_mouvement_foreign` (`code_mouvement`),
  ADD KEY `t_mouvement_rectification_cui_foreign` (`cui`),
  ADD KEY `t_mouvement_rectification_code_institution_destinataire_foreign` (`code_institution_destinataire`),
  ADD KEY `t_mouvement_rectification_code_rectification_foreign` (`code_rectification`);

--
-- Indexes for table `t_paiement_document`
--
ALTER TABLE `t_paiement_document`
  ADD PRIMARY KEY (`code_paiement_document`),
  ADD KEY `t_paiement_document_code_demande_document_foreign` (`code_demande_document`),
  ADD KEY `t_paiement_document_cui_foreign` (`cui`);

--
-- Indexes for table `t_personne_sitmat`
--
ALTER TABLE `t_personne_sitmat`
  ADD PRIMARY KEY (`code_personne_sitMat`),
  ADD KEY `t_personne_sitmat_code_personne_foreign` (`code_personne`),
  ADD KEY `t_personne_sitmat_code_situation_matrimoniale_foreign` (`code_situation_matrimoniale`);

--
-- Indexes for table `t_rectification`
--
ALTER TABLE `t_rectification`
  ADD PRIMARY KEY (`code_rectification`),
  ADD KEY `t_rectification_code_type_acte_foreign` (`code_type_acte`),
  ADD KEY `t_rectification_code_requisition_foreign` (`code_requisition`),
  ADD KEY `t_rectification_code_filiation_foreign` (`code_filiation`),
  ADD KEY `t_rectification_cui_foreign` (`cui`),
  ADD KEY `t_rectification_code_institution_foreign` (`code_institution`),
  ADD KEY `t_rectification_code_institution_destinataire_foreign` (`code_institution_destinataire`);

--
-- Indexes for table `t_requisition`
--
ALTER TABLE `t_requisition`
  ADD PRIMARY KEY (`code_requisition`),
  ADD KEY `t_requisition_cui_foreign` (`cui`),
  ADD KEY `t_requisition_code_type_requisition_foreign` (`code_type_requisition`),
  ADD KEY `t_requisition_code_institution_foreign` (`code_institution`);

--
-- Indexes for table `t_residence_personne`
--
ALTER TABLE `t_residence_personne`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_residence_personne_code_localite_foreign` (`code_localite`),
  ADD KEY `t_residence_personne_code_personne_foreign` (`code_personne`);

--
-- Indexes for table `t_retrait_acte`
--
ALTER TABLE `t_retrait_acte`
  ADD PRIMARY KEY (`code_retrait_acte`),
  ADD KEY `t_retrait_acte_cui_foreign` (`cui`);

--
-- Indexes for table `t_signature_mariage`
--
ALTER TABLE `t_signature_mariage`
  ADD PRIMARY KEY (`code_signature_mariage`),
  ADD KEY `t_signature_mariage_code_declaration_mariage_foreign` (`code_declaration_mariage`);

--
-- Indexes for table `t_sms_templates`
--
ALTER TABLE `t_sms_templates`
  ADD PRIMARY KEY (`code_template`),
  ADD KEY `t_sms_templates_code_action_foreign` (`code_action`);

--
-- Indexes for table `t_tarification`
--
ALTER TABLE `t_tarification`
  ADD PRIMARY KEY (`code_tarification`),
  ADD KEY `t_tarification_code_type_document_demande_foreign` (`code_type_document_demande`),
  ADD KEY `t_tarification_cui_foreign` (`cui`);

--
-- Indexes for table `t_user_arrondissement`
--
ALTER TABLE `t_user_arrondissement`
  ADD PRIMARY KEY (`code_arrondissement`,`cui`),
  ADD KEY `t_user_arrondissement_cui_foreign` (`cui`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `mobile_money_transaction_details`
--
ALTER TABLE `mobile_money_transaction_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paiement_details`
--
ALTER TABLE `paiement_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tr_user_audit_trail`
--
ALTER TABLE `tr_user_audit_trail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `t_contact_personne`
--
ALTER TABLE `t_contact_personne`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `t_copie`
--
ALTER TABLE `t_copie`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_extrait`
--
ALTER TABLE `t_extrait`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_ins_user_localite`
--
ALTER TABLE `t_ins_user_localite`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_residence_personne`
--
ALTER TABLE `t_residence_personne`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_demande_document`
--
ALTER TABLE `detail_demande_document`
  ADD CONSTRAINT `detail_demande_document_code_demande_document_foreign` FOREIGN KEY (`code_demande_document`) REFERENCES `t_demande_document` (`code_demande_document`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paiement_details`
--
ALTER TABLE `paiement_details`
  ADD CONSTRAINT `paiement_details_code_demande_document_foreign` FOREIGN KEY (`code_demande_document`) REFERENCES `t_demande_document` (`code_demande_document`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paiement_documents`
--
ALTER TABLE `paiement_documents`
  ADD CONSTRAINT `paiement_documents_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_arrondissement`
--
ALTER TABLE `tr_arrondissement`
  ADD CONSTRAINT `tr_arrondissement_code_commune_foreign` FOREIGN KEY (`code_commune`) REFERENCES `tr_commune` (`code_commune`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_communaute_urbaine`
--
ALTER TABLE `tr_communaute_urbaine`
  ADD CONSTRAINT `tr_communaute_urbaine_code_district_foreign` FOREIGN KEY (`code_district`) REFERENCES `tr_district` (`code_district`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_commune`
--
ALTER TABLE `tr_commune`
  ADD CONSTRAINT `tr_commune_code_departement_foreign` FOREIGN KEY (`code_departement`) REFERENCES `tr_departement` (`code_departement`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_district`
--
ALTER TABLE `tr_district`
  ADD CONSTRAINT `tr_district_code_departement_foreign` FOREIGN KEY (`code_departement`) REFERENCES `tr_departement` (`code_departement`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_ff`
--
ALTER TABLE `tr_ff`
  ADD CONSTRAINT `tr_ff_code_fonction_foreign` FOREIGN KEY (`code_fonction`) REFERENCES `tr_fonction` (`code_fonction`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_ff_code_fonctionnalite_foreign` FOREIGN KEY (`code_fonctionnalite`) REFERENCES `tr_fonctionnalite` (`code_fonctionnalite`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_fonctionnalite`
--
ALTER TABLE `tr_fonctionnalite`
  ADD CONSTRAINT `tr_fonctionnalite_code_fonctionnalite_parent_foreign` FOREIGN KEY (`code_fonctionnalite_parent`) REFERENCES `tr_fonctionnalite` (`code_fonctionnalite`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_fonctionnalite_code_module_foreign` FOREIGN KEY (`code_module`) REFERENCES `tr_module` (`code_module`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_identification_personne`
--
ALTER TABLE `tr_identification_personne`
  ADD CONSTRAINT `tr_identification_personne_code_localite_foreign` FOREIGN KEY (`code_localite`) REFERENCES `tr_localite` (`code_localite`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_identification_personne_code_nationalite_foreign` FOREIGN KEY (`code_nationalite`) REFERENCES `tr_nationalite` (`code_nationalite`) ON DELETE CASCADE,
  ADD CONSTRAINT `tr_identification_personne_code_profession_foreign` FOREIGN KEY (`code_profession`) REFERENCES `tr_profession` (`code_profession`) ON DELETE CASCADE;

--
-- Constraints for table `tr_institution`
--
ALTER TABLE `tr_institution`
  ADD CONSTRAINT `tr_institution_code_institution_parent_foreign` FOREIGN KEY (`code_institution_parent`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_institution_code_localite_foreign` FOREIGN KEY (`code_localite`) REFERENCES `tr_localite` (`code_localite`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_institution_code_pompe_funebre_foreign` FOREIGN KEY (`code_pompe_funebre`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_institution_code_type_institution_foreign` FOREIGN KEY (`code_type_institution`) REFERENCES `tr_type_institution` (`code_type_institution`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_ins_user`
--
ALTER TABLE `tr_ins_user`
  ADD CONSTRAINT `tr_ins_user_code_fonction_foreign` FOREIGN KEY (`code_fonction`) REFERENCES `tr_fonction` (`code_fonction`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_ins_user_code_institution_foreign` FOREIGN KEY (`code_institution`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_ins_user_code_user_foreign` FOREIGN KEY (`code_user`) REFERENCES `tr_user` (`code_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_localisation`
--
ALTER TABLE `tr_localisation`
  ADD CONSTRAINT `tr_localisation_code_departement_foreign` FOREIGN KEY (`code_departement`) REFERENCES `tr_departement` (`code_departement`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_localite`
--
ALTER TABLE `tr_localite`
  ADD CONSTRAINT `tr_localite_code_localite_parent_foreign` FOREIGN KEY (`code_localite_parent`) REFERENCES `tr_localite` (`code_localite`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_localite_code_type_localite_foreign` FOREIGN KEY (`code_type_localite`) REFERENCES `tr_type_localite` (`code_type_localite`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_registre`
--
ALTER TABLE `tr_registre`
  ADD CONSTRAINT `tr_registre_approbation_tribunal_foreign` FOREIGN KEY (`approbation_tribunal`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_registre_cloture_cec_foreign` FOREIGN KEY (`cloture_cec`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_registre_code_type_registre_foreign` FOREIGN KEY (`code_type_registre`) REFERENCES `tr_type_registre` (`code_type_registre`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_registre_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_rubrique`
--
ALTER TABLE `tr_rubrique`
  ADD CONSTRAINT `tr_rubrique_code_type_acte_foreign` FOREIGN KEY (`code_type_acte`) REFERENCES `tr_type_acte` (`code_type_acte`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_tribunal`
--
ALTER TABLE `tr_tribunal`
  ADD CONSTRAINT `tr_tribunal_code_cour_appel_foreign` FOREIGN KEY (`code_cour_appel`) REFERENCES `tr_cour_appel` (`code_cour_appel`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_type_institution`
--
ALTER TABLE `tr_type_institution`
  ADD CONSTRAINT `tr_type_institution_code_type_categorie_ins_foreign` FOREIGN KEY (`code_type_categorie_ins`) REFERENCES `tr_type_categorie_ins` (`code_type_categorie_ins`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_uf`
--
ALTER TABLE `tr_uf`
  ADD CONSTRAINT `tr_uf_code_fonctionnalite_foreign` FOREIGN KEY (`code_fonctionnalite`) REFERENCES `tr_fonctionnalite` (`code_fonctionnalite`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_uf_code_user_foreign` FOREIGN KEY (`code_user`) REFERENCES `tr_user` (`code_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_user`
--
ALTER TABLE `tr_user`
  ADD CONSTRAINT `tr_user_code_personne_foreign` FOREIGN KEY (`code_personne`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_acte_deces`
--
ALTER TABLE `t_acte_deces`
  ADD CONSTRAINT `t_acte_deces_approbation_pompe_funebre_foreign` FOREIGN KEY (`approbation_pompe_funebre`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_deces_code_declaration_deces_foreign` FOREIGN KEY (`code_declaration_deces`) REFERENCES `t_declaration_deces` (`code_declaration_deces`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_deces_code_institution_foreign` FOREIGN KEY (`code_institution`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_deces_code_registre_foreign` FOREIGN KEY (`code_registre`) REFERENCES `tr_registre` (`code_registre`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_deces_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_acte_mariage`
--
ALTER TABLE `t_acte_mariage`
  ADD CONSTRAINT `t_acte_mariage_approbation_mairie_foreign` FOREIGN KEY (`approbation_mairie`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_mariage_code_declaration_mariage_foreign` FOREIGN KEY (`code_declaration_mariage`) REFERENCES `t_declaration_mariage` (`code_declaration_mariage`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_mariage_code_institution_foreign` FOREIGN KEY (`code_institution`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_mariage_code_registre_foreign` FOREIGN KEY (`code_registre`) REFERENCES `tr_registre` (`code_registre`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_mariage_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_acte_naissance`
--
ALTER TABLE `t_acte_naissance`
  ADD CONSTRAINT `t_acte_naissance_approbation_mairie_foreign` FOREIGN KEY (`approbation_mairie`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_naissance_code_declaration_naissance_foreign` FOREIGN KEY (`code_declaration_naissance`) REFERENCES `t_declaration_naissance` (`code_declaration_naissance`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_naissance_code_institution_foreign` FOREIGN KEY (`code_institution`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_naissance_code_registre_foreign` FOREIGN KEY (`code_registre`) REFERENCES `tr_registre` (`code_registre`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_acte_naissance_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_api_headers`
--
ALTER TABLE `t_api_headers`
  ADD CONSTRAINT `t_api_headers_code_providers_foreign` FOREIGN KEY (`code_providers`) REFERENCES `tr_sms_providers` (`code_providers`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_api_params`
--
ALTER TABLE `t_api_params`
  ADD CONSTRAINT `t_api_params_code_providers_foreign` FOREIGN KEY (`code_providers`) REFERENCES `tr_sms_providers` (`code_providers`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_contact_personne`
--
ALTER TABLE `t_contact_personne`
  ADD CONSTRAINT `t_contact_personne_code_personne_foreign` FOREIGN KEY (`code_personne`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_copie`
--
ALTER TABLE `t_copie`
  ADD CONSTRAINT `t_copie_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_ddecescause`
--
ALTER TABLE `t_ddecescause`
  ADD CONSTRAINT `t_ddecescause_code_cause_deces_foreign` FOREIGN KEY (`code_cause_deces`) REFERENCES `tr_cause_deces` (`code_cause_deces`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_ddecescause_code_declaration_deces_foreign` FOREIGN KEY (`code_declaration_deces`) REFERENCES `t_declaration_deces` (`code_declaration_deces`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_declaration_deces`
--
ALTER TABLE `t_declaration_deces`
  ADD CONSTRAINT `t_declaration_deces_cec_approuve_par_foreign` FOREIGN KEY (`cec_approuve_par`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_cause_deces_foreign` FOREIGN KEY (`code_cause_deces`) REFERENCES `tr_cause_deces` (`code_cause_deces`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_conjoint_foreign` FOREIGN KEY (`code_conjoint`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_declarant_foreign` FOREIGN KEY (`code_declarant`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_defunt_foreign` FOREIGN KEY (`code_defunt`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_document_foreign` FOREIGN KEY (`code_document`) REFERENCES `t_document` (`code_document`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_filiation_foreign` FOREIGN KEY (`code_filiation`) REFERENCES `tr_filiation` (`code_filiation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_institution_destinataire_foreign` FOREIGN KEY (`code_institution_destinataire`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_institution_foreign` FOREIGN KEY (`code_institution`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_jugement_foreign` FOREIGN KEY (`code_jugement`) REFERENCES `t_jugement` (`code_jugement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_lieu_survenance_foreign` FOREIGN KEY (`code_lieu_survenance`) REFERENCES `tr_lieu_survenance` (`code_lieu_survenance`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_mere_foreign` FOREIGN KEY (`code_mere`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_pere_foreign` FOREIGN KEY (`code_pere`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_regime_foreign` FOREIGN KEY (`code_regime`) REFERENCES `tr_regime` (`code_regime`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_religion_foreign` FOREIGN KEY (`code_religion`) REFERENCES `tr_religion` (`code_religion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_requisition_foreign` FOREIGN KEY (`code_requisition`) REFERENCES `t_requisition` (`code_requisition`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_situation_matrimoniale_foreign` FOREIGN KEY (`code_situation_matrimoniale`) REFERENCES `tr_situation_matrimoniale` (`code_situation_matrimoniale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_code_user_institution_foreign` FOREIGN KEY (`code_user_institution`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_deces_tribunal_approuve_par_foreign` FOREIGN KEY (`tribunal_approuve_par`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_declaration_mariage`
--
ALTER TABLE `t_declaration_mariage`
  ADD CONSTRAINT `t_declaration_mariage_cec_approuve_par_foreign` FOREIGN KEY (`cec_approuve_par`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_epouse_foreign` FOREIGN KEY (`code_epouse`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_epoux_foreign` FOREIGN KEY (`code_epoux`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_filiation_chef_famille_foreign` FOREIGN KEY (`code_filiation_chef_famille`) REFERENCES `tr_filiation` (`code_filiation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_institution_destinataire_foreign` FOREIGN KEY (`code_institution_destinataire`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_institution_foreign` FOREIGN KEY (`code_institution`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_option_mariage_foreign` FOREIGN KEY (`code_option_mariage`) REFERENCES `tr_option_mariage` (`code_option_mariage`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_profession_epouse_foreign` FOREIGN KEY (`code_profession_epouse`) REFERENCES `tr_profession` (`code_profession`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_profession_epoux_foreign` FOREIGN KEY (`code_profession_epoux`) REFERENCES `tr_profession` (`code_profession`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_profession_temoin_f_epouse_foreign` FOREIGN KEY (`code_profession_temoin_f_epouse`) REFERENCES `tr_profession` (`code_profession`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_profession_temoin_f_epoux_foreign` FOREIGN KEY (`code_profession_temoin_f_epoux`) REFERENCES `tr_profession` (`code_profession`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_profession_temoin_h_epouse_foreign` FOREIGN KEY (`code_profession_temoin_h_epouse`) REFERENCES `tr_profession` (`code_profession`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_profession_temoin_h_epoux_foreign` FOREIGN KEY (`code_profession_temoin_h_epoux`) REFERENCES `tr_profession` (`code_profession`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_regime_foreign` FOREIGN KEY (`code_regime`) REFERENCES `tr_regime` (`code_regime`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_situation_mat_epouse_foreign` FOREIGN KEY (`code_situation_mat_epouse`) REFERENCES `tr_situation_matrimoniale` (`code_situation_matrimoniale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_situation_mat_epoux_foreign` FOREIGN KEY (`code_situation_mat_epoux`) REFERENCES `tr_situation_matrimoniale` (`code_situation_matrimoniale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_temoin_femme_epouse_foreign` FOREIGN KEY (`code_temoin_femme_epouse`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_temoin_femme_epoux_foreign` FOREIGN KEY (`code_temoin_femme_epoux`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_temoin_homme_epouse_foreign` FOREIGN KEY (`code_temoin_homme_epouse`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_code_temoin_homme_epoux_foreign` FOREIGN KEY (`code_temoin_homme_epoux`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_mariage_tribunal_approuve_par_foreign` FOREIGN KEY (`tribunal_approuve_par`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_declaration_naissance`
--
ALTER TABLE `t_declaration_naissance`
  ADD CONSTRAINT `t_declaration_naissance_cec_approuve_par_foreign` FOREIGN KEY (`cec_approuve_par`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_adoptant_foreign` FOREIGN KEY (`code_adoptant`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_declarant_foreign` FOREIGN KEY (`code_declarant`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_enfant_foreign` FOREIGN KEY (`code_enfant`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_filiation_foreign` FOREIGN KEY (`code_filiation`) REFERENCES `tr_filiation` (`code_filiation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_institution_destinataire_foreign` FOREIGN KEY (`code_institution_destinataire`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_jugement_foreign` FOREIGN KEY (`code_jugement`) REFERENCES `t_jugement` (`code_jugement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_lieu_survenance_foreign` FOREIGN KEY (`code_lieu_survenance`) REFERENCES `tr_lieu_survenance` (`code_lieu_survenance`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_mere_foreign` FOREIGN KEY (`code_mere`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_pere_foreign` FOREIGN KEY (`code_pere`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_situation_mat_foreign` FOREIGN KEY (`code_situation_mat`) REFERENCES `tr_situation_matrimoniale` (`code_situation_matrimoniale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_code_user_institution_foreign` FOREIGN KEY (`code_user_institution`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_declaration_naissance_tribunal_approuve_par_foreign` FOREIGN KEY (`tribunal_approuve_par`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_demande_document`
--
ALTER TABLE `t_demande_document`
  ADD CONSTRAINT `t_demande_document_code_type_document_demande_foreign` FOREIGN KEY (`code_type_document_demande`) REFERENCES `tr_type_document_demande` (`code_type_document_demande`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_detail_livret`
--
ALTER TABLE `t_detail_livret`
  ADD CONSTRAINT `t_detail_livret_code_enfant_foreign` FOREIGN KEY (`code_enfant`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_detail_livret_code_livret_famille_foreign` FOREIGN KEY (`code_livret_famille`) REFERENCES `t_livret_famille` (`code_livret_famille`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_detail_livret_code_type_extrait_foreign` FOREIGN KEY (`code_type_extrait`) REFERENCES `tr_type_extrait` (`code_type_extrait`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_detail_rectification`
--
ALTER TABLE `t_detail_rectification`
  ADD CONSTRAINT `t_detail_rectification_code_rectification_foreign` FOREIGN KEY (`code_rectification`) REFERENCES `t_rectification` (`code_rectification`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_detail_rectification_code_rubrique_foreign` FOREIGN KEY (`code_rubrique`) REFERENCES `tr_rubrique` (`code_rubrique`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_document`
--
ALTER TABLE `t_document`
  ADD CONSTRAINT `t_document_code_personne_foreign` FOREIGN KEY (`code_personne`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_document_code_type_document_foreign` FOREIGN KEY (`code_type_document`) REFERENCES `tr_type_document` (`code_type_document`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_extrait`
--
ALTER TABLE `t_extrait`
  ADD CONSTRAINT `t_extrait_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_ins_user_localite`
--
ALTER TABLE `t_ins_user_localite`
  ADD CONSTRAINT `t_ins_user_localite_code_localite_foreign` FOREIGN KEY (`code_localite`) REFERENCES `tr_localite` (`code_localite`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_ins_user_localite_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON UPDATE CASCADE;

--
-- Constraints for table `t_jugement`
--
ALTER TABLE `t_jugement`
  ADD CONSTRAINT `t_jugement_code_institution_foreign` FOREIGN KEY (`code_institution`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_jugement_code_type_jugement_foreign` FOREIGN KEY (`code_type_jugement`) REFERENCES `tr_type_jugement` (`code_type_jugement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_jugement_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_livret_famille`
--
ALTER TABLE `t_livret_famille`
  ADD CONSTRAINT `t_livret_famille_code_declaration_mariage_foreign` FOREIGN KEY (`code_declaration_mariage`) REFERENCES `t_declaration_mariage` (`code_declaration_mariage`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_mouvement_deces`
--
ALTER TABLE `t_mouvement_deces`
  ADD CONSTRAINT `t_mouvement_deces_code_declaration_deces_foreign` FOREIGN KEY (`code_declaration_deces`) REFERENCES `t_declaration_deces` (`code_declaration_deces`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_deces_code_mouvement_foreign` FOREIGN KEY (`code_mouvement`) REFERENCES `tr_mouvement` (`code_mouvement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_deces_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_mouvement_dossier`
--
ALTER TABLE `t_mouvement_dossier`
  ADD CONSTRAINT `t_mouvement_dossier_code_mouvement_foreign` FOREIGN KEY (`code_mouvement`) REFERENCES `tr_mouvement` (`code_mouvement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_dossier_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_mouvement_mariage`
--
ALTER TABLE `t_mouvement_mariage`
  ADD CONSTRAINT `t_mouvement_mariage_code_declaration_mariage_foreign` FOREIGN KEY (`code_declaration_mariage`) REFERENCES `t_declaration_mariage` (`code_declaration_mariage`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_mariage_code_institution_destinataire_foreign` FOREIGN KEY (`code_institution_destinataire`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_mariage_code_mouvement_foreign` FOREIGN KEY (`code_mouvement`) REFERENCES `tr_mouvement` (`code_mouvement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_mariage_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_mouvement_naissance`
--
ALTER TABLE `t_mouvement_naissance`
  ADD CONSTRAINT `t_mouvement_naissance_code_declaration_naissance_foreign` FOREIGN KEY (`code_declaration_naissance`) REFERENCES `t_declaration_naissance` (`code_declaration_naissance`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_naissance_code_institution_destinataire_foreign` FOREIGN KEY (`code_institution_destinataire`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_naissance_code_mouvement_foreign` FOREIGN KEY (`code_mouvement`) REFERENCES `tr_mouvement` (`code_mouvement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_naissance_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_mouvement_rectification`
--
ALTER TABLE `t_mouvement_rectification`
  ADD CONSTRAINT `t_mouvement_rectification_code_institution_destinataire_foreign` FOREIGN KEY (`code_institution_destinataire`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_rectification_code_mouvement_foreign` FOREIGN KEY (`code_mouvement`) REFERENCES `tr_mouvement` (`code_mouvement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_rectification_code_rectification_foreign` FOREIGN KEY (`code_rectification`) REFERENCES `t_rectification` (`code_rectification`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_mouvement_rectification_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_paiement_document`
--
ALTER TABLE `t_paiement_document`
  ADD CONSTRAINT `t_paiement_document_code_demande_document_foreign` FOREIGN KEY (`code_demande_document`) REFERENCES `t_demande_document` (`code_demande_document`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_paiement_document_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_personne_sitmat`
--
ALTER TABLE `t_personne_sitmat`
  ADD CONSTRAINT `t_personne_sitmat_code_personne_foreign` FOREIGN KEY (`code_personne`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_personne_sitmat_code_situation_matrimoniale_foreign` FOREIGN KEY (`code_situation_matrimoniale`) REFERENCES `tr_situation_matrimoniale` (`code_situation_matrimoniale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_rectification`
--
ALTER TABLE `t_rectification`
  ADD CONSTRAINT `t_rectification_code_filiation_foreign` FOREIGN KEY (`code_filiation`) REFERENCES `tr_filiation` (`code_filiation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_rectification_code_institution_destinataire_foreign` FOREIGN KEY (`code_institution_destinataire`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_rectification_code_institution_foreign` FOREIGN KEY (`code_institution`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_rectification_code_requisition_foreign` FOREIGN KEY (`code_requisition`) REFERENCES `t_requisition` (`code_requisition`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_rectification_code_type_acte_foreign` FOREIGN KEY (`code_type_acte`) REFERENCES `tr_type_acte` (`code_type_acte`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_rectification_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_requisition`
--
ALTER TABLE `t_requisition`
  ADD CONSTRAINT `t_requisition_code_institution_foreign` FOREIGN KEY (`code_institution`) REFERENCES `tr_institution` (`code_institution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_requisition_code_type_requisition_foreign` FOREIGN KEY (`code_type_requisition`) REFERENCES `tr_type_requisition` (`code_type_requisition`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_requisition_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_residence_personne`
--
ALTER TABLE `t_residence_personne`
  ADD CONSTRAINT `t_residence_personne_code_localite_foreign` FOREIGN KEY (`code_localite`) REFERENCES `tr_localite` (`code_localite`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_residence_personne_code_personne_foreign` FOREIGN KEY (`code_personne`) REFERENCES `tr_identification_personne` (`code_personne`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_retrait_acte`
--
ALTER TABLE `t_retrait_acte`
  ADD CONSTRAINT `t_retrait_acte_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_signature_mariage`
--
ALTER TABLE `t_signature_mariage`
  ADD CONSTRAINT `t_signature_mariage_code_declaration_mariage_foreign` FOREIGN KEY (`code_declaration_mariage`) REFERENCES `t_declaration_mariage` (`code_declaration_mariage`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_sms_templates`
--
ALTER TABLE `t_sms_templates`
  ADD CONSTRAINT `t_sms_templates_code_action_foreign` FOREIGN KEY (`code_action`) REFERENCES `t_action` (`code_action`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_tarification`
--
ALTER TABLE `t_tarification`
  ADD CONSTRAINT `t_tarification_code_type_document_demande_foreign` FOREIGN KEY (`code_type_document_demande`) REFERENCES `tr_type_document_demande` (`code_type_document_demande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_tarification_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_user_arrondissement`
--
ALTER TABLE `t_user_arrondissement`
  ADD CONSTRAINT `t_user_arrondissement_code_arrondissement_foreign` FOREIGN KEY (`code_arrondissement`) REFERENCES `tr_arrondissement` (`code_arrondissement`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_user_arrondissement_cui_foreign` FOREIGN KEY (`cui`) REFERENCES `tr_ins_user` (`cui`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
