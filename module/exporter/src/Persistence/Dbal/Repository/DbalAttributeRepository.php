<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportAttribute;
use Ergonode\Exporter\Domain\Repository\AttributeRepositoryInterface;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalAttributeRepository implements AttributeRepositoryInterface
{
    private const TABLE_ATTRIBUTE = 'exporter.attribute';

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
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('*')
            ->from(self::TABLE_ATTRIBUTE)
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->toString())
            ->execute()
            ->fetch();

        //todo if not or other  type or exeption
        return $this->serializer->deserialize($result['data'], ExportAttribute::class, 'json');
    }

    /**
     * @param ExportAttribute $attribute
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ExportAttribute $attribute): void
    {
        $this->connection->update(
            self::TABLE_ATTRIBUTE,
            [
                'data' => $this->serializer->serialize($attribute, 'json'),
                'code' => $attribute->getCode(),
            ],
            [
                'id' => $attribute->getId()->toString(),
            ]
        );
    }
}
