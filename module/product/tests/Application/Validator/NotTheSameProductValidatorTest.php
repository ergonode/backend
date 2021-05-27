<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Validator;

use Ergonode\Product\Application\Validator\NotTheSameProduct;
use Ergonode\Product\Application\Validator\NotTheSameProductValidator;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class NotTheSameProductValidatorTest extends ConstraintValidatorTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new NotTheSameProduct(['aggregateId' => '8aec500d-735a-4323-a2ef-33322563e4de']));
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new NotTheSameProduct(['aggregateId' => '8aec500d-735a-4323-a2ef-33322563e4de']));

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->validator->validate(Uuid::uuid4()->toString(), new NotTheSameProduct(['aggregateId' => '8aec500d-735a-4323-a2ef-33322563e4de']));

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidation(): void
    {
        $constraint = new NotTheSameProduct(['aggregateId' => '8aec500d-735a-4323-a2ef-33322563e4de']);
        $value= '8aec500d-735a-4323-a2ef-33322563e4de';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }


    protected function createValidator(): NotTheSameProductValidator
    {
        return new NotTheSameProductValidator();
    }
}
