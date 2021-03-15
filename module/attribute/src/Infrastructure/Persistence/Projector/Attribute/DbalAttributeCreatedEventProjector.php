<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

class DbalAttributeCreatedEventProjector
{
    private const TABLE = 'attribute';
    private const TABLE_PARAMETER = 'attribute_parameter';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(AttributeCreatedEvent $event): void
    {
        $labelUuid = Uuid::uuid4();
        $placeholderUuid = Uuid::uuid4();
        $hintUuid = Uuid::uuid4();

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
                'scope' => $event->getScope()->getValue(),
                'code' => $event->getCode()->getValue(),
                'type' => $event->getType(),
                'label' => $labelUuid->toString(),
                'placeholder' => $placeholderUuid->toString(),
                'hint' => $hintUuid->toString(),
                'system' => $event->isSystem(),
            ],
            [
                'multilingual' => \PDO::PARAM_BOOL,
                'system' => \PDO::PARAM_BOOL,
                'editable' => \PDO::PARAM_BOOL,
                'deletable' => \PDO::PARAM_BOOL,
            ]
        );

        foreach ($event->getParameters() as $name => $value) {
            if (!is_null($value)) {
                $this->connection->insert(
                    self::TABLE_PARAMETER,
                    [
                        'attribute_id' => $event->getAggregateId()->getValue(),
                        'type' => $name,
                        'value' => $this->serializer->serialize($value),
                    ]
                );
            }
        }
    }
}
