<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Ergonode Migration Class:
*/
final class Version20200608120319 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE exporter.shopware6_category(
                    channel_id uuid NOT NULL,
                    category_id uuid NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    update_at timestamp with time zone NOT NULL,
                    PRIMARY KEY (channel_id, category_id)
                )'
        );

        $this->addSql(
            'CREATE TABLE exporter.shopware6_product(
                    channel_id uuid NOT NULL,
                    product_id uuid NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    update_at timestamp with time zone NOT NULL,
                    PRIMARY KEY (channel_id, product_id)
                )'
        );

        $this->addSql(
            'CREATE TABLE exporter.shopware6_tax(
                    channel_id uuid NOT NULL,
                    tax  DECIMAL (10, 2) NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    update_at timestamp with time zone NOT NULL,
                    PRIMARY KEY (channel_id, tax)
                )'
        );

        $this->addSql(
            'CREATE TABLE exporter.shopware6_currency(
                    channel_id uuid NOT NULL,
                    iso varchar(255) NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    update_at timestamp with time zone NOT NULL,
                    PRIMARY KEY (channel_id, iso)
                )'
        );

        $this->addSql(
            'CREATE TABLE exporter.shopware6_property_group(
                    channel_id uuid NOT NULL,
                    attribute_id uuid NOT NULL,
                    type varchar(36) NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    update_at timestamp with time zone NOT NULL,
                    PRIMARY KEY (channel_id, attribute_id)
                )'
        );

        $this->addSql(
            'CREATE TABLE exporter.shopware6_property_group_options(
                    channel_id uuid NOT NULL,
                    attribute_id uuid NOT NULL,
                    option_id uuid NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    update_at timestamp with time zone NOT NULL,
                    PRIMARY KEY (channel_id, attribute_id, option_id)
                )'
        );

        $this->addSql(
            'ALTER TABLE exporter.shopware6_property_group_options 
                    ADD CONSTRAINT shopware6_property_group_options_fk FOREIGN KEY (channel_id,attribute_id) 
                    REFERENCES exporter.shopware6_property_group(channel_id,attribute_id) ON DELETE CASCADE'
        );


        $this->addSql(
            'CREATE TABLE exporter.shopware6_custom_field(
                    channel_id uuid NOT NULL,
                    attribute_id uuid NOT NULL,
                    type varchar(36) NOT NULL,
                    shopware6_id varchar(36) NOT NULL,
                    update_at timestamp with time zone NOT NULL,
                    PRIMARY KEY (channel_id, attribute_id)
                )'
        );

        $this->addSql(
            'CREATE TABLE exporter.shopware6_language(
                    channel_id uuid NOT null,
	                shopware6_id varchar(36) NOT NULL,
	                locale_id varchar(36) NOT NULL,
	                translation_code_id varchar(36) NOT NULL,
	                iso varchar(5) NOT NULL,
	                update_at timestamp with time zone NOT NULL,
	                PRIMARY KEY(channel_id, iso)
	            )'
        );

        $this->addSql(
            'CREATE TABLE exporter.shopware6_multimedia(
                    channel_id uuid NOT null,
	                multimedia_id  uuid NOT null,
	                shopware6_id varchar(36) NOT null,
	                update_at timestamp with time zone NOT NULL,
	                PRIMARY KEY(channel_id, multimedia_id)
	            )'
        );
    }
}
