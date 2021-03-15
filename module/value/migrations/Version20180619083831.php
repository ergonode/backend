<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20180619083831 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->createEventStoreEvents([
            'Ergonode\Value\Domain\Event\ValueAddedEvent' => 'Value created',
            'Ergonode\Value\Domain\Event\ValueChangedEvent' => 'Value changed',
            'Ergonode\Value\Domain\Event\ValueRemovedEvent' => 'Value deleted',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
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
