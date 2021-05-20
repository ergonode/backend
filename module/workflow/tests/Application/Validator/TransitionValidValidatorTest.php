<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Application\Validator;

use Ergonode\Workflow\Application\Form\Model\TransitionFormModel;
use Ergonode\Workflow\Application\Validator\TransitionValid;
use Ergonode\Workflow\Application\Validator\TransitionValidValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class TransitionValidValidatorTest extends ConstraintValidatorTestCase
{
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new TransitionValid());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    public function testCorrectValidation(): void
    {
        $model = $this->createMock(TransitionFormModel::class);
        $model->source = 'test1';
        $model->destination = 'test2';
        $this->validator->validate($model, new TransitionValid());
        $this->assertNoViolation();
    }

    public function testTransitionValidValidation(): void
    {
        $model = $this->createMock(TransitionFormModel::class);
        $model->source = 'test';
        $model->destination = 'test';
        $constraint = new TransitionValid();

        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->message)
            ->atPath('property.path.source');
        $assertion->assertRaised();
    }

    protected function createValidator(): TransitionValidValidator
    {
        return new TransitionValidValidator();
    }
}
