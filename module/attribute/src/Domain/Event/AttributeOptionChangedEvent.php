<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeOptionChangedEvent implements DomainEventInterface
{
    /**
     * @var OptionKey
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionKey")
     */
    private $key;

    /**
     * @var OptionInterface
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionValue\AbstractOption")
     */
    private $from;

    /**
     * @var OptionInterface
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionValue\AbstractOption")
     */
    private $to;

    /**
     * @param OptionKey       $key
     * @param OptionInterface $from
     * @param OptionInterface $to
     */
    public function __construct(OptionKey $key, OptionInterface $from, OptionInterface $to)
    {
        $this->key = $key;
        $this->from = $from;
        $this->to = $to;
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
