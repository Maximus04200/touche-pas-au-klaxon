DROP TABLE IF EXISTS trajet;
DROP TABLE IF EXISTS agence;
DROP TABLE IF EXISTS utilisateur;

CREATE TABLE agence (
    id_agence   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    ville       VARCHAR(100)    NOT NULL,
    PRIMARY KEY (id_agence),
    UNIQUE KEY uq_agence_ville (ville)
) ENGINE = InnoDB;

CREATE TABLE utilisateur (
    id_utilisateur  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nom             VARCHAR(100)    NOT NULL,
    prenom          VARCHAR(100)    NOT NULL,
    email           VARCHAR(190)    NOT NULL,
    telephone       VARCHAR(20)     NOT NULL,
    mot_de_passe    VARCHAR(255)    NOT NULL,
    role            ENUM('employe', 'admin') NOT NULL DEFAULT 'employe',
    actif           TINYINT(1)      NOT NULL DEFAULT 1,
    PRIMARY KEY (id_utilisateur),
    UNIQUE KEY uq_utilisateur_email (email)
) ENGINE = InnoDB;

CREATE TABLE trajet (
    id_trajet           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_agence_depart    INT UNSIGNED    NOT NULL,
    id_agence_arrivee   INT UNSIGNED    NOT NULL,
    date_heure_depart   DATETIME        NOT NULL,
    date_heure_arrivee  DATETIME        NOT NULL,
    nb_places_total     TINYINT UNSIGNED NOT NULL,
    nb_places_dispo     TINYINT UNSIGNED NOT NULL,
    id_utilisateur      INT UNSIGNED    NOT NULL,
    date_creation       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_trajet),
    KEY idx_trajet_date_depart (date_heure_depart),
    KEY idx_trajet_agence_depart (id_agence_depart),
    KEY idx_trajet_agence_arrivee (id_agence_arrivee),
    KEY idx_trajet_utilisateur (id_utilisateur),
    CONSTRAINT fk_trajet_agence_depart
        FOREIGN KEY (id_agence_depart) REFERENCES agence (id_agence)
        ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_trajet_agence_arrivee
        FOREIGN KEY (id_agence_arrivee) REFERENCES agence (id_agence)
        ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_trajet_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)
        ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT chk_trajet_agences_differentes
        CHECK (id_agence_depart <> id_agence_arrivee),
    CONSTRAINT chk_trajet_dates_coherentes
        CHECK (date_heure_arrivee > date_heure_depart),
    CONSTRAINT chk_trajet_places_coherentes
        CHECK (nb_places_dispo <= nb_places_total)
) ENGINE = InnoDB;
