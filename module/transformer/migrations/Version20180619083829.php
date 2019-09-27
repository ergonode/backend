<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20180619083829 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS importer');

        $this->addSql('
            CREATE TABLE importer.transformer (
                id UUID NOT NULL, 
                name VARCHAR(128) NOT NULL, 
                key VARCHAR(128) NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE TABLE importer.transformer_converter (id UUID NOT NULL, transformer_id UUID NOT NULL, field VARCHAR(64) NOT NULL, type VARCHAR(255) NOT NULL, options JSON NOT NULL, PRIMARY KEY(id))');

        $this->addSql('
            CREATE TABLE importer.processor (
                id UUID NOT NULL, 
                import_id UUID NOT NULL, 
                transformer_Id UUID NOT NULL, 
                action VARCHAR(64) NOT NULL,
                created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                started_at TIMESTAMP WITHOUT TIME ZONE,
                ended_at TIMESTAMP WITHOUT TIME ZONE, 
                status character varying(32) NOT NULL, 
                PRIMARY KEY(id)
            )
        ');

        $this->createEventStoreEvents([
            'Ergonode\Transformer\Domain\Event\ProcessorCreatedEvent' => 'Transformer processor created',
            'Ergonode\Transformer\Domain\Event\ProcessorStatusChangedEvent' => 'Transformer processor status changed',
            'Ergonode\Transformer\Domain\Event\TransformerConverterAddedEvent' => 'Transformer converter added',
            'Ergonode\Transformer\Domain\Event\TransformerCreatedEvent' => 'Transformer created',
            'Ergonode\Transformer\Domain\Event\TransformerDeletedEvent' => 'Transformer deleted',
        ]);
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
