<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20180617083829 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS importer');

        $this->addSql('
            CREATE TABLE IF NOT EXISTS  importer.transformer (
                id UUID NOT NULL, 
                name VARCHAR(128) NOT NULL, 
                key VARCHAR(128) NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->createEventStoreEvents([
            'Ergonode\Transformer\Domain\Event\TransformerFieldAddedEvent' => 'Transformer field added',
            'Ergonode\Transformer\Domain\Event\TransformerAttributeAddedEvent' => 'Transformer attribute added',
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
