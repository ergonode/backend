<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Infrastructure\Validator;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Infrastructure\Validator\Constraint\LanguageCodeExists;
use Ergonode\Core\Infrastructure\Validator\LanguageCodeExistsValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class LanguageCodeExistsValidatorTest extends ConstraintValidatorTestCase
{
    private LanguageQueryInterface $query;

    /**
     *
     */
    public function setUp(): void
    {
        $this->query = $this->createMock(LanguageQueryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new LanguageCodeExists());
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
        $this->validator->validate('', new LanguageCodeExists());
        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValueValidation(): void
    {
        $this->query->method('getDictionary')->willReturn(['en']);
        $this->validator->validate('en', new LanguageCodeExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testInCorrectValueValidation(): void
    {
        $constraint = new LanguageCodeExists();
        $value = 'JL';
        $this->query->method('getDictionary')->willReturn(['en']);
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return LanguageCodeExistsValidator
     */
    protected function createValidator(): LanguageCodeExistsValidator
    {
        return new LanguageCodeExistsValidator($this->query);
    }
}
