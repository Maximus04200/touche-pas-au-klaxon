<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Core\Database;
use App\Models\Agence;
use App\Models\Trajet;
use Tests\Unit\DatabaseTestCase;

final class AgenceTest extends DatabaseTestCase
{
    public function testCreateInsertsAgenceAndReturnsItsId(): void
    {
        $id = Agence::create('Rennes');

        $agence = Agence::findById($id);

        self::assertNotNull($agence);
        self::assertSame('Rennes', $agence['ville']);
    }

    public function testUpdateChangesVille(): void
    {
        $id = Agence::create('Rennes');

        $result = Agence::update($id, 'Angers');

        self::assertTrue($result);
        self::assertSame('Angers', Agence::findById($id)['ville']);
    }

    public function testDeleteRemovesAnUnusedAgence(): void
    {
        $id = Agence::create('Rennes');

        $result = Agence::delete($id);

        self::assertTrue($result);
        self::assertNull(Agence::findById($id));
    }

    public function testDeleteFailsWhenAgenceIsReferencedByATrajet(): void
    {
        $depart = Agence::create('Rennes');
        $arrivee = Agence::create('Angers');
        $userId = $this->createFixtureUtilisateur();

        Trajet::create([
            'id_agence_depart' => $depart,
            'id_agence_arrivee' => $arrivee,
            'date_heure_depart' => '2099-01-01 08:00:00',
            'date_heure_arrivee' => '2099-01-01 12:00:00',
            'nb_places_total' => 3,
            'nb_places_dispo' => 3,
            'id_utilisateur' => $userId,
        ]);

        $result = Agence::delete($depart);

        self::assertFalse($result);
        self::assertNotNull(Agence::findById($depart));
    }

    public function testVilleExistsDetectsDuplicatesCaseSensitively(): void
    {
        Agence::create('Rennes');

        self::assertTrue(Agence::villeExists('Rennes'));
        self::assertFalse(Agence::villeExists('Nancy'));
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
