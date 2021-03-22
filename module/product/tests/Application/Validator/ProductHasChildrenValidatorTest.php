<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Validator;

use Ergonode\Product\Application\Model\Product\Binding\ProductBindFormModel;
use Ergonode\Product\Application\Validator\ProductHasChildren;
use Ergonode\Product\Application\Validator\ProductHasChildrenValidator;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ProductHasChildrenValidatorTest extends ConstraintValidatorTestCase
{
    private ProductBindFormModel $model;

    protected function setUp(): void
    {
        $this->model = $this->createMock(ProductBindFormModel::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ProductHasChildren());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $variableProduct = $this->createMock(VariableProduct::class);
        $this->model->product = $variableProduct;
        $this->validator->validate($this->model, new ProductHasChildren());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->model->bindId = '06c6969a-c645-41c1-adad-a63cee70d5ea';
        $variableProduct = $this->createMock(VariableProduct::class);
        $variableProduct->method('getChildren')->willReturn([]);
        $this->model->product = $variableProduct;
        $constraint = new ProductHasChildren();
        $this->validator->validate($this->model, $constraint);

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $this->model->bindId = '06c6969a-c645-41c1-adad-a63cee70d5ea';
        $attributeId = $this->createMock(AttributeId::class);
        $variableProduct = $this->createMock(VariableProduct::class);
        $variableProduct->method('getChildren')->willReturn([$attributeId]);
        $this->model->product = $variableProduct;
        $constraint = new ProductHasChildren();
        $this->validator->validate($this->model, $constraint);

        $assertion = $this->buildViolation($constraint->message)
            ->atPath('property.path.bindId');
        $assertion->assertRaised();
    }

    protected function createValidator(): ProductHasChildrenValidator
    {
        return new ProductHasChildrenValidator();
    }
}
