<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AvatarDeletedEvent implements DomainEventInterface
{
    /**
     * @var AvatarId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AvatarId")
     */
    private AvatarId $id;

    /**
     * @param AvatarId $id
     */
    public function __construct(AvatarId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AvatarId
     */
    public function getAggregateId(): AvatarId
    {
        return $this->id;
    }
}
