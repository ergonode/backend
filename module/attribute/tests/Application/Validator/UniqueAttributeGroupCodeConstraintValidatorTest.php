<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Application\Validator;

use Ergonode\Attribute\Application\Validator\UniqueAttributeGroupCodeConstraint;
use Ergonode\Attribute\Application\Validator\UniqueAttributeGroupCodeConstraintValidator;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UniqueAttributeGroupCodeConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var AttributeGroupQueryInterface|MockObject
     */
    private $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(AttributeGroupQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new UniqueAttributeGroupCodeConstraint());
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
        $this->validator->validate('', new UniqueAttributeGroupCodeConstraint());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new UniqueAttributeGroupCodeConstraint());

        $this->assertNoViolation();
    }

    public function testCodeExistsValidation(): void
    {
        $this->query->method('checkAttributeGroupExistsByCode')->willReturn(true);
        $constraint = new UniqueAttributeGroupCodeConstraint();
        $value = 'code';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): UniqueAttributeGroupCodeConstraintValidator
    {
        return new UniqueAttributeGroupCodeConstraintValidator($this->query);
    }
}
