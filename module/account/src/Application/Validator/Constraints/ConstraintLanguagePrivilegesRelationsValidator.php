<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 */
class ConstraintLanguagePrivilegesRelationsValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConstraintLanguagePrivilegesRelations) {
            throw new UnexpectedTypeException($constraint, ConstraintLanguagePrivilegesRelations::class);
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        foreach ($value['edit'] as $item) {
            if (in_array($item, $value['read'], true)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $item)
                    ->addViolation();
            }
        }
    }
}
