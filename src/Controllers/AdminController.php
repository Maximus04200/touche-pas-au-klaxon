<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\FlashMessage;
use App\Core\Validator;
use App\Models\Agence;
use App\Models\Trajet;
use App\Models\Utilisateur;

/**
 * Tableau de bord administrateur : utilisateurs (lecture seule),
 * agences (CRUD complet) et trajets (lecture + suppression).
 */
final class AdminController extends Controller
{
    public function dashboard(): void
    {
        $this->requireAdmin();

        $this->render('admin/dashboard', [
            'nbUtilisateurs' => count(Utilisateur::all()),
            'nbAgences' => count(Agence::all()),
            'nbTrajets' => count(Trajet::allForAdmin()),
        ]);
    }

    public function utilisateurs(): void
    {
        $this->requireAdmin();

        $this->render('admin/utilisateurs', [
            'utilisateurs' => Utilisateur::all(),
        ]);
    }

    public function agences(): void
    {
        $this->requireAdmin();

        $this->render('admin/agences', [
            'agences' => Agence::all(),
        ]);
    }

    public function storeAgence(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $ville = (string) $this->input('ville', '');

        $validator = new Validator(['ville' => $ville], ['ville' => ['required', 'max:100']]);

        if ($validator->fails() || Agence::villeExists($ville)) {
            FlashMessage::error(
                $validator->fails() ? ($validator->firstError() ?? 'Donnees invalides.') : 'Cette agence existe deja.'
            );
            $this->redirect('/admin/agences');
        }

        Agence::create($ville);

        FlashMessage::success('Agence creee avec succes.');
        $this->redirect('/admin/agences');
    }

    public function updateAgence(int $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $ville = (string) $this->input('ville', '');

        $validator = new Validator(['ville' => $ville], ['ville' => ['required', 'max:100']]);

        if ($validator->fails() || Agence::villeExists($ville, $id)) {
            FlashMessage::error(
                $validator->fails() ? ($validator->firstError() ?? 'Donnees invalides.') : 'Cette agence existe deja.'
            );
            $this->redirect('/admin/agences');
        }

        Agence::update($id, $ville);

        FlashMessage::success('Agence modifiee avec succes.');
        $this->redirect('/admin/agences');
    }

    public function destroyAgence(int $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        if (!Agence::delete($id)) {
            FlashMessage::error(
                'Impossible de supprimer cette agence : elle est utilisee par au moins un trajet.'
            );
            $this->redirect('/admin/agences');
        }

        FlashMessage::success('Agence supprimee avec succes.');
        $this->redirect('/admin/agences');
    }

    public function trajets(): void
    {
        $this->requireAdmin();

        $this->render('admin/trajets', [
            'trajets' => Trajet::allForAdmin(),
        ]);
    }

    public function destroyTrajet(int $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        Trajet::delete($id);

        FlashMessage::success('Le trajet a ete supprime.');
        $this->redirect('/admin/trajets');
    }
}
