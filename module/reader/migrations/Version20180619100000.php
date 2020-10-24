<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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

        $this->connection->insert('privileges_group', ['area' => 'Reader']);
        $this->createReaderPrivileges(
            [
                'READER_CREATE',
                'READER_READ',
                'READER_UPDATE',
                'READER_DELETE',
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
        foreach ($collection as $code) {
            $this->connection->insert('privileges', [
                'id' => Uuid::uuid4()->toString(),
                'code' => $code,
                'area' => 'Reader',
            ]);
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
            $this->connection->insert('event_store_event', [
                'id' => Uuid::uuid4()->toString(),
                'event_class' => $class,
                'translation_key' => $translation,
            ]);
        }
    }
}
