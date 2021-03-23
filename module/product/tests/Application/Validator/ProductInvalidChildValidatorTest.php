<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Validator;

use Ergonode\Product\Application\Model\Product\Relation\ProductChildFormModel;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Application\Validator\ProductInvalidChild;
use Ergonode\Product\Application\Validator\ProductInvalidChildValidator;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ProductInvalidChildValidatorTest extends ConstraintValidatorTestCase
{
    private ProductQueryInterface $query;

    private ProductRepositoryInterface $productRepository;

    private ProductChildFormModel $model;


    protected function setUp(): void
    {
        $this->query = $this->createMock(ProductQueryInterface::class);
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->model = $this->createMock(ProductChildFormModel::class);
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
        $this->validator->validate($this->model, new ProductInvalidChild());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->model->childId = Uuid::uuid4()->toString();
        $productId = $this->createMock(ProductId::class);
        $uuid = Uuid::uuid4()->toString();
        $attributeId = $this->createMock(AttributeId::class);
        $attributeId->method('getValue')->willReturn($uuid);
        $this->model->method('getParentId')->willReturn($productId);
        $variableProduct = $this->createMock(VariableProduct::class);
        $attributeId = $this->createMock(AttributeId::class);
        $attributeId->method('getValue')->willReturn($uuid);
        $variableProduct->method('getBindings')->willReturn([$attributeId]);
        $this->productRepository->method('load')->willReturn($variableProduct);
        $this->query->method('findAttributeIdsByProductId')->willReturn([$attributeId]);
        $constraint = new ProductInvalidChild();
        $this->validator->validate($this->model, $constraint);

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $this->model->childId = Uuid::uuid4()->toString();
        $productId = $this->createMock(ProductId::class);
        $this->model->method('getParentId')->willReturn($productId);
        $variableProduct = $this->createMock(VariableProduct::class);
        $attributeId1 = $this->createMock(AttributeId::class);
        $attributeId1->method('getValue')->willReturn(Uuid::uuid4()->toString());
        $attributeId2 = $this->createMock(AttributeId::class);
        $attributeId2->method('getValue')->willReturn(Uuid::uuid4()->toString());
        $variableProduct->method('getBindings')->willReturn([$attributeId1]);
        $this->productRepository->method('load')->willReturn($variableProduct);
        $this->query->method('findAttributeIdsByProductId')->willReturn([$attributeId2]);
        $constraint = new ProductInvalidChild();
        $this->validator->validate($this->model, $constraint);

        $assertion = $this->buildViolation($constraint->message)
            ->atPath('property.path.childId');
        $assertion->assertRaised();
    }


    protected function createValidator(): ProductInvalidChildValidator
    {
        return new ProductInvalidChildValidator($this->query, $this->productRepository);
    }
}
