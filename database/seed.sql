USE touche_pas_au_klaxon;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE trajet;
TRUNCATE TABLE utilisateur;
TRUNCATE TABLE agence;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO agence (id_agence, ville) VALUES
    (1,  'Paris'),
    (2,  'Lyon'),
    (3,  'Marseille'),
    (4,  'Toulouse'),
    (5,  'Nice'),
    (6,  'Nantes'),
    (7,  'Strasbourg'),
    (8,  'Montpellier'),
    (9,  'Bordeaux'),
    (10, 'Lille'),
    (11, 'Rennes'),
    (12, 'Reims');

INSERT INTO utilisateur
    (id_utilisateur, nom, prenom, email, telephone, mot_de_passe, role, actif)
VALUES
    (1,  'Admin',     'Systeme',  'admin@klaxon.local',        '0100000000',
         '$2y$10$TizUTY5axjY6R7HBdONLFujqTlHsSpcDVl9RlUElCy0P4.kPKokhu', 'admin', 1),
    (2,  'Martin',    'Alexandre', 'alexandre.martin@email.fr', '0612345678',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (3,  'Dubois',    'Sophie',    'sophie.dubois@email.fr',    '0698765432',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (4,  'Bernard',   'Julien',    'julien.bernard@email.fr',   '0622446688',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (5,  'Moreau',    'Camille',   'camille.moreau@email.fr',   '0611223344',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (6,  'Lefevre',   'Lucie',     'lucie.lefevre@email.fr',    '0777889900',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (7,  'Leroy',     'Thomas',    'thomas.leroy@email.fr',     '0655443322',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (8,  'Roux',      'Chloe',     'chloe.roux@email.fr',       '0633221199',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (9,  'Petit',     'Maxime',    'maxime.petit@email.fr',     '0766778899',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (10, 'Garnier',   'Laura',     'laura.garnier@email.fr',    '0688776655',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (11, 'Dupuis',    'Antoine',   'antoine.dupuis@email.fr',   '0744556677',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (12, 'Lefebvre',  'Emma',      'emma.lefebvre@email.fr',    '0699887766',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (13, 'Fontaine',  'Louis',     'louis.fontaine@email.fr',   '0655667788',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (14, 'Chevalier', 'Clara',     'clara.chevalier@email.fr',  '0788990011',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (15, 'Robin',     'Nicolas',   'nicolas.robin@email.fr',    '0644332211',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (16, 'Gauthier',  'Marine',    'marine.gauthier@email.fr',  '0677889922',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (17, 'Fournier',  'Pierre',    'pierre.fournier@email.fr',  '0722334455',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (18, 'Girard',    'Sarah',     'sarah.girard@email.fr',     '0688665544',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (19, 'Lambert',   'Hugo',      'hugo.lambert@email.fr',     '0611223366',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (20, 'Masson',    'Julie',     'julie.masson@email.fr',     '0733445566',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1),
    (21, 'Henry',     'Arthur',    'arthur.henry@email.fr',     '0666554433',
         '$2y$10$6PEwhzTwHyQVcdXqKito1.neAo949DyH40K42Ot0/9DIDShZXqRDC', 'employe', 1);

INSERT INTO trajet
    (id_agence_depart, id_agence_arrivee, date_heure_depart, date_heure_arrivee,
     nb_places_total, nb_places_dispo, id_utilisateur)
VALUES
    (1, 2,  DATE_ADD(NOW(), INTERVAL 1 DAY),  DATE_ADD(NOW(), INTERVAL 1 DAY) + INTERVAL 5 HOUR,  4, 2, 2),
    (2, 3,  DATE_ADD(NOW(), INTERVAL 2 DAY),  DATE_ADD(NOW(), INTERVAL 2 DAY) + INTERVAL 3 HOUR,  3, 1, 3),
    (1, 10, DATE_ADD(NOW(), INTERVAL 3 DAY),  DATE_ADD(NOW(), INTERVAL 3 DAY) + INTERVAL 2 HOUR,  4, 4, 4),
    (9, 6,  DATE_ADD(NOW(), INTERVAL 4 DAY),  DATE_ADD(NOW(), INTERVAL 4 DAY) + INTERVAL 3 HOUR,  2, 1, 5),
    (4, 1,  DATE_ADD(NOW(), INTERVAL 5 DAY),  DATE_ADD(NOW(), INTERVAL 5 DAY) + INTERVAL 6 HOUR,  5, 3, 6),
    (7, 2,  DATE_ADD(NOW(), INTERVAL 6 DAY),  DATE_ADD(NOW(), INTERVAL 6 DAY) + INTERVAL 4 HOUR,  3, 3, 7),
    (5, 8,  DATE_ADD(NOW(), INTERVAL 8 DAY),  DATE_ADD(NOW(), INTERVAL 8 DAY) + INTERVAL 4 HOUR,  4, 2, 9),
    (11, 1, DATE_ADD(NOW(), INTERVAL 9 DAY),  DATE_ADD(NOW(), INTERVAL 9 DAY) + INTERVAL 4 HOUR,  3, 3, 12),
    (3, 1, DATE_ADD(NOW(), INTERVAL 2 DAY),  DATE_ADD(NOW(), INTERVAL 2 DAY) + INTERVAL 3 HOUR,  2, 0, 8),
    (1, 3, DATE_SUB(NOW(), INTERVAL 5 DAY),  DATE_SUB(NOW(), INTERVAL 5 DAY) + INTERVAL 3 HOUR,  4, 2, 10),
    (6, 1, DATE_ADD(NOW(), INTERVAL 7 DAY),  DATE_ADD(NOW(), INTERVAL 7 DAY) + INTERVAL 4 HOUR,  4, 1, 11),
    (1, 9, DATE_ADD(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 10 DAY) + INTERVAL 5 HOUR, 3, 2, 2);
