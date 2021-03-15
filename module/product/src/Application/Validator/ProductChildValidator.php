<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Ergonode\Product\Application\Model\Product\Relation\ProductChildFormModel;

class ProductChildValidator extends ConstraintValidator
{
    /**
     * @param mixed                    $value
     * @param ProductExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductChild) {
            throw new UnexpectedTypeException($constraint, ProductChild::class);
        }

        if (!$value instanceof ProductChildFormModel) {
            throw new UnexpectedTypeException($value, ProductChildFormModel::class);
        }

        if (null === $value->childId) {
            return ;
        }

        if ($value->childId === $value->getParentId()->getValue()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('childId')
                ->addViolation();
        }
    }
}
