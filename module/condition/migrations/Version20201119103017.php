<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

class Version20201119103017 extends AbstractErgonodeMigration
{

    public function up(Schema $schema): void
    {

        $this->addSql(
            'WITH conditions_list AS (
                    SELECT id, 
                            arr.position AS position,
                            (SELECT jsonb_agg(iso) FROM language WHERE active = true) AS languages
                    FROM condition_set,
                         jsonb_array_elements(conditions) WITH ORDINALITY arr(item_object, position)
                    WHERE arr.item_object::jsonb ->> \'type\' = \'PRODUCT_HAS_STATUS_CONDITION\')
                UPDATE condition_set
                SET conditions = jsonb_set(
                                    conditions,
                                     (\'{\'|| conditions_list.position -1 || \',language}\')::text[], 
                                     conditions_list.languages
                                )
                FROM conditions_list
                WHERE condition_set.id = conditions_list.id'
        );

        $this->addSql(
            'WITH conditions_list AS (
                    SELECT id,
                            arr.position AS position,
                            (SELECT jsonb_agg(iso) FROM language WHERE active = true) AS languages
                    FROM event_store,
                        jsonb_array_elements(payload->\'conditions\') WITH ORDINALITY arr(item_object, position)
                    WHERE arr.item_object::jsonb ->> \'type\' = \'PRODUCT_HAS_STATUS_CONDITION\')
                UPDATE event_store
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
                WHERE event_store.id = conditions_list.id'
        );
    }
}
