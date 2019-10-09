<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Infrastructure\Validator;

use Ergonode\Condition\Domain\Query\ConditionSetQueryInterface;
use Ergonode\Condition\Infrastructure\Validator\UniqueConditionSetCode;
use Ergonode\Condition\Infrastructure\Validator\UniqueConditionSetCodeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class UniqueConditionSetCodeValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ConditionSetQueryInterface
     */
    private $query;

    /**
     */
    protected function setUp()
    {
        $this->query = $this->createMock(ConditionSetQueryInterface::class);
        parent::setUp();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongValueProvided(): void
    {
        $this->validator->validate(new \stdClass(), new UniqueConditionSetCode());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongConstraintProvided(): void
    {
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new UniqueConditionSetCode());

        $this->assertNoViolation();
    }

    /**
     */
    public function testIsValidConditionSetCodeValidation(): void
    {
        $constraint = new UniqueConditionSetCode();
        $value = '5dJcnyRXZULMq1XglZAL3XrCztK7COfLTN5NkcEkBEbZaQjYCTtatYvaBNGOapGJuAkmHDnSeeKQyoSI8FafltkDvrVqSB7y13FaVAJue';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     */
    public function testConditionSetCodeExistsValidation(): void
    {
        $this->query->method('isExistsByCode')->willReturn(true);
        $constraint = new UniqueConditionSetCode();
        $value = 'Value';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    /**
     * @return UniqueConditionSetCodeValidator
     */
    protected function createValidator(): UniqueConditionSetCodeValidator
    {
        return new UniqueConditionSetCodeValidator($this->query);
    }
}
