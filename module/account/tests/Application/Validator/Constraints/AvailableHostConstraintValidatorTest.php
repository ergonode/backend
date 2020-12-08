<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Application\Validator\Constraints;

use Ergonode\Account\Application\Validator\Constraints\AvailableHostConstraint;
use Ergonode\Account\Application\Validator\Constraints\AvailableHostConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class AvailableHostConstraintValidatorTest extends ConstraintValidatorTestCase
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
        $this->validator->validate(new \stdClass(), new AvailableHostConstraint());
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
        $this->validator->validate('', new AvailableHostConstraint());

        $this->assertNoViolation();
    }

    public function testCorrectHostValueValidation(): void
    {
        $this->validator->validate('http://localhost/test', new AvailableHostConstraint());

        $this->assertNoViolation();
    }

    public function testCorrectIpValueValidation(): void
    {
        $this->validator->validate('http://127.0.0.1/test', new AvailableHostConstraint());

        $this->assertNoViolation();
    }

    public function testCorrectErgonodeHostValueValidation(): void
    {
        $this->validator->validate('https://ergonode.com/test', new AvailableHostConstraint());

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $constraint = new AvailableHostConstraint();
        $value = 'site';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): AvailableHostConstraintValidator
    {
        return new AvailableHostConstraintValidator($this->sites);
    }
}
