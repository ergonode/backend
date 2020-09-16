<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Validator;

use Ergonode\Product\Application\Model\Product\Relation\ProductChildFormModel;
use Ergonode\Product\Domain\Query\ProductBindingQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class ProductNoBindingsValidator extends ConstraintValidator
{
    /**
     * @var ProductBindingQueryInterface
     */
    private ProductBindingQueryInterface $query;

    /**
     * @param ProductBindingQueryInterface $query
     */
    public function __construct(ProductBindingQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                    $value
     * @param ProductExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductNoBindings) {
            throw new UnexpectedTypeException($constraint, ProductNoBindings::class);
        }

        if (!$value instanceof ProductChildFormModel) {
            throw new UnexpectedTypeException($value, ProductChildFormModel::class);
        }

        if (null === $value->childId) {
            return;
        }
        $bindings = $this->query->getBindings($value->getParentId());

        if (empty($bindings)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
