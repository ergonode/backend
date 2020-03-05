<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Editor\Domain\Event\ProductDraftValueAdded;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductDraftValueAddedEventProjector
{
    private const DRAFT_VALUE_TABLE = 'designer.draft_value';

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
     * @param ProductDraftValueAdded $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductDraftValueAdded $event): void
    {
        $draftId = $event->getAggregateId()->getValue();
        $elementId = AttributeId::fromKey($event->getAttributeCode()->getValue())->getValue();
        $value = $event->getTo();

        $this->insertValue($draftId, $elementId, $value);
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
        } else {
            throw new \RuntimeException(sprintf(sprintf('Unknown Value class "%s"', \get_class($value->getValue()))));
        }
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
