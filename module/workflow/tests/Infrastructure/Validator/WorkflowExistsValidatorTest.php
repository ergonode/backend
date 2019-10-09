<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Infrastructure\Validator;

use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Infrastructure\Validator\WorkflowExists;
use Ergonode\Workflow\Infrastructure\Validator\WorkflowExistsValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class WorkflowExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var WorkflowRepositoryInterface
     */
    private $repository;

    /**
     */
    protected function setUp()
    {
        $this->repository = $this->createMock(WorkflowRepositoryInterface::class);
        parent::setUp();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongValueProvided(): void
    {
        $this->validator->validate(new \stdClass(), new WorkflowExists());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongConstraintProvided(): void
    {
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new WorkflowExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testWorkflowExistsValidation(): void
    {
        $this->repository->method('load')->willReturn(null);
        $this->validator->validate(WorkflowId::generate(), new WorkflowExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testWorkflowNotExistsValidation(): void
    {
        $this->repository->method('load')->willReturn($this->createMock(Workflow::class));
        $constraint = new WorkflowExists();
        $value = WorkflowId::generate();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return WorkflowExistsValidator
     */
    protected function createValidator(): WorkflowExistsValidator
    {
        return new WorkflowExistsValidator($this->repository);
    }
}
