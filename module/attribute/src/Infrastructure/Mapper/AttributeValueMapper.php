<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper;

use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Infrastructure\Mapper\Strategy\AttributeMapperStrategyInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

class AttributeValueMapper
{
    /**
     * @var AttributeMapperStrategyInterface[]
     */
    private array $strategies;

    public function __construct(AttributeMapperStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function map(AttributeType $type, array $values): ValueInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported($type)) {
                return $strategy->map($values);
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find mapping strategy for attribute "%s" type', $type->getValue())
        );
    }
}
