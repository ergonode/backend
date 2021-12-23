<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Handler;

use Doctrine\DBAL\Types\Types;
use Ergonode\Product\Application\Event\ProductUpdatedEvent;

class AuditProductUpdatedEventHandler extends AbstractAuditEventHandler
{
    private const TABLE = 'audit';

    public function __invoke(ProductUpdatedEvent $event): void
    {
        $createdAt = new \DateTime();
        $createdBy = $this->getUser();

        $this->connection->update(
            self::TABLE,
            [
                'edited_at' => $createdAt,
                'edited_by' => $createdBy,
            ],
            [
                'id' => $event->getProduct()->getId()->getValue(),
            ],
            [
                'edited_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
