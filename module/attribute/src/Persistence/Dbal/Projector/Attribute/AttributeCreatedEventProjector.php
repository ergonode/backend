<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeCreatedEventProjector
{
    private const TABLE = 'attribute';
    private const TABLE_PARAMETER = 'attribute_parameter';
    private const TABLE_VALUE = 'value';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

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
     * @param AttributeCreatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(AttributeCreatedEvent $event): void
    {
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
                'id' => $event->getAggregateId()->getValue(),
                'multilingual' => $event->isMultilingual(),
                'code' => $event->getCode()->getValue(),
                'type' => $event->getType(),
                'label' => $labelUuid->toString(),
                'placeholder' => $placeholderUuid->toString(),
                'hint' => $hintUuid->toString(),
                'system' => $event->isSystem(),
                'editable' => $event->isEditable(),
                'deletable' => $event->isDeletable(),
            ],
            [
                'multilingual' => \PDO::PARAM_BOOL,
                'system' => \PDO::PARAM_BOOL,
                'editable' => \PDO::PARAM_BOOL,
                'deletable' => \PDO::PARAM_BOOL,
            ]
        );

        foreach ($event->getParameters() as $name => $value) {
            if (!empty($value)) {
                $this->connection->insert(
                    self::TABLE_PARAMETER,
                    [
                        'attribute_id' => $event->getAggregateId()->getValue(),
                        'type' => $name,
                        'value' => $this->serializer->serialize($value, 'json'),
                    ]
                );
            }
        }
    }
}
