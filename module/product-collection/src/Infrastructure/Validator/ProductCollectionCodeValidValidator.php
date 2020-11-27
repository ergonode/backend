<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Validator;

use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionCodeValid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @deprecated
 */
class ProductCollectionCodeValidValidator extends ConstraintValidator
{
    /**
     * @param mixed                                 $value
     * @param ProductCollectionCodeValid|Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductCollectionCodeValid) {
            throw new UnexpectedTypeException($constraint, ProductCollectionCodeValid::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!ProductCollectionCode::isValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }
    }
}
