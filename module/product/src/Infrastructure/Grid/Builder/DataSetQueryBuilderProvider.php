<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Builder;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;

class DataSetQueryBuilderProvider
{
    /**
     * @var AttributeDataSetQueryBuilderInterface[]
     */
    private array $strategies;

    public function __construct(AttributeDataSetQueryBuilderInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function provide(AbstractAttribute $attribute): AttributeDataSetQueryBuilderInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($attribute)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(
            sprintf('Can\' find query builder strategy for %s attribute', $attribute->getType())
        );
    }
}
