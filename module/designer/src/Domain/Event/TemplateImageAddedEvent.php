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
class TemplateImageAddedEvent implements DomainEventInterface
{
    /**
     * @var MultimediaId
     *
     * @JMS\Type("Ergonode\Multimedia\Domain\Entity\MultimediaId")
     */
    private $imageId;

    /**
     * @param MultimediaId $imageId
     */
    public function __construct(MultimediaId $imageId)
    {
        $this->imageId = $imageId;
    }

    /**
     * @return MultimediaId
     */
    public function getImageId(): MultimediaId
    {
        return $this->imageId;
    }
}
