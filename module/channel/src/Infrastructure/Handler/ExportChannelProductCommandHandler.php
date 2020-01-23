<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\ExportProductChannelCommand;
use Ergonode\Core\Application\Exception\NotImplementedException;

/**
 */
class ExportChannelProductCommandHandler
{
    /**
     * @param ExportProductChannelCommand $command
     *
     * @throws NotImplementedException
     */
    public function __invoke(ExportProductChannelCommand $command)
    {
        throw new NotImplementedException();
    }
}
