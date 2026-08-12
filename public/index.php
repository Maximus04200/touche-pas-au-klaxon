<?php

/**
 * Front controller : point d'entree unique de l'application.
 */

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Database;
use Bramus\Router\Router;

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

$config = require dirname(__DIR__) . '/config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', $config['app']['debug'] ? '1' : '0');

Database::configure($config['db']);

$router = new Router();

require dirname(__DIR__) . '/config/routes.php';

$router->set404(static function (): void {
    http_response_code(404);
    require dirname(__DIR__) . '/views/errors/404.php';
});

$router->run();
