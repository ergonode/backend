<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Application\Validator;

use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Category\Application\Validator\CategoryTreeExists;
use Ergonode\Category\Application\Validator\CategoryTreeExistsValidator;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CategoryTreeExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var TreeRepositoryInterface|MockObject
     */
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TreeRepositoryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(ValidatorException::class);
        $this->validator->validate(new \stdClass(), new CategoryTreeExists());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(ValidatorException::class);
        /** @var Constraint $constrain */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new CategoryTreeExists());

        $this->assertNoViolation();
    }

    public function testTreeExistsValidation(): void
    {
        $this->repository->method('exists')->willReturn(true);
        $this->validator->validate(CategoryTreeId::generate(), new CategoryTreeExists());

        $this->assertNoViolation();
    }

    public function testTreeNotExistsValidation(): void
    {
        $this->repository->method('load')->willReturn(null);
        $constraint = new CategoryTreeExists();
        $value = CategoryTreeId::generate();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    protected function createValidator(): CategoryTreeExistsValidator
    {
        return new CategoryTreeExistsValidator($this->repository);
    }
}
