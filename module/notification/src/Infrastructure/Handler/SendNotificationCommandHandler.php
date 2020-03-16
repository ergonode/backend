<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Infrastructure\Handler;

use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Notification\Infrastructure\Sender\NotificationSender;

/**
 */
class SendNotificationCommandHandler
{
    /**
     * @var NotificationSender
     */
    private NotificationSender $notificationService;

    /**
     * @param NotificationSender $notificationService
     */
    public function __construct(NotificationSender $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @param SendNotificationCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(SendNotificationCommand $command)
    {
        $this->notificationService->send($command->getNotification(), $command->getRecipients());
    }
}
