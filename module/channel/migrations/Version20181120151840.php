<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20181120151840 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS exporter');
        $this->addSql(
            'CREATE TABLE exporter.channel(
                    id uuid NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    type VARCHAR(255) NOT NULL,
                    class VARCHAR(255) NOT NULL, 
                    configuration JSONB not null,
                    created_at timestamp without time zone NOT NULL,
                    updated_at timestamp without time zone DEFAULT NULL,
                    PRIMARY KEY (id)
                 )'
        );

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
            $this->connection->insert('privileges', [
                'id' => Uuid::uuid4()->toString(),
                'code' => $code,
                'area' => $area,
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
