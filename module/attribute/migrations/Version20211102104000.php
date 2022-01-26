<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Attribute\Domain\Event\Option\OptionCreatedEvent;
use Ergonode\Attribute\Domain\Event\Option\OptionLabelChangedEvent;
use Ergonode\Attribute\Domain\Event\Option\OptionCodeChangedEvent;

final class Version20211102104000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        // clear redundant snapshots
        $this->addSql(
            '
            DELETE 
            FROM event_store_snapshot 
            WHERE aggregate_id IN (
                SELECT aggregate_id
                FROM event_store
                WHERE event_id IN (SELECT id FROM event_store_event WHERE event_class IN (?,?,?))
            )',
            [OptionCreatedEvent::class, OptionCodeChangedEvent::class, OptionLabelChangedEvent::class]
        );

        // clean database from redundant option events (options without projection)
        $this->addSql(
            '
            DELETE 
            FROM event_store 
            WHERE aggregate_id IN (
                SELECT aggregate_id 
                FROM event_store 
                WHERE event_id IN (SELECT id FROM event_store_event WHERE event_class IN (?,?,?)) 
                AND aggregate_id NOT IN (SELECT id FROM attribute_option)
             )',
            [OptionCreatedEvent::class, OptionCodeChangedEvent::class, OptionLabelChangedEvent::class]
        );
    }
}
