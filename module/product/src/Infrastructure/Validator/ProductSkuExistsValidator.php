<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Domain\Query\ProductQueryInterface;

class ProductSkuExistsValidator extends ConstraintValidator
{
    private ProductQueryInterface $query;

    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                    $value
     * @param ProductExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductSkuExists) {
            throw new UnexpectedTypeException($constraint, ProductSkuExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!Sku::isValid($value)) {
            return;
        }

        $result = $this->query->findProductIdBySku(new Sku($value));

        if (!$result) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
