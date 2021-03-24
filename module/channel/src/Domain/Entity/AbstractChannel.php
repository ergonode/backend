<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use JMS\Serializer\Annotation as JMS;

abstract class AbstractChannel implements ChannelInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    private ChannelId $id;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @throws \Exception
     */
    public function __construct(ChannelId $channelId, string $name)
    {
        $this->id = $channelId;
        $this->name = $name;
    }

    public function getId(): ChannelId
    {
        return $this->id;
    }

    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("type")
     */
    abstract public static function getType(): string;

    /**
     * @throws \Exception
     */
    public function setName(string $name): void
    {
        if (!$this->name !== $name) {
            $this->name = $name;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }
}
