<?php

/**
 * @var \Bramus\Router\Router $router
 */

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TrajetController;

$router->get('/', fn () => (new HomeController())->index());
$router->get('/login', fn () => (new AuthController())->showLogin());
$router->post('/login', fn () => (new AuthController())->login());
$router->post('/logout', fn () => (new AuthController())->logout());

$router->get('/trajets/creer', fn () => (new TrajetController())->create());
$router->post('/trajets/creer', fn () => (new TrajetController())->store());
$router->get('/trajets/(\d+)/details', fn (string $id) => (new TrajetController())->details((int) $id));
$router->get('/trajets/(\d+)/modifier', fn (string $id) => (new TrajetController())->edit((int) $id));
$router->post('/trajets/(\d+)/modifier', fn (string $id) => (new TrajetController())->update((int) $id));
$router->post('/trajets/(\d+)/supprimer', fn (string $id) => (new TrajetController())->destroy((int) $id));

$router->get('/admin', fn () => (new AdminController())->dashboard());
$router->get('/admin/utilisateurs', fn () => (new AdminController())->utilisateurs());
$router->get('/admin/agences', fn () => (new AdminController())->agences());
$router->post('/admin/agences/creer', fn () => (new AdminController())->storeAgence());
$router->post('/admin/agences/(\d+)/modifier', fn (string $id) => (new AdminController())->updateAgence((int) $id));
$router->post('/admin/agences/(\d+)/supprimer', fn (string $id) => (new AdminController())->destroyAgence((int) $id));
$router->get('/admin/trajets', fn () => (new AdminController())->trajets());
$router->post('/admin/trajets/(\d+)/supprimer', fn (string $id) => (new AdminController())->destroyTrajet((int) $id));
