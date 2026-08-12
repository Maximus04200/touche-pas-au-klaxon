<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Trajet;

/**
 * Page d'accueil : liste des trajets a venir avec places disponibles.
 */
final class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('home/index', [
            'trajets' => Trajet::upcomingWithSeats(),
        ]);
    }
}
