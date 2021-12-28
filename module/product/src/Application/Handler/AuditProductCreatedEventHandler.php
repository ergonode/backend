<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Handler;

use Ergonode\Product\Application\Event\ProductCreatedEvent;
use Doctrine\DBAL\Types\Types;

class AuditProductCreatedEventHandler extends AbstractAuditEventHandler
{
    private const TABLE = 'audit';

    public function __invoke(ProductCreatedEvent $event): void
    {
        $date = new \DateTime();
        $user = $this->getUser();

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getProduct()->getId()->getValue(),
                'created_at' => $date,
                'edited_at' => $date,
                'created_by' => $user,
                'edited_by' => $user,
            ],
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
                'edited_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
