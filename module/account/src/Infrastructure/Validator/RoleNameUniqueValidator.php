<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Validator;

use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class RoleNameUniqueValidator extends ConstraintValidator
{
    /**
     * @var RoleQueryInterface
     */
    private RoleQueryInterface $query;

    /**
     * @param RoleQueryInterface $query
     */
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

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;


        $roleId = $this->query->findIdByRoleName($value);

        if ($roleId) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
