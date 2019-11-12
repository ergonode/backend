<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Infrastructure\Handler;

use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Notification\Infrastructure\Service\NotificationService;

/**
 */
class SendNotificationCommandHandler
{
    /**
     * @var NotificationService
     */
    private $notificationService;

    /**
     * @var RoleQueryInterface
     */
    private $query;

    /**
     * @param NotificationService $notificationService
     * @param RoleQueryInterface  $query
     */
    public function __construct(NotificationService $notificationService, RoleQueryInterface $query)
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

        $this->notificationService->send($recipients, $command->getMessage(), $command->getAuthorId(), $command->getParameters());
    }
}
