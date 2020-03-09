<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Infrastructure\Validator;

use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionTypeCodeUnique;
use Ergonode\ProductCollection\Infrastructure\Validator\ProductCollectionTypeCodeUniqueValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class ProductCollectionTypeCodeUniqueValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ProductCollectionTypeRepositoryInterface|MockObject
     */
    private $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(ProductCollectionTypeRepositoryInterface::class);
        parent::setUp();
    }


    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new ProductCollectionTypeCodeUnique());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\UnexpectedTypeException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new ProductCollectionTypeCodeUnique());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new ProductCollectionTypeCodeUnique());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCodeExistsValidation(): void
    {
        $this->query->method('exists')->willReturn(true);
        $constraint = new ProductCollectionTypeCodeUnique();
        $value = 'code';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return ProductCollectionTypeCodeUniqueValidator
     */
    protected function createValidator(): ProductCollectionTypeCodeUniqueValidator
    {
        return new ProductCollectionTypeCodeUniqueValidator($this->query);
    }
}
