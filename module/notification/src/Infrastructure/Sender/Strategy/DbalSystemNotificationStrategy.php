<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Infrastructure\Sender\Strategy;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Notification\Domain\NotificationInterface;
use Ergonode\Notification\Infrastructure\Sender\NotificationStrategyInterface;
use Ramsey\Uuid\Uuid;
use Ergonode\Core\Application\Serializer\SerializerInterface;

class DbalSystemNotificationStrategy implements NotificationStrategyInterface
{
    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param UserId[] $recipients
     *
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Exception
     */
    public function send(NotificationInterface $notification, array $recipients): void
    {
        $this->connection->beginTransaction();
        try {
            $notificationId = Uuid::uuid4()->toString();

            $this->connection->insert(
                'notification',
                [
                    'id' => $notificationId,
                    'created_at' => $notification->getCreatedAt(),
                    'message' => $notification->getMessage(),
                    'parameters' => $this->serializer->serialize($notification->getParameters()),
                    'author_id' => $notification->getAuthorId() ? $notification->getAuthorId()->getValue() : null,
                ],
                [
                    'created_at' => Types::DATETIMETZ_MUTABLE,
                ],
            );

            foreach ($recipients as $recipient) {
                $this->connection->insert(
                    'users_notification',
                    [
                        'notification_id' => $notificationId,
                        'recipient_id' => $recipient->getValue(),
                    ]
                );
            }
            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();

            throw $exception;
        }
    }
}
