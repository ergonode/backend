<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition;

use Symfony\Component\Validator\Constraint;

interface ConditionValidatorStrategyInterface
{
    public function supports(string $type): bool;

    /**
     * @param array $data
     */
    public function build(array $data): Constraint;

    /**
     * Returned class must implement same properties as validated object in the build
     */
    public function getValidatedClass(): string;
}
