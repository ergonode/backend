<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Validator;

use Ergonode\Attribute\Application\Form\Model\Option\SimpleOptionModel;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Infrastructure\Validator\OptionCodeExists;
use Ergonode\Attribute\Infrastructure\Validator\OptionCodeExistsValidator;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class OptionCodeExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(OptionQueryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new OptionCodeExists());
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
        $value = $this->getMockBuilder(SimpleOptionModel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value->code = null;
        $this->validator->validate($value, new OptionCodeExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testOptionIdNotExists(): void
    {
        $value = $this->getMockBuilder(SimpleOptionModel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value->optionId = null;
        $value->code = 'code';
        $value->attributeId = new AttributeId('bd50b704-f225-49a1-8d1b-ae43adc3f0e1');
        $this->query->method('findIdByAttributeIdAndCode')->willReturn(null);
        $this->validator->validate($value, new OptionCodeExists());
        $this->assertNoViolation();
    }

    /**
     */
    public function testOptionIdNotExistsFoundOptionId(): void
    {
        $value = $this->getMockBuilder(SimpleOptionModel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value->optionId = null;
        $value->code = 'code';
        $value->attributeId = new AttributeId('bd50b704-f225-49a1-8d1b-ae43adc3f0e1');
        $this->query
            ->method('findIdByAttributeIdAndCode')
            ->willReturn(new AggregateId('0c6e599f-f821-4337-bc05-c3a0b9ac076b'));
        $constraint = new OptionCodeExists();
        $this->validator->validate($value, $constraint);
        $assertion = $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value->code);
        $assertion->assertRaised();
    }

    /**
     */
    public function testAttributeIdInvalidValidation(): void
    {
        $value = $this->getMockBuilder(SimpleOptionModel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $value->optionId = new AggregateId('0ae3491f-8052-402d-b84b-b2b36f673669');
        $value->code = 'code';
        $value->attributeId = new AttributeId('bd50b704-f225-49a1-8d1b-ae43adc3f0e1');
        $this->query
            ->method('findIdByAttributeIdAndCode')
            ->willReturn(new AggregateId('0c6e599f-f821-4337-bc05-c3a0b9ac076b'));
        $constraint = new OptionCodeExists();
        $this->validator->validate($value, $constraint);
        $assertion = $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value->code);
        $assertion->assertRaised();
    }

    /**
     * @return OptionCodeExistsValidator
     */
    protected function createValidator(): OptionCodeExistsValidator
    {
        return new OptionCodeExistsValidator($this->query);
    }
}
