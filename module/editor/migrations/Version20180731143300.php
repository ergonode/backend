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
final class Version20180731143300 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE designer.draft (
                id UUID NOT NULL,
                sku VARCHAR(255) DEFAULT NULL,
                type VARCHAR(16) NOT NULL DEFAULT \'NEW\',
                product_id UUID DEFAULT NULL,      
                applied boolean NOT NULL DEFAULT FALSE,                                          
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE TABLE designer.draft_value (
                id UUID NOT NULL,
                draft_id UUID DEFAULT NULL,
                element_id UUID NOT NULL,
                language VARCHAR(5) DEFAULT NULL, 
                value text,                                           
                PRIMARY KEY(id)
            )
        ');

        $this->createEventStoreEvents([
            'Ergonode\Editor\Domain\Event\ProductDraftApplied' => 'Applied product draft',
            'Ergonode\Editor\Domain\Event\ProductDraftCreated' => 'Product draft created',
            'Ergonode\Editor\Domain\Event\ProductDraftValueAdded' => 'Value added to product draft',
            'Ergonode\Editor\Domain\Event\ProductDraftValueChanged' => 'Product draft value changed',
            'Ergonode\Editor\Domain\Event\ProductDraftValueRemoved' => 'Product draft value removed',
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
