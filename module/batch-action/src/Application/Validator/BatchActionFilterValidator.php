<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Validator;

use Ergonode\BatchAction\Application\Form\Model\BatchActionFilterFormModel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BatchActionFilterValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof BatchActionFilter) {
            throw new UnexpectedTypeException($constraint, BatchActionFilter::class);
        }

        if (!$value instanceof BatchActionFilterFormModel) {
            return;
        }
        if (null !== $value->query || null !== $value->ids) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
