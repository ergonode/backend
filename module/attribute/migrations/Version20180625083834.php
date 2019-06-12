<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Attribute\Domain\Entity\AttributeGroupId;
use Ergonode\Migration\AbstractErgonodeMigration;

/**
 */
final class Version20180625083834 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE attribute (
                    id UUID NOT NULL,    
                    index SERIAL,                
                    type VARCHAR(32) NOT NULL,
                    code VARCHAR(255) NOT NULL,     
                    label UUID NOT NULL,
                    placeholder UUID NOT NULL,
                    hint UUID NOT NULL,       
                    multilingual BOOLEAN NOT NULL,                
                    PRIMARY KEY(id)
                )'
        );

        $this->addSql(
            'CREATE TABLE attribute_value (
                    id UUID NOT NULL,            
                    type VARCHAR(255) NOT NULL,           
                    value JSONB NOT NULL,                
                    PRIMARY KEY(id)
                )'
        );

        $this->addSql(
            'CREATE TABLE entity_attribute_value (
                    entity_id UUID NOT NULL,            
                    attribute_id UUID NOT NULL,           
                    value_id UUID NOT NULL,                
                    PRIMARY KEY(entity_id, attribute_id, value_id)
                )'
        );

        $this->addSql(
            'CREATE TABLE attribute_option (                 
                    attribute_id UUID NOT NULL, 
                    value_id UUID NOT NULL,        
                    key VARCHAR(255) NOT NULL,                                         
                    PRIMARY KEY(attribute_id, value_id)
                )'
        );

        // @todo verify is really needed
        $this->addSql(
            'CREATE TABLE attribute_parameter (               
                    attribute_id UUID NOT NULL, 
                    type VARCHAR(32) NOT NULL,
                    value JSONB NOT NULL,                                                   
                    PRIMARY KEY(attribute_id, type)
                )'
        );

        // @todo verify is really needed
        $this->addSql(
            'CREATE TABLE value (               
                    id UUID NOT NULL, 
                    key TEXT,                                                                       
                    PRIMARY KEY(id)
                )'
        );

        $this->addSql(
            'CREATE TABLE value_translation (      
                    id UUID NOT NULL,
                    value_id UUID NOT NULL, 
                    language VARCHAR(2) DEFAULT NULL,
                    value TEXT NOT NULL,                                                   
                    PRIMARY KEY(id)
                )'
        );

        $this->addSql('CREATE INDEX ix_value_translation_language ON value_translation USING btree (language)');
        $this->addSql('CREATE INDEX ix_value_translation_value_id ON value_translation USING btree (value_id)');

        $this->addSql('CREATE UNIQUE INDEX attribute_code_key ON attribute USING btree (code)');
        $this->addSql('CREATE UNIQUE INDEX attribute_value_key_key ON value USING btree (key)');

        $this->addSql(
            'CREATE TABLE attribute_group (
                    id UUID NOT NULL,
                    label VARCHAR(255) NOT NULL,
                    "default" BOOLEAN DEFAULT false,                   
                    PRIMARY KEY(id)
                )'
        );

        $this->addSql(
            'CREATE TABLE attribute_group_attribute (
                    attribute_id UUID NOT NULL,
                    attribute_group_id UUID NOT NULL,                                         
                    PRIMARY KEY(attribute_id, attribute_group_id)
                )'
        );

        $this->addGroup('Default', true);
        $this->addGroup('System');
    }
    /**
     * @param string $label
     * @param bool   $default
     *
     * @throws \Exception
     */
    private function addGroup(string $label, bool $default = false): void
    {
        $id = AttributeGroupId::generate();
        $this->addSql('INSERT INTO attribute_group (id, label, "default") VALUES (?, ?, ?)', [$id, $label, (int) $default], ['default' => \PDO::PARAM_BOOL]);
    }
}
