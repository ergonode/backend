<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Option;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\AttributeOptionAddedEvent;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeOptionAddedEventProjector
{
    private const TABLE_ATTRIBUTE_OPTION = 'public.attribute_option';
    private const TABLE_VALUE_TRANSLATION = 'public.value_translation';

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
     * @param AttributeOptionAddedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(AttributeOptionAddedEvent $event): void
    {

        $valueId = Uuid::uuid4()->toString();

        $this->insertOption($valueId, $event->getOption());

        $this->connection->insert(
            self::TABLE_ATTRIBUTE_OPTION,
            [
                'key' => $event->getKey()->getValue(),
                'attribute_id' => $event->getAggregateId()->getValue(),
                'value_id' => $valueId,
            ]
        );
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
}
