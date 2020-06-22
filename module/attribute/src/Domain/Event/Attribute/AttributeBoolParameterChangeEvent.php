<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeBoolParameterChangeEvent implements DomainEventInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $from;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $to;

    /**
     * @param AttributeId $id
     * @param string      $name
     * @param bool        $from
     * @param bool        $to
     */
    public function __construct(AttributeId $id, string $name, bool $from, bool $to)
    {
        $this->id = $id;
        $this->name = $name;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function getFrom(): bool
    {
        return $this->from;
    }

    /**
     * @return bool
     */
    public function getTo(): bool
    {
        return $this->to;
    }
}
