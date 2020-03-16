<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Infrastructure\Validator;

use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Infrastructure\Validator\SkuExists;
use Ergonode\Product\Infrastructure\Validator\SkuExistsValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class SkuExistsValidatorTest extends ConstraintValidatorTestCase
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
        $this->validator->validate(new \stdClass(), new SkuExists());
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
        $this->validator->validate('', new SkuExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testSkuExistsValidation(): void
    {
        $this->query->method('findBySku')->willReturn([]);
        $this->validator->validate(new Sku('Value'), new SkuExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testSkuNotExistsValidation(): void
    {
        $this->query->method('findBySku')->willReturn(['Value']);
        $constraint = new SkuExists();
        $value = new Sku('Value');
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return SkuExistsValidator
     */
    protected function createValidator(): SkuExistsValidator
    {
        return new SkuExistsValidator($this->query);
    }
}
