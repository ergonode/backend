<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Infrastructure\Validator;

use Ergonode\Product\Domain\Query\ProductBindingQueryInterface;
use Ergonode\Product\Infrastructure\Validator\ProductNoBindings;
use Ergonode\Product\Infrastructure\Validator\ProductNoBindingsValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ProductNoBindingsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ProductBindingQueryInterface|MockObject
     */
    private ProductBindingQueryInterface $query;


    protected function setUp(): void
    {
        $this->query = $this->createMock(ProductBindingQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ProductNoBindings());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new ProductNoBindings());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->query->method('getBindings')->willReturn([Uuid::uuid4()->toString()]);
        $uuid = Uuid::uuid4()->toString();
        $constraint = new ProductNoBindings();
        $this->validator->validate($uuid, $constraint);

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $this->query->method('getBindings')->willReturn(array());
        $uuid = Uuid::uuid4()->toString();
        $constraint = new ProductNoBindings();
        $this->validator->validate($uuid, $constraint);

        $assertion = $this->buildViolation($constraint->message);
        $assertion->assertRaised();
    }


    /**
     * @return ProductNoBindingsValidator
     */
    protected function createValidator(): ProductNoBindingsValidator
    {
        return new ProductNoBindingsValidator($this->query);
    }
}
