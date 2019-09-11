<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Resolver;

use Symfony\Component\Validator\Constraint;

/**
 */
class ConditionConstraintResolver
{
    /**
     * @var array
     */
    private $constraints = [];

    /**
     * @param string $type
     * @param string $constraintClass
     */
    public function set(string $type, string $constraintClass): void
    {
        $this->constraints[$type] = $constraintClass;
    }

    /**
     * @param string $type
     *
     * @return Constraint
     *
     * @throws \OutOfBoundsException
     */
    public function resolve(string $type): Constraint
    {
        if (!array_key_exists($type, $this->constraints)) {
            throw new \OutOfBoundsException(sprintf('Constraint by condition type "%s" not found', $type));
        }

        $class = $this->constraints[$type];

        return new $class();
    }
}
