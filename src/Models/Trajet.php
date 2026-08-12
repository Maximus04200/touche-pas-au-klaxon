<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

/**
 * Acces aux donnees de la table `trajet`.
 *
 * Les methodes de lecture renvoient des lignes deja jointes aux
 * agences (villes) et, pour le detail, a l'auteur/contact.
 */
final class Trajet
{
    private const SELECT_BASE = '
        SELECT
            t.id_trajet, t.date_heure_depart, t.date_heure_arrivee,
            t.nb_places_total, t.nb_places_dispo, t.id_utilisateur,
            ad.id_agence AS id_agence_depart, ad.ville AS ville_depart,
            aa.id_agence AS id_agence_arrivee, aa.ville AS ville_arrivee
        FROM trajet t
        INNER JOIN agence ad ON ad.id_agence = t.id_agence_depart
        INNER JOIN agence aa ON aa.id_agence = t.id_agence_arrivee
    ';

    /**
     * Trajets a venir avec au moins une place disponible, tries par
     * date de depart croissante (page d'accueil).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function upcomingWithSeats(): array
    {
        $statement = Database::connection()->prepare(
            self::SELECT_BASE . '
            WHERE t.date_heure_depart > NOW() AND t.nb_places_dispo > 0
            ORDER BY t.date_heure_depart ASC'
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Detail complet d'un trajet, avec les coordonnees de l'auteur/contact.
     *
     * @return array<string, mixed>|null
     */
    public static function findWithDetails(int $id): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT
                t.id_trajet, t.date_heure_depart, t.date_heure_arrivee,
                t.nb_places_total, t.nb_places_dispo, t.id_utilisateur,
                ad.id_agence AS id_agence_depart, ad.ville AS ville_depart,
                aa.id_agence AS id_agence_arrivee, aa.ville AS ville_arrivee,
                u.nom AS auteur_nom, u.prenom AS auteur_prenom,
                u.email AS auteur_email, u.telephone AS auteur_telephone
            FROM trajet t
            INNER JOIN agence ad ON ad.id_agence = t.id_agence_depart
            INNER JOIN agence aa ON aa.id_agence = t.id_agence_arrivee
            INNER JOIN utilisateur u ON u.id_utilisateur = t.id_utilisateur
            WHERE t.id_trajet = :id'
        );
        $statement->execute(['id' => $id]);

        $trajet = $statement->fetch();

        return $trajet === false ? null : $trajet;
    }

    /**
     * Ensemble des trajets (passes et futurs), pour le tableau de bord admin.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function allForAdmin(): array
    {
        $statement = Database::connection()->prepare(
            self::SELECT_BASE . ' ORDER BY t.date_heure_depart DESC'
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @param array{
     *     id_agence_depart: int,
     *     id_agence_arrivee: int,
     *     date_heure_depart: string,
     *     date_heure_arrivee: string,
     *     nb_places_total: int,
     *     nb_places_dispo: int,
     *     id_utilisateur: int
     * } $data
     */
    public static function create(array $data): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO trajet
                (id_agence_depart, id_agence_arrivee, date_heure_depart, date_heure_arrivee,
                 nb_places_total, nb_places_dispo, id_utilisateur)
             VALUES
                (:id_agence_depart, :id_agence_arrivee, :date_heure_depart, :date_heure_arrivee,
                 :nb_places_total, :nb_places_dispo, :id_utilisateur)'
        );
        $statement->execute($data);

        return (int) Database::connection()->lastInsertId();
    }

    /**
     * @param array{
     *     id_agence_depart: int,
     *     id_agence_arrivee: int,
     *     date_heure_depart: string,
     *     date_heure_arrivee: string,
     *     nb_places_total: int,
     *     nb_places_dispo: int
     * } $data
     */
    public static function update(int $id, array $data): bool
    {
        $statement = Database::connection()->prepare(
            'UPDATE trajet SET
                id_agence_depart = :id_agence_depart,
                id_agence_arrivee = :id_agence_arrivee,
                date_heure_depart = :date_heure_depart,
                date_heure_arrivee = :date_heure_arrivee,
                nb_places_total = :nb_places_total,
                nb_places_dispo = :nb_places_dispo
             WHERE id_trajet = :id'
        );

        return $statement->execute($data + ['id' => $id]);
    }

    public static function delete(int $id): bool
    {
        $statement = Database::connection()->prepare(
            'DELETE FROM trajet WHERE id_trajet = :id'
        );

        return $statement->execute(['id' => $id]);
    }
}
