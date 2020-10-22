<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Validator;

use Ergonode\Product\Domain\Query\ProductBindingQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProductNoBindingsValidator extends ConstraintValidator
{
    private ProductBindingQueryInterface $query;

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

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (ProductId::isValid($value)) {
            $bindings = $this->query->getBindings(new ProductId($value));
        }

        if (empty($bindings)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
