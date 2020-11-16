<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\DBALException;

final class Version20201116123500 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'DELETE FROM event_store WHERE aggregate_id 
                 IN (SELECT aggregate_id FROM event_store_class WHERE class = ?)',
            ['Ergonode\Editor\Domain\Entity\ProductDraft']
        );

        $this->addSql(
            'DELETE FROM event_store_history WHERE aggregate_id 
                 IN (SELECT aggregate_id FROM event_store_class WHERE class = ?)',
            ['Ergonode\Editor\Domain\Entity\ProductDraft']
        );

        $this->addSql(
            'DELETE FROM event_store_class WHERE class = ?',
            ['Ergonode\Editor\Domain\Entity\ProductDraft']
        );

        $this->deleteEventStoreEvents([
            'Ergonode\Editor\Domain\Event\ProductDraftApplied',
            'Ergonode\Editor\Domain\Event\ProductDraftCreated',
            'Ergonode\Editor\Domain\Event\ProductDraftValueAdded',
            'Ergonode\Editor\Domain\Event\ProductDraftValueChanged',
            'Ergonode\Editor\Domain\Event\ProductDraftValueRemoved',
        ]);

        $this->addSql('DROP TABLE designer.draft_value');
        $this->addSql('DROP TABLE designer.draft');
    }

    /**
     * @param array $collection
     *
     * @throws DBALException
     */
    private function deleteEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class) {
            $this->connection->delete(
                'event_store_event',
                [
                    'event_class' => $class,
                ]
            );
        }
    }
}
