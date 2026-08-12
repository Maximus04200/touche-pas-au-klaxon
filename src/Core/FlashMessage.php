<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Messages flash (succes/erreur) affiches une seule fois apres une
 * redirection consecutive a une operation d'ecriture.
 */
final class FlashMessage
{
    private const SESSION_KEY = 'flash_messages';

    public static function add(string $type, string $message): void
    {
        $_SESSION[self::SESSION_KEY][] = ['type' => $type, 'message' => $message];
    }

    public static function success(string $message): void
    {
        self::add('success', $message);
    }

    public static function error(string $message): void
    {
        self::add('danger', $message);
    }

    /**
     * Recupere et vide les messages en attente (affichage unique).
     *
     * @return array<int, array{type: string, message: string}>
     */
    public static function pull(): array
    {
        $messages = $_SESSION[self::SESSION_KEY] ?? [];
        unset($_SESSION[self::SESSION_KEY]);

        return $messages;
    }
}
