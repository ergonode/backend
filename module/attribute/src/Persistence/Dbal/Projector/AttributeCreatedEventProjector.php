<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'attribute';
    private const TABLE_PARAMETER = 'attribute_parameter';
    private const TABLE_VALUE = 'value';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

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
        return $event instanceof AttributeCreatedEvent;
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
        if (!$event instanceof AttributeCreatedEvent) {
            throw new UnsupportedEventException($event, AttributeCreatedEvent::class);
        }

        try {
            $this->connection->beginTransaction();
            $labelUuid = Uuid::uuid4();
            $placeholderUuid = Uuid::uuid4();
            $hintUuid = Uuid::uuid4();

            $this->connection->insert(
                self::TABLE_VALUE,
                [
                    'id' => $labelUuid->toString(),
                ]
            );

            $this->connection->insert(
                self::TABLE_VALUE,
                [
                    'id' => $placeholderUuid->toString(),
                ]
            );

            $this->connection->insert(
                self::TABLE_VALUE,
                [
                    'id' => $hintUuid->toString(),
                ]
            );

            foreach ($event->getLabel()->getTranslations() as $language => $value) {
                $this->connection->insert(
                    self::TABLE_VALUE_TRANSLATION,
                    [
                        'id' => Uuid::uuid4()->toString(),
                        'value_id' => $labelUuid,
                        'language' => $language,
                        'value' => $value,
                    ]
                );
            }

            foreach ($event->getHint()->getTranslations() as $language => $value) {
                $this->connection->insert(
                    self::TABLE_VALUE_TRANSLATION,
                    [
                        'id' => Uuid::uuid4()->toString(),
                        'value_id' => $hintUuid,
                        'language' => $language,
                        'value' => $value,
                    ]
                );
            }

            foreach ($event->getPlaceholder()->getTranslations() as $language => $value) {
                $this->connection->insert(
                    self::TABLE_VALUE_TRANSLATION,
                    [
                        'id' => Uuid::uuid4()->toString(),
                        'value_id' => $placeholderUuid,
                        'language' => $language,
                        'value' => $value,
                    ]
                );
            }

            $this->connection->insert(
                self::TABLE,
                [
                    'id' => $aggregateId->getValue(),
                    'multilingual' => $event->isMultilingual(),
                    'code' => $event->getCode()->getValue(),
                    'type' => $event->getType(),
                    'label' => $labelUuid->toString(),
                    'placeholder' => $placeholderUuid->toString(),
                    'hint' => $hintUuid->toString(),
                ],
                [
                    'multilingual' => \PDO::PARAM_BOOL,
                ]
            );

            foreach ($event->getParameters() as $name => $value) {
                if (!empty($value)) {
                    $this->connection->insert(
                        self::TABLE_PARAMETER,
                        [
                            'attribute_id' => $aggregateId->getValue(),
                            'type' => $name,
                            'value' => \json_encode($value),
                        ]
                    );
                }
            }
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
    }
}
