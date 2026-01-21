-- Script SQL pour créer la table t_feuillet_registre
-- Cette table est nécessaire pour la génération des actes de naissance

CREATE TABLE IF NOT EXISTS `t_feuillet_registre` (
  `code_feuillet_registre` VARCHAR(16) NOT NULL,
  `code_acte` VARCHAR(30) NOT NULL,
  `numero_acte` VARCHAR(30) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`code_feuillet_registre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

