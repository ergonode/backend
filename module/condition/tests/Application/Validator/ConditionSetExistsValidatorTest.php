<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Application\Validator;

use Ergonode\Condition\Application\Validator\ConditionSetExists;
use Ergonode\Condition\Application\Validator\ConditionSetExistsValidator;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ConditionSetExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ConditionSetRepositoryInterface|MockObject
     */
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ConditionSetRepositoryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ConditionSetExists());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constrain */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new ConditionSetExists());

        $this->assertNoViolation();
    }

    public function testConditionSetExistsValidation(): void
    {
        $this->repository->method('exists')->willReturn(true);
        $this->validator->validate(ConditionSetId::generate(), new ConditionSetExists());

        $this->assertNoViolation();
    }

    public function testConditionSetNotExistsValidation(): void
    {
        $this->repository->method('load')->willReturn(null);
        $constraint = new ConditionSetExists();
        $value = ConditionSetId::generate();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    protected function createValidator(): ConditionSetExistsValidator
    {
        return new ConditionSetExistsValidator($this->repository);
    }
}
