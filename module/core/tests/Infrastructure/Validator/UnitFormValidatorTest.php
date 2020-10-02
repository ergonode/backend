<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Infrastructure\Validator;

use Ergonode\Core\Application\Model\UnitFormModel;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\Core\Infrastructure\Validator\Constraint\UnitForm;
use Ergonode\Core\Infrastructure\Validator\UnitFormValidator;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class UnitFormValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var UnitQueryInterface|MockObject
     */
    private UnitQueryInterface $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(UnitQueryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new UnitForm());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    /**
     */
    public function testCorrectEmptyNameValidation(): void
    {
        $model = $this->createMock(UnitFormModel::class);
        $model->name = 'name';
        $this->query->method('findIdByName')->willReturn(null);

        $this->validator->validate($model, new UnitForm());
        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectEmptySymbolValidation(): void
    {
        $model = $this->createMock(UnitFormModel::class);
        $model->symbol = 'symbol';
        $this->query->method('findIdByCode')->willReturn(null);

        $this->validator->validate($model, new UnitForm());
        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectNullValidation(): void
    {
        $model = $this->createMock(UnitFormModel::class);
        $model->symbol = null;
        $model->name = null;

        $this->validator->validate($model, new UnitForm());
        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectRaisedNameValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $model = $this->createMock(UnitFormModel::class);
        $model->name = 'name';
        $model->method('getUnitId')->willReturn(new UnitId($uuid));
        $this->query->method('findIdByName')->willReturn($this->createMock(UnitId::class));
        $constraint = new UnitForm();

        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueNameMessage);
        $assertion->assertRaised();
    }

    /**
     */
    public function testCorrectRaisedSymbolValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $model = $this->createMock(UnitFormModel::class);
        $model->symbol = 'AB';
        $model->method('getUnitId')->willReturn(new UnitId($uuid));
        $this->query->method('findIdByCode')->willReturn($this->createMock(UnitId::class));
        $constraint = new UnitForm();

        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueSymbolMessage);
        $assertion->assertRaised();
    }

    /**
     * @return UnitFormValidator
     */
    protected function createValidator(): UnitFormValidator
    {
        return new UnitFormValidator($this->query);
    }
}
