<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20191120151840 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS exporter');
        $this->addSql(
            'CREATE TABLE exporter.channel(
                    id uuid NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    type VARCHAR(64) NOT NULL,
                    class VARCHAR(255) NOT NULL, 
                    configuration JSONB not null,
                    created_at timestamp with time zone NOT NULL,
                    updated_at timestamp with time zone DEFAULT NULL,
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE exporter.scheduler(
                    id uuid NOT NULL,
                    active boolean NOT NULL,
                    start timestamp with time zone DEFAULT NULL,
                    last timestamp with time zone DEFAULT NULL,
                    hour integer DEFAULT NULL, 
                    minute integer DEFAULT NULL,                    
                    PRIMARY KEY (id)
                 )'
        );
        $this->addSql(
            'ALTER TABLE exporter.scheduler 
                    ADD CONSTRAINT scheduler_channel_fk FOREIGN KEY (id) 
                    REFERENCES exporter.channel ON UPDATE CASCADE ON DELETE CASCADE'
        );

        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Channel']);
        $this->createPrivileges([
            'CHANNEL_CREATE' => 'Channel',
            'CHANNEL_READ' => 'Channel',
            'CHANNEL_UPDATE' => 'Channel',
            'CHANNEL_DELETE' => 'Channel',
        ]);

        $this->createEventStoreEvents([
            'Ergonode\Channel\Domain\Event\ChannelCreatedEvent' => 'Channel created',
            'Ergonode\Channel\Domain\Event\ChannelDeletedEvent' => 'Channel deleted',
            'Ergonode\Channel\Domain\Event\ChannelNameChangedEvent' => 'Channel name changed',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createPrivileges(array $collection): void
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
