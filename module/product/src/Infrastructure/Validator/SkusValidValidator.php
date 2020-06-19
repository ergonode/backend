<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Validator;

use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 */
class SkusValidValidator extends ConstraintValidator
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $query;

    /**
     * @param ProductQueryInterface $query
     */
    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                $value
     * @param SkusValid|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof SkusValid) {
            throw new UnexpectedTypeException($constraint, SkusValid::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;
        $skus = array_map('trim', explode(',', $value));

        foreach ($skus as $sku) {
            if (!\Ergonode\Product\Domain\ValueObject\Sku::isValid($sku)) {
                $this->context->buildViolation($constraint->invalidMessage)
                    ->setParameter('{{ value }}', $sku)
                    ->addViolation();
            } else {
                $result = $this->query->findProductIdBySku(new \Ergonode\Product\Domain\ValueObject\Sku($sku));

                if (!$result) {
                    $this->context->buildViolation($constraint->notExistsMessage)
                        ->setParameter('{{ value }}', $sku)
                        ->addViolation();
                }
            }
        }
    }
}
