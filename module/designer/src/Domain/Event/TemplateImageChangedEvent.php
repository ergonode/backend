<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;

class TemplateImageChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private MultimediaId $to;

    public function __construct(TemplateId $id, MultimediaId $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getTo(): MultimediaId
    {
        return $this->to;
    }
}
