<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Segment\Domain\Service\SegmentConfigurationStrategyInterface;

/**
 */
class ConditionConfigurationProvider
{
    /**
     * @var SegmentConfigurationStrategyInterface[]
     */
    private $strategies;

    /**
     * @param SegmentConfigurationStrategyInterface ...$strategies
     */
    public function __construct(SegmentConfigurationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param Language $language
     * @param string   $type
     *
     * @return array
     */
    public function getConfiguration(Language $language, string $type): array
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupportedBy($type)) {
                return $strategy->getConfiguration($language);
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find configuration strategy for %s condition type', $type));
    }
}
