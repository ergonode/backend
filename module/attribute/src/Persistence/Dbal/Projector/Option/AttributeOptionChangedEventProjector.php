<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Option;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\AttributeOptionChangedEvent;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeOptionChangedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE_ATTRIBUTE_OPTION = 'attribute_option';
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
     * {@inheritDoc}
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof AttributeOptionChangedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof AttributeOptionChangedEvent) {
            throw new UnsupportedEventException($event, AttributeOptionChangedEvent::class);
        }

        $this->connection->beginTransaction();
        try {
            $valueId = Uuid::uuid4()->toString();
            $attributeId = $aggregateId->getValue();

            $this->delete($event->getKey()->getValue(), $attributeId);

            $this->connection->insert(
                self::TABLE_ATTRIBUTE_OPTION,
                [
                    'attribute_id' => $aggregateId->getValue(),
                    'value_id' => $valueId,
                    'key' => $event->getKey(),
                ]
            );

            $this->insertOption($valueId, $event->getTo());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
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
