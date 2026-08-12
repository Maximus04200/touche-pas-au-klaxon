<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Core\Database;
use App\Models\Agence;
use App\Models\Trajet;
use Tests\Unit\DatabaseTestCase;

final class TrajetTest extends DatabaseTestCase
{
    private int $agenceDepart;
    private int $agenceArrivee;
    private int $utilisateurId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agenceDepart = Agence::create('Rennes');
        $this->agenceArrivee = Agence::create('Angers');
        $this->utilisateurId = $this->createFixtureUtilisateur();
    }

    public function testCreateInsertsATrajetVisibleInDetails(): void
    {
        $id = Trajet::create($this->validTrajetData());

        $trajet = Trajet::findWithDetails($id);

        self::assertNotNull($trajet);
        self::assertSame('Rennes', $trajet['ville_depart']);
        self::assertSame('Angers', $trajet['ville_arrivee']);
        self::assertSame(3, (int) $trajet['nb_places_dispo']);
    }

    public function testUpcomingWithSeatsExcludesFullTrajets(): void
    {
        Trajet::create($this->validTrajetData(['nb_places_dispo' => 0]));
        $idAvecPlaces = Trajet::create($this->validTrajetData());

        $trajets = Trajet::upcomingWithSeats();
        $ids = array_column($trajets, 'id_trajet');

        self::assertContains($idAvecPlaces, $ids);
    }

    public function testUpcomingWithSeatsExcludesPastTrajets(): void
    {
        $idPasse = Trajet::create($this->validTrajetData([
            'date_heure_depart' => '2020-01-01 08:00:00',
            'date_heure_arrivee' => '2020-01-01 12:00:00',
        ]));

        $ids = array_column(Trajet::upcomingWithSeats(), 'id_trajet');

        self::assertNotContains($idPasse, $ids);
    }

    public function testUpdateModifiesTrajetFields(): void
    {
        $id = Trajet::create($this->validTrajetData());

        $result = Trajet::update($id, [
            'id_agence_depart' => $this->agenceDepart,
            'id_agence_arrivee' => $this->agenceArrivee,
            'date_heure_depart' => '2099-02-01 08:00:00',
            'date_heure_arrivee' => '2099-02-01 12:00:00',
            'nb_places_total' => 3,
            'nb_places_dispo' => 1,
        ]);

        $trajet = Trajet::findWithDetails($id);

        self::assertTrue($result);
        self::assertSame(1, (int) $trajet['nb_places_dispo']);
        self::assertSame('2099-02-01 08:00:00', $trajet['date_heure_depart']);
    }

    public function testDeleteRemovesTheTrajet(): void
    {
        $id = Trajet::create($this->validTrajetData());

        $result = Trajet::delete($id);

        self::assertTrue($result);
        self::assertNull(Trajet::findWithDetails($id));
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    private function validTrajetData(array $overrides = []): array
    {
        return array_merge([
            'id_agence_depart' => $this->agenceDepart,
            'id_agence_arrivee' => $this->agenceArrivee,
            'date_heure_depart' => '2099-01-01 08:00:00',
            'date_heure_arrivee' => '2099-01-01 12:00:00',
            'nb_places_total' => 3,
            'nb_places_dispo' => 3,
            'id_utilisateur' => $this->utilisateurId,
        ], $overrides);
    }

    private function createFixtureUtilisateur(): int
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role, actif)
             VALUES (:nom, :prenom, :email, :telephone, :mot_de_passe, :role, 1)'
        );
        $statement->execute([
            'nom' => 'Test',
            'prenom' => 'Fixture',
            'email' => uniqid('fixture_', true) . '@klaxon.local',
            'telephone' => '0600000000',
            'mot_de_passe' => password_hash('secret', PASSWORD_DEFAULT),
            'role' => 'employe',
        ]);

        return (int) Database::connection()->lastInsertId();
    }
}
