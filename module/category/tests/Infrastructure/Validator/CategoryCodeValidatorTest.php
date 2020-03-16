<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Validator;

use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Infrastructure\Validator\CategoryCode;
use Ergonode\Category\Infrastructure\Validator\CategoryCodeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

/**
 */
class CategoryCodeValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var CategoryQueryInterface|MockObject
     */
    private CategoryQueryInterface $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(CategoryQueryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new CategoryCode());
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
        $this->validator->validate('', new CategoryCode());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCategoryCodeInvalidValidation(): void
    {
        $constraint = new CategoryCode();
        $value = 'P0kt9KzF1aUomd9mHQ1PKGhvgKKH4WtjrjExSWnokxv568g3WGLtZGfl9sJCBm4QqwZWX1Vks4UeRVIJJhEkTHjIkW5e4EKFlDEZ'.
            'ZWeW5pl8FyyG8j534Zr6bTarhDl236Ma2U8ECDtTbMkr6ZN8X4PG7C2QFRCmU9rV15HwZXQibNH9hVwdDsWlIY0flMi6GoTpaunky1dyI'.
            'wOW6lCeios94BeqRHN4iFTG7tSWhFTgUPbl5cXkDEasKkYxH7wPJswZ';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     */
    public function testCategoryNotExistsValidation(): void
    {
        $this->query->method('findIdByCode')->willReturn($this->createMock(CategoryId::class));
        $constraint = new CategoryCode();
        $value = 'value';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    /**
     * @return CategoryCodeValidator
     */
    protected function createValidator(): CategoryCodeValidator
    {
        return new CategoryCodeValidator($this->query);
    }
}
