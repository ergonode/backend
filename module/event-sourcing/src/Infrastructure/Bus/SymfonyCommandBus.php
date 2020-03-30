<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Bus;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class SymfonyCommandBus implements CommandBusInterface
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $syncBus;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $asyncBus;

    /**
     * @var bool
     */
    private bool $async;

    /**
     * @param MessageBusInterface $syncBus
     * @param MessageBusInterface $asyncBus
     * @param bool                $async
     */
    public function __construct(MessageBusInterface $syncBus, MessageBusInterface $asyncBus, bool $async = false)
    {
        $this->syncBus = $syncBus;
        $this->asyncBus = $asyncBus;
        $this->async = $async;
        var_dump($async);
    }

    /**
     * @param DomainCommandInterface $command
     */
    public function dispatch(DomainCommandInterface $command): void
    {
        if ($this->async) {
            $this->asyncBus->dispatch($command);
        }

        $this->syncBus->dispatch($command);
    }
}
