<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Product\Domain\Event\ProductCreated;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableCollectionValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductCreateEventProjector implements DomainEventProjectorInterface
{
    private const NAMESPACE = 'cb2600df-94fb-4755-9e6a-a15591a8e510';
    private const TABLE_PRODUCT = 'product';
    private const TABLE_PRODUCT_CATEGORY = 'product_category_product';
    private const TABLE_PRODUCT_VALUE = 'product_value';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * ProductCreateEventProjector constructor.
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
        return $event instanceof ProductCreated;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ProductCreated) {
            throw new UnsupportedEventException($event, ProductCreated::class);
        }

        try {
            $productId = $aggregateId->getValue();
            $this->connection->beginTransaction();
            $this->connection->insert(
                self::TABLE_PRODUCT,
                [
                    'id' => $aggregateId->getValue(),
                    'sku' => $event->getSku()->getValue(),
                    'template_id' => $event->getTemplateId()->getValue(),
                    'status' => 'new',
                ]
            );

            foreach ($event->getCategories() as $categoryCode) {
                $this->connection->insert(
                    self::TABLE_PRODUCT_CATEGORY,
                    [
                        'product_id' => $aggregateId->getValue(),
                        'category_id' => CategoryId::fromCode($categoryCode),
                    ]
                );
            }

            foreach ($event->getAttributes() as $code => $value) {
                $attributeId = AttributeId::fromKey(new AttributeCode($code))->getValue();
                $this->insertValue($productId, $attributeId, $value);
            }

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }

    /**
     * @param string         $productId
     * @param string         $attributeId
     * @param ValueInterface $value
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insertValue(string $productId, string $attributeId, ValueInterface $value): void
    {
        if ($value instanceof StringValue) {
            $this->insert($productId, $attributeId, $value->getValue());
        } elseif ($value instanceof StringCollectionValue) {
            foreach ($value->getValue() as $phrase) {
                $this->insert($productId, $attributeId, $phrase);
            }
        } elseif ($value instanceof TranslatableStringValue) {
            $translation = $value->getValue();
            foreach ($translation as $language => $phrase) {
                $this->insert($productId, $attributeId, $phrase, $language);
            }
        } elseif ($value instanceof TranslatableCollectionValue) {
            $collection = $value->getValue();
            foreach ($collection as $translation) {
                foreach ($translation as $language => $phrase) {
                    $this->insert($productId, $attributeId, $phrase, $language);
                }
            }
        } else {
            throw new \RuntimeException(sprintf(sprintf('Unknown Value class "%s"', \get_class($value->getValue()))));
        }
    }

    /**
     * @param string      $productId
     * @param string      $attributeId
     * @param string      $value
     * @param string|null $language
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(string $productId, string $attributeId, string $value, string $language = null): void
    {
        if ($value !== '') {
            $valueId = Uuid::uuid5(self::NAMESPACE, implode('|', [$value, $language]))->toString();

            if (!array_key_exists($valueId, $this->cache)) {
                $result = $this->provideValue($valueId, $value, $language);
                $this->cache[$valueId] = $result;
            }

            $this->connection->insert(

                self::TABLE_PRODUCT_VALUE,
                [
                    'product_id' => $productId,
                    'attribute_id' => $attributeId,
                    'value_id' => $valueId,
                ]
            );
        }
    }

    /**
     * @param string      $valueId
     * @param string      $value
     * @param string|null $language
     *
     * @return bool
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function provideValue(string $valueId, string $value, string $language = null): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('*')
            ->from(self::TABLE_VALUE_TRANSLATION)
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $valueId)
            ->execute()
            ->fetch();

        if (!$result) {
            $this->connection->executeQuery(
                'INSERT INTO value_translation (id, value_id, value, language) VALUES (?, ?, ?, ?) ON CONFLICT DO NOTHING',
                [$valueId, $valueId, $value, $language ?: null]
            );
        }

        return true;
    }
}
