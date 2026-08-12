-- =====================================================================
-- Touche pas au klaxon — jeu d'essai
--
-- IMPORTANT : l'export RH reel et la liste reelle des agences sont
-- fournis en annexe du devoir et n'etaient pas disponibles au moment
-- de la redaction de ce script. Les donnees ci-dessous sont un jeu
-- d'essai FICTIF construit pour couvrir les cas de test attendus
-- (tri par date, trajet complet, trajet passe, etc.). A remplacer par
-- l'export RH reel avant mise en production.
--
-- Comptes de demonstration (mot de passe en clair, uniquement pour ce
-- jeu d'essai) :
--   Admin    : admin@klaxon.local     / Admin#2026
--   Employe  : j.dupont@klaxon.local  / Employe#2026
-- =====================================================================

USE touche_pas_au_klaxon;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE trajet;
TRUNCATE TABLE utilisateur;
TRUNCATE TABLE agence;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- Agences
-- ---------------------------------------------------------------------
INSERT INTO agence (id_agence, ville) VALUES
    (1, 'Paris'),
    (2, 'Lyon'),
    (3, 'Marseille'),
    (4, 'Lille'),
    (5, 'Bordeaux'),
    (6, 'Nantes'),
    (7, 'Toulouse'),
    (8, 'Strasbourg');

-- ---------------------------------------------------------------------
-- Utilisateurs
-- Hash bcrypt de "Admin#2026" et "Employe#2026" genere via
-- password_hash(..., PASSWORD_DEFAULT).
-- ---------------------------------------------------------------------
INSERT INTO utilisateur
    (id_utilisateur, nom, prenom, email, telephone, mot_de_passe, role, actif)
VALUES
    (1,  'Admin',     'Système',   'admin@klaxon.local',
         '0100000000',
         '$2y$10$TizUTY5axjY6R7HBdONLFujqTlHsSpcDVl9RlUElCy0P4.kPKokhu',
         'admin', 1),
    (2,  'Dupont',    'Jean',      'j.dupont@klaxon.local',
         '0601020304',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC',
         'employe', 1),
    (3,  'Martin',    'Claire',    'c.martin@klaxon.local',
         '0602030405',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC',
         'employe', 1),
    (4,  'Bernard',   'Sophie',    's.bernard@klaxon.local',
         '0603040506',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC',
         'employe', 1),
    (5,  'Petit',     'Nicolas',   'n.petit@klaxon.local',
         '0604050607',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC',
         'employe', 1),
    (6,  'Durand',    'Julie',     'j.durand@klaxon.local',
         '0605060708',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC',
         'employe', 1),
    (7,  'Leroy',     'Thomas',    't.leroy@klaxon.local',
         '0606070809',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC',
         'employe', 1),
    (8,  'Moreau',    'Emma',      'e.moreau@klaxon.local',
         '0607080910',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC',
         'employe', 1);

-- ---------------------------------------------------------------------
-- Trajets
-- Dates calculees par rapport a NOW() pour que le jeu d'essai reste
-- valide quelle que soit la date de chargement du script.
-- ---------------------------------------------------------------------
INSERT INTO trajet
    (id_agence_depart, id_agence_arrivee, date_heure_depart, date_heure_arrivee,
     nb_places_total, nb_places_dispo, id_utilisateur)
VALUES
    -- Trajets futurs avec places disponibles (doivent apparaitre, tries par date croissante)
    (1, 2, DATE_ADD(NOW(), INTERVAL 1 DAY),  DATE_ADD(NOW(), INTERVAL 1 DAY) + INTERVAL 5 HOUR,  4, 2, 2),
    (2, 3, DATE_ADD(NOW(), INTERVAL 2 DAY),  DATE_ADD(NOW(), INTERVAL 2 DAY) + INTERVAL 3 HOUR,  3, 1, 3),
    (1, 4, DATE_ADD(NOW(), INTERVAL 3 DAY),  DATE_ADD(NOW(), INTERVAL 3 DAY) + INTERVAL 2 HOUR,  4, 4, 4),
    (5, 6, DATE_ADD(NOW(), INTERVAL 4 DAY),  DATE_ADD(NOW(), INTERVAL 4 DAY) + INTERVAL 3 HOUR,  2, 1, 5),
    (7, 1, DATE_ADD(NOW(), INTERVAL 5 DAY),  DATE_ADD(NOW(), INTERVAL 5 DAY) + INTERVAL 6 HOUR,  5, 3, 6),
    (8, 2, DATE_ADD(NOW(), INTERVAL 6 DAY),  DATE_ADD(NOW(), INTERVAL 6 DAY) + INTERVAL 4 HOUR,  3, 3, 2),

    -- Trajet futur complet : ne doit PAS apparaitre sur la liste publique
    (3, 1, DATE_ADD(NOW(), INTERVAL 2 DAY),  DATE_ADD(NOW(), INTERVAL 2 DAY) + INTERVAL 3 HOUR,  2, 0, 7),

    -- Trajet passe avec places dispo : ne doit PAS apparaitre (date depassee)
    (1, 3, DATE_SUB(NOW(), INTERVAL 5 DAY),  DATE_SUB(NOW(), INTERVAL 5 DAY) + INTERVAL 3 HOUR,  4, 2, 8),

    -- Trajet avec une seule place restante (cas limite)
    (6, 1, DATE_ADD(NOW(), INTERVAL 7 DAY),  DATE_ADD(NOW(), INTERVAL 7 DAY) + INTERVAL 4 HOUR,  4, 1, 3),

    -- Trajet propose par le compte de demonstration (pour tester modification/suppression par l'auteur)
    (1, 5, DATE_ADD(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 10 DAY) + INTERVAL 5 HOUR, 3, 2, 2);
