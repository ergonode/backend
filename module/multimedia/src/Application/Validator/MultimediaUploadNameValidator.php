<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Validator;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MultimediaUploadNameValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof MultimediaUploadName) {
            throw new UnexpectedTypeException($constraint, MultimediaUploadName::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof UploadedFile) {
            throw new UnexpectedTypeException($value, UploadedFile::class);
        }

        if ($constraint->max < strlen($value->getClientOriginalName())) {
            $this->context->buildViolation($constraint->messageMax)
                ->setParameter('{{ limit }}', (string) $constraint->max)
                ->addViolation();
        } elseif (mb_strpos($value->getClientOriginalName(), '/') !== false) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
