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

class TemplateDefaultLabelAddedEvent implements AggregateEventInterface
{
    private TemplateId $id;

    private AttributeId $defaultLabel;

    public function __construct(TemplateId $id, AttributeId $defaultLabel)
    {
        $this->id = $id;
        $this->defaultLabel = $defaultLabel;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getDefaultLabel(): AttributeId
    {
        return $this->defaultLabel;
    }
}
