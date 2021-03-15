<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition;

use Ergonode\Core\Domain\ValueObject\Language;

interface ConditionConfigurationStrategyInterface
{
    public function supports(string $type): bool;

    /**
     * @return array
     */
    public function getConfiguration(Language $language): array;
}
