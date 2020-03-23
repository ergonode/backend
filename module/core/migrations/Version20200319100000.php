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
final class Version20200319100000 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "ltree"');

        $this->addSql(
            'CREATE TABLE unit (id UUID NOT NULL, name VARCHAR(255) NOT NULL,
                  symbol VARCHAR(255) NOT NULL,
                  PRIMARY KEY(id))'
        );

        $this->createEventStoreEvents([
            'Ergonode\Core\Domain\Event\UnitSymbolChangedEvent'
            => 'Unit symbol changed',
            'Ergonode\Core\Domain\Event\UnitNameChangedEvent'
            => 'Unit name changed',
            'Ergonode\Core\Domain\Event\UnitDeletedEvent'
            => 'Unit deleted',
            'Ergonode\Core\Domain\Event\UnitCreatedEvent'
            => 'Unit created',
        ]);
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
