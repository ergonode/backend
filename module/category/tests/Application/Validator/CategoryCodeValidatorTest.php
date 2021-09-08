<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Application\Validator;

use Ergonode\Category\Application\Validator\CategoryCode;
use Ergonode\Category\Application\Validator\CategoryCodeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CategoryCodeValidatorTest extends ConstraintValidatorTestCase
{
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new CategoryCode());
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
        $this->validator->validate('', new CategoryCode());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new CategoryCode());

        $this->assertNoViolation();
    }

    public function testInCorrectLongValueValidation(): void
    {
        $constraint = new CategoryCode();
        $value = 'CODE_NOT_VALID_'.str_repeat('a', 114);
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->maxMessage)->setParameter('{{ limit }}', $constraint->max);
        $assertion->assertRaised();
    }

    public function testInCorrectShortValueValidation(): void
    {
        $constraint = new CategoryCode();
        $value = ' ';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->minMessage)->setParameter('{{ limit }}', $constraint->min);
        $assertion->assertRaised();
    }

    public function testInCorrectValueValidation(): void
    {
        $constraint = new CategoryCode();
        $value = 'SKU!!';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->regexMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): CategoryCodeValidator
    {
        return new CategoryCodeValidator();
    }
}
