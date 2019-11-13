<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Infrastructure\Sender\Strategy;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Notification\Infrastructure\Sender\NotificationStrategyInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalSystemNotificationStrategy implements NotificationStrategyInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param UserId[]    $recipients
     * @param string      $message
     * @param UserId|null $author
     *
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Exception
     */
    public function send(array $recipients, string $message, ?UserId $author = null): void
    {
        $id  = Uuid::uuid4()->toString();
        $createdAt = new \DateTime();

        $this->connection->beginTransaction();
        try {
            $this->connection->insert(
                'notification',
                [
                    'id' => $id,
                    'created_at' => $createdAt->format('Y-m-d H:i:s'),
                    'message' => $message,
                    'author_id' => $author ? $author->getValue() : null,
                ]
            );

            foreach ($recipients as $recipient) {
                $this->connection->insert(
                    'users_notification',
                    [
                        'notification_id' => $id,
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
