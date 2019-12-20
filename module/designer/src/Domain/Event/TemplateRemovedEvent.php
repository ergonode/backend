<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateRemovedEvent extends AbstractDeleteEvent
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateId")
     */
    private $id;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private $reason;

    /**
     * @param TemplateId  $id
     * @param string|null $reason
     */
    public function __construct(TemplateId $id, ?string $reason = null)
    {
        $this->id = $id;
        $this->reason = $reason;
    }

    /**
     * @return AbstractId|TemplateId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }
}
