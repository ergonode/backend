<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Provider;

use Ergonode\Condition\Domain\Exception\ConditionStrategyNotFoundException;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class ConditionConfigurationProvider
{
    /**
     * @var ConditionConfigurationStrategyInterface[]
     */
    private array $strategies;

    public function __construct(ConditionConfigurationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @return array
     *
     * @throws ConditionStrategyNotFoundException
     */
    public function getConfiguration(Language $language, string $type): array
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy->getConfiguration($language);
            }
        }

        throw new ConditionStrategyNotFoundException($type);
    }
}
