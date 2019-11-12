<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Infrastructure\Service\Strategy;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Notification\Infrastructure\Service\NotificationStrategyInterface;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class SystemNotificationStrategy implements NotificationStrategyInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param UserId[]    $recipients
     * @param string      $message
     * @param UserId|null $author
     * @param array       $parameters
     *
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Exception
     */
    public function send(array $recipients, string $message, ?UserId $author = null, array $parameters = []): void
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
                    'parameters' => $this->serializer->serialize($parameters, 'json'),
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
