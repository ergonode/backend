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
        $languages = $this->getDictionaryActive();

        $this->addSql(sprintf(
            'WITH conditions_list as (
                SELECT id, (\'{\'|| arr.position -1 || \',language}\')::text[] as path
                FROM condition_set,
                    jsonb_array_elements(conditions) with ordinality arr(item_object, position)
                where arr.item_object::jsonb ->> \'type\' = \'PRODUCT_HAS_STATUS_CONDITION\')
            UPDATE condition_set
            set conditions = jsonb_set(conditions, conditions_list.path, \'%s\')
            from conditions_list
            where condition_set.id = conditions_list.id',
            $languages
        ));

        $this->addSql(sprintf(
            'WITH conditions_list as (
                SELECT id, (\'{\'|| arr.position -1 || \',language}\')::text[] as path
                FROM event_store,
                     jsonb_array_elements(payload->\'conditions\') with ordinality arr(item_object, position)
                where arr.item_object::jsonb ->> \'type\' = \'PRODUCT_HAS_STATUS_CONDITION\')
            UPDATE event_store
            set payload = jsonb_set(
            payload, \'{conditions}\', jsonb_set(payload->\'conditions\', conditions_list.path, \'%s\')
            )
            from conditions_list
            where event_store.id = conditions_list.id',
            $languages
        ));
    }

    /**
     * @return array
     */
    public function getDictionaryActive(): string
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'iso',
                'iso as name'
            )
            ->from('language');

        $result = $qb
            ->where($qb->expr()->eq('active', ':active'))
            ->setParameter(':active', true, \PDO::PARAM_BOOL)
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);

        return json_encode(array_keys($result));
    }
}
