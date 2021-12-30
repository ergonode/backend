<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Test;

use Ergonode\Completeness\Application\Event\ProductCompletenessCalculatedEvent;
use Ergonode\Product\Application\Event\ProductCreatedEvent;
use Ergonode\Product\Application\Event\ProductDeletedEvent;
use Ergonode\Product\Application\Event\ProductUpdatedEvent;
use Ergonode\Segment\Application\Transport\SegmentTransport;
use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class TestSegmentEventHandler
{
    private SegmentTransport $transport;
    private MessageBusInterface $commandBus;

    public function __construct(SegmentTransport $transport, MessageBusInterface $commandBus)
    {
        $this->transport = $transport;
        $this->commandBus = $commandBus;
    }

    /**
     * @deprecated
     */
    public function __invoke(SegmentCreatedEvent $event): void
    {
        $this->calculate();
    }

    public function onSegmentCreatedEvent(SegmentCreatedEvent $event): void
    {
        $this->calculate();
    }

    public function onSegmentConditionSetChangedEvent(SegmentConditionSetChangedEvent $event): void
    {
        $this->calculate();
    }

    public function onProductCreatedEvent(ProductCreatedEvent $event): void
    {
        $this->calculate();
    }

    public function onProductCompletenessCalculatedEvent(ProductCompletenessCalculatedEvent $event): void
    {
        $this->calculate();
    }

    public function onProductDeletedEvent(ProductDeletedEvent $event): void
    {
        $this->calculate();
    }

    public function onProductUpdatedEvent(ProductUpdatedEvent $event): void
    {
        $this->calculate();
    }

    private function calculate(): void
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
