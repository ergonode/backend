<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider\Decorator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\TranslationDeepl\Infrastructure\Provider\TranslationDeeplProvider;
use Ergonode\TranslationDeepl\Infrastructure\Provider\TranslationDeeplProviderInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class TranslationDeeplProviderDecorator implements TranslationDeeplProviderInterface
{
    private const TABLE = 'translation_deepl';
    private const FIELDS = [
        'a.translation',
    ];
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var TranslationDeeplProvider
     */
    private $provider;

    /**
     * TranslationDeeplProviderDecorator constructor.
     *
     * @param TranslationDeeplProvider $provider
     * @param Connection               $connection
     */
    public function __construct(TranslationDeeplProvider $provider, Connection $connection)
    {
        $this->connection = $connection;
        $this->provider = $provider;
    }

    /**
     * @param string   $content
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     *
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     */
    public function provide(string $content, Language $sourceLanguage, Language $targetLanguage): string
    {
        $namespace = 'a16c8554-70f5-487e-b0b7-a4a52e890ab3';
        $name = sprintf('%s_%s_%s', $sourceLanguage, $targetLanguage, $content);
        $translationDeeplUuid = Uuid::uuid5($namespace, $name);
        $translation = $this->getTranslation($translationDeeplUuid);

        if (!$translation) {
            $translation = $this->provider->provide($content, $sourceLanguage, $targetLanguage);
            $this->save($translationDeeplUuid, $translation);
        }

        return $translation;
    }

    /**
     * @param $translationDeeplUuid
     *
     * @return false|mixed
     */
    private function getTranslation($translationDeeplUuid)
    {
        $qb = $this->getQuery();

        return $qb
            ->andWhere($qb->expr()->eq('a.id', ':id'))
            ->setParameter(':id', $translationDeeplUuid)
            ->execute()
            ->fetchColumn();
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 'a');
    }

    /**
     * @param $translationDeeplUuid
     * @param $translation
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function save($translationDeeplUuid, $translation): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $translationDeeplUuid,
                'translation' => $translation,
            ]
        );
    }
}
