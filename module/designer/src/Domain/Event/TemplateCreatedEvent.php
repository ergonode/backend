<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;

class TemplateCreatedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId")
     */
    private TemplateGroupId $groupId;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $defaultLabel;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $defaultImage;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private ?MultimediaId $imageId;

    /**
     * TemplateCreatedEvent constructor.
     */
    public function __construct(
        TemplateId $id,
        TemplateGroupId $groupId,
        string $name,
        ?AttributeId $defaultLabel,
        ?AttributeId $defaultImage,
        ?MultimediaId $imageId
    ) {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->name = $name;
        $this->defaultLabel = $defaultLabel;
        $this->defaultImage = $defaultImage;
        $this->imageId = $imageId;
    }


    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getGroupId(): TemplateGroupId
    {
        return $this->groupId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDefaultLabel(): ?AttributeId
    {
        return $this->defaultLabel;
    }

    public function getDefaultImage(): ?AttributeId
    {
        return $this->defaultImage;
    }

    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }
}
