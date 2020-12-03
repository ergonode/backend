<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Application\Validator;

use Ergonode\Category\Application\Validator\UniqueCategoryCodeConstraint;
use Ergonode\Category\Application\Validator\UniqueCategoryCodeConstraintValidator;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UniqueCategoryCodeConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var CategoryQueryInterface|MockObject
     */
    private CategoryQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(CategoryQueryInterface::class);
        parent::setUp();
    }
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new UniqueCategoryCodeConstraint());
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
        $this->validator->validate('', new UniqueCategoryCodeConstraint());

        $this->assertNoViolation();
    }

    public function testCategoryNotExistsValidation(): void
    {
        $this->query->method('findIdByCode')->willReturn($this->createMock(CategoryId::class));
        $constraint = new UniqueCategoryCodeConstraint();
        $value = 'value';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): UniqueCategoryCodeConstraintValidator
    {
        return new UniqueCategoryCodeConstraintValidator($this->query);
    }
}
