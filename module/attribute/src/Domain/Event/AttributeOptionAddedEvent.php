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
class AttributeOptionAddedEvent implements DomainEventInterface
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
    private OptionInterface $option;

    /**
     * @param AttributeId     $id
     * @param OptionKey       $key
     * @param OptionInterface $option
     */
    public function __construct(AttributeId $id, OptionKey $key, OptionInterface $option)
    {
        $this->id = $id;
        $this->key = $key;
        $this->option = $option;
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
    public function getOption(): OptionInterface
    {
        return $this->option;
    }
}
