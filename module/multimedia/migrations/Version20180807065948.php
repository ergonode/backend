<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;
use Ergonode\Multimedia\Domain\Event\MultimediaDeletedEvent;
use Ramsey\Uuid\Uuid;
use Ergonode\Multimedia\Domain\Event\MultimediaAltChangedEvent;
use Ergonode\Multimedia\Domain\Event\MultimediaNameChangedEvent;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20180807065948 extends AbstractErgonodeMigration
{
    /**
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
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Multimedia']);
        $this->createMultimediaPrivileges(
            [
                'MULTIMEDIA_CREATE' => 'Multimedia',
                'MULTIMEDIA_READ' => 'Multimedia',
                'MULTIMEDIA_UPDATE' => 'Multimedia',
                'MULTIMEDIA_DELETE' => 'Multimedia',
            ]
        );

        $this->createEventStoreEvents([
            MultimediaCreatedEvent::class => 'Multimedia added',
            MultimediaAltChangedEvent::class => 'Multimedia alt changed',
            MultimediaNameChangedEvent::class => 'Multimedia name changed',
            MultimediaDeletedEvent::class => 'Multimedia deleted',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createMultimediaPrivileges(array $collection): void
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
     * @throws \Exception
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
