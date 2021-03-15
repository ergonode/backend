<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20201113124000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE segment DROP COLUMN status');

        $this->addSql(
            'DELETE FROM event_store_event WHERE event_class = ?',
            ['Ergonode\Segment\Domain\Event\SegmentStatusChangedEvent']
        );
    }
}
