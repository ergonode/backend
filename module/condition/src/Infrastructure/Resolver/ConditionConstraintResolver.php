<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Resolver;

use Ergonode\Condition\Infrastructure\Builder\ConditionValidatorBuilderInterface;

/**
 */
class ConditionConstraintResolver
{
    /**
     * @var array
     */
    private $constraints = [];

    /**
     * @param string                             $type
     * @param ConditionValidatorBuilderInterface $constraintClass
     */
    public function set(string $type, ConditionValidatorBuilderInterface $constraintClass): void
    {
        $this->constraints[$type] = $constraintClass;
    }

    /**
     * @param string $type
     *
     * @return ConditionValidatorBuilderInterface
     *
     * @throws \OutOfBoundsException
     */
    public function resolve(string $type): ConditionValidatorBuilderInterface
    {
        if (!array_key_exists($type, $this->constraints)) {
            throw new \OutOfBoundsException(sprintf('Constraint by condition type "%s" not found', $type));
        }

        return $this->constraints[$type];
    }
}
