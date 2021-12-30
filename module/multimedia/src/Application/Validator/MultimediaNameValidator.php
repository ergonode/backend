<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Validator;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MultimediaNameValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof MultimediaName) {
            throw new UnexpectedTypeException($constraint, MultimediaName::class);
        }

        if (null === $value) {
            return;
        }

        $value = (string) $value;

        if ($constraint->max < strlen($value)) {
            $this->context->buildViolation($constraint->messageMax)
                ->setParameter('{{ limit }}', (string) $constraint->max)
                ->addViolation();
        } elseif (mb_strpos($value, '/') !== false) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
