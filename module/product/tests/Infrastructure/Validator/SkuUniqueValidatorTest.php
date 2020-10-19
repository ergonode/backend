<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Infrastructure\Validator;

use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Infrastructure\Validator\SkuUnique;
use Ergonode\Product\Infrastructure\Validator\SkuUniqueValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class SkuUniqueValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(ProductQueryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new SkuUnique());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new SkuUnique());

        self::assertNoViolation();
    }

    /**
     */
    public function testSkuNotExistsValidation(): void
    {
        $this->query->method('findProductIdBySku')->willReturn(null);
        $this->validator->validate(new Sku('Value'), new SkuUnique());

        self::assertNoViolation();
    }

    /**
     */
    public function testSkuUniqueValidation(): void
    {
        $productId = $this->createMock(ProductId::class);
        $this->query->method('findProductIdBySku')->willReturn($productId);
        $constraint = new SkuUnique();
        $value = new Sku('Value');
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return SkuUniqueValidator
     */
    protected function createValidator(): SkuUniqueValidator
    {
        return new SkuUniqueValidator($this->query);
    }
}
