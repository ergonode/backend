<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Validator;

use Ergonode\Core\Application\Model\UnitFormModel;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\Core\Application\Validator\UnitForm;
use Ergonode\Core\Application\Validator\UnitFormValidator;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UnitFormValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var UnitQueryInterface|MockObject
     */
    private UnitQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(UnitQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new UnitForm());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    public function testCorrectValidation(): void
    {
        $model = $this->createMock(UnitFormModel::class);
        $model->name = 'name';
        $this->query->method('findIdByName')->willReturn(null);
        $model->symbol = 'symbol';
        $this->query->method('findIdByCode')->willReturn(null);

        $this->validator->validate($model, new UnitForm());
        $this->assertNoViolation();
    }

    public function testCorrectNullNameValidation(): void
    {
        $model = $this->createMock(UnitFormModel::class);
        $model->symbol = 'symbol';
        $this->query->method('findIdByCode')->willReturn(null);
        $model->name = null;

        $constraint = new UnitForm();
        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->emptyNameMessage)
        ->atPath('property.path.name');
        $assertion->assertRaised();
    }

    public function testCorrectRaisedEmptyNameValidation(): void
    {
        $model = $this->createMock(UnitFormModel::class);
        $model->symbol = 'symbol';
        $this->query->method('findIdByCode')->willReturn(null);
        $model->name = null;

        $constraint = new UnitForm();
        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->emptyNameMessage)
            ->atPath('property.path.name');
        $assertion->assertRaised();
    }

    public function testCorrectRaisedEmptySymbolValidation(): void
    {
        $model = $this->createMock(UnitFormModel::class);
        $model->name = 'name';
        $this->query->method('findIdByName')->willReturn(null);
        $model->symbol = null;

        $constraint = new UnitForm();
        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->emptySymbolMessage)
            ->atPath('property.path.symbol');
        $assertion->assertRaised();
    }

    public function testCorrectRaisedUniqueNameValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $model = $this->createMock(UnitFormModel::class);
        $model->name = 'name';
        $model->method('getUnitId')->willReturn(new UnitId($uuid));
        $this->query->method('findIdByName')->willReturn($this->createMock(UnitId::class));
        $model->symbol = 'symbol';
        $this->query->method('findIdByCode')->willReturn(null);
        $constraint = new UnitForm();

        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueNameMessage)
            ->atPath('property.path.name');
        $assertion->assertRaised();
    }

    public function testCorrectRaisedUniqueSymbolValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $model = $this->createMock(UnitFormModel::class);
        $model->name = 'symbol';
        $model->method('getUnitId')->willReturn(new UnitId($uuid));
        $this->query->method('findIdByCode')->willReturn($this->createMock(UnitId::class));
        $model->symbol = 'name';
        $this->query->method('findIdByName')->willReturn(null);
        $constraint = new UnitForm();

        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueSymbolMessage)
            ->atPath('property.path.symbol');
        $assertion->assertRaised();
    }

    protected function createValidator(): UnitFormValidator
    {
        return new UnitFormValidator($this->query);
    }
}
