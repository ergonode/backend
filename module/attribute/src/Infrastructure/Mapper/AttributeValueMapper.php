<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Infrastructure\Mapper\Strategy\ContextAwareAttributeMapperStrategyInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Webmozart\Assert\Assert;

class AttributeValueMapper
{
    /**
     * @var ContextAwareAttributeMapperStrategyInterface[]
     */
    private iterable $strategies;

    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, ContextAwareAttributeMapperStrategyInterface::class);

        $this->strategies = $strategies;
    }

    public function map(AttributeType $type, array $values, ProductId $productId): ValueInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported($type)) {
                return $strategy->map($values, $productId);
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find mapping strategy for attribute "%s" type', $type->getValue())
        );
    }
}
