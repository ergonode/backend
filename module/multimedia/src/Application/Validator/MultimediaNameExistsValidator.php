<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Validator;

use Ergonode\Multimedia\Application\Model\MultimediaModel;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MultimediaNameExistsValidator extends ConstraintValidator
{
    private MultimediaQueryInterface $multimediaQuery;
    private MultimediaRepositoryInterface $multimediaRepository;

    public function __construct(
        MultimediaQueryInterface $multimediaQuery,
        MultimediaRepositoryInterface $multimediaRepository
    ) {
        $this->multimediaQuery = $multimediaQuery;
        $this->multimediaRepository = $multimediaRepository;
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
            throw new \Symfony\Component\Validator\Exception\UnexpectedTypeException($value, MultimediaModel::class);
        }

        if (null === $value->name || null === $value->multimediaId) {
            return;
        }

        $multimedia = $this->multimediaRepository->load($value->multimediaId);

        if (!isset($multimedia) || $multimedia->getName() === $value->name) {
            return;
        }

        if ($this->multimediaQuery->findIdByFilename($value->name)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->name)
                ->addViolation();
        }
    }
}
