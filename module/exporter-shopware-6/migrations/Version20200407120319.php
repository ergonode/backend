<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Ergonode Migration Class:
*/
final class Version20200407120319 extends AbstractErgonodeMigration
{
    /**
    * @param Schema $schema
    */
    public function up(Schema $schema) : void
    {
        $this->addSql(
            'CREATE TABLE exporter.shopware6_category(
                    export_profile_id uuid NOT NULL,
                    category_id uuid NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    PRIMARY KEY (export_profile_id, category_id)
                )'
        );
    }
}
