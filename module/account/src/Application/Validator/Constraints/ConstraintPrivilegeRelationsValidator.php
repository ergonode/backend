<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Ergonode\Account\Domain\Provider\PrivilegeGroupedByAreaProvider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ConstraintPrivilegeRelationsValidator extends ConstraintValidator
{
    /**
     * @var PrivilegeGroupedByAreaProvider
     */
    private PrivilegeGroupedByAreaProvider $privilegeGroupedByAreaProvider;

    /**
     * @param PrivilegeGroupedByAreaProvider $privilegeGroupedByAreaProvider
     */
    public function __construct(PrivilegeGroupedByAreaProvider $privilegeGroupedByAreaProvider)
    {
        $this->privilegeGroupedByAreaProvider = $privilegeGroupedByAreaProvider;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConstraintPrivilegeRelations) {
            throw new UnexpectedTypeException($constraint, ConstraintPrivilegeRelations::class);
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        array_walk($value, function (&$item) {
            $item = (string) $item;
        });

        foreach ($this->privilegeGroupedByAreaProvider->provide() as $privileges) {
            $intersect = array_intersect($privileges, $value);

            if (empty($intersect)) {
                continue;
            }

            if (!in_array($privileges['read'], $intersect, true)) {
                $this->context->buildViolation($constraint->message)->addViolation();
                break;
            }
        }
    }
}
