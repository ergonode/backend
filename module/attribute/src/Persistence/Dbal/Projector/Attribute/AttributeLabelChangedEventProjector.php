<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeLabelChangedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeLabelChangedEventProjector implements DomainEventProjectorInterface
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
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof AttributeLabelChangedEvent;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, AttributeLabelChangedEvent::class);
        }

        $from = $event->getFrom()->getTranslations();
        $to = $event->getTo()->getTranslations();

        $this->connection->transactional(function () use ($aggregateId, $to, $from) {
            foreach ($to as $language => $value) {
                $result = $this->connection->update(
                    self::TABLE,
                    [
                        'language' => $language,
                        'value' => $value,
                    ],
                    [
                        'value_id' => $this->getTranslationId('label', $aggregateId),
                        'language' => $language,
                    ]
                );
                if (!$result) {
                    $this->connection->insert(
                        self::TABLE,
                        [
                            'id' => Uuid::uuid4()->toString(),
                            'value_id' => $this->getTranslationId('label', $aggregateId),
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
                            'value_id' => $this->getTranslationId('label', $aggregateId),
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
