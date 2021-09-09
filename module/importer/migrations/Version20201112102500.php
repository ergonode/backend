<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20201112102500 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->migrateEvent(
            'Ergonode\Transformer\Domain\Event\TransformerFieldAddedEvent',
            'Ergonode\Importer\Domain\Event\TransformerFieldAddedEvent'
        );

        $this->migrateEvent(
            'Ergonode\Transformer\Domain\Event\TransformerAttributeAddedEvent',
            'Ergonode\Importer\Domain\Event\TransformerAttributeAddedEvent'
        );

        $this->migrateEvent(
            'Ergonode\Transformer\Domain\Event\TransformerCreatedEvent',
            'Ergonode\Importer\Domain\Event\TransformerCreatedEvent'
        );

        $this->migrateEvent(
            'Ergonode\Transformer\Domain\Event\TransformerDeletedEvent',
            'Ergonode\Importer\Domain\Event\TransformerDeletedEvent'
        );
    }

    private function migrateEvent(string $from, string $to): void
    {
        $this->addSql('UPDATE event_store_event SET event_class = ? WHERE event_class = ?', [$to, $from]);
    }
}
