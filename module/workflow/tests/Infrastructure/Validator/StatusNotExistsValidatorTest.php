<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Infrastructure\Validator;

use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Infrastructure\Validator\StatusNotExists;
use Ergonode\Workflow\Infrastructure\Validator\StatusNotExistsValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class StatusNotExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var StatusRepositoryInterface
     */
    private $repository;

    /**
     */
    protected function setUp()
    {
        $this->repository = $this->createMock(StatusRepositoryInterface::class);
        parent::setUp();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongValueProvided(): void
    {
        $this->validator->validate(new \stdClass(), new StatusNotExists());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongConstraintProvided(): void
    {
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new StatusNotExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testStatusNotValidValidation(): void
    {
        $this->repository->method('load')->willReturn($this->createMock(Status::class));
        $constraint = new StatusNotExists();
        $value = 'Value';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     */
    public function testStatusExistsValidation(): void
    {
        $this->repository->method('load')->willReturn($this->createMock(Status::class));
        $this->validator->validate(StatusId::generate(), new StatusNotExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testStatusNotExistsValidation(): void
    {
        $this->repository->method('load')->willReturn(null);
        $constraint = new StatusNotExists();
        $value = StatusId::generate();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return StatusNotExistsValidator
     */
    protected function createValidator(): StatusNotExistsValidator
    {
        return new StatusNotExistsValidator($this->repository);
    }
}
