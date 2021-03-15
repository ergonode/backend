<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

class Version20201130110000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'WITH conditions_list AS (
                    SELECT id,
                            arr.position AS position,
                            (SELECT jsonb_agg(iso) FROM language WHERE active = true) AS languages
                    FROM event_store,
                        jsonb_array_elements(payload->\'to\') WITH ORDINALITY arr(item_object, position)
                    WHERE event_id = (SELECT id FROM event_store_event 
                        WHERE event_class = \'Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent\')
                    AND arr.item_object::jsonb ->> \'type\' = \'PRODUCT_HAS_STATUS_CONDITION\')
                UPDATE event_store
                SET payload = jsonb_set(
                            payload,
                             \'{to}\',
                             jsonb_set(
                                    payload->\'to\',
                                    (\'{\'|| conditions_list.position -1 || \',language}\')::text[],
                                     conditions_list.languages
                             )
                         )
                FROM conditions_list
                WHERE event_store.id = conditions_list.id'
        );

        $this->addSql(
            'WITH conditions_list AS (
                SELECT id,
                        arr.position AS position,
                        (SELECT jsonb_agg(iso) FROM language WHERE active = true) AS languages
                FROM event_store,
                    jsonb_array_elements(payload->\'from\') WITH ORDINALITY arr(item_object, position)
                WHERE event_id = (SELECT id FROM event_store_event 
                    WHERE event_class = \'Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent\')
                AND arr.item_object::jsonb ->> \'type\' = \'PRODUCT_HAS_STATUS_CONDITION\')
            UPDATE event_store
            SET payload = jsonb_set(
                        payload,
                         \'{from}\',
                         jsonb_set(
                                payload->\'from\',
                                (\'{\'|| conditions_list.position -1 || \',language}\')::text[],
                                 conditions_list.languages
                         )
                     )
            FROM conditions_list
            WHERE event_store.id = conditions_list.id'
        );

        $this->addSql(
            'WITH conditions_list AS (
                    SELECT id,
                            arr.position AS position,
                            (SELECT jsonb_agg(iso) FROM language WHERE active = true) AS languages
                    FROM event_store_snapshot,
                        jsonb_array_elements(payload->\'conditions\') WITH ORDINALITY arr(item_object, position)
                    WHERE aggregate_id IN (SELECT aggregate_id FROM event_store_class 
                        WHERE class = \'Ergonode\Condition\Domain\Entity\ConditionSet\')
                    AND arr.item_object::jsonb ->> \'type\' = \'PRODUCT_HAS_STATUS_CONDITION\')
                UPDATE event_store_snapshot
                SET payload = jsonb_set(
                            payload,
                             \'{conditions}\',
                             jsonb_set(
                                    payload->\'conditions\',
                                    (\'{\'|| conditions_list.position -1 || \',language}\')::text[],
                                     conditions_list.languages
                             )
                         )
                FROM conditions_list
                WHERE event_store_snapshot.id = conditions_list.id'
        );
    }
}
