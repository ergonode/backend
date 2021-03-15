<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Application\Validator;

use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;
use Ergonode\ProductCollection\Application\Validator\ProductCollectionCodeUnique;
use Ergonode\ProductCollection\Application\Validator\ProductCollectionCodeUniqueValidator;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ProductCollectionCodeUniqueValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ProductCollectionQueryInterface|MockObject
     */
    private $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(ProductCollectionQueryInterface::class);
        parent::setUp();
    }


    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ProductCollectionCodeUnique());
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
        $this->validator->validate('', new ProductCollectionCodeUnique());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('code', new ProductCollectionCodeUnique());

        $this->assertNoViolation();
    }

    public function testCodeExistsValidation(): void
    {
        $collectionId = $this->createMock(ProductCollectionId::class);
        $this->query->method('findIdByCode')->willReturn($collectionId);
        $constraint = new ProductCollectionCodeUnique();
        $value = 'code';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    protected function createValidator(): ProductCollectionCodeUniqueValidator
    {
        return new ProductCollectionCodeUniqueValidator($this->query);
    }
}
