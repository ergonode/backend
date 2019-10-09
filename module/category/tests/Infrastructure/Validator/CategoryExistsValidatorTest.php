<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Validator;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Infrastructure\Validator\CategoryExists;
use Ergonode\Category\Infrastructure\Validator\CategoryExistsValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class CategoryExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var CategoryRepositoryInterface|MockObject
     */
    private $repository;

    /**
     */
    protected function setUp()
    {
        $this->repository = $this->createMock(CategoryRepositoryInterface::class);
        parent::setUp();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongValueProvided(): void
    {
        $this->validator->validate(new \stdClass(), new CategoryExists());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongConstraintProvided(): void
    {
        /** @var constraint $constrain */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new CategoryExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCategoryExistsValidation(): void
    {
        $this->repository->method('exists')->willReturn(true);
        $this->validator->validate(CategoryId::generate(), new CategoryExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCategoryNotExistsValidation(): void
    {
        $this->repository->method('load')->willReturn(null);
        $constraint = new CategoryExists();
        $value = CategoryId::generate();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return CategoryExistsValidator
     */
    protected function createValidator(): CategoryExistsValidator
    {
        return new CategoryExistsValidator($this->repository);
    }
}
