<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateGroupChangedEvent implements DomainEventInterface
{
    /**
     * @var TemplateGroupId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateGroupId")
     */
    private $from;

    /**
     * @var TemplateGroupId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateGroupId")
     */
    private $to;

    /**
     * @param TemplateGroupId $from
     * @param TemplateGroupId $to
     */
    public function __construct(TemplateGroupId $from, TemplateGroupId $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return TemplateGroupId
     */
    public function getOld(): TemplateGroupId
    {
        return $this->from;
    }

    /**
     * @return TemplateGroupId
     */
    public function getNew(): TemplateGroupId
    {
        return $this->to;
    }
}
