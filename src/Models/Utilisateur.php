<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

/**
 * Acces aux donnees de la table `utilisateur`.
 *
 * Les utilisateurs proviennent d'un export RH : aucune methode de
 * creation/modification/suppression n'est exposee depuis l'application.
 */
final class Utilisateur
{
    /**
     * @return array<string, mixed>|null
     */
    public static function findById(int $id): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT id_utilisateur, nom, prenom, email, telephone, mot_de_passe, role, actif
             FROM utilisateur WHERE id_utilisateur = :id'
        );
        $statement->execute(['id' => $id]);

        $utilisateur = $statement->fetch();

        return $utilisateur === false ? null : $utilisateur;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findByEmail(string $email): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT id_utilisateur, nom, prenom, email, telephone, mot_de_passe, role, actif
             FROM utilisateur WHERE email = :email'
        );
        $statement->execute(['email' => $email]);

        $utilisateur = $statement->fetch();

        return $utilisateur === false ? null : $utilisateur;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        $statement = Database::connection()->prepare(
            'SELECT id_utilisateur, nom, prenom, email, telephone, role, actif
             FROM utilisateur ORDER BY nom ASC, prenom ASC'
        );
        $statement->execute();

        return $statement->fetchAll();
    }
}
