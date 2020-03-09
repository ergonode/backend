<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Condition\Application\Validator;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\View\AttributeViewModel;
use Ergonode\Condition\Application\Validator\ConstraintAttributeExistsCondition;
use Ergonode\Condition\Application\Validator\ConstraintAttributeExistsConditionValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class ConstraintAttributeExistsConditionValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var MockObject|AttributeQueryInterface
     */
    private MockObject $attributeQuery;

    /**
     */
    protected function setUp(): void
    {
        $this->attributeQuery = $this->createMock(AttributeQueryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ConstraintAttributeExistsCondition());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate([], $constraint);
    }

    /**
     */
    public function testAttributeExistsValidation(): void
    {
        $this
            ->attributeQuery
            ->expects($this->once())
            ->method('findAttributeByCode')
            ->willReturn($this->createMock(AttributeViewModel::class));
        $this->validator->validate(['code' => 'value'], new ConstraintAttributeExistsCondition());

        $this->assertNoViolation();
    }


    /**
     */
    public function testConstraintAttributeNotExistsConditionValidation(): void
    {
        $constraint = new ConstraintAttributeExistsCondition();
        $value = ['test' => 'example'];
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation('Attribute code not set');
        $assertion->assertRaised();
    }

    /**
     */
    public function testConstraintAttributeExistsConditionValidation(): void
    {
        $constraint = new ConstraintAttributeExistsCondition();
        $value = ['code' => 'value'];
        $this->attributeQuery->expects($this->once())->method('findAttributeByCode')->willReturn(null);
        $this->validator->validate($value, $constraint);

        $assertion = $this
            ->buildViolation('Attribute code "value" not found')
            ->setParameter('value', $value['code'])
            ->atPath('property.path.code');
        $assertion->assertRaised();
    }

    /**
     * @return ConstraintAttributeExistsConditionValidator
     */
    protected function createValidator(): ConstraintAttributeExistsConditionValidator
    {
        return new ConstraintAttributeExistsConditionValidator($this->attributeQuery);
    }
}
