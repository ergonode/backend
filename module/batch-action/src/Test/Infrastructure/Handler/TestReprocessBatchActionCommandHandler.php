<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Test\Infrastructure\Handler;

use Ergonode\BatchAction\Application\Transport\BatchActionTransport;
use Symfony\Component\Messenger\MessageBusInterface;
use Ergonode\BatchAction\Domain\Command\ReprocessBatchActionCommand;

class TestReprocessBatchActionCommandHandler
{
    private BatchActionTransport $transport;

    private MessageBusInterface $commandBus;

    public function __construct(BatchActionTransport $transport, MessageBusInterface $commandBus)
    {
        $this->transport = $transport;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ReprocessBatchActionCommand $command): void
    {
        $messages = $this->transport->get();

        while (!empty($messages)) {
            foreach ($messages as $message) {
                try {
                    $message = $this->commandBus->dispatch($message->getMessage());
                    $this->transport->ack($message);
                } catch (\Throwable $exception) {
                    $this->transport->reject($message);
                }
            }
            $messages = $this->transport->get();
        }
    }
}
