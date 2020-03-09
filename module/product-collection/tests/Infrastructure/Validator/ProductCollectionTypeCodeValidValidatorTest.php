<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Infrastructure\Validator;

use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionTypeCodeValid;
use Ergonode\ProductCollection\Infrastructure\Validator\ProductCollectionTypeCodeValidValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class ProductCollectionTypeCodeValidValidatorTest extends ConstraintValidatorTestCase
{

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new ProductCollectionTypeCodeValid());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\UnexpectedTypeException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new ProductCollectionTypeCodeValid());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new ProductCollectionTypeCodeValid());

        $this->assertNoViolation();
    }

    /**
     */
    public function testInCorrectValueValidation(): void
    {
        $constraint = new ProductCollectionTypeCodeValid();
        $value = 'SKU!!';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return ProductCollectionTypeCodeValidValidator
     */
    protected function createValidator(): ProductCollectionTypeCodeValidValidator
    {
        return new ProductCollectionTypeCodeValidValidator();
    }
}
