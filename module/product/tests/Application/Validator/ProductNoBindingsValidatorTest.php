<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Validator;

use Ergonode\Product\Domain\Query\ProductBindingQueryInterface;
use Ergonode\Product\Application\Validator\ProductInvalidChild;
use Ergonode\Product\Application\Validator\ProductInvalidChildValidator;
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
        $this->validator->validate(new \stdClass(), new ProductInvalidChild());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new ProductInvalidChild());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->query->method('getBindings')->willReturn([Uuid::uuid4()->toString()]);
        $uuid = Uuid::uuid4()->toString();
        $constraint = new ProductInvalidChild();
        $this->validator->validate($uuid, $constraint);

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $this->query->method('getBindings')->willReturn(array());
        $uuid = Uuid::uuid4()->toString();
        $constraint = new ProductInvalidChild();
        $this->validator->validate($uuid, $constraint);

        $assertion = $this->buildViolation($constraint->message);
        $assertion->assertRaised();
    }


    protected function createValidator(): ProductInvalidChildValidator
    {
        return new ProductInvalidChildValidator($this->query);
    }
}
