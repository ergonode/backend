<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ChannelCreatedEvent implements DomainEventInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    private ChannelId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $class;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $type;

    /**
     * @param ChannelId $channelId
     * @param string    $name
     * @param string    $class
     * @param string    $type
     */
    public function __construct(
        ChannelId $channelId,
        string $name,
        string $class,
        string $type
    ) {
        $this->id = $channelId;
        $this->name = $name;
        $this->class = $class;
        $this->type = $type;
    }

    /**
     * @return ChannelId
     */
    public function getAggregateId(): ChannelId
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
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
