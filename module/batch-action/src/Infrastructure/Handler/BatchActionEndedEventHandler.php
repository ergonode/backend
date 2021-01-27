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
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BatchActionEndedEventHandler
{
    private Security $security;

    private CommandBusInterface $commandBus;

    private BatchActionRepositoryInterface $batchActionRepository;

    private TranslatorInterface $translator;

    public function __construct(
        Security $security,
        CommandBusInterface $commandBus,
        BatchActionRepositoryInterface $batchActionRepository,
        TranslatorInterface $translator
    ) {
        $this->security = $security;
        $this->commandBus = $commandBus;
        $this->batchActionRepository = $batchActionRepository;
        $this->translator = $translator;
    }

    public function __invoke(BatchActionEndedEvent $event): void
    {
        $user = $this->security->getUser();
        $batchAction = $this->batchActionRepository->load($event->getId());

        if ($user && $batchAction) {
            $userId = $user->getId();
            $type = $this->translator->trans($batchAction->getType()->getValue(), [], 'notification', 'en');
            $notification = new BatchActionProcessEndedNotification($type, $userId);
            $notificationCommand = new SendNotificationCommand($notification, [$userId]);
            $this->commandBus->dispatch($notificationCommand);
        }
    }
}
