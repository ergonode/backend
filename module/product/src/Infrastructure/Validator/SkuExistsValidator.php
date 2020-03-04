<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Validator;

use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class SkuExistsValidator
 */
class SkuExistsValidator extends ConstraintValidator
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $query;

    /**
     * SkuExistsValidator constructor.
     *
     * @param ProductQueryInterface $query
     */
    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                $value
     * @param SkuExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof SkuExists) {
            throw new UnexpectedTypeException($constraint, SkuExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $sku = new \Ergonode\Product\Domain\ValueObject\Sku($value);

        $result = $this->query->findBySku($sku);

        if (!empty($result)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
