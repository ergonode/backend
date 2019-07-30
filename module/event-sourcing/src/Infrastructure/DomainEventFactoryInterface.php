<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;

/**
 */
interface DomainEventFactoryInterface
{
    /**
     * @param AbstractId $id
     * @param array      $records
     *
     * @return DomainEventEnvelope[]
     */
    public function create(AbstractId $id, array $records): array ;
}
