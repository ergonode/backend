<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Ergonode\Product\Application\Model\Product\Binding\ProductBindFormModel;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProductHasChildrenValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductHasChildren) {
            throw new UnexpectedTypeException($constraint, ProductHasChildren::class);
        }

        if (!$value instanceof ProductBindFormModel) {
            throw new UnexpectedTypeException($value, ProductBindFormModel::class);
        }

        if (!$value->product instanceof VariableProduct) {
            throw new UnexpectedTypeException($value->product, VariableProduct::class);
        }

        if (null === $value->bindId) {
            return;
        }
        if (!empty($value->product->getChildren())) {
            $this->context->buildViolation($constraint->message)
                ->atPath('bindId')
                ->addViolation();
        }
    }
}
