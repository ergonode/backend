<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class TemplateImageRemovedEvent implements AggregateEventInterface
{
    private TemplateId $id;

    private MultimediaId $imageId;

    public function __construct(TemplateId $id, MultimediaId $imageId)
    {
        $this->id = $id;
        $this->imageId = $imageId;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getImageId(): MultimediaId
    {
        return $this->imageId;
    }
}
