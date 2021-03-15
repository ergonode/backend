<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Application\Validator;

use Ergonode\Channel\Application\Form\Model\SchedulerModel;
use Ergonode\Channel\Application\Validator\Scheduler;
use Ergonode\Channel\Application\Validator\SchedulerValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class SchedulerValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @dataProvider valuesProvider
     */
    public function testShouldValidate(SchedulerModel $model, bool $expectViolation): void
    {
        $scheduler = new Scheduler();

        $this->validator->validate($model, $scheduler);

        $assertion = $this->buildViolation($scheduler->message)->atPath('property.path.minute');

        $expectViolation ?
            $assertion->assertRaised() :
            $this->assertNoViolation();
    }

    public function valuesProvider(): array
    {
        $model = new class(0, 0) extends SchedulerModel {
            public function __construct(?int $hour, ?int $minute)
            {
                $this->hour = $hour;
                $this->minute = $minute;
            }
        };
        $class = get_class($model);

        return [
            [$model, true],
            [new $class(null, null), false],
            [new $class(0, null), false],
            [new $class(null, 0), false],
        ];
    }

    public function testShouldThrowExceptionOnInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(new SchedulerModel(), $this->createMock(Constraint::class));
    }

    public function testShouldThrowExceptionOnInvalidModel(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new Scheduler());
    }

    protected function createValidator(): SchedulerValidator
    {
        return new SchedulerValidator();
    }
}
