-- Script SQL pour supprimer les personnes créées par DeclarationNaissanceSeeder
-- Ce script supprime les personnes (enfant, père, mère, déclarant) liées aux déclarations de naissance
-- Les suppressions en cascade s'occuperont automatiquement de t_contact_personne et t_residence_personne

-- Désactiver temporairement les vérifications de clés étrangères (si nécessaire)
SET FOREIGN_KEY_CHECKS = 0;

-- Créer une table temporaire pour stocker les codes de personnes à supprimer
-- Utiliser BINARY pour éviter les problèmes de collation
CREATE TEMPORARY TABLE IF NOT EXISTS temp_personnes_a_supprimer (
    code_personne VARCHAR(16) BINARY PRIMARY KEY
);

-- Insérer tous les codes de personnes liées aux déclarations de naissance
INSERT INTO temp_personnes_a_supprimer (code_personne)
SELECT DISTINCT code_personne
FROM (
    SELECT code_enfant AS code_personne FROM t_declaration_naissance WHERE code_enfant IS NOT NULL
    UNION
    SELECT code_pere AS code_personne FROM t_declaration_naissance WHERE code_pere IS NOT NULL
    UNION
    SELECT code_mere AS code_personne FROM t_declaration_naissance WHERE code_mere IS NOT NULL
    UNION
    SELECT code_declarant AS code_personne FROM t_declaration_naissance WHERE code_declarant IS NOT NULL
    UNION
    SELECT code_adoptant AS code_personne FROM t_declaration_naissance WHERE code_adoptant IS NOT NULL
) AS personnes_a_supprimer
WHERE code_personne IS NOT NULL;

-- Afficher le nombre de personnes à supprimer (optionnel, pour vérification)
SELECT COUNT(*) AS nombre_personnes_a_supprimer FROM temp_personnes_a_supprimer;

-- Supprimer les personnes de la table principale
-- Les suppressions en cascade s'occuperont automatiquement de :
-- - t_contact_personne
-- - t_residence_personne
-- Utiliser BINARY pour forcer une comparaison binaire (évite les problèmes de collation)
DELETE p FROM tr_identification_personne p
INNER JOIN temp_personnes_a_supprimer t 
    ON BINARY p.code_personne = BINARY t.code_personne;

-- Supprimer la table temporaire
DROP TEMPORARY TABLE IF EXISTS temp_personnes_a_supprimer;

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Note: Les tables t_contact_personne et t_residence_personne seront automatiquement 
-- nettoyées grâce aux contraintes ON DELETE CASCADE définies dans les migrations.

