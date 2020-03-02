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
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\Exporter\Persistence\Dbal\Repository\Factory\ExportProfileFactory;

/**
 */
class DbalExportProfileRepository implements ExportProfileRepositoryInterface
{
    private const TABLE = 'exporter.export_profile';
    private const FIELDS = [
        'id',
        'type',
        'name',
        'configuration',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ExportProfileFactory
     */
    private ExportProfileFactory $factory;

    /**
     * @param Connection           $connection
     * @param ExportProfileFactory $factory
     */
    public function __construct(Connection $connection, ExportProfileFactory $factory)
    {
        $this->connection = $connection;
        $this->factory = $factory;
    }

    /**
     * @param ExportProfileId $id
     *
     * @return AbstractExportProfile|null
     */
    public function load(ExportProfileId $id): ?AbstractExportProfile
    {
        $qb = $this->getQuery();
        $record = $qb->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return $this->factory->create($record);
        }

        return null;
    }

    /**
     * @param AbstractExportProfile $exportProfile
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(AbstractExportProfile $exportProfile): void
    {
        if ($this->exists($exportProfile->getId())) {
            $this->update($exportProfile);
        } else {
            $this->insert($exportProfile);
        }
    }

    /**
     * @param ExportProfileId $id
     *
     * @return bool
     */
    public function exists(ExportProfileId $id): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param AbstractExportProfile $exportProfile
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete(AbstractExportProfile $exportProfile): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $exportProfile->getId()->getValue(),
            ]
        );
    }


    /**
     * @param AbstractExportProfile $exportProfile
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(AbstractExportProfile $exportProfile): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'name' => $exportProfile->getName(),
                'configuration' => \json_encode($exportProfile->getConfiguration(), JSON_THROW_ON_ERROR, 512),
                'type' => \get_class($exportProfile),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => $exportProfile->getId()->getValue(),
            ]
        );
    }

    /**
     * @param AbstractExportProfile $exportProfile
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(AbstractExportProfile $exportProfile): void
    {

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $exportProfile->getId()->getValue(),
                'name' => $exportProfile->getName(),
                'configuration' => \json_encode($exportProfile->getConfiguration(), JSON_THROW_ON_ERROR, 512),
                'type' => \get_class($exportProfile),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
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
