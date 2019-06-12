<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeImage\Domain\Event;

use Ergonode\AttributeImage\Domain\ValueObject\ImageFormat;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeImageFormatAddedEvent implements DomainEventInterface
{
    /**
     * @var ImageFormat
     *
     * @JMS\Type("Ergonode\AttributeImage\Domain\ValueObject\ImageFormat")
     */
    private $format;

    /**
     * @param ImageFormat $format
     */
    public function __construct(ImageFormat $format)
    {
        $this->format = $format;
    }

    /**
     * @return ImageFormat
     */
    public function getFormat(): ImageFormat
    {
        return $this->format;
    }
}
