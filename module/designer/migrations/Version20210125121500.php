<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20210125121500 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE designer.template_attribute (
                template_id UUID NOT NULL,
                x INTEGER NOT NULL,
                y INTEGER NOT NULL,
                attribute_id uuid NOT NULL ,           
                PRIMARY KEY(template_id, x, y)
            )
        ');

        $this->addSql('ALTER TABLE designer.template_element ADD COLUMN type VARCHAR(32) DEFAULT NULL');

        $this->addSql('
        INSERT INTO designer.template_attribute (
            SELECT template_id, x,y, (properties->>\'attribute_id\')::UUID as attribute_id 
            FROM designer.template_element WHERE properties->\'attribute_id\' iS NOT NULL
            )
        ');

        $this->addSql('UPDATE designer.template_element SET type = properties->>\'variant\'');

        $this->addSql('ALTER TABLE designer.template_element ALTER COLUMN type DROP DEFAULT');
        $this->addSql('ALTER TABLE designer.template_element ALTER COLUMN type SET NOT NULL');

        $this->addSql('ALTER TABLE designer.template_element DROP COLUMN properties');

        $this->addSql('
            UPDATE event_store 
            SET payload = jsonb_set(payload, \'{element, type}\', payload->\'element\'->\'properties\'->\'variant\') 
            WHERE event_id IN (
                SELECT id 
                FROM event_store_event 
                WHERE event_class = \'Ergonode\Designer\Domain\Event\TemplateElementAddedEvent\'
                OR event_class =  \'Ergonode\Designer\Domain\Event\TemplateElementChangedEvent\'
            )');

        $this->addSql('
            UPDATE event_store 
            SET payload = jsonb_insert(payload,\'{element, attribute_id}\', 
                payload->\'element\'->\'properties\'->\'attribute_id\')
            WHERE event_id IN (
                SELECT id 
                FROM event_store_event 
                WHERE event_class = \'Ergonode\Designer\Domain\Event\TemplateElementAddedEvent\'
                OR event_class =  \'Ergonode\Designer\Domain\Event\TemplateElementChangedEvent\'
                )
            AND payload->\'element\'->\'properties\'->\'attribute_id\' IS NOT NULL');

        $this->addSql('
            UPDATE event_store 
            SET payload = jsonb_insert(payload,\'{element, required}\', 
                payload->\'element\'->\'properties\'->\'required\')
            WHERE event_id IN (
                SELECT id 
                FROM event_store_event 
                WHERE event_class = \'Ergonode\Designer\Domain\Event\TemplateElementAddedEvent\'
                OR event_class =  \'Ergonode\Designer\Domain\Event\TemplateElementChangedEvent\'
                )
            AND payload->\'element\'->\'properties\'->\'required\' IS NOT NULL');

        $this->addSql('
            UPDATE event_store 
            SET payload = jsonb_insert(payload,\'{element, label}\', payload->\'element\'->\'properties\'->\'label\')
            WHERE event_id IN (
                SELECT id 
                FROM event_store_event 
                WHERE event_class = \'Ergonode\Designer\Domain\Event\TemplateElementAddedEvent\'
                OR event_class =  \'Ergonode\Designer\Domain\Event\TemplateElementChangedEvent\'
                )
            AND payload->\'element\'->\'properties\'->\'label\' IS NOT NULL');

        $this->addSql('
            UPDATE event_store 
            SET payload  = payload #- \'{element,properties}\' 
            WHERE event_id IN (
                SELECT id FROM event_store_event 
                WHERE event_class = \'Ergonode\Designer\Domain\Event\TemplateElementAddedEvent\'
                OR event_class =  \'Ergonode\Designer\Domain\Event\TemplateElementChangedEvent\'
            )
        ');

        $this->addSql('DELETE FROM event_store_snapshot WHERE aggregate_id in (SELECT id FROM designer.template)');
    }
}
