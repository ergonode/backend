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

class SkuValidator extends ConstraintValidator
{
    /**
     * @param mixed          $value
     * @param Sku|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Sku) {
            throw new UnexpectedTypeException($constraint, Sku::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!\Ergonode\Product\Domain\ValueObject\Sku::isValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ sku }}', $value)
                ->addViolation();
        }
    }
}
