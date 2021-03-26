<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Messenger;

use Symfony\Component\Messenger\MessageBusInterface;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;
use Ergonode\SharedKernel\Application\ApplicationEventInterface;

class ApplicationEventBus implements ApplicationEventBusInterface
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function dispatch(ApplicationEventInterface $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
