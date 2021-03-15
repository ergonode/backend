<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator;

use Ergonode\Account\Domain\Provider\PrivilegeGroupedByAreaProvider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PrivilegeRelationsValidator extends ConstraintValidator
{
    private PrivilegeGroupedByAreaProvider $privilegeGroupedByAreaProvider;

    public function __construct(PrivilegeGroupedByAreaProvider $privilegeGroupedByAreaProvider)
    {
        $this->privilegeGroupedByAreaProvider = $privilegeGroupedByAreaProvider;
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PrivilegeRelations) {
            throw new UnexpectedTypeException($constraint, PrivilegeRelations::class);
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        array_walk($value, function (&$item): void {
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
