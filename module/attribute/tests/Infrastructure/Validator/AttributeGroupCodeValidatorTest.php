<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Validator;

use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Attribute\Infrastructure\Validator\AttributeGroupCode;
use Ergonode\Attribute\Infrastructure\Validator\AttributeGroupCodeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class AttributeGroupCodeValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var AttributeGroupQueryInterface
     */
    private AttributeGroupQueryInterface $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(AttributeGroupQueryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new AttributeGroupCode());
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
        $this->validator->validate('', new AttributeGroupCode());

        $this->assertNoViolation();
    }

    /**
     */
    public function testAttributeGropuCodeValidation(): void
    {
        $attributeGroupCode = 'code';
        $this->validator->validate($attributeGroupCode, new AttributeGroupCode());

        $this->assertNoViolation();
    }

    /**
     */
    public function testAttributeGroupCodeInvalidValidation(): void
    {
        $value = 'fes//efs..';
        $this->validator->validate($value, new AttributeGroupCode());
        $constraint = new AttributeGroupCode();
        $assertion = $this->buildViolation($constraint->validMessage)
            ->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     */
    public function testAttributeGroupCodeInvalidGroupExistsValidation(): void
    {
        $value = 'code';
        $this->query->method('checkAttributeGroupExistsByCode')->willReturn(true);
        $this->validator->validate($value, new AttributeGroupCode());
        $constraint = new AttributeGroupCode();
        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    /**
     * @return AttributeGroupCodeValidator
     */
    protected function createValidator(): AttributeGroupCodeValidator
    {
        return new AttributeGroupCodeValidator($this->query);
    }
}
