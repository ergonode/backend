<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Bus;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class SymfonyCommandBus implements CommandBusInterface
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param object $command
     */
    public function dispatch($command): void
    {
        $this->bus->dispatch($command);
    }
}
