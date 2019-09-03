<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Editor\Domain\Event\ProductDraftValueChanged;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableCollectionValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductDraftValueChangedEventProjector implements DomainEventProjectorInterface
{
    private const DRAFT_VALUE_TABLE = 'designer.draft_value';

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
        return $event instanceof ProductDraftValueChanged;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ProductDraftValueChanged) {
            throw new UnsupportedEventException($event, ProductDraftValueChanged::class);
        }

        $this->connection->transactional(function () use ($aggregateId, $event) {
            $draftId = $aggregateId->getValue();
            $elementId = AttributeId::fromKey($event->getAttributeCode())->getValue();

            $value = $event->getTo();

            $this->delete($draftId, $elementId);
            $this->insertValue($draftId, $elementId, $value);
        });
    }

    /**
     * @param string         $draftId
     * @param string         $elementId
     * @param ValueInterface $value
     *
     * @throws DBALException
     */
    private function insertValue(string $draftId, string $elementId, ValueInterface $value): void
    {
        if ($value instanceof StringValue) {
            $this->insert($draftId, $elementId, $value->getValue());
        } elseif ($value instanceof StringCollectionValue) {
            foreach ($value->getValue() as $phrase) {
                $this->insert($draftId, $elementId, $phrase);
            }
        } elseif ($value instanceof TranslatableStringValue) {
            $translation = $value->getValue();
            foreach ($translation as $language => $phrase) {
                $this->insert($draftId, $elementId, $phrase, $language);
            }
        } elseif ($value instanceof TranslatableCollectionValue) {
            $collection = $value->getValue();
            foreach ($collection as $translation) {
                foreach ($translation as $language => $phrase) {
                    $this->insert($draftId, $elementId, $phrase, $language);
                }
            }
        } else {
            throw new \RuntimeException(sprintf(sprintf('Unknown Value class "%s"', \get_class($value->getValue()))));
        }
    }

    /**
     * @param string $draftId
     * @param string $elementId
     *
     * @throws DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    private function delete(string $draftId, string $elementId): void
    {
        $this->connection->delete(
            self::DRAFT_VALUE_TABLE,
            [
                'draft_id' => $draftId,
                'element_id' => $elementId,
            ]
        );
    }

    /**
     * @param string      $draftId
     * @param string      $elementId
     * @param string      $value
     * @param string|null $language
     *
     * @throws DBALException
     * @throws \Exception
     */
    private function insert(string $draftId, string $elementId, string $value, string $language = null): void
    {
        $this->connection->insert(
            self::DRAFT_VALUE_TABLE,
            [
                'id' => Uuid::uuid4()->toString(),
                'draft_id' => $draftId,
                'element_id' => $elementId,
                'value' => $value,
                'language' => $language ?: null,
            ]
        );
    }
}
