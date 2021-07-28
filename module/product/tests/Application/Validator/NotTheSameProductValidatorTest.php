<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Validator;

use Ergonode\Category\Domain\Entity\Category;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\Product\Application\Validator\NotTheSameProduct;
use Ergonode\Product\Application\Validator\NotTheSameProductValidator;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class NotTheSameProductValidatorTest extends ConstraintValidatorTestCase
{
    private EventStoreManagerInterface $manager;

    private AggregateId $aggregateId;

    protected function setUp(): void
    {
        $this->manager = $this->createMock(EventStoreManagerInterface::class);
        $this->aggregateId = new AggregateId('8aec500d-735a-4323-a2ef-33322563e4de');
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(
            new \stdClass(),
            new NotTheSameProduct(['aggregateId' => $this->aggregateId])
        );
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate(
            '',
            new NotTheSameProduct(['aggregateId' => $this->aggregateId])
        );

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->manager->method('load')->willReturn($this->createMock(AbstractProduct::class));
        $this->validator->validate(
            Uuid::uuid4()->toString(),
            new NotTheSameProduct(['aggregateId' => $this->aggregateId])
        );

        $this->assertNoViolation();
    }

    public function testInCorrectAggregateIdValidation(): void
    {
        $this->manager->method('load')->willReturn($this->createMock(Category::class));
        $constraint = new NotTheSameProduct(['aggregateId' => $this->aggregateId]);
        $this->validator->validate(Uuid::uuid4()->toString(), $constraint);

        $assertion = $this->buildViolation($constraint->messageNotProduct)
            ->setParameter('{{ value }}', $this->aggregateId->getValue());
        $assertion->assertRaised();
    }

    public function testInCorrectValueValidation(): void
    {
        $this->manager->method('load')->willReturn($this->createMock(AbstractProduct::class));
        $constraint = new NotTheSameProduct(['aggregateId' => $this->aggregateId]);
        $this->validator->validate($this->aggregateId, $constraint);

        $assertion = $this->buildViolation($constraint->messageSameProduct);
        $assertion->assertRaised();
    }


    protected function createValidator(): NotTheSameProductValidator
    {
        return new NotTheSameProductValidator($this->manager);
    }
}
