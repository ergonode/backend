<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Product\Application\Validator\ProductTypeValidator;
use Ergonode\Product\Application\Validator\ProductType;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class ProductTypeValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ProductRepositoryInterface|MockObject
     */
    private ProductRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepositoryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ProductType());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new ProductType());

        $this->assertNoViolation();
    }

    public function testCorrectTypeValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $product = $this->createMock(AbstractProduct::class);
        $product->method('getType')->willReturn('type');
        $this->repository->method('load')->willReturn($product);
        $constraint = new ProductType();
        $constraint->type = ['type'];
        $this->validator->validate($uuid, $constraint);

        $this->assertNoViolation();
    }

    public function testIncorrectTypeValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $product = $this->createMock(AbstractProduct::class);
        $product->method('getType')->willReturn('type1');
        $this->repository->method('load')->willReturn($product);
        $constraint = new ProductType();
        $constraint->type = ['type2'];
        $this->validator->validate($uuid, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $uuid);
        $assertion->assertRaised();
    }


    protected function createValidator(): ProductTypeValidator
    {
        return new ProductTypeValidator($this->repository);
    }
}
