<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Condition;

/**
 */
interface ConditionInterface
{
    /**
     * @param array $configuration
     *
     * @return ConditionInterface
     */
    public static function createFormArray(array $configuration): self;

    /**
     * @return string
     */
    public function getType(): string;
}
