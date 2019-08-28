<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateImageChangedEvent implements DomainEventInterface
{
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
     * @param MultimediaId $from
     * @param MultimediaId $to
     */
    public function __construct(MultimediaId $from, MultimediaId $to)
    {
        $this->from = $from;
        $this->to = $to;
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
