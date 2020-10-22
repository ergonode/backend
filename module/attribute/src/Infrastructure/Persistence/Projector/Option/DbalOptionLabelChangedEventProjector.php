<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Option;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Option\OptionLabelChangedEvent;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ramsey\Uuid\Uuid;

class DbalOptionLabelChangedEventProjector
{
    private const TABLE_ATTRIBUTE_OPTION = 'attribute_option';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param OptionLabelChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(OptionLabelChangedEvent $event): void
    {
        $valueId = Uuid::uuid4()->toString();

        $this->insertOption($valueId, $event->getTo());

        $this->connection->update(
            self::TABLE_ATTRIBUTE_OPTION,
            [
                'value_id' => $valueId,
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }

    /**
     * @param string             $valueId
     * @param TranslatableString $label
     *
     * @throws DBALException
     */
    private function insertOption(string $valueId, TranslatableString $label): void
    {
        foreach ($label->getTranslations() as $language => $phrase) {
            $this->insert($valueId, $phrase, $language);
        }
    }

    /**
     * @param string      $valueId
     * @param string      $value
     * @param string|null $language
     *
     * @throws DBALException
     * @throws \Exception
     */
    private function insert(string $valueId, string $value, string $language = null): void
    {
        $this->connection->insert(
            self::TABLE_VALUE_TRANSLATION,
            [
                'id' => Uuid::uuid4()->toString(),
                'value_id' => $valueId,
                'value' => $value,
                'language' => $language ?: null,
            ]
        );
    }
}
