<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Database;
use PHPUnit\Framework\TestCase;

/**
 * Classe de base pour les tests touchant la base de donnees : chaque
 * test s'execute dans une transaction annulee en fin de test, pour
 * ne jamais laisser de donnees residuelles dans la base de test.
 */
abstract class DatabaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Database::connection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        Database::connection()->rollBack();
        parent::tearDown();
    }
}
