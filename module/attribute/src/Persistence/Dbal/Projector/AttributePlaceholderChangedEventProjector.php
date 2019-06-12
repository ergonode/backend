<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributePlaceholderChangedEvent;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributePlaceholderChangedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'value_translation';

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
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof AttributePlaceholderChangedEvent;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws ProjectorException
     * @throws UnsupportedEventException
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof AttributePlaceholderChangedEvent) {
            throw new UnsupportedEventException($event, AttributePlaceholderChangedEvent::class);
        }


        $from = $event->getFrom()->getTranslations();
        $to = $event->getTo()->getTranslations();

        try {
            $this->connection->beginTransaction();
            foreach ($to as $language => $value) {
                $result = $this->connection->update(
                    self::TABLE,
                    [
                        'language' => $language,
                        'value' => $value,
                    ],
                    [
                        'value_id' => $this->getTranslationId('placeholder', $aggregateId),
                        'language' => $language,
                    ]
                );
                if (!$result) {
                    $this->connection->insert(
                        self::TABLE,
                        [
                            'id' => Uuid::uuid4()->toString(),
                            'value_id' => $this->getTranslationId('placeholder', $aggregateId),
                            'language' => $language,
                            'value' => $value,
                        ]
                    );
                }
            }

            foreach ($from as $language => $value) {
                if (!isset($to[$language])) {
                    $this->connection->delete(
                        self::TABLE,
                        [
                            'value_id' => $this->getTranslationId('placeholder', $aggregateId),
                            'language' => $language,
                        ]
                    );
                }
            }
            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
    }

    /**
     * @param string     $field
     * @param AbstractId $attributeId
     *
     * @return mixed
     */
    private function getTranslationId(string $field, AbstractId $attributeId): string
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select($field)
            ->from('attribute')
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $attributeId->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);
    }
}
