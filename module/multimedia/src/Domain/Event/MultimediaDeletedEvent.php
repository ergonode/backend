<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Core\Domain\Entity\AbstractId;

/**
 */
class MultimediaDeletedEvent implements DomainEventInterface
{
    /**
     * @var MultimediaId
     *
     * @JMS\Type("Ergonode\Multimedia\Domain\Entity\MultimediaId")
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
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
