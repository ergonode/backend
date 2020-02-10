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
class TemplateImageRemovedEvent implements DomainEventInterface
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
    private $imageId;

    /**
     * @param TemplateId   $id
     * @param MultimediaId $imageId
     */
    public function __construct(TemplateId $id, MultimediaId $imageId)
    {
        $this->id = $id;
        $this->imageId = $imageId;
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
    public function getImageId(): MultimediaId
    {
        return $this->imageId;
    }
}
