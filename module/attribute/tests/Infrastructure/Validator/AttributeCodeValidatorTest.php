<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Infrastructure\Validator;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Infrastructure\Validator\AttributeCode;
use Ergonode\Attribute\Infrastructure\Validator\AttributeCodeValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class AttributeCodeValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var AttributeQueryInterface|MockObject
     */
    private $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(AttributeQueryInterface::class);
        parent::setUp();
    }


    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new AttributeCode());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new AttributeCode());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new AttributeCode());

        $this->assertNoViolation();
    }

    /**
     */
    public function testInCorrectValueValidation(): void
    {
        $constraint = new AttributeCode();
        $value = 'SKU!!';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     */
    public function testCodeExistsValidation(): void
    {
        $this->query->method('checkAttributeExistsByCode')->willReturn(true);
        $constraint = new AttributeCode();
        $value = 'code';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    /**
     * @return AttributeCodeValidator
     */
    protected function createValidator(): AttributeCodeValidator
    {
        return new AttributeCodeValidator($this->query);
    }
}
