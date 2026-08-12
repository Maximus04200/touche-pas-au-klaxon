<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

/**
 * Acces aux donnees de la table `agence`.
 *
 * Seul l'administrateur peut creer/modifier/supprimer une agence
 * (controle effectue au niveau du controleur, pas ici).
 */
final class Agence
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        $statement = Database::connection()->prepare(
            'SELECT id_agence, ville FROM agence ORDER BY ville ASC'
        );
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findById(int $id): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT id_agence, ville FROM agence WHERE id_agence = :id'
        );
        $statement->execute(['id' => $id]);

        $agence = $statement->fetch();

        return $agence === false ? null : $agence;
    }

    public static function villeExists(string $ville, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM agence WHERE ville = :ville';
        $params = ['ville' => $ville];

        if ($excludeId !== null) {
            $sql .= ' AND id_agence != :excludeId';
            $params['excludeId'] = $excludeId;
        }

        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }

    public static function create(string $ville): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO agence (ville) VALUES (:ville)'
        );
        $statement->execute(['ville' => $ville]);

        return (int) Database::connection()->lastInsertId();
    }

    public static function update(int $id, string $ville): bool
    {
        $statement = Database::connection()->prepare(
            'UPDATE agence SET ville = :ville WHERE id_agence = :id'
        );

        return $statement->execute(['ville' => $ville, 'id' => $id]);
    }

    /**
     * Supprime une agence. Renvoie false si l'agence est encore
     * referencee par au moins un trajet (contrainte d'integrite).
     */
    public static function delete(int $id): bool
    {
        try {
            $statement = Database::connection()->prepare(
                'DELETE FROM agence WHERE id_agence = :id'
            );

            return $statement->execute(['id' => $id]);
        } catch (PDOException $exception) {
            // Code SQLSTATE 23000 : violation de contrainte d'integrite (FK).
            if ($exception->getCode() === '23000') {
                return false;
            }

            throw $exception;
        }
    }
}
