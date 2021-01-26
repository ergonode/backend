<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\Account\Application\Security\Security;
use Ergonode\BatchAction\Domain\Event\BatchActionFinishedEvent;
use Ergonode\BatchAction\Domain\Notification\BatchActionProcessFinishedNotification;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Contracts\Translation\TranslatorInterface;

class BatchActionFinishedEventHandler
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
    public function __invoke(BatchActionFinishedEvent $event): void
    {
        $user = $this->security->getUser();

        if ($user) {
            $userId = $user->getId();
            $type = $this->translator->trans($event->getType()->getValue(), [], 'batch_action', 'en');
            $notification = new BatchActionProcessFinishedNotification($type, $userId);
            $notificationCommand = new SendNotificationCommand($notification, [$userId]);
            $this->commandBus->dispatch($notificationCommand);
        } else {
            throw new AuthenticationException('User not set');
        }
    }
}
