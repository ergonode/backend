<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Event\Group;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use JMS\Serializer\Annotation as JMS;

class TemplateGroupCreatedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId")
     */
    private TemplateGroupId $id;
    /**
     * @JMS\Type("string")
     */
    private string $name;

    public function __construct(TemplateGroupId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getAggregateId(): TemplateGroupId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
