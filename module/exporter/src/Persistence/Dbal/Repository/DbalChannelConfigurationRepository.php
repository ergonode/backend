<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Exporter\Domain\Entity\Configuration\AbstractChannelConfiguration;
use Ergonode\Exporter\Domain\Repository\ChannelConfigurationRepositoryInterface;
use Ergonode\Exporter\Persistence\Dbal\Repository\Factory\ChannelConfigurationFactory;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use JMS\Serializer\SerializerInterface;

/**
 */
class DbalChannelConfigurationRepository implements ChannelConfigurationRepositoryInterface
{
    private const TABLE = 'exporter.channel_configuration';
    private const FIELDS = [
        'id',
        'type',
        'configuration',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ChannelConfigurationFactory
     */
    private ChannelConfigurationFactory $factory;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection                  $connection
     * @param ChannelConfigurationFactory $factory
     * @param SerializerInterface         $serializer
     */
    public function __construct(
        Connection $connection,
        ChannelConfigurationFactory $factory,
        SerializerInterface $serializer
    ) {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->serializer = $serializer;
    }

    /**
     * @param ChannelId $id
     *
     * @return AbstractChannelConfiguration|null
     */
    public function load(ChannelId $id): ?AbstractChannelConfiguration
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
     * @param AbstractChannelConfiguration $channelConfiguration
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(AbstractChannelConfiguration $channelConfiguration): void
    {
        if ($this->exists($channelConfiguration->getChannelId())) {
            $this->update($channelConfiguration);
        } else {
            $this->insert($channelConfiguration);
        }
    }

    /**
     * @param ChannelId $id
     *
     * @return bool
     */
    public function exists(ChannelId $id): bool
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
     * @param AbstractChannelConfiguration $channelConfiguration
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete(AbstractChannelConfiguration $channelConfiguration): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $channelConfiguration->getChannelId()->getValue(),
            ]
        );
    }

    /**
     * @param AbstractChannelConfiguration $channelConfiguration
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(AbstractChannelConfiguration $channelConfiguration): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'configuration' => $this->serializer->serialize($channelConfiguration, 'json'),
                'type' => \get_class($channelConfiguration),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => $channelConfiguration->getChannelId()->getValue(),
            ]
        );
    }

    /**
     * @param AbstractChannelConfiguration $channelConfiguration
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(AbstractChannelConfiguration $channelConfiguration): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $channelConfiguration->getChannelId()->getValue(),
                'configuration' => $this->serializer->serialize($channelConfiguration, 'json'),
                'type' => \get_class($channelConfiguration),
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
