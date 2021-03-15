<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

class AttributeScopeChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $id;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeScope")
     */
    private AttributeScope $to;

    public function __construct(AttributeId $id, AttributeScope $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }

    public function getTo(): AttributeScope
    {
        return $this->to;
    }
}
