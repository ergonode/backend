<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\Reader\Domain\Entity\ReaderId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ReaderDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var ReaderId
     *
     * @JMS\Type("Ergonode\Reader\Domain\Entity\ReaderId")
     */
    private $id;

    /**
     * @param ReaderId $id
     */
    public function __construct(ReaderId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ReaderId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
