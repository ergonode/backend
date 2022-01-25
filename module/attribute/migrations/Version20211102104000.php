<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Attribute\Domain\Event\Option\OptionCreatedEvent;

final class Version20211102104000 extends AbstractErgonodeMigration
{
       /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        // clean database from redundant option events (options without projection)
        $this->addSql(
            'DELETE 
                FROM event_store 
                WHERE aggregate_id IN (
                    SELECT aggregate_id 
                    FROM event_store 
                    WHERE event_id = (SELECT id FROM event_store_event WHERE event_class = ?) 
                AND aggregate_id NOT IN (SELECT id FROM attribute_option)
             )',
            [OptionCreatedEvent::class]
        );
    }
}
