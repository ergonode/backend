<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Product\Tests\Infrastructure\Validator;

use Ergonode\Product\Infrastructure\Validator\Sku;
use Ergonode\Product\Infrastructure\Validator\SkuValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class SkuValidatorTest extends ConstraintValidatorTestCase
{
    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new Sku());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new Sku());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('SKU', new Sku());

        $this->assertNoViolation();
    }

    /**
     */
    public function testInCorrectValueValidation(): void
    {
        $constraint = new Sku();
        $value = str_repeat('a', 500);
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ sku }}', $value);
        $assertion->assertRaised();
    }


    /**
     * @return SkuValidator
     */
    protected function createValidator(): SkuValidator
    {
        return new SkuValidator();
    }
}
