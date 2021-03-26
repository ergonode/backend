<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Messenger;

use Ergonode\SharedKernel\Domain\DomainEventInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Ergonode\SharedKernel\Domain\Bus\DomainEventBusInterface;

class DomainEventBus implements DomainEventBusInterface
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function dispatch(DomainEventInterface $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
