<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateImageChangedEvent implements DomainEventInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private $id;

    /**
     * @var MultimediaId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private $from;

    /**
     * @var MultimediaId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private $to;

    /**
     * @param TemplateId   $id
     * @param MultimediaId $from
     * @param MultimediaId $to
     */
    public function __construct(TemplateId $id, MultimediaId $from, MultimediaId $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return TemplateId
     */
    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    /**
     * @return MultimediaId
     */
    public function getFrom(): MultimediaId
    {
        return $this->from;
    }

    /**
     * @return MultimediaId
     */
    public function getTo(): MultimediaId
    {
        return $this->to;
    }
}
