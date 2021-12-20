<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Validator;

use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class StatusIdsContainAllValidator extends ConstraintValidator
{
    private StatusQueryInterface $query;

    public function __construct(StatusQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof StatusIdsContainAll) {
            throw new UnexpectedTypeException($constraint, StatusIdsContainAll::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        $allStatusIds = $this->query->getAllStatusIds();

        sort($allStatusIds);
        sort($value);

        if ($value !== $allStatusIds) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
