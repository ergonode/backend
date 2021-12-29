<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DeleteChannelCommand implements ChannelCommandInterface
{
    private ChannelId $id;

    /**
     * @throws \Exception
     */
    public function __construct(ChannelId $id)
    {
        $this->id = $id;
    }

    public function getId(): ChannelId
    {
        return $this->id;
    }
}
