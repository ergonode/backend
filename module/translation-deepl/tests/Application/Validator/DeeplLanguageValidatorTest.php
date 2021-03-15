<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\TranslationDeepl\Tests\Application\Validator;

use Ergonode\TranslationDeepl\Application\Validator\DeeplLanguageAvailable;
use Ergonode\TranslationDeepl\Application\Validator\DeeplLanguageAvailableValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class DeeplLanguageValidatorTest extends ConstraintValidatorTestCase
{
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('EN', $constrain);
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new DeeplLanguageAvailable());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('EN', new DeeplLanguageAvailable());

        $this->assertNoViolation();
    }

    public function testIncorrectValueValidation(): void
    {
        $constraint = new DeeplLanguageAvailable();
        $value = 'HR';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ language }}', $value);
        $assertion->assertRaised();
    }

    protected function createValidator(): DeeplLanguageAvailableValidator
    {
        return new DeeplLanguageAvailableValidator();
    }
}
