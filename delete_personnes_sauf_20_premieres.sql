-- Script SQL pour supprimer toutes les personnes de tr_identification_personne
-- SAUF les 20 premières personnes
-- Les suppressions en cascade s'occuperont automatiquement de :
-- - t_contact_personne
-- - t_residence_personne
-- - t_declaration_naissance (si les contraintes le permettent)

-- Désactiver temporairement les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Créer une table temporaire pour stocker les codes des 20 premières personnes à conserver
CREATE TEMPORARY TABLE IF NOT EXISTS temp_personnes_a_conserver (
    code_personne VARCHAR(16) BINARY PRIMARY KEY
);

-- Insérer les 20 premières personnes à conserver
-- Option 1: Par code_personne (les 20 premiers codes numériquement) - RECOMMANDÉ
INSERT INTO temp_personnes_a_conserver (code_personne)
SELECT code_personne
FROM tr_identification_personne
ORDER BY code_personne ASC
LIMIT 20;

-- Option 2: Par date de création (les 20 premières créées) - DÉCOMMENTER si nécessaire
-- INSERT INTO temp_personnes_a_conserver (code_personne)
-- SELECT code_personne
-- FROM tr_identification_personne
-- ORDER BY created_at ASC
-- LIMIT 20;

-- Afficher les personnes qui seront conservées (pour vérification)
SELECT 'Personnes à conserver:' AS info;
SELECT code_personne, nom, prenom FROM tr_identification_personne
WHERE code_personne IN (SELECT code_personne FROM temp_personnes_a_conserver)
ORDER BY code_personne;

-- Afficher le nombre de personnes à supprimer (pour vérification)
SELECT COUNT(*) AS nombre_personnes_a_supprimer
FROM tr_identification_personne
WHERE code_personne NOT IN (SELECT code_personne FROM temp_personnes_a_conserver);

-- Supprimer toutes les personnes SAUF les 20 premières
-- Utiliser LEFT JOIN (méthode plus fiable avec MySQL)
DELETE p FROM tr_identification_personne p
LEFT JOIN temp_personnes_a_conserver t ON BINARY p.code_personne = BINARY t.code_personne
WHERE t.code_personne IS NULL;

-- Supprimer la table temporaire
DROP TEMPORARY TABLE IF EXISTS temp_personnes_a_conserver;

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Afficher le nombre final de personnes restantes
SELECT COUNT(*) AS nombre_personnes_restantes FROM tr_identification_personne;

