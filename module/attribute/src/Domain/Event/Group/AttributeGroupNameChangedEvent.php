<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Group;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

class AttributeGroupNameChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId")
     */
    private AttributeGroupId $id;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $to;

    public function __construct(AttributeGroupId $id, TranslatableString $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): AttributeGroupId
    {
        return $this->id;
    }

    public function getTo(): TranslatableString
    {
        return $this->to;
    }
}
