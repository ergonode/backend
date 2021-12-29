<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210325094500 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE importer.transformer');

        $events = [
            'Ergonode\Importer\Domain\Event\TransformerFieldAddedEvent',
            'Ergonode\Importer\Domain\Event\TransformerAttributeAddedEvent',
            'Ergonode\Importer\Domain\Event\TransformerCreatedEvent',
            'Ergonode\Importer\Domain\Event\TransformerDeletedEvent',
        ];

        $this->deleteEventStoreEvents($events);
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function deleteEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class) {
            $this->addSql('DELETE FROM event_store_event WHERE event_class = ?', [$class]);
        }
    }
}
