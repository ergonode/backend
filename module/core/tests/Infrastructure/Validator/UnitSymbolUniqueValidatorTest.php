<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Infrastructure\Validator;

use Ergonode\Core\Application\Model\UnitFormModel;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\Core\Infrastructure\Validator\Constraint\UnitSymbolUnique;
use Ergonode\Core\Infrastructure\Validator\UnitSymbolUniqueValidator;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class UnitSymbolUniqueValidatorTest extends ConstraintValidatorTestCase
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
        $this->validator->validate(new \stdClass(), new UnitSymbolUnique());
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
    public function testCorrectEmptyValidation(): void
    {
        $model = $this->createMock(UnitFormModel::class);
        $model->symbol = 'symbol';
        $this->query->method('findIdByName')->willReturn(null);

        $this->validator->validate($model, new UnitSymbolUnique());
        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectRaisedValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $model = $this->createMock(UnitFormModel::class);
        $model->symbol = 'AB';
        $model->method('getUnitId')->willReturn(new UnitId($uuid));
        $this->query->method('findIdByCode')->willReturn($this->createMock(UnitId::class));
        $constraint = new UnitSymbolUnique();

        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    /**
     * @return UnitSymbolUniqueValidator
     */
    protected function createValidator(): UnitSymbolUniqueValidator
    {
        return new UnitSymbolUniqueValidator($this->query);
    }
}
