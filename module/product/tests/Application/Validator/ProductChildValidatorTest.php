<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Product\Application\Validator\ProductChildValidator;
use Ergonode\Product\Application\Validator\ProductChild;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Application\Model\Product\Relation\ProductChildFormModel;

class ProductChildValidatorTest extends ConstraintValidatorTestCase
{
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ProductChild());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $model = $this->createMock(ProductChildFormModel::class);
        $this->validator->validate($model, new ProductChild());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $model = $this->createMock(ProductChildFormModel::class);
        $model->method('getParentId')->willReturn(new ProductId($uuid));
        $model->childId = Uuid::uuid4()->toString();
        $constraint = new ProductChild();
        $this->validator->validate($model, $constraint);

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $model = $this->createMock(ProductChildFormModel::class);
        $model->method('getParentId')->willReturn(new ProductId($uuid));
        $model->childId = $uuid;
        $constraint = new ProductChild();
        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->message)->atPath('property.path.childId');
        $assertion->assertRaised();
    }


    protected function createValidator(): ProductChildValidator
    {
        return new ProductChildValidator();
    }
}
