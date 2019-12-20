<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateGroupChangedEvent implements DomainEventInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateId")
     */
    private $id;

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
     * @param TemplateId      $id
     * @param TemplateGroupId $from
     * @param TemplateGroupId $to
     */
    public function __construct(TemplateId $id, TemplateGroupId $from, TemplateGroupId $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return AbstractId|TemplateId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
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
