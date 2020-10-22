<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Validator\Constraint\LanguageCodeConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LanguageCodeValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof LanguageCodeConstraint) {
            throw new UnexpectedTypeException($constraint, LanguageCodeConstraint::class);
        }

        if (empty($value)) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!Language::isValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ language }}', $value)
                ->addViolation();
        }
    }
}
