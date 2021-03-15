<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Application\Validator;

use Ergonode\Account\Application\Form\Model\RoleFormModel;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Account\Application\Validator\RoleNameUnique;
use Ergonode\Account\Application\Validator\RoleNameUniqueValidator;

class RoleNameUniqueValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var RoleQueryInterface|MockObject
     */
    private RoleQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(RoleQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new RoleNameUnique());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    public function testCorrectEmptyValidation(): void
    {
        $model = $this->createMock(RoleFormModel::class);
        $model->name = 'name';
        $this->query->method('findIdByRoleName')->willReturn(null);

        $this->validator->validate($model, new RoleNameUnique());
        $this->assertNoViolation();
    }

    public function testRoleNameExistsValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $model = $this->createMock(RoleFormModel::class);
        $model->name = 'name';
        $model->method('getRoleId')->willReturn(new RoleId($uuid));
        $this->query->method('findIdByRoleName')->willReturn($this->createMock(RoleId::class));
        $constraint = new RoleNameUnique();

        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage)
        ->atPath('property.path.name');
        $assertion->assertRaised();
    }

    protected function createValidator(): RoleNameUniqueValidator
    {
        return new RoleNameUniqueValidator($this->query);
    }
}
