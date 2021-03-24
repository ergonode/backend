<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

abstract class AbstractChannel implements ChannelInterface
{
    private ChannelId $id;

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
