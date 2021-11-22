<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Validator;

use Ergonode\Workflow\Application\Form\Model\TransitionFormModel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TransitionValidValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof TransitionValid) {
            throw new UnexpectedTypeException($constraint, TransitionValid::class);
        }

        if (!$value instanceof TransitionFormModel) {
            throw new UnexpectedTypeException($value, TransitionFormModel::class);
        }

        if (null === $value->from || null === $value->to) {
            return;
        }

        if ($value->from === $value->to) {
            $this->context->buildViolation($constraint->message)
                ->atPath('from')
                ->addViolation();
        }
    }
}
