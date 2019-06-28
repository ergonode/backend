<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20180731143300 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE designer.product (
                    product_id UUID NOT NULL,
                    template_id UUID NOT NULL,                               
                    PRIMARY KEY(product_id, template_id)
                )'
        );

        $this->addSql(
            'CREATE TABLE designer.draft (
                    id UUID NOT NULL,
                    sku VARCHAR(255) DEFAULT NULL,
                    type VARCHAR(16) NOT NULL DEFAULT \'NEW\',
                    product_id UUID DEFAULT NULL,      
                    applied boolean NOT NULL DEFAULT FALSE,                                          
                    PRIMARY KEY(id)
                )'
        );

        $this->addSql(
            'CREATE TABLE designer.draft_value (
                    id UUID NOT NULL,
                    draft_id UUID DEFAULT NULL,
                    element_id UUID NOT NULL,
                    language VARCHAR(2) DEFAULT NULL, 
                    value text NOT NULL,                                           
                    PRIMARY KEY(id)
                )'
        );

        $this->addSql('ALTER TABLE designer.product ADD CONSTRAINT product_template_id_fk FOREIGN KEY (template_id) REFERENCES designer.template (id) ON DELETE RESTRICT');
    }
}
