<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeArrayParameterChangeEvent implements DomainEventInterface
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Type("array")
     */
    private $from;

    /**
     * @var string
     *
     * @JMS\Type("array")
     */
    private $to;

    /**
     * @param string $name
     * @param array  $from
     * @param array  $to
     */
    public function __construct(string $name, array $from, array $to)
    {
        $this->name = $name;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }
}
