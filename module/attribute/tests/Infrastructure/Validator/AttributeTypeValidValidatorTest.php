<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Validator;

use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Validator\AttributeTypeValid;
use Ergonode\Attribute\Infrastructure\Validator\AttributeTypeValidValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class AttributeTypeValidValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(AttributeRepositoryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new AttributeTypeValid());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new AttributeTypeValid());

        $this->assertNoViolation();
    }

    /**
     */
    public function testAttributeTypeValidation(): void
    {
        $value = '0ae3491f-8052-402d-b84b-b2b36f673669';
        $attribute = $this->createMock(UnitAttribute::class);
        $attribute->method('getType')->willReturn('UNIT');
        $this->query->method('load')->willReturn($attribute);
        $this->validator->validate($value, new AttributeTypeValid(['type' => 'UNIT']));
        $this->assertNoViolation();
    }

    /**
     */
    public function testAttributeIdInvalidValidation(): void
    {
        $value = 'fes//efs..';
        $this->validator->validate($value, new AttributeTypeValid());
        $constraint = new AttributeTypeValid();
        $assertion = $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     */
    public function testAttributeTypeNotMatchValidation(): void
    {
        $value = '0ae3491f-8052-402d-b84b-b2b36f673669';
        $attribute = $this->createMock(UnitAttribute::class);
        $attribute->method('getType')->willReturn('PRICE');
        $this->query->method('load')->willReturn($attribute);
        $this->validator->validate($value, new AttributeTypeValid(['type' => 'UNIT']));
        $constraint = new AttributeTypeValid();
        $assertion = $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return AttributeTypeValidValidator
     */
    protected function createValidator(): AttributeTypeValidValidator
    {
        return new AttributeTypeValidValidator($this->query);
    }
}
