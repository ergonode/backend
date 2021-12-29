<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Application\Validator;

use Ergonode\Account\Application\Validator\HostAvailable;
use Ergonode\Account\Application\Validator\HostAvailableValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class HostAvailableValidatorTest extends ConstraintValidatorTestCase
{
    private array $sites;

    public function setUp(): void
    {
        $this->sites = [
            'localhost',
            '127.0.0.1',
            'https://ergonode.com/',
        ];
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new HostAvailable());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new HostAvailable());

        $this->assertNoViolation();
    }

    public function testCorrectHostValueValidation(): void
    {
        $this->validator->validate('http://localhost/test', new HostAvailable());

        $this->assertNoViolation();
    }

    public function testCorrectIpValueValidation(): void
    {
        $this->validator->validate('http://127.0.0.1/test', new HostAvailable());

        $this->assertNoViolation();
    }

    public function testCorrectErgonodeHostValueValidation(): void
    {
        $this->validator->validate('https://ergonode.com/test', new HostAvailable());

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $constraint = new HostAvailable();
        $value = 'site';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): HostAvailableValidator
    {
        return new HostAvailableValidator($this->sites);
    }
}
