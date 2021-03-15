<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20180619100000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS importer');

        $this->addSql('
            CREATE TABLE IF NOT EXISTS importer.reader (
                id UUID NOT NULL,
                name VARCHAR(64) NOT NULL,
                type VARCHAR(32) NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Reader']);
        $this->createReaderPrivileges(
            [
                'READER_CREATE' => 'Reader',
                'READER_READ' => 'Reader',
                'READER_UPDATE' => 'Reader',
                'READER_DELETE' => 'Reader',
            ]
        );

        $this->createEventStoreEvents([
            'Ergonode\Reader\Domain\Event\ReaderCreatedEvent' => 'Reader created',
            'Ergonode\Reader\Domain\Event\ReaderDeletedEvent' => 'Reader deleted',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createReaderPrivileges(array $collection): void
    {
        foreach ($collection as $code => $area) {
            $this->addSql(
                'INSERT INTO privileges (id, code, area) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $code,  $area, ]
            );
        }
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class => $translation) {
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }
}
