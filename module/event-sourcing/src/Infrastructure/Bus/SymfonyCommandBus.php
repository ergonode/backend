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
    private bool $asyncEnable;

    /**
     * @param MessageBusInterface $syncBus
     * @param MessageBusInterface $asyncBus
     * @param bool                $asyncEnable
     */
    public function __construct(MessageBusInterface $syncBus, MessageBusInterface $asyncBus, bool $asyncEnable = false)
    {
        $this->syncBus = $syncBus;
        $this->asyncBus = $asyncBus;
        $this->asyncEnable = $asyncEnable;
    }

    /**
     * @param DomainCommandInterface $command
     * @param bool                   $asyncMode
     */
    public function dispatch(DomainCommandInterface $command, bool $asyncMode = false): void
    {
        if ($this->asyncEnable && $asyncMode) {
            $this->asyncBus->dispatch($command);
        } else {
            $this->syncBus->dispatch($command);
        }
    }
}
