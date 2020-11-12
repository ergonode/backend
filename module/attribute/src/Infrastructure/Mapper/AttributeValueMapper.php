<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
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

    public function map(AbstractAttribute $attribute, array $values): ValueInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported(new AttributeType($attribute->getType()))) {
                return $strategy->map($values);
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find mapping strategy for attribute "%s" type', $attribute->getType())
        );
    }
}
