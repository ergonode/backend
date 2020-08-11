<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class DbalShopware6LanguageRepository implements Shopware6LanguageRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_language';
    private const FIELDS = [
        'channel_id',
        'shopware6_id',
        'locale_id',
        'translation_code_id',
        'iso',
    ];

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
     * @param ChannelId $channelId
     * @param string    $iso
     *
     * @return Shopware6Language|null
     */
    public function load(ChannelId $channelId, string $iso): ?Shopware6Language
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'c')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('c.iso', ':iso'))
            ->setParameter(':iso', $iso)
            ->execute()
            ->fetch();

        if ($record) {
            return new Shopware6Language(
                $record['shopware6_id'],
                '',
                $record['locale_id'],
                $record['translation_code_id'],
                $record['iso']
            );
        }

        return null;
    }

    /**
     * @param ChannelId         $channelId
     * @param Shopware6Language $shopware6Language
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ChannelId $channelId, Shopware6Language $shopware6Language): void
    {
        if ($this->exists($channelId, $shopware6Language->getIso())) {
            $this->update($channelId, $shopware6Language);
        } else {
            $this->insert($channelId, $shopware6Language);
        }
    }

    /**
     * @param ChannelId $channelId
     * @param string    $iso
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, string $iso): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('iso', ':iso'))
            ->setParameter(':iso', $iso)
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ChannelId         $channelId
     * @param Shopware6Language $shopware6Language
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ChannelId $channelId, Shopware6Language $shopware6Language): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopware6Language->getId(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
                'locale_id' => $shopware6Language->getLocaleId(),
                'translation_code_id' => $shopware6Language->getTranslationCodeId(),
            ],
            [
                'iso' => $shopware6Language->getIso(),
                'channel_id' => $channelId->getValue(),
            ]
        );
    }

    /**
     * @param ChannelId         $channelId
     * @param Shopware6Language $shopware6Language
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ChannelId $channelId, Shopware6Language $shopware6Language): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'channel_id' => $channelId->getValue(),
                'shopware6_id' => $shopware6Language->getId(),
                'iso' => $shopware6Language->getIso(),
                'locale_id' => $shopware6Language->getLocaleId(),
                'translation_code_id' => $shopware6Language->getTranslationCodeId(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
