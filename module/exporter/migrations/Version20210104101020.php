<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210104101020 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE exporter.export
                    DROP CONSTRAINT export_channel_id_fk,
                    ADD CONSTRAINT export_channel_id_fk FOREIGN KEY (channel_id) 
                    REFERENCES exporter.channel(id) ON UPDATE CASCADE ON DELETE CASCADE'
        );
    }
}
