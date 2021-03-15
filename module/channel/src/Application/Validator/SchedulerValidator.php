<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Validator;

use Ergonode\Channel\Application\Form\Model\SchedulerModel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SchedulerValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Scheduler) {
            throw new UnexpectedTypeException($constraint, Scheduler::class);
        }

        if (!$value instanceof SchedulerModel) {
            throw new UnexpectedTypeException($value, SchedulerModel::class);
        }

        if (0 !== $value->hour || null === $value->minute || 0 < $value->minute) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->atPath('minute')
            ->addViolation();
    }
}
