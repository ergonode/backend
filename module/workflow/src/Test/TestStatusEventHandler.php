<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Test;

use Ergonode\Core\Application\Event\LanguageTreeUpdatedEvent;
use Ergonode\Workflow\Application\Transport\StatusTransport;
use Symfony\Component\Messenger\MessageBusInterface;

class TestStatusEventHandler
{
    private StatusTransport $statusTransport;
    private MessageBusInterface $commandBus;

    public function __construct(StatusTransport $statusTransport, MessageBusInterface $commandBus)
    {
        $this->statusTransport = $statusTransport;
        $this->commandBus = $commandBus;
    }

    public function __invoke(LanguageTreeUpdatedEvent $event): void
    {
        $messages = $this->statusTransport->get();

        while (!empty($messages)) {
            foreach ($messages as $message) {
                try {
                    $message = $this->commandBus->dispatch($message->getMessage());
                    $this->statusTransport->ack($message);
                } catch (\Throwable $exception) {
                    $this->statusTransport->reject($message);
                }
            }
            $messages = $this->statusTransport->get();
        }
    }
}
