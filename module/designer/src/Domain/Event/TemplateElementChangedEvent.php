<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class TemplateElementChangedEvent implements AggregateEventInterface
{
    private TemplateId $id;

    private TemplateElementInterface $element;

    public function __construct(TemplateId $id, TemplateElementInterface $element)
    {
        $this->id = $id;
        $this->element = $element;
    }

    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    public function getElement(): TemplateElementInterface
    {
        return $this->element;
    }
}
