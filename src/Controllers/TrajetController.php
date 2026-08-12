<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\FlashMessage;
use App\Core\Validator;
use App\Models\Agence;
use App\Models\Trajet;
use DateTime;

/**
 * Creation, consultation, modification et suppression des trajets
 * par les employes connectes.
 */
final class TrajetController extends Controller
{
    public function create(): void
    {
        $this->requireAuth();

        $this->render('trajet/create', [
            'agences' => Agence::all(),
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $userId = Auth::id();

        if ($userId === null) {
            $this->redirect('/login');
        }

        $errors = $this->validateTrajetInput($_POST);

        if ($errors !== []) {
            FlashMessage::error(reset($errors) ?: 'Donnees invalides.');
            $this->redirect('/trajets/creer');
        }

        $nbPlacesTotal = (int) $this->input('nb_places_total');

        Trajet::create([
            'id_agence_depart' => (int) $this->input('id_agence_depart'),
            'id_agence_arrivee' => (int) $this->input('id_agence_arrivee'),
            'date_heure_depart' => $this->toMysqlDateTime((string) $this->input('date_heure_depart')),
            'date_heure_arrivee' => $this->toMysqlDateTime((string) $this->input('date_heure_arrivee')),
            'nb_places_total' => $nbPlacesTotal,
            'nb_places_dispo' => $nbPlacesTotal,
            'id_utilisateur' => $userId,
        ]);

        FlashMessage::success('Le trajet a ete cree avec succes.');
        $this->redirect('/');
    }

    public function details(int $id): void
    {
        $this->requireAuth();

        $trajet = Trajet::findWithDetails($id);

        if ($trajet === null) {
            $this->json(['error' => 'Trajet introuvable.'], 404);
        }

        $this->json(['trajet' => $trajet]);
    }

    public function edit(int $id): void
    {
        $this->requireAuth();

        $trajet = $this->findEditableOrFail($id);

        $this->render('trajet/edit', [
            'trajet' => $trajet,
            'agences' => Agence::all(),
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->findEditableOrFail($id);

        $errors = $this->validateTrajetInput($_POST, requirePlacesDispo: true);

        if ($errors !== []) {
            FlashMessage::error(reset($errors) ?: 'Donnees invalides.');
            $this->redirect("/trajets/{$id}/modifier");
        }

        Trajet::update($id, [
            'id_agence_depart' => (int) $this->input('id_agence_depart'),
            'id_agence_arrivee' => (int) $this->input('id_agence_arrivee'),
            'date_heure_depart' => $this->toMysqlDateTime((string) $this->input('date_heure_depart')),
            'date_heure_arrivee' => $this->toMysqlDateTime((string) $this->input('date_heure_arrivee')),
            'nb_places_total' => (int) $this->input('nb_places_total'),
            'nb_places_dispo' => (int) $this->input('nb_places_dispo'),
        ]);

        FlashMessage::success('Le trajet a ete modifie avec succes.');
        $this->redirect('/');
    }

    public function destroy(int $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->findEditableOrFail($id);

        Trajet::delete($id);

        FlashMessage::success('Le trajet a ete supprime.');
        $this->redirect('/');
    }

    /**
     * @return array<string, mixed>
     */
    private function findEditableOrFail(int $id): array
    {
        $trajet = Trajet::findWithDetails($id);

        if ($trajet === null) {
            http_response_code(404);
            echo 'Trajet introuvable.';
            exit;
        }

        $isAuteur = (int) $trajet['id_utilisateur'] === Auth::id();

        if (!$isAuteur && !Auth::isAdmin()) {
            http_response_code(403);
            echo 'Vous ne pouvez modifier que vos propres trajets.';
            exit;
        }

        return $trajet;
    }

    /**
     * Validation de coherence propre au domaine metier (agences
     * distinctes, dates coherentes, places disponibles <= places totales).
     *
     * @param array<string, mixed> $input
     * @return array<int, string>
     */
    private function validateTrajetInput(array $input, bool $requirePlacesDispo = false): array
    {
        $rules = [
            'id_agence_depart' => ['required', 'integer'],
            'id_agence_arrivee' => ['required', 'integer'],
            'date_heure_depart' => ['required', 'date'],
            'date_heure_arrivee' => ['required', 'date'],
            'nb_places_total' => ['required', 'integer:1,9'],
        ];

        if ($requirePlacesDispo) {
            $rules['nb_places_dispo'] = ['required', 'integer:0,9'];
        }

        $validator = new Validator($input, $rules);

        if ($validator->fails()) {
            return [$validator->firstError() ?? 'Donnees invalides.'];
        }

        $errors = [];

        $agenceDepart = (int) ($input['id_agence_depart'] ?? 0);
        $agenceArrivee = (int) ($input['id_agence_arrivee'] ?? 0);

        if ($agenceDepart === $agenceArrivee) {
            $errors[] = "L'agence de depart et l'agence d'arrivee doivent etre differentes.";
        }

        if (Agence::findById($agenceDepart) === null || Agence::findById($agenceArrivee) === null) {
            $errors[] = 'Agence de depart ou d\'arrivee invalide.';
        }

        $depart = $this->parseDateTime((string) ($input['date_heure_depart'] ?? ''));
        $arrivee = $this->parseDateTime((string) ($input['date_heure_arrivee'] ?? ''));

        if ($depart !== null && $arrivee !== null && $arrivee <= $depart) {
            $errors[] = "La date d'arrivee doit etre posterieure a la date de depart.";
        }

        if ($depart !== null && $depart < new DateTime()) {
            $errors[] = 'La date de depart ne peut pas etre dans le passe.';
        }

        $nbPlacesTotal = (int) ($input['nb_places_total'] ?? 0);
        $nbPlacesDispo = $requirePlacesDispo ? (int) ($input['nb_places_dispo'] ?? 0) : $nbPlacesTotal;

        if ($nbPlacesDispo > $nbPlacesTotal) {
            $errors[] = 'Le nombre de places disponibles ne peut pas depasser le nombre de places totales.';
        }

        return $errors;
    }

    private function parseDateTime(string $value): ?DateTime
    {
        $date = DateTime::createFromFormat('Y-m-d\TH:i', $value);

        return $date === false ? null : $date;
    }

    private function toMysqlDateTime(string $value): string
    {
        $date = $this->parseDateTime($value);

        return $date === null ? $value : $date->format('Y-m-d H:i:s');
    }
}
