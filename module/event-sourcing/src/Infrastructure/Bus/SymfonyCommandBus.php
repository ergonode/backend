<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Bus;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyCommandBus implements CommandBusInterface
{
    private MessageBusInterface $syncBus;

    private MessageBusInterface $asyncBus;

    private bool $asyncEnable;

    public function __construct(MessageBusInterface $syncBus, MessageBusInterface $asyncBus, bool $asyncEnable = false)
    {
        $this->syncBus = $syncBus;
        $this->asyncBus = $asyncBus;
        $this->asyncEnable = $asyncEnable;
    }

    public function dispatch(DomainCommandInterface $command, bool $asyncMode = false): void
    {
        if ($this->asyncEnable && $asyncMode) {
            $this->asyncBus->dispatch($command);
        } else {
            $this->syncBus->dispatch($command);
        }
    }
}
