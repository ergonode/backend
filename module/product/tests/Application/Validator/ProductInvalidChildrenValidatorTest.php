<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Validator;

use Ergonode\Product\Application\Model\Product\Relation\ProductChildBySkusFormModel;
use Ergonode\Product\Application\Validator\ProductInvalidChildren;
use Ergonode\Product\Application\Validator\ProductInvalidChildrenValidator;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ProductInvalidChildrenValidatorTest extends ConstraintValidatorTestCase
{
    private ProductQueryInterface $query;

    private ProductChildBySkusFormModel $model;

    protected function setUp(): void
    {
        $this->query = $this->createMock(ProductQueryInterface::class);
        $this->model = $this->createMock(ProductChildBySkusFormModel::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ProductInvalidChildren());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $variableProduct = $this->createMock(VariableProduct::class);
        $this->model->parentProduct = $variableProduct;
        $this->model->skus = [];
        $this->validator->validate($this->model, new ProductInvalidChildren());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->model->skus = ['sku1'];
        $uuid = Uuid::uuid4()->toString();
        $attributeId = $this->createMock(AttributeId::class);
        $attributeId->method('getValue')->willReturn($uuid);
        $variableProduct = $this->createMock(VariableProduct::class);
        $variableProduct->method('getBindings')->willReturn([$attributeId]);
        $this->model->parentProduct = $variableProduct;
        $this->query->method('findAttributeIdsBySku')->willReturn([$attributeId]);
        $constraint = new ProductInvalidChildren();
        $this->validator->validate($this->model, $constraint);

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $this->model->skus = ['sku1'];
        $attributeId1 = $this->createMock(AttributeId::class);
        $attributeId1->method('getValue')->willReturn(Uuid::uuid4()->toString());
        $attributeId2 = $this->createMock(AttributeId::class);
        $attributeId2->method('getValue')->willReturn(Uuid::uuid4()->toString());
        $variableProduct = $this->createMock(VariableProduct::class);
        $variableProduct->method('getBindings')->willReturn([$attributeId1]);
        $this->model->parentProduct = $variableProduct;
        $this->query->method('findAttributeIdsBySku')->willReturn([$attributeId2]);
        $constraint = new ProductInvalidChildren();
        $this->validator->validate($this->model, $constraint);

        $assertion = $this->buildViolation($constraint->message)
            ->setParameter('{{ sku }}', 'sku1')
            ->atPath('property.path.skus[0]');
        $assertion->assertRaised();
    }

    protected function createValidator(): ProductInvalidChildrenValidator
    {
        return new ProductInvalidChildrenValidator($this->query);
    }
}
