<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeOptionChangedEvent implements DomainEventInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $id;

    /**
     * @var OptionKey
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionKey")
     */
    private OptionKey $key;

    /**
     * @var OptionInterface
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionInterface")
     */
    private OptionInterface $from;

    /**
     * @var OptionInterface
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionInterface")
     */
    private OptionInterface $to;

    /**
     * @param AttributeId     $id
     * @param OptionKey       $key
     * @param OptionInterface $from
     * @param OptionInterface $to
     */
    public function __construct(AttributeId $id, OptionKey $key, OptionInterface $from, OptionInterface $to)
    {
        $this->id = $id;
        $this->key = $key;
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
     * @return OptionKey
     */
    public function getKey(): OptionKey
    {
        return $this->key;
    }

    /**
     * @return OptionInterface
     */
    public function getFrom(): OptionInterface
    {
        return $this->from;
    }

    /**
     * @return OptionInterface
     */
    public function getTo(): OptionInterface
    {
        return $this->to;
    }
}
