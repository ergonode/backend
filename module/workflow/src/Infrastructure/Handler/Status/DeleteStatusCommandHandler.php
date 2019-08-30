<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Status;

use Ergonode\Core\Application\Exception\NotImplementedException;
use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;

/**
 * @todo Implement workflow status delete
 */
class DeleteStatusCommandHandler
{
    /**
     * @param DeleteStatusCommand $command
     *
     * @throws NotImplementedException
     */
    public function __invoke(DeleteStatusCommand $command)
    {
        throw new NotImplementedException();
    }
}
