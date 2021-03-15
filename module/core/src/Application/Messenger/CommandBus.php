<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Messenger;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
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
