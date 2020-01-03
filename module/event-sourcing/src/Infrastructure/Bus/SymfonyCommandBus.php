<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

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
     * @param DomainCommandInterface $command
     */
    public function dispatch(DomainCommandInterface $command): void
    {
        $this->bus->dispatch($command);
    }
}
