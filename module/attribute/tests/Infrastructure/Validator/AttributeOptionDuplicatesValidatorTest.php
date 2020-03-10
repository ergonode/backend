<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Application\Form\Model\AttributeOptionModel;
use Ergonode\Attribute\Infrastructure\Validator\AttributeOptionDuplicates;
use Ergonode\Attribute\Infrastructure\Validator\AttributeOptionDuplicatesValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class AttributeOptionDuplicatesValidatorTest extends ConstraintValidatorTestCase
{
    /**
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new AttributeOptionDuplicates());
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
        $this->validator->validate('', new AttributeOptionDuplicates());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValidation(): void
    {
        $value = new ArrayCollection([$this->createMock(AttributeOptionModel::class)]);
        $this->validator->validate($value, new AttributeOptionDuplicates());

        $this->assertNoViolation();
    }

    /**
     */
    public function testAttributeOptionDuplicatesValidation(): void
    {
        $attributeOptionModel1 = $this->createMock(AttributeOptionModel::class);
        $attributeOptionModel2 = $this->createMock(AttributeOptionModel::class);
        $attributeOptionModel1->key = 'key1';
        $attributeOptionModel2->key = 'key1';
        $value = new ArrayCollection([$attributeOptionModel1, $attributeOptionModel2]);
        $constraint = new AttributeOptionDuplicates();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message);
        $assertion->assertRaised();
    }

    /**
     * @return AttributeOptionDuplicatesValidator
     */
    protected function createValidator(): AttributeOptionDuplicatesValidator
    {
        return new AttributeOptionDuplicatesValidator();
    }
}
