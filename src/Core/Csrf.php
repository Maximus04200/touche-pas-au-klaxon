<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Protection CSRF par jeton unique en session, verifie sur chaque
 * requete qui modifie l'etat de l'application (POST).
 */
final class Csrf
{
    private const SESSION_KEY = 'csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function field(): string
    {
        return sprintf(
            '<input type="hidden" name="csrf_token" value="%s">',
            htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8')
        );
    }

    public static function validate(?string $token): bool
    {
        if (empty($_SESSION[self::SESSION_KEY]) || $token === null) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $token);
    }
}
