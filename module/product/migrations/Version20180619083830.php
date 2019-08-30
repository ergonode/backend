<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20180619083830 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS product (
                    id UUID NOT NULL,
                    index SERIAL,
                    template_id UUID NOT NULL,
                    sku VARCHAR(128) NOT NULL,
                    status VARCHAR(32) NOT NULL,
                    version INT NOT NULL DEFAULT 0,
                    attributes JSONB NOT NULL DEFAULT \'{}\'::JSONB,
                    PRIMARY KEY(id)
                )'
        );

        $this->addSql('CREATE UNIQUE INDEX product_sku_key ON product USING btree(sku)');
        $this->addSql('CREATE TABLE product_value (product_id UUID NOT NULL, attribute_id UUID NOT NULL, value_id UUID NOT NULL, PRIMARY KEY(product_id, attribute_id, value_id))');
        $this->addSql('CREATE TABLE product_category_product (category_id UUID NOT NULL, product_id UUID NOT NULL, PRIMARY KEY(category_id, product_id))');

        $this->addSql('CREATE TABLE product_status (code VARCHAR(32), name VARCHAR(64), PRIMARY KEY(code))');
        $this->addSql('INSERT INTO product_status (code, name) VALUES(?, ?)', ['DRAFT', 'Draft']);
        $this->addSql('INSERT INTO product_status (code, name) VALUES(?, ?)', ['ACCEPTED', 'Accepted']);
        $this->addSql('INSERT INTO product_status (code, name) VALUES(?, ?)', ['TO_ACCEPTED', 'To accept']);
        $this->addSql('INSERT INTO product_status (code, name) VALUES(?, ?)', ['TO_CORRECT', 'To correct']);

        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'PRODUCT_CREATE', 'Product']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'PRODUCT_READ', 'Product']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'PRODUCT_UPDATE', 'Product']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'PRODUCT_DELETE', 'Product']);
    }
}
