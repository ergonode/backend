<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Segment\Infrastructure\Validator;

use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Infrastructure\Validator\UniqueSegmentCode;
use Ergonode\Segment\Infrastructure\Validator\UniqueSegmentCodeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class UniqueSegmentCodeValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var SegmentQueryInterface
     */
    private SegmentQueryInterface $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(SegmentQueryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new UniqueSegmentCode());
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
        $this->validator->validate('', new UniqueSegmentCode());

        $this->assertNoViolation();
    }

    /**
     */
    public function testSegmentCodeNotValidValidation(): void
    {
        $constraint = new UniqueSegmentCode();
        $value =
            '5XPeqpDgL2sJXSKSkgq7Wf3J0oI9fSAMznAdUJ16Jynr6ZYmL87ougT4WlHylg2iIYEkIhDy6icd5yhw2Bnx0l9agBlYf80MIqBtN';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     */
    public function testStatusExistsValidation(): void
    {
        $this->validator->validate(new SegmentCode('code'), new UniqueSegmentCode());

        $this->assertNoViolation();
    }

    /**
     */
    public function testUniqueSegmentCodeValidation(): void
    {
        $this->query->method('isExistsByCode')->willReturn(true);
        $constraint = new UniqueSegmentCode();
        $value = new SegmentCode('code');
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->uniqueMessage);
        $assertion->assertRaised();
    }

    /**
     * @return UniqueSegmentCodeValidator
     */
    protected function createValidator(): UniqueSegmentCodeValidator
    {
        return new UniqueSegmentCodeValidator($this->query);
    }
}
