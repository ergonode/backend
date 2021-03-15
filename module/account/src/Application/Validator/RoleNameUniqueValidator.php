<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator;

use Ergonode\Account\Application\Form\Model\RoleFormModel;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RoleNameUniqueValidator extends ConstraintValidator
{
    private RoleQueryInterface $query;

    public function __construct(RoleQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                     $value
     * @param RoleNameUnique|Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof RoleNameUnique) {
            throw new UnexpectedTypeException($constraint, RoleNameUnique::class);
        }

        if (!$value instanceof RoleFormModel) {
            throw new UnexpectedTypeException($value, RoleFormModel::class);
        }

        if (null === $value->name) {
            return;
        }

        $roleId = $this->query->findIdByRoleName($value->name);

        // phpcs:ignore
        if (null !== $roleId && $roleId != $value->getRoleId()) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->atPath('name')
                ->addViolation();
        }
    }
}
