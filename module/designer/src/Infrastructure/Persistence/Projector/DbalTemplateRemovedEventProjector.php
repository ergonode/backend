<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;

class DbalTemplateRemovedEventProjector
{
    private const TABLE = 'designer.template';
    private const ELEMENT_TABLE = 'designer.template_element';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(TemplateRemovedEvent $event): void
    {
        $this->connection->transactional(function () use ($event): void {
            $this->connection->delete(
                self::ELEMENT_TABLE,
                [
                    'template_id' => $event->getAggregateId()->getValue(),
                ]
            );

            $this->connection->delete(
                self::TABLE,
                [
                    'id' => $event->getAggregateId()->getValue(),
                ]
            );
        });
    }
}
