<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Utilisateur;

/**
 * Gestion de l'authentification et de la session utilisateur.
 */
final class Auth
{
    private const SESSION_KEY = 'utilisateur_id';

    /**
     * Tente une connexion par email/mot de passe.
     *
     * @return bool true si les identifiants sont valides
     */
    public static function attempt(string $email, string $password): bool
    {
        $utilisateur = Utilisateur::findByEmail($email);

        if ($utilisateur === null || (int) $utilisateur['actif'] !== 1) {
            return false;
        }

        if (!password_verify($password, $utilisateur['mot_de_passe'])) {
            return false;
        }

        // Regeneration de l'identifiant de session pour eviter la fixation de session.
        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = (int) $utilisateur['id_utilisateur'];

        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    public static function check(): bool
    {
        return isset($_SESSION[self::SESSION_KEY]);
    }

    public static function isAdmin(): bool
    {
        $utilisateur = self::user();

        return $utilisateur !== null && $utilisateur['role'] === 'admin';
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function user(): ?array
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            return null;
        }

        return Utilisateur::findById((int) $_SESSION[self::SESSION_KEY]);
    }

    public static function id(): ?int
    {
        return isset($_SESSION[self::SESSION_KEY]) ? (int) $_SESSION[self::SESSION_KEY] : null;
    }
}
