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
            'ALTER TABLE exporter.shopware6_product_collection 
                    ADD CONSTRAINT shopware6_product_collection_fk FOREIGN KEY (channel_id) 
                    REFERENCES exporter.channel(id) ON DELETE CASCADE'
        );

        $this->addSql(
            'UPDATE exporter.channel
                    SET "configuration" = jsonb_set("configuration",\'{cross_selling}\',\'{}\',true)
                    WHERE type = \'shopware-6-api\''
        );

        $this->addSql(
            'UPDATE exporter.channel
                    SET "configuration" = jsonb_set("configuration",\'{attribute_product_meta_title}\',\'null\',true)
                    WHERE type = \'shopware-6-api\''
        );

        $this->addSql(
            'UPDATE exporter.channel
                    SET "configuration" = jsonb_set(
                        "configuration",
                        \'{attribute_product_meta_description}\',
                        \'null\',
                        true)
                    WHERE type = \'shopware-6-api\''
        );

        $this->addSql(
            'UPDATE exporter.channel
                    SET "configuration" = jsonb_set("configuration",\'{attribute_product_keywords}\',\'null\',true)
                    WHERE type = \'shopware-6-api\''
        );

        $this->addSql(
            'DELETE 
                    FROM exporter.shopware6_category
                    WHERE shopware6_category.channel_id IN(
                        SELECT channel_id 
                        FROM exporter.shopware6_category 
                        LEFT JOIN exporter.channel ON channel.id = shopware6_category.channel_id 
                        WHERE channel.id IS NULL )'
        );
        $this->addSql(
            'ALTER TABLE exporter.shopware6_category 
                    ADD CONSTRAINT shopware6_category_fk FOREIGN KEY (channel_id) 
                    REFERENCES exporter.channel(id) ON DELETE CASCADE'
        );

        $this->addSql(
            'DELETE 
                    FROM exporter.shopware6_currency
                    WHERE shopware6_currency.channel_id IN(
                        SELECT channel_id 
                        FROM exporter.shopware6_currency 
                        LEFT JOIN exporter.channel ON channel.id = shopware6_currency.channel_id 
                        WHERE channel.id IS NULL )'
        );
        $this->addSql(
            'ALTER TABLE exporter.shopware6_currency
                    ADD CONSTRAINT shopware6_currency_fk FOREIGN KEY (channel_id)
                    REFERENCES exporter.channel(id) ON DELETE CASCADE'
        );

        $this->addSql(
            'DELETE 
                    FROM exporter.shopware6_custom_field
                    WHERE shopware6_custom_field.channel_id IN(
                        SELECT channel_id 
                        FROM exporter.shopware6_custom_field 
                        LEFT JOIN exporter.channel ON channel.id = shopware6_custom_field.channel_id 
                        WHERE channel.id IS NULL )'
        );
        $this->addSql(
            'ALTER TABLE exporter.shopware6_custom_field
                    ADD CONSTRAINT shopware6_custom_field_fk FOREIGN KEY (channel_id)
                    REFERENCES exporter.channel(id) ON DELETE CASCADE'
        );

        $this->addSql(
            'DELETE 
                    FROM exporter.shopware6_language
                    WHERE shopware6_language.channel_id IN(
                        SELECT channel_id 
                        FROM exporter.shopware6_language 
                        LEFT JOIN exporter.channel ON channel.id = shopware6_language.channel_id 
                        WHERE channel.id IS NULL )'
        );
        $this->addSql(
            'ALTER TABLE exporter.shopware6_language
                    ADD CONSTRAINT shopware6_language_fk FOREIGN KEY (channel_id)
                    REFERENCES exporter.channel(id) ON DELETE CASCADE'
        );

        $this->addSql(
            'DELETE 
                    FROM exporter.shopware6_multimedia
                    WHERE shopware6_multimedia.channel_id IN(
                        SELECT channel_id 
                        FROM exporter.shopware6_multimedia 
                        LEFT JOIN exporter.channel ON channel.id = shopware6_multimedia.channel_id 
                        WHERE channel.id IS NULL )'
        );
        $this->addSql(
            'ALTER TABLE exporter.shopware6_multimedia
                    ADD CONSTRAINT shopware6_multimedia_fk FOREIGN KEY (channel_id)
                    REFERENCES exporter.channel(id) ON DELETE CASCADE'
        );

        $this->addSql(
            'DELETE 
                    FROM exporter.shopware6_product
                    WHERE shopware6_product.channel_id IN(
                        SELECT channel_id 
                        FROM exporter.shopware6_product 
                        LEFT JOIN exporter.channel ON channel.id = shopware6_product.channel_id 
                        WHERE channel.id IS NULL )'
        );
        $this->addSql(
            'ALTER TABLE exporter.shopware6_product
                    ADD CONSTRAINT shopware6_product_fk FOREIGN KEY (channel_id)
                    REFERENCES exporter.channel(id) ON DELETE CASCADE'
        );

        $this->addSql(
            'DELETE 
                    FROM exporter.shopware6_property_group
                    WHERE shopware6_property_group.channel_id IN(
                        SELECT channel_id 
                        FROM exporter.shopware6_property_group 
                        LEFT JOIN exporter.channel ON channel.id = shopware6_property_group.channel_id 
                        WHERE channel.id IS NULL )'
        );
        $this->addSql(
            'ALTER TABLE exporter.shopware6_property_group
                    ADD CONSTRAINT shopware6_property_group_fk FOREIGN KEY (channel_id)
                    REFERENCES exporter.channel(id) ON DELETE CASCADE'
        );

        $this->addSql(
            'DELETE 
                    FROM exporter.shopware6_tax
                    WHERE shopware6_tax.channel_id IN(
                        SELECT channel_id 
                        FROM exporter.shopware6_tax 
                        LEFT JOIN exporter.channel ON channel.id = shopware6_tax.channel_id 
                        WHERE channel.id IS NULL )'
        );
        $this->addSql(
            'ALTER TABLE exporter.shopware6_tax
                    ADD CONSTRAINT shopware6_tax_fk FOREIGN KEY (channel_id)
                    REFERENCES exporter.channel(id) ON DELETE CASCADE'
        );
    }
}
