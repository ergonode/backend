<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Infrastructure\Handler;

use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Notification\Infrastructure\Sender\NotificationSender;

/**
 */
class SendNotificationCommandHandler
{
    /**
     * @var NotificationSender
     */
    private $notificationService;

    /**
     * @var RoleQueryInterface
     */
    private $query;

    /**
     * @param NotificationSender $notificationService
     * @param RoleQueryInterface $query
     */
    public function __construct(NotificationSender $notificationService, RoleQueryInterface $query)
    {
        $this->notificationService = $notificationService;
        $this->query = $query;
    }

    /**
     * @param SendNotificationCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(SendNotificationCommand $command)
    {
        $recipients = $this->query->getAllRoleUsers($command->getRoleId());

        $this->notificationService->send($recipients, $command->getMessage(), $command->getAuthorId());
    }
}
