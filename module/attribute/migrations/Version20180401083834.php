<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20180401083834 extends AbstractErgonodeMigration
{
    private const CURRENCIES = [
        'GBP' => 'Pound Sterling',
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'PLN' => 'Zloty',
        'RUB' => 'Russian Ruble',
        'JPY' => 'Japanese yen',
        'AUD' => 'Australian dollar',
        'CAD' => 'Canadian dollar',
        'CHF' => 'Swiss franc',
        'CNY' => 'Chinese Yuan Renminbi',
    ];

    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE attribute (
                id UUID NOT NULL,    
                index SERIAL,                
                type VARCHAR(32) NOT NULL,
                code VARCHAR(128) NOT NULL,     
                label UUID NOT NULL,
                placeholder UUID NOT NULL,
                hint UUID NOT NULL,       
                scope VARCHAR(16) NOT NULL,
                system BOOLEAN NOT NULL,                        
                PRIMARY KEY(id)
                                   
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX attribute_code_key ON attribute USING btree (code)');

        $this->addSql('
            CREATE TABLE attribute_value (
                id UUID NOT NULL,            
                type VARCHAR(255) NOT NULL,           
                value JSONB NOT NULL,                
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE TABLE value (               
                id UUID NOT NULL, 
                key TEXT,                                                                       
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX attribute_value_key_key ON value USING btree (key)');

        $this->addSql('
            CREATE TABLE value_translation (      
                id UUID NOT NULL,
                value_id UUID NOT NULL, 
                language VARCHAR(5) DEFAULT NULL,
                value TEXT,                                                   
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX ix_value_translation_language ON value_translation USING btree (language)');
        $this->addSql('CREATE INDEX ix_value_translation_value_id ON value_translation USING btree (value_id)');

        $this->addSql('
            CREATE TABLE attribute_group (
                id UUID NOT NULL,
                code VARCHAR(128) NOT NULL,
                name JSONB NOT NULL,                    
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('CREATE UNIQUE INDEX attribute_group_code_key ON attribute_group USING btree (code)');

        $this->addSql('
            CREATE TABLE entity_attribute_value (
                entity_id UUID NOT NULL,            
                attribute_id UUID NOT NULL,           
                value_id UUID NOT NULL,                
                PRIMARY KEY(entity_id, attribute_id, value_id)
            )
        ');
        $this->addSql('
            ALTER TABLE entity_attribute_value 
                ADD CONSTRAINT entity_attribute_value_attribute_id_fk 
                    FOREIGN KEY (attribute_id) REFERENCES attribute ON UPDATE CASCADE ON DELETE CASCADE');

        $this->addSql('
            CREATE TABLE attribute_option (   
                id uuid NOT NULL,
                attribute_id UUID NOT NULL, 
                value_id UUID NOT NULL,        
                key VARCHAR(255) NOT NULL,                                         
                PRIMARY KEY(id, attribute_id)
            )
        ');

        $this->addSql('CREATE UNIQUE INDEX attribute_option_code_attribute_key 
            ON attribute_option USING btree (attribute_id, key)');

        $this->addSql('
            ALTER TABLE attribute_option 
                ADD CONSTRAINT attribute_option_attribute_id_fk 
                    FOREIGN KEY (attribute_id) REFERENCES attribute ON UPDATE CASCADE ON DELETE CASCADE');

        // @todo verify is really needed
        $this->addSql('
            CREATE TABLE attribute_parameter (               
                attribute_id UUID NOT NULL, 
                type VARCHAR(16) NOT NULL,
                value JSONB NOT NULL,                                                   
                PRIMARY KEY(attribute_id, type)
            )
        ');
        $this->addSql('
            ALTER TABLE attribute_parameter 
                ADD CONSTRAINT attribute_parameter_attribute_id_fk 
                    FOREIGN KEY (attribute_id) REFERENCES attribute ON UPDATE CASCADE ON DELETE CASCADE');

        $this->addSql('
            CREATE TABLE attribute_group_attribute (
                attribute_id UUID NOT NULL,
                attribute_group_id UUID NOT NULL,                                         
                PRIMARY KEY(attribute_id, attribute_group_id)
            )
        ');
        $this->addSql('
            ALTER TABLE attribute_group_attribute
                ADD CONSTRAINT attribute_group_attribute_attribute_id_fk 
                    FOREIGN KEY (attribute_id) REFERENCES attribute ON UPDATE RESTRICT ON DELETE CASCADE');
        $this->addSql('
            ALTER TABLE attribute_group_attribute 
                ADD CONSTRAINT attribute_group_attribute_group_id_fk 
                    FOREIGN KEY (attribute_group_id) REFERENCES attribute_group ON UPDATE RESTRICT ON DELETE RESTRICT');

        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Attribute']);
        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Attribute group']);

        $this->createPrivileges([
            'ATTRIBUTE_CREATE' => 'Attribute',
            'ATTRIBUTE_READ' => 'Attribute',
            'ATTRIBUTE_UPDATE' => 'Attribute',
            'ATTRIBUTE_DELETE' => 'Attribute',
            'ATTRIBUTE_GROUP_CREATE' => 'Attribute group',
            'ATTRIBUTE_GROUP_READ' => 'Attribute group',
            'ATTRIBUTE_GROUP_UPDATE' => 'Attribute group',
            'ATTRIBUTE_GROUP_DELETE' => 'Attribute group',
        ]);

        $this->createEventStoreEvents([
            'Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent' => 'Attribute added',
            'Ergonode\Attribute\Domain\Event\Attribute\AttributeHintChangedEvent' => 'Attribute hint changed',
            'Ergonode\Attribute\Domain\Event\Attribute\AttributeLabelChangedEvent' => 'Attribute label changed',
            'Ergonode\Attribute\Domain\Event\Attribute\AttributePlaceholderChangedEvent' =>
                'Attribute placeholder changed',
            'Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent' =>
                'Attribute parameter changed',
            'Ergonode\Attribute\Domain\Event\Attribute\AttributeBoolParameterChangeEvent' =>
                'Attribute textarea parameter changed',
            'Ergonode\Attribute\Domain\Event\Group\AttributeGroupCreatedEvent' => 'Attribute group created',
            'Ergonode\Attribute\Domain\Event\Group\AttributeGroupDeletedEvent' => 'Attribute group removed',
            'Ergonode\Attribute\Domain\Event\Group\AttributeGroupNameChangedEvent' => 'Attribute group name changed',
            'Ergonode\Attribute\Domain\Event\AttributeGroupAddedEvent' => 'Attribute added to group',
            'Ergonode\Attribute\Domain\Event\AttributeGroupRemovedEvent' => 'Attribute removed from group',
            'Ergonode\Attribute\Domain\Event\Option\OptionCreatedEvent' => 'Attribute option added',
            'Ergonode\Attribute\Domain\Event\Option\OptionRemovedEvent' => 'Attribute option removed',
            'Ergonode\Attribute\Domain\Event\Option\OptionLabelChangedEvent' => 'Attribute option label changed',
            'Ergonode\Attribute\Domain\Event\Option\OptionCodeChangedEvent' => 'Attribute option code changed',
            'Ergonode\Attribute\Domain\Event\Attribute\AttributeDeletedEvent' => 'Attribute deleted',
            'Ergonode\Attribute\Domain\Event\Attribute\AttributeScopeChangedEvent' => 'Attribute scope changed',
        ]);

        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "ltree"');

        $this->addSql(
            'CREATE TABLE currency (id UUID NOT NULL, iso VARCHAR(3) NOT NULL, name VARCHAR(64), PRIMARY KEY(id))'
        );

        foreach (self::CURRENCIES as $iso => $name) {
            $this->addSql(
                'INSERT INTO currency (id, iso, name) VALUES (?, ?, ?)',
                [Uuid::uuid4()->toString(), $iso, $name]
            );
        }
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createPrivileges(array $collection): void
    {
        foreach ($collection as $code => $area) {
            $this->addSql(
                'INSERT INTO privileges (id, code, area) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $code,  $area, ]
            );
        }
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class => $translation) {
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }
}
