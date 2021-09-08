<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper;

use Ergonode\Attribute\Infrastructure\Mapper\Strategy\AttributeMapperStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
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

    public function map(AttributeType $type, array $values, ?AggregateId $aggregateId = null): ValueInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported($type)) {
                    return $strategy->map($values, $aggregateId);/* @phpstan-ignore-line */
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find mapping strategy for attribute "%s" type', $type->getValue())
        );
    }
}
