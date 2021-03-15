<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Repository\LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalLanguageRepository implements LanguageRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_language';
    private const FIELDS = [
        'channel_id',
        'shopware6_id',
        'locale_id',
        'translation_code_id',
        'iso',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

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
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ChannelId $channelId, Shopware6Language $shopware6Language): void
    {
        $sql = 'INSERT INTO '.self::TABLE.' (channel_id, iso, locale_id, translation_code_id, shopware6_id, update_at) 
        VALUES (:channelId, :iso, :localeId, :translationCodeId, :shopware6Id, :updatedAt)
            ON CONFLICT ON CONSTRAINT shopware6_language_pkey
                DO UPDATE SET 
                    shopware6_id = :shopware6Id,
                    locale_id = :localeId,
                    translation_code_id = :translationCodeId,
                    update_at = :updatedAt
        ';

        $this->connection->executeQuery(
            $sql,
            [
                'channelId' => $channelId->getValue(),
                'iso' => $shopware6Language->getIso(),
                'localeId' => $shopware6Language->getLocaleId(),
                'translationCodeId' => $shopware6Language->getTranslationCodeId(),
                'shopware6Id' => $shopware6Language->getId(),
                'updatedAt' => new \DateTimeImmutable(),
            ],
            [
                'updatedAt' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

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
}
