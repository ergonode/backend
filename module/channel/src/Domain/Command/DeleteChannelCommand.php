<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\Channel\Domain\Entity\ChannelId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteChannelCommand
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Channel\Domain\Entity\ChannelId")
     */
    private $id;

    /**
     * @param ChannelId $id
     */
    public function __construct(ChannelId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ChannelId
     */
    public function getId(): ChannelId
    {
        return $this->id;
    }
}
