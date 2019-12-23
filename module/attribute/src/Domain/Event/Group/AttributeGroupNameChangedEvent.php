<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event\Group;

use Ergonode\Attribute\Domain\Entity\AttributeGroupId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeGroupNameChangedEvent implements DomainEventInterface
{
    /**
     * @var AttributeGroupId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeGroupId")
     */
    private $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $from;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $to;

    /**
     * @param AttributeGroupId   $id
     * @param TranslatableString $from
     * @param TranslatableString $to
     */
    public function __construct(AttributeGroupId $id, TranslatableString $from, TranslatableString $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return AttributeGroupId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return TranslatableString
     */
    public function getFrom(): TranslatableString
    {
        return $this->from;
    }

    /**
     * @return TranslatableString
     */
    public function getTo(): TranslatableString
    {
        return $this->to;
    }
}
