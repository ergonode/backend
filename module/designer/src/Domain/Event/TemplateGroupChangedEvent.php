<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;

class TemplateGroupChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId")
     */
    private TemplateGroupId $from;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId")
     */
    private TemplateGroupId $to;

    public function __construct(TemplateId $id, TemplateGroupId $from, TemplateGroupId $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getOld(): TemplateGroupId
    {
        return $this->from;
    }

    public function getNew(): TemplateGroupId
    {
        return $this->to;
    }
}
