<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class DeleteChannelCommand implements DomainCommandInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Channel\Domain\Entity\ChannelId")
     */
    private ChannelId $id;

    /**
     * @param ChannelId $id
     *
     * @throws \Exception
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
