<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Ergonode Migration Class:
*/
final class Version20201211072933 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE exporter.shopware6_product_collection(
                    channel_id uuid NOT NULL,
                    product_collection_id uuid NOT NULL,
                    product_id uuid NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    updated_at timestamp with time zone NOT NULL,
                    PRIMARY KEY (channel_id, product_collection_id, product_id)
                )'
        );

        $this->addSql(
            'UPDATE exporter.channel
                    SET "configuration" = jsonb_set("configuration",\'{cross_selling}\',\'{}\',true)
                    WHERE type = \'shopware-6-api\''
        );
    }
}
