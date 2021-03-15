<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Application\Validator;

use Ergonode\Category\Domain\Query\TreeQueryInterface;
use Ergonode\Category\Application\Validator\CategoryTreeCodeUnique;
use Ergonode\Category\Application\Validator\CategoryTreeCodeUniqueValidator;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CategoryTreeCodeUniqueValidatorTest extends ConstraintValidatorTestCase
{
    private TreeQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(TreeQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(ValidatorException::class);
        $this->validator->validate(new \stdClass(), new CategoryTreeCodeUnique());
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
        $this->validator->validate('', new CategoryTreeCodeUnique());

        $this->assertNoViolation();
    }

    public function testTreeUniqueValidation(): void
    {
        $this->query->method('findTreeIdByCode')->willReturn(null);
        $this->validator->validate(CategoryTreeId::generate(), new CategoryTreeCodeUnique());

        $this->assertNoViolation();
    }

    public function testTreeNotUniqueValidation(): void
    {
        $value = CategoryTreeId::generate();
        $this->query->method('findTreeIdByCode')->willReturn($value);
        $constraint = new CategoryTreeCodeUnique();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    protected function createValidator(): CategoryTreeCodeUniqueValidator
    {
        return new CategoryTreeCodeUniqueValidator($this->query);
    }
}
