<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Option;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\AttributeOptionChangedEvent;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeOptionChangedEventProjector
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
     * @param AttributeOptionChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(AttributeOptionChangedEvent $event): void
    {
        $valueId = Uuid::uuid4()->toString();
        $attributeId = $event->getAggregateId()->getValue();

        $this->delete($event->getKey()->getValue(), $attributeId);

        $this->connection->insert(
            self::TABLE_ATTRIBUTE_OPTION,
            [
                'attribute_id' => $event->getAggregateId()->getValue(),
                'value_id' => $valueId,
                'key' => $event->getKey(),
            ]
        );

        $this->insertOption($valueId, $event->getTo());
    }

    /**
     * @param string          $valueId
     * @param OptionInterface $option
     *
     * @throws DBALException
     */
    private function insertOption(string $valueId, OptionInterface $option): void
    {
        if ($option instanceof StringOption) {
            $this->insert($valueId, $option->getValue());
        } elseif ($option instanceof MultilingualOption) {
            $translation = $option->getValue();
            foreach ($translation->getTranslations() as $language => $phrase) {
                $this->insert($valueId, $phrase, $language);
            }
        } else {
            throw new \RuntimeException(sprintf('Unknown Value class "%s"', \get_class($option)));
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

    /**
     * @param string $key
     * @param string $attributeId
     *
     * @throws DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    private function delete(string $key, string $attributeId): void
    {
        $this->connection->delete(
            self::TABLE_ATTRIBUTE_OPTION,
            [
                'attribute_id' => $attributeId,
                'key' => $key,
            ]
        );
    }
}
