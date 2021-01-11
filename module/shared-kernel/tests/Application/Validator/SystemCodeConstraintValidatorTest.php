<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Tests\Application\Validator;

use Ergonode\SharedKernel\Application\Validator\SystemCodeConstraint;
use Ergonode\SharedKernel\Application\Validator\SystemCodeConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class SystemCodeConstraintValidatorTest extends ConstraintValidatorTestCase
{
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new SystemCodeConstraint());
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
        $this->validator->validate('', new SystemCodeConstraint());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new SystemCodeConstraint());

        $this->assertNoViolation();
    }

    public function testInCorrectLongValueValidation(): void
    {
        $constraint = new SystemCodeConstraint();
        $value = 'CODE_NOT_VALID_'.str_repeat('a', 114);
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->maxMessage)->setParameter('{{ limit }}', $constraint->max);
        $assertion->assertRaised();
    }

    public function testInCorrectShortValueValidation(): void
    {
        $constraint = new SystemCodeConstraint();
        $value = ' ';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->minMessage)->setParameter('{{ limit }}', $constraint->min);
        $assertion->assertRaised();
    }

    protected function createValidator(): SystemCodeConstraintValidator
    {
        return new SystemCodeConstraintValidator();
    }
}
