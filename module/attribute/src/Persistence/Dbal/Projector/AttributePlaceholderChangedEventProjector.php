<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributePlaceholderChangedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
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
     * {@inheritDoc}
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof AttributePlaceholderChangedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof AttributePlaceholderChangedEvent) {
            throw new UnsupportedEventException($event, AttributePlaceholderChangedEvent::class);
        }

        $from = $event->getFrom()->getTranslations();
        $to = $event->getTo()->getTranslations();

        $this->connection->transactional(function () use ($aggregateId, $from, $to) {
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
        });
    }

    /**
     * @param string     $field
     * @param AbstractId $attributeId
     *
     * @return string
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
