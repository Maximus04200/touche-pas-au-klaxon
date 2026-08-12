<?php

declare(strict_types=1);

namespace Tests\Unit\Core;

use App\Core\Csrf;
use PHPUnit\Framework\TestCase;

final class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function testTokenIsStableAcrossCallsWithinTheSameSession(): void
    {
        self::assertSame(Csrf::token(), Csrf::token());
    }

    public function testValidateAcceptsTheCurrentToken(): void
    {
        $token = Csrf::token();

        self::assertTrue(Csrf::validate($token));
    }

    public function testValidateRejectsAWrongToken(): void
    {
        Csrf::token();

        self::assertFalse(Csrf::validate('jeton-invalide'));
    }

    public function testValidateRejectsANullToken(): void
    {
        Csrf::token();

        self::assertFalse(Csrf::validate(null));
    }
}
