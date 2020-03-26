<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportAttribute;
use Ergonode\Exporter\Domain\Repository\AttributeRepositoryInterface;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalAttributeRepository implements AttributeRepositoryInterface
{
    private const TABLE = 'exporter.attribute';
    private const FIELDS = [
        'id',
        'data',
        'code',
    ];

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
     * @param Uuid $id
     *
     * @return ExportAttribute|null
     */
    public function load(Uuid $id): ?ExportAttribute
    {
        $qb = $this->getQuery();
        $record = $qb->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->toString())
            ->execute()
            ->fetch();

        if ($record) {
            return $this->serializer->deserialize($record['data'], ExportAttribute::class, 'json');
        }

        return null;
    }

    /**
     * @param ExportAttribute $attribute
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ExportAttribute $attribute): void
    {
        if ($this->exists($attribute->getId())) {
            $this->update($attribute);
        } else {
            $this->insert($attribute);
        }
    }

    /**
     * @param Uuid $id
     *
     * @return bool
     */
    public function exists(Uuid $id): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->toString())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ExportAttribute $attribute
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete(ExportAttribute $attribute): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $attribute->getId()->toString(),
            ]
        );
    }


    /**
     * @param ExportAttribute $attribute
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function update(ExportAttribute $attribute): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'data' => $this->serializer->serialize($attribute, 'json'),
                'code' => $attribute->getCode(),
            ],
            [
                'id' => $attribute->getId()->toString(),
            ]
        );
    }

    /**
     * @param ExportAttribute $attribute
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function insert(ExportAttribute $attribute): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $attribute->getId()->toString(),
                'data' => $this->serializer->serialize($attribute, 'json'),
                'code' => $attribute->getCode(),
            ]
        );
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
