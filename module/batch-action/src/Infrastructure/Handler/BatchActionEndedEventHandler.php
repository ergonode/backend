<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Event\BatchActionEndedEvent;
use Ergonode\BatchAction\Domain\Notification\BatchActionEndedNotification;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\Core\Application\Security\Security;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

class BatchActionEndedEventHandler
{
    private Security $security;

    private CommandBusInterface $commandBus;

    private BatchActionRepositoryInterface $batchActionRepository;

    public function __construct(
        Security $security,
        CommandBusInterface $commandBus,
        BatchActionRepositoryInterface $batchActionRepository
    ) {
        $this->security = $security;
        $this->commandBus = $commandBus;
        $this->batchActionRepository = $batchActionRepository;
    }

    public function __invoke(BatchActionEndedEvent $event): void
    {
        $user = $this->security->getUser();
        $batchAction = $this->batchActionRepository->load($event->getId());

        if ($user && $batchAction) {
            $userId = $user->getId();
            $notification = new BatchActionEndedNotification($batchAction, $userId);
            $notificationCommand = new SendNotificationCommand($notification, [$userId]);
            $this->commandBus->dispatch($notificationCommand);
        }
    }
}
