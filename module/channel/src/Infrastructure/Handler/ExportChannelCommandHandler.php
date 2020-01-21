<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\ExportProductCommand;
use Ergonode\Core\Application\Exception\NotImplementedException;

/**
 */
class ExportChannelCommandHandler
{
    /**
     * @param ExportProductCommand $command
     *
     * @throws NotImplementedException
     */
    public function __invoke(ExportProductCommand $command)
    {
        throw new NotImplementedException();
    }
}
