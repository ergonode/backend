<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Core\Domain\Entity\AbstractId;

/**
 */
class MultimediaDeletedEvent implements DomainEventInterface
{
    /**
     * @var MultimediaId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private $id;

    /**
     * @param MultimediaId $id
     */
    public function __construct(MultimediaId $id)
    {
        $this->id = $id;
    }

    /**
     * @return MultimediaId
     */
    public function getAggregateId(): MultimediaId
    {
        return $this->id;
    }
}
