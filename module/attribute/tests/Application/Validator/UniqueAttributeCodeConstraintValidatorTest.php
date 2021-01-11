<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Application\Validator;

use Ergonode\Attribute\Application\Validator\UniqueAttributeCodeConstraint;
use Ergonode\Attribute\Application\Validator\UniqueAttributeCodeConstraintValidator;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UniqueAttributeCodeConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var AttributeQueryInterface|MockObject
     */
    private $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(AttributeQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new UniqueAttributeCodeConstraint());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new UniqueAttributeCodeConstraint());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new UniqueAttributeCodeConstraint());

        $this->assertNoViolation();
    }


    public function testCodeExistsValidation(): void
    {
        $this->query->method('checkAttributeExistsByCode')->willReturn(true);
        $constraint = new UniqueAttributeCodeConstraint();
        $value = 'code';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): UniqueAttributeCodeConstraintValidator
    {
        return new UniqueAttributeCodeConstraintValidator($this->query);
    }
}
