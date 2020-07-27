<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use JMS\Serializer\Annotation as JMS;

/**
 */
abstract class AbstractChannel implements ChannelInterface
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
     * @param ChannelId $channelId
     * @param string    $name
     *
     * @throws \Exception
     */
    public function __construct(ChannelId $channelId, string $name)
    {
        $this->id = $channelId;
        $this->name = $name;
    }

    /**
     * @return ChannelId
     */
    public function getId(): ChannelId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param string $name
     *
     * @throws \Exception
     */
    public function setName(string $name): void
    {
        if (!$this->name !== $name) {
            $this->name = $name;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
