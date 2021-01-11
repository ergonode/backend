<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Application\Validator;

use Ergonode\Category\Application\Validator\CategoryCodeConstraint;
use Ergonode\Category\Application\Validator\CategoryCodeConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CategoryCodeConstraintValidatorTest extends ConstraintValidatorTestCase
{
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new CategoryCodeConstraint());
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
        $this->validator->validate('', new CategoryCodeConstraint());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new CategoryCodeConstraint());

        $this->assertNoViolation();
    }

    public function testInCorrectLongValueValidation(): void
    {
        $constraint = new CategoryCodeConstraint();
        $value = 'CODE_NOT_VALID_'.str_repeat('a', 114);
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->maxMessage)->setParameter('{{ limit }}', $constraint->max);
        $assertion->assertRaised();
    }

    public function testInCorrectShortValueValidation(): void
    {
        $constraint = new CategoryCodeConstraint();
        $value = ' ';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->minMessage)->setParameter('{{ limit }}', $constraint->min);
        $assertion->assertRaised();
    }

    public function testInCorrectValueValidation(): void
    {
        $constraint = new CategoryCodeConstraint();
        $value = 'SKU!!';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->regexMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): CategoryCodeConstraintValidator
    {
        return new CategoryCodeConstraintValidator();
    }
}
