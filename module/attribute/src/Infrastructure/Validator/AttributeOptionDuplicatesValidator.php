<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 */
class AttributeOptionDuplicatesValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AttributeOptionDuplicates) {
            throw new UnexpectedTypeException($constraint, AttributeOptionDuplicates::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof ArrayCollection) {
            throw new UnexpectedValueException($value, 'ArrayCollection');
        }
        $keys = [];
        foreach ($value->getValues() as $val) {
            $keys[] = ($val->key);
        }

        if (array_unique($keys) !== $keys) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();

            return;
        }
    }
}
