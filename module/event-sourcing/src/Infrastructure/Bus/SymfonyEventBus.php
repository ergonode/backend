<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Bus;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class SymfonyEventBus implements EventBusInterface
{
    /**
     * @var MessageBusInterface
     */
    private $eventBus;

    /**
     * @param MessageBusInterface $eventBus
     */
    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @param DomainEventInterface $event
     */
    public function dispatch(DomainEventInterface $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
