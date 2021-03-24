<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class TemplateDefaultImageRemovedEvent implements AggregateEventInterface
{
    private TemplateId $id;

    private AttributeId $defaultImage;

    public function __construct(TemplateId $id, AttributeId $defaultImage)
    {
        $this->id = $id;
        $this->defaultImage = $defaultImage;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getDefaultImage(): AttributeId
    {
        return $this->defaultImage;
    }
}
