<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Status;

use Doctrine\DBAL\Connection;
use Ergonode\Workflow\Domain\Command\Status\SetStatusOrderCommand;

class SetStatusOrderCommandHandler
{
    protected const TABLE = 'status';

    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(SetStatusOrderCommand $command): void
    {
        foreach ($command->getStatusIds() as $position => $statusId) {
            $this->connection->update(
                self::TABLE,
                [
                    'index' => $position,
                ],
                [
                    'id' => $statusId->getValue(),
                ]
            );
        }
    }
}
