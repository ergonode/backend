<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Provider;

use Ergonode\Importer\Infrastructure\Proposal\AttributeProposalStrategyInterface;

class AttributeProposalProvider
{
    /**
     * @var AttributeProposalStrategyInterface[]
     */
    private array $strategies;

    public function __construct(AttributeProposalStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param array $values
     */
    public function provide(string $name, array $values): AttributeProposalStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($name, $values)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find proposal for column %s values', $name));
    }
}
