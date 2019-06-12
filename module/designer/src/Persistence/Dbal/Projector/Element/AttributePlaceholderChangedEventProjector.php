<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Projector\Element;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributePlaceholderChangedEvent;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class AttributePlaceholderChangedEventProjector implements DomainEventProjectorInterface
{
    private const ELEMENT_PLACEHOLDER_TABLE = 'designer.element_placeholder';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * TemplateCreateEventProjector constructor.
     *
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
     * @throws UnsupportedEventException
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof AttributePlaceholderChangedEvent) {
            throw new UnsupportedEventException($event, AttributePlaceholderChangedEvent::class);
        }

        $this->connection->beginTransaction();
        try {
            foreach ($event->getTo()->getTranslations() as $language => $value) {
                $result = $this->connection->update(
                    self::ELEMENT_PLACEHOLDER_TABLE,
                    [
                        'value' => $value,
                    ],
                    [
                        'element_id' => $aggregateId->getValue(),
                        'language' => $language,
                    ]
                );
                if (!$result) {
                    $this->connection->insert(
                        self::ELEMENT_PLACEHOLDER_TABLE,
                        [
                            'element_id' => $aggregateId->getValue(),
                            'language' => $language,
                            'value' => $value,
                        ]
                    );
                }
            }
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }
}
