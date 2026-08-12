<?php

declare(strict_types=1);

namespace Tests\Unit\Core;

use App\Core\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    public function testRequiredFailsOnEmptyValue(): void
    {
        $validator = new Validator(['email' => ''], ['email' => ['required']]);

        self::assertTrue($validator->fails());
    }

    public function testEmailRuleRejectsInvalidAddress(): void
    {
        $validator = new Validator(['email' => 'pas-un-email'], ['email' => ['required', 'email']]);

        self::assertTrue($validator->fails());
    }

    public function testEmailRuleAcceptsValidAddress(): void
    {
        $validator = new Validator(['email' => 'jean.dupont@klaxon.local'], ['email' => ['required', 'email']]);

        self::assertTrue($validator->passes());
    }

    public function testIntegerRuleEnforcesRange(): void
    {
        $validator = new Validator(['places' => '15'], ['places' => ['integer:1,9']]);

        self::assertTrue($validator->fails());
    }

    public function testIntegerRuleAcceptsValueWithinRange(): void
    {
        $validator = new Validator(['places' => '4'], ['places' => ['integer:1,9']]);

        self::assertTrue($validator->passes());
    }

    public function testMaxRuleRejectsTooLongValue(): void
    {
        $validator = new Validator(['ville' => str_repeat('a', 101)], ['ville' => ['max:100']]);

        self::assertTrue($validator->fails());
    }
}
