<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Validator;

use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MultimediaFileExistsValidator extends ConstraintValidator
{
    private MultimediaQueryInterface $multimediaQuery;

    public function __construct(
        MultimediaQueryInterface $multimediaQuery
    ) {
        $this->multimediaQuery = $multimediaQuery;
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof MultimediaFileExists) {
            throw new UnexpectedTypeException($constraint, MultimediaFileExists::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof UploadedFile) {
            throw new UnexpectedTypeException($value, UploadedFile::class);
        }

        if ($this->multimediaQuery->findIdByFilename($value->getClientOriginalName())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->getClientOriginalName())
                ->addViolation();
        }
    }
}
