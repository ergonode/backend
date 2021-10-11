<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Validator;

use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MultimediaNameExistsValidator extends ConstraintValidator
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
        if (!$constraint instanceof MultimediaNameExists) {
            throw new UnexpectedTypeException($constraint, MultimediaNameExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $multimediaId = $this->multimediaQuery->findIdByFilename($value);

        if ($multimediaId) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
