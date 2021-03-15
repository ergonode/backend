<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\NewRelic\Application\EventSubscriber;

use Ergonode\NewRelic\Application\NewRelic\NewRelicInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ConsoleEventSubscriber implements EventSubscriberInterface
{
    private NewRelicInterface $newRelic;

    public function __construct(NewRelicInterface $newRelic)
    {
        $this->newRelic = $newRelic;
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand();

        if (!$command || !$command->getName()) {
            return;
        }

        $this->newRelic->nameTransaction(
            $command->getName(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleCommandEvent::class => 'onConsoleCommand',
        ];
    }
}
