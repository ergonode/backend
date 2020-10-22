<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

class AttributeScopeChangedEvent implements DomainEventInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $id;

    /**
     * @var AttributeScope $from
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeScope")
     */
    private AttributeScope $from;

    /**
     * @var AttributeScope $to
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeScope")
     */
    private AttributeScope $to;

    /**
     * @param AttributeId    $id
     * @param AttributeScope $from
     * @param AttributeScope $to
     */
    public function __construct(AttributeId $id, AttributeScope $from, AttributeScope $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return AttributeId
     */
    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return AttributeScope
     */
    public function getFrom(): AttributeScope
    {
        return $this->from;
    }

    /**
     * @return AttributeScope
     */
    public function getTo(): AttributeScope
    {
        return $this->to;
    }
}
