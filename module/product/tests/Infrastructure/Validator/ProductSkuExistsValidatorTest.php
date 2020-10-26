<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Product\Infrastructure\Validator\ProductSkuExistsValidator;
use Ergonode\Product\Infrastructure\Validator\ProductSkuExists;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductSkuExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ProductQueryInterface|MockObject
     */
    private ProductQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(ProductQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ProductSkuExists());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new ProductSkuExists());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $this->query->method('findProductIdBySku')->willReturn(new ProductId($uuid));
        $this->validator->validate('SKU', new ProductSkuExists());

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $constraint = new ProductSkuExists();
        $value = 'SKU';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message);
        $assertion->assertRaised();
    }


    protected function createValidator(): ProductSkuExistsValidator
    {
        return new ProductSkuExistsValidator($this->query);
    }
}
