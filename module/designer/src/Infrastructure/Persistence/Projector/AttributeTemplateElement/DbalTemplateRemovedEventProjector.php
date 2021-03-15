<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector\AttributeTemplateElement;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;

class DbalTemplateRemovedEventProjector
{
    private const ELEMENT_TABLE = 'designer.template_attribute';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(TemplateRemovedEvent $event): void
    {
        $this->connection->delete(
            self::ELEMENT_TABLE,
            [
                'template_id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
