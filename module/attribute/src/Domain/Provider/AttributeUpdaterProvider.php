<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Provider;

use Ergonode\Attribute\Domain\AttributeUpdaterInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

/**
 */
class AttributeUpdaterProvider
{
    /**
     * @var AttributeUpdaterInterface[]
     */
    private array $updaters;

    /**
     * @param AttributeUpdaterInterface ...$updaters
     */
    public function __construct(AttributeUpdaterInterface ...$updaters)
    {

        $this->updaters = $updaters;
    }

    /**
     * @param AttributeType $type
     *
     * @return AttributeUpdaterInterface
     */
    public function provide(AttributeType $type): AttributeUpdaterInterface
    {
        foreach ($this->updaters as $updater) {
            if ($updater->isSupported($type)) {
                return $updater;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find updater for attribute %s', $type->getValue()));
    }
}
