<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Validator;

use Ergonode\Multimedia\Application\Model\MultimediaModel;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

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

        if (!$value instanceof MultimediaModel) {
            throw new UnexpectedValueException($value, MultimediaModel::class);
        }

        if (null === $value->name) {
            return;
        }

        if ($value->multimedia->getName() === $value->name) {
            return;
        }

        if ($this->multimediaQuery->findIdByFilename($value->name)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->name)
                ->atPath('name')
                ->addViolation();
        }
    }
}
