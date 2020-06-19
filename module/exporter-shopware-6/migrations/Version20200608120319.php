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
final class Version20200608120319 extends AbstractErgonodeMigration
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
                    update_at timestamp without time zone NOT NULL,
                    PRIMARY KEY (export_profile_id, category_id)
                )'
        );

        $this->addSql(
            'CREATE TABLE exporter.shopware6_tax(
                    export_profile_id uuid NOT NULL,
                    tax  DECIMAL (5, 2) NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    update_at timestamp without time zone NOT NULL,
                    PRIMARY KEY (export_profile_id, tax)
                )'
        );

        $this->addSql(
            'CREATE TABLE exporter.shopware6_currency(
                    export_profile_id uuid NOT NULL,
                    iso varchar(255) NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    update_at timestamp without time zone NOT NULL,
                    PRIMARY KEY (export_profile_id, iso)
                )'
        );
    }
}
