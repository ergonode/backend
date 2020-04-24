<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Validator;

use Ergonode\ProductCollection\Application\Model\ProductCollectionElementMultipleCreateFormModel;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\SegmentOrSkuAtLeastOne;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class SegmentOrSkuAtLeastOneValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof SegmentOrSkuAtLeastOne) {
            throw new UnexpectedTypeException($constraint, SegmentOrSkuAtLeastOne::class);
        }

        if (!$value instanceof ProductCollectionElementMultipleCreateFormModel) {
            throw new UnexpectedTypeException($value, ProductCollectionElementMultipleCreateFormModel::class);
        }

        if (null === $value->skus && [] === $value->segments) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
