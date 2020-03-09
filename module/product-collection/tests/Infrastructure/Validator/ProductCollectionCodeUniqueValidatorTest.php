<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Infrastructure\Validator;

use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionCodeUnique;
use Ergonode\ProductCollection\Infrastructure\Validator\ProductCollectionCodeUniqueValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class ProductCollectionCodeUniqueValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ProductCollectionRepositoryInterface|MockObject
     */
    private $query;

    /**
     */
    protected function setUp(): void

    {
        $this->query = $this->createMock(ProductCollectionRepositoryInterface::class);
        parent::setUp();
    }


    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongValueProvided(): void
    {
        $this->validator->validate(new \stdClass(), new ProductCollectionCodeUnique());
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function testWrongConstraintProvided(): void
    {
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new ProductCollectionCodeUnique());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new ProductCollectionCodeUnique());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCodeExistsValidation(): void
    {
        $this->query->method('exists')->willReturn(true);
        $constraint = new ProductCollectionCodeUnique();
        $value = 'code';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return ProductCollectionCodeUniqueValidator
     */
    protected function createValidator(): ProductCollectionCodeUniqueValidator
    {
        return new ProductCollectionCodeUniqueValidator($this->query);
    }
}
