<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateImageChangedEvent implements DomainEventInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateId")
     */
    private $id;

    /**
     * @var MultimediaId
     *
     * @JMS\Type("Ergonode\Multimedia\Domain\Entity\MultimediaId")
     */
    private $from;

    /**
     * @var MultimediaId
     *
     * @JMS\Type("Ergonode\Multimedia\Domain\Entity\MultimediaId")
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
     * @return AbstractId|TemplateId
     */
    public function getAggregateId(): AbstractId
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
