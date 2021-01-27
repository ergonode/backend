<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
    }
}
