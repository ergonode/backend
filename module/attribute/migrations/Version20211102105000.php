<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionAddedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionMovedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionRemovedEvent;

final class Version20211102105000 extends AbstractErgonodeMigration
{
    private const EVENTS = [
        AttributeOptionAddedEvent::class => 'Attribute option added',
        AttributeOptionRemovedEvent::class => 'Attribute option removed',
        AttributeOptionMovedEvent::class => 'Attribute option moved',
    ];

    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE attribute_options (   
                attribute_id UUID NOT NULL, 
                option_id UUID NOT NULL,        
                index integer NOT NULL,                                         
                PRIMARY KEY(attribute_id, option_id, index)
            )
        ');

//        $this->addSql('CREATE UNIQUE INDEX options_attribute_option_key
//            ON attribute_options USING btree (attribute_id, option_id)');
//
//        $this->addSql('CREATE UNIQUE INDEX options_attribute_index_key
//            ON attribute_options USING btree (attribute_id, index)');

        $this->addSql(' ALTER TABLE attribute_option DROP CONSTRAINT attribute_option_pkey ');
        $this->addSql(' ALTER TABLE attribute_option ADD CONSTRAINT attribute_option_pkey PRIMARY KEY (id)');

        $this->addSql('
            ALTER TABLE attribute_options 
                ADD CONSTRAINT attribute_options_attribute_id_fk 
                    FOREIGN KEY (attribute_id) REFERENCES attribute ON UPDATE CASCADE ON DELETE CASCADE');

        $this->addSql('
            ALTER TABLE attribute_options
                ADD CONSTRAINT attribute_options_option_id_fk
                    FOREIGN KEY (option_id) REFERENCES attribute_option ON UPDATE CASCADE ON DELETE RESTRICT');

        //@todo uncomment after changes in projections
        //$this->addSql('ALTER TABLE  attribute_option DROP COLUMN attribute_id');

        $attributes = $this->connection->executeQuery('SELECT DISTINCT attribute_id FROM attribute_option ')
            ->fetchFirstColumn();

        foreach ($attributes as $attribute) {
            $options = $this->connection->executeQuery(
                'SELECT id FROM attribute_option WHERE attribute_id = :id',
                ['id' => $attribute]
            )->fetchFirstColumn();

            $i = 0;
            foreach ($options as $option) {
                $this->addSql(
                    'INSERT INTO attribute_options (attribute_id, option_id, index) VALUES (?,?,?)',
                    [$attribute, $option, $i]
                );
                $i++;
            }
        }

        foreach (self::EVENTS as $class => $translation) {
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }
}
