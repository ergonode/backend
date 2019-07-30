<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Dispatcher;

use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 */
class SymfonyDomainEventDispatcher implements DomainEventDispatcherInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param DomainEventEnvelope $event
     */
    public function dispatch(DomainEventEnvelope $event): void
    {
        $this->dispatcher->dispatch($event->getType(), $event);
    }
}
