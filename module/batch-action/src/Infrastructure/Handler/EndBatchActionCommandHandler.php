<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Command\EndBatchActionCommand;
use Ergonode\BatchAction\Domain\Event\BatchActionEndedEvent;
use Ergonode\SharedKernel\Domain\Bus\EventBusInterface;

class EndBatchActionCommandHandler
{

    private EventBusInterface $eventBus;


    public function __construct(EventBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function __invoke(EndBatchActionCommand $command): void
    {
        $event = new BatchActionEndedEvent($command->getId());
        $this->eventBus->dispatch($event);
    }
}
