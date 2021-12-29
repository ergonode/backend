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
final class Version20210303135330 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'UPDATE event_store
                SET payload = jsonb_set(payload, \'{from}\', (payload->>\'to\')::jsonb)
                WHERE
	                event_id IN(
		                SELECT id
		                FROM event_store_event
		                WHERE event_class = \'Ergonode\Product\Domain\Event\ProductValueChangedEvent\'
	                )
	               '
        );

        $this->addSql(
            'UPDATE event_store
                SET payload = jsonb_set(payload, \'{from}\', (new_payload->>\'value\')::jsonb)
                FROM
                (
                SELECT 
                    DISTINCT ON (table_a.id)
                    table_a.id,
                    table_b.payload::jsonb AS new_payload
                FROM 
                (
                    SELECT id, aggregate_id, "sequence", payload 
                    FROM event_store es
                    WHERE
                        es.event_id IN(
                            SELECT id
                            FROM event_store_event
                            WHERE event_class = \'Ergonode\Product\Domain\Event\ProductValueChangedEvent\'
                        )
                    ORDER BY es."sequence" ASC
                ) table_a,
                (
                    SELECT id, aggregate_id, "sequence", payload 
                    FROM event_store es
                    WHERE
                        es.event_id IN(
                            SELECT id
                            FROM event_store_event
                            WHERE event_class = \'Ergonode\Product\Domain\Event\ProductValueAddedEvent\'
                        )
                    ORDER BY es."sequence" ASC
                ) table_b
                WHERE table_a.aggregate_id = table_b.aggregate_id 
                AND table_a.sequence > table_b.sequence
                AND table_a.payload->>\'code\' = table_b.payload->>\'code\'
            ) tmp
            WHERE event_store.id = tmp.id 
            AND event_store.event_id IN(
				        SELECT id
				        FROM event_store_event
				        WHERE event_class = \'Ergonode\Product\Domain\Event\ProductValueChangedEvent\'
			        )
            '
        );

        $this->addSql(
            'UPDATE event_store
                SET payload = jsonb_set(payload, \'{from}\', (new_payload->>\'to\')::jsonb)
                FROM
                (
                SELECT 
                    DISTINCT ON (table_a.id)
                    table_a.id,
                    table_b.payload::jsonb AS new_payload
                FROM 
                (
                    SELECT id, aggregate_id, "sequence", payload 
                    FROM event_store es
                    WHERE
                        es.event_id IN(
                            SELECT id
                            FROM event_store_event
                            WHERE event_class = \'Ergonode\Product\Domain\Event\ProductValueChangedEvent\'
                        )
                    ORDER BY es."sequence" DESC
                ) table_a,
                (
                    SELECT id, aggregate_id, "sequence", payload 
                    FROM event_store es
                    WHERE
                        es.event_id IN(
                            SELECT id
                            FROM event_store_event
                            WHERE event_class = \'Ergonode\Product\Domain\Event\ProductValueChangedEvent\'
                        )
                    ORDER BY es."sequence" DESC
                ) table_b
                WHERE table_a.aggregate_id = table_b.aggregate_id 
                AND table_a.sequence > table_b.sequence
                AND table_a.payload->>\'code\' = table_b.payload->>\'code\'
            ) tmp
            WHERE event_store.id = tmp.id
        '
        );
    }
}
