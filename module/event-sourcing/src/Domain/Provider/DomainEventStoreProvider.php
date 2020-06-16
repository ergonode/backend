<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Domain\Provider;

use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;

/**
 */
class DomainEventStoreProvider
{
    /**
     * @var DomainEventStoreInterface[]
     */
    private array $items;

    /**
     * @param array|DomainEventStoreInterface[] $items
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * @param string $class
     *
     * @return DomainEventStoreInterface
     */
    public function provide(?string $class = null): DomainEventStoreInterface
    {
        return reset($this->items);
    }
}
