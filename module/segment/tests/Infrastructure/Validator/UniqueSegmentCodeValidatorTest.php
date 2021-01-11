<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Infrastructure\Validator;

use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Infrastructure\Validator\UniqueSegmentCode;
use Ergonode\Segment\Infrastructure\Validator\UniqueSegmentCodeValidator;
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
        $this->validator->validate(new \stdClass(), new UniqueSegmentCode());
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
        $this->validator->validate('', new UniqueSegmentCode());

        $this->assertNoViolation();
    }

    public function testStatusExistsValidation(): void
    {
        $this->validator->validate(new SegmentCode('code'), new UniqueSegmentCode());

        $this->assertNoViolation();
    }

    public function testUniqueSegmentCodeValidation(): void
    {
        $this->query->method('isExistsByCode')->willReturn(true);
        $constraint = new UniqueSegmentCode();
        $value = new SegmentCode('code');
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): UniqueSegmentCodeValidator
    {
        return new UniqueSegmentCodeValidator($this->query);
    }
}
