<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Application\Validator;

use Ergonode\Attribute\Application\Validator\AttributeGroupCodeConstraint;
use Ergonode\Attribute\Application\Validator\AttributeGroupCodeConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class AttributeGroupCodeConstraintValidatorTest extends ConstraintValidatorTestCase
{
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new AttributeGroupCodeConstraint());
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
        $this->validator->validate('', new AttributeGroupCodeConstraint());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new AttributeGroupCodeConstraint());

        $this->assertNoViolation();
    }

    public function testInCorrectLongValueValidation(): void
    {
        $constraint = new AttributeGroupCodeConstraint();
        $value = 'CODE_NOT_VALID_'.str_repeat('a', 114);
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->maxMessage)->setParameter('{{ limit }}', $constraint->max);
        $assertion->assertRaised();
    }

    public function testInCorrectShortValueValidation(): void
    {
        $constraint = new AttributeGroupCodeConstraint();
        $value = ' ';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->minMessage)->setParameter('{{ limit }}', $constraint->min);
        $assertion->assertRaised();
    }

    public function testAttributeGroupCodeInvalidValidation(): void
    {
        $value = 'fes//efs..';
        $this->validator->validate($value, new AttributeGroupCodeConstraint());
        $constraint = new AttributeGroupCodeConstraint();
        $assertion = $this->buildViolation($constraint->regexMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): AttributeGroupCodeConstraintValidator
    {
        return new AttributeGroupCodeConstraintValidator();
    }
}
