<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Provider;

use Ergonode\Condition\Domain\Exception\ConditionStrategyNotFoundException;
use Ergonode\Condition\Domain\Service\ConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class ConditionConfigurationProvider
{
    /**
     * @var ConfigurationStrategyInterface[]
     */
    private $strategies;

    /**
     * @param ConfigurationStrategyInterface ...$strategies
     */
    public function __construct(ConfigurationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param Language $language
     * @param string   $type
     *
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
