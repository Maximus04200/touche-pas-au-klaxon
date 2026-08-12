<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Controleur de base : rendu de vues dans le layout commun,
 * redirections, acces aux entrees et garde-fous d'authentification.
 */
abstract class Controller
{
    private static string $viewsPath = __DIR__ . '/../../views/';

    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): void
    {
        $data['currentUser'] = Auth::user();
        $data['flashMessages'] = FlashMessage::pull();

        $viewFile = self::$viewsPath . $view . '.php';
        $layoutFile = self::$viewsPath . 'layout.php';

        $content = $this->capture($viewFile, $data);
        $data['content'] = $content;

        echo $this->capture($layoutFile, $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;

        return (string) ob_get_clean();
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . $path);
        exit;
    }

    /**
     * @return mixed
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        $value = $_POST[$key] ?? $default;

        return is_string($value) ? trim($value) : $value;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit;
    }

    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            FlashMessage::error('Vous devez etre connecte pour acceder a cette page.');
            $this->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();

        if (!Auth::isAdmin()) {
            http_response_code(403);
            echo 'Acces refuse.';
            exit;
        }
    }

    protected function verifyCsrf(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            FlashMessage::error('Votre session a expire, merci de reessayer.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }
}
