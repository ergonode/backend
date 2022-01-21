<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Application\Validator;

use Ergonode\Workflow\Application\Validator\StatusIdsContainAll;
use Ergonode\Workflow\Application\Validator\StatusIdsContainAllValidator;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class StatusIdsContainAllValidatorTest extends ConstraintValidatorTestCase
{
    private StatusQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(StatusQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new StatusIdsContainAll());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    public function testStatusIdsContainAllValidation(): void
    {
        $value = ['f3b39ae7-cf4c-4f6d-bb03-bf70f5f19c35'];
        $this->query->method('getAllStatusIds')->willReturn(['f3b39ae7-cf4c-4f6d-bb03-bf70f5f19c35']);
        $this->validator->validate($value, new StatusIdsContainAll());

        $this->assertNoViolation();
    }

    public function testStatusIdsContainAllInvalidValidation(): void
    {
        $value = ['f3b39ae7-cf4c-4f6d-bb03-bf70f5f19c35'];
        $this->query->method('getAllStatusIds')->willReturn(['ecf32584-0c38-467a-b101-ee2a75435532']);
        $constraint = new StatusIdsContainAll();
        $this->validator->validate($value, $constraint);
        $assertion = $this->buildViolation($constraint->message);
        $assertion->assertRaised();
    }

    protected function createValidator(): StatusIdsContainAllValidator
    {
        return new StatusIdsContainAllValidator($this->query);
    }
}
