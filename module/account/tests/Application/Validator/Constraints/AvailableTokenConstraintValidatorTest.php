<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Application\Validator\Constraints;

use Ergonode\Account\Application\Validator\Constraints\AvailableTokenConstraint;
use Ergonode\Account\Application\Validator\Constraints\AvailableTokenConstraintValidator;
use Ergonode\Account\Domain\Validator\TokenValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class AvailableTokenConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var TokenValidator|MockObject
     */
    private TokenValidator $tokenValidator;

    public function setUp(): void
    {
        $this->tokenValidator =  $this->createMock(TokenValidator::class);

        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new AvailableTokenConstraint());
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
        $this->validator->validate('', new AvailableTokenConstraint());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->tokenValidator->method('validate')
            ->willReturn(true);

        $this->validator->validate('test', new AvailableTokenConstraint());

        $this->assertNoViolation();
    }

    public function testInCorrectTimeValueValidation(): void
    {
        $this->tokenValidator->method('validate')
            ->willReturn(false);

        $constraint = new AvailableTokenConstraint();
        $value = 'test';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): AvailableTokenConstraintValidator
    {
        return new AvailableTokenConstraintValidator($this->tokenValidator);
    }
}
