<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Acces centralise a la connexion PDO (singleton par requete).
 *
 * Toutes les requetes de l'application passent par des requetes
 * preparees via cette connexion : aucune concatenation de valeurs
 * utilisateur dans du SQL n'est autorisee ailleurs dans le code.
 */
final class Database
{
    private static ?PDO $instance = null;

    /** @var array<string, mixed> */
    private static array $config = [];

    /**
     * @param array<string, mixed> $config
     */
    public static function configure(array $config): void
    {
        self::$config = $config;
    }

    public static function connection(): PDO
    {
        if (self::$instance === null) {
            $config = self::$config;
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $config['host'] ?? '127.0.0.1',
                $config['port'] ?? '3306',
                $config['database'] ?? ''
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['username'] ?? '',
                    $config['password'] ?? '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $exception) {
                // Ne jamais exposer le DSN/les identifiants au client.
                throw new RuntimeException('Connexion a la base de donnees impossible.', 0, $exception);
            }
        }

        return self::$instance;
    }
}
