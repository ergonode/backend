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

class TemplateCreatedEvent implements AggregateEventInterface
{
    private TemplateId $id;

    private TemplateGroupId $groupId;

    private string $name;

    private ?AttributeId $defaultLabel;

    private ?AttributeId $defaultImage;

    private ?MultimediaId $imageId;

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
