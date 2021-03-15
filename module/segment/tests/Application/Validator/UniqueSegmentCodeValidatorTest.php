<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Application\Validator;

use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Application\Validator\SegmentCodeUnique;
use Ergonode\Segment\Application\Validator\SegmentCodeUniqueValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UniqueSegmentCodeValidatorTest extends ConstraintValidatorTestCase
{
    private SegmentQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(SegmentQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new SegmentCodeUnique());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new SegmentCodeUnique());

        $this->assertNoViolation();
    }

    public function testExistsValidation(): void
    {
        $this->validator->validate(new SegmentCode('code'), new SegmentCodeUnique());

        $this->assertNoViolation();
    }

    public function testSegmentCodeUniqueValidation(): void
    {
        $this->query->method('isExistsByCode')->willReturn(true);
        $constraint = new SegmentCodeUnique();
        $value = new SegmentCode('code');
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): SegmentCodeUniqueValidator
    {
        return new SegmentCodeUniqueValidator($this->query);
    }
}
