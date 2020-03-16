<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Tests\Infrastructure\Validator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\TranslationDeepl\Infrastructure\Validator\Constraints\DeeplLanguageConstraint;
use Ergonode\TranslationDeepl\Infrastructure\Validator\DeeplLanguageValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class DeeplLanguageValidatorTest extends ConstraintValidatorTestCase
{

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate(Language::fromString('EN'), $constrain);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new DeeplLanguageConstraint());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValueValidation(): void
    {
        $this->validator->validate(Language::fromString('EN'), new DeeplLanguageConstraint());

        $this->assertNoViolation();
    }

    /**
     */
    public function testIncorrectValueValidation(): void
    {
        $constraint = new DeeplLanguageConstraint();
        $value = Language::fromString('HR');
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ language }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return DeeplLanguageValidator
     */
    protected function createValidator(): DeeplLanguageValidator
    {
        return new DeeplLanguageValidator();
    }
}
