<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper;

use Ergonode\Attribute\Infrastructure\Mapper\Strategy\AttributeMapperStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Infrastructure\Mapper\Strategy\ContextAwareAttributeMapperStrategyInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Webmozart\Assert\Assert;

class AttributeValueMapper
{
    /**
     * @var AttributeMapperStrategyInterface[]
     */
    private iterable $strategies;

    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, AttributeMapperStrategyInterface::class);

        $this->strategies = $strategies;
    }

    public function map(AttributeType $type, array $values, ?AggregateId $productId): ValueInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported($type)) {
                if ($strategy instanceof ContextAwareAttributeMapperStrategyInterface) {
                    return $strategy->map($values, $productId);
                }

                return $strategy->map($values);
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find mapping strategy for attribute "%s" type', $type->getValue())
        );
    }
}
