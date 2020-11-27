<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AttributeCodeConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed                        $value
     * @param AttributeCodeConstraint|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AttributeCodeConstraint) {
            throw new UnexpectedTypeException($constraint, AttributeCodeConstraint::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = trim((string) $value);
        if (!AttributeCode::isValid($value)) {
            if (mb_strlen($value) >= $constraint->max) {
                $this->context->buildViolation($constraint->maxMessage)
                    ->setParameter('{{ limit }}', $constraint->max)
                    ->addViolation();

                return;
            }

            if (mb_strlen($value) <= $constraint->min) {
                $this->context->buildViolation($constraint->minMessage)
                    ->setParameter('{{ limit }}', $constraint->min)
                    ->addViolation();

                return;
            }
            if (!preg_match($constraint->pattern, $value)) {
                $this->context->buildViolation($constraint->regexMessage)
                    ->addViolation();

                return;
            }

            $this->context->buildViolation($constraint->validMessage)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
