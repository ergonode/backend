<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\NewRelic\Application\EventSubscriber;

use Ergonode\NewRelic\Application\NewRelic\NewRelicInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\AbstractWorkerMessageEvent;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;

final class MessengerEventSubscriber implements EventSubscriberInterface
{
    private NewRelicInterface $newRelic;

    public function __construct(NewRelicInterface $newRelic)
    {
        $this->newRelic = $newRelic;
    }

    public function onMessageReceived(WorkerMessageReceivedEvent $event): void
    {
        $this->newRelic->endTransaction();
        $this->newRelic->startTransaction();
        $this->newRelic->nameTransaction(
            $this->getTransactionName($event),
        );
    }

    public function onMessageFinished(AbstractWorkerMessageEvent $event): void
    {
        $this->newRelic->endTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageReceivedEvent::class => 'onMessageReceived',
            WorkerMessageFailedEvent::class => 'onMessageFinished',
            WorkerMessageHandledEvent::class => 'onMessageFinished',
        ];
    }

    private function getTransactionName(WorkerMessageReceivedEvent $event): string
    {
        return sprintf(
            'consuming_%s_%s',
            $event->getReceiverName(),
            get_class($event->getEnvelope()->getMessage()),
        );
    }
}
