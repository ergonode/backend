<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\Account\Application\Security\Security;
use Ergonode\BatchAction\Domain\Event\BatchActionEndedEvent;
use Ergonode\BatchAction\Domain\Notification\BatchActionProcessEndedNotification;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BatchActionEndedEventHandler
{
    private TranslatorInterface $translator;

    private Security $security;

    private CommandBusInterface $commandBus;

    public function __construct(
        TranslatorInterface $translator,
        Security $security,
        CommandBusInterface $commandBus
    ) {
        $this->translator = $translator;
        $this->security = $security;
        $this->commandBus = $commandBus;
    }

    public function __invoke(BatchActionEndedEvent $event): void
    {
        $user = $this->security->getUser();

        if ($user) {
            $userId = $user->getId();
            $type = $this->translator->trans($event->getType()->getValue(), [], 'batch_action', 'en');
            $notification = new BatchActionProcessEndedNotification($type, $userId);
            $notificationCommand = new SendNotificationCommand($notification, [$userId]);
            $this->commandBus->dispatch($notificationCommand);
        }
    }
}
