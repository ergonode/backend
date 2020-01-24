<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento2\Infrastructure\Provider;

use Ergonode\ImporterMagento2\Infrastructure\Proposal\AttributeProposalStrategyInterface;

/**
 */
class AttributeProposalProvider
{
    /**
     * @var AttributeProposalStrategyInterface[]
     */
    private array $strategies;

    /**
     * @param AttributeProposalStrategyInterface ...$strategies
     */
    public function __construct(AttributeProposalStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param string $name
     * @param array  $values
     *
     * @return AttributeProposalStrategyInterface
     */
    public function provide(string $name, array $values): AttributeProposalStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if($strategy->support($name, $values)) {
                return $strategy;
            }
        }

        var_dump(count($this->strategies));

        throw new \RuntimeException(sprintf('Can\'t find proposal for column %s values', $name));
    }


}