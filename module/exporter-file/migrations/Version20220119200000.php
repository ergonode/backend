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
final class Version20220119200000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
        UPDATE exporter.channel
        SET configuration = jsonb_set(configuration, \'{segment_id}\', \'null\' )
        WHERE class = \'Ergonode\ExporterFile\Domain\Entity\FileExportChannel\'
        ');
    }
}
