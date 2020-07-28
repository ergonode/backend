<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20180807065948 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE multimedia (
                id UUID NOT NULL,
                name VARCHAR(128) NOT NULL,               
                extension varchar(4) NOT NULL,
                mime VARCHAR(255) NOT NULL,
                size INTEGER NOT NULL,
                hash VARCHAR(128) NOT NULL,
                created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');

        $this->createMultimediaPrivileges(
            [
                'MULTIMEDIA_CREATE',
                'MULTIMEDIA_READ',
                'MULTIMEDIA_UPDATE',
                'MULTIMEDIA_DELETE',
            ]
        );

        $this->createEventStoreEvents([
            MultimediaCreatedEvent::class => 'Multimedia added',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createMultimediaPrivileges(array $collection): void
    {
        foreach ($collection as $code) {
            $this->connection->insert('privileges', [
                'id' => Uuid::uuid4()->toString(),
                'code' => $code,
                'area' => 'Multimedia',
            ]);
        }
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
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
