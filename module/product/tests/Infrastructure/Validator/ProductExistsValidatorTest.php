<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Product\Tests\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Product\Infrastructure\Validator\ProductExistsValidator;
use Ergonode\Product\Infrastructure\Validator\ProductExists;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductExistsValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var ProductRepositoryInterface|MockObject
     */
    private ProductRepositoryInterface $repository;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepositoryInterface::class);
        parent::setUp();
    }


    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new ProductExists());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new ProductExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testCorrectValueValidation(): void
    {
        $this->validator->validate('SKU', new ProductExists());

        $this->assertNoViolation();
    }

    /**
     */
    public function testInCorrectValueValidation(): void
    {
        $constraint = new ProductExists();
        $value = Uuid::uuid4()->toString();
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message);
        $assertion->assertRaised();
    }


    /**
     * @return ProductExistsValidator
     */
    protected function createValidator(): ProductExistsValidator
    {
        return new ProductExistsValidator($this->repository);
    }
}
