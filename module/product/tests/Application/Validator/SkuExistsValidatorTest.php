<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Product\Application\Validator\SkuExistsValidator;
use Ergonode\Product\Application\Validator\SkuExists;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class SkuExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ProductQueryInterface|MockObject
     */
    private ProductQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(ProductQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new SkuExists());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new SkuExists());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $this->query->method('findProductIdBySku')->willReturn(new ProductId($uuid));
        $this->validator->validate('SKU', new SkuExists());

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $constraint = new SkuExists();
        $value = 'SKU';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message);
        $assertion->assertRaised();
    }


    protected function createValidator(): SkuExistsValidator
    {
        return new SkuExistsValidator($this->query);
    }
}
