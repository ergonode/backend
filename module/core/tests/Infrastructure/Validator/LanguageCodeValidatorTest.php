<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Infrastructure\Validator;

use Ergonode\Core\Infrastructure\Validator\Constraint\LanguageCodeConstraint;
use Ergonode\Core\Infrastructure\Validator\LanguageCodeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class LanguageCodeValidatorTest extends ConstraintValidatorTestCase
{
    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new LanguageCodeConstraint());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new LanguageCodeConstraint());
        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('EN', new LanguageCodeConstraint());

        $this->assertNoViolation();
    }

    /**
     */
    public function testInCorrectValueValidation(): void
    {
        $constraint = new LanguageCodeConstraint();
        $value = 'JL';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ language }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return LanguageCodeValidator
     */
    protected function createValidator(): LanguageCodeValidator
    {
        return new LanguageCodeValidator();
    }
}
