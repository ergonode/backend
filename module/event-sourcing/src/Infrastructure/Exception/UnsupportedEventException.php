<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Exception;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;

/**
 */
class UnsupportedEventException extends \Exception
{
    /**
     * @param DomainEventInterface $event
     * @param string               $class
     */
    public function __construct(DomainEventInterface $event, string $class)
    {
        parent::__construct(sprintf('Required event "%s" not valid, give event "%s"', $class, \get_class($event)));
    }
}
