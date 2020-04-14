<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 */
class ConstraintLanguagePrivilegesValidValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConstraintLanguagePrivilegesValid) {
            throw new UnexpectedTypeException($constraint, ConstraintLanguagePrivilegesValid::class);
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }
        if (!LanguagePrivileges::isValid($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
