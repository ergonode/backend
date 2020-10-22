<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain;

interface ConditionInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
