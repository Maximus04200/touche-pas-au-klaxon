-- =====================================================================
-- Touche pas au klaxon — creation de la base de donnees
-- A executer une seule fois, avec un compte disposant du privilege
-- CREATE (ex : root ou un compte d'administration MySQL/MariaDB) :
--   mysql -u root -p < create_database.sql
-- =====================================================================

CREATE DATABASE IF NOT EXISTS touche_pas_au_klaxon
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
