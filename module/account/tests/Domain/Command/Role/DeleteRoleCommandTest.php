<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Command\Role;

use Ergonode\Account\Domain\Command\Role\DeleteRoleCommand;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteRoleCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        /** @var RoleId|MockObject $id */
        $id = $this->createMock(RoleId::class);

        $command = new DeleteRoleCommand($id);

        $this->assertEquals($id, $command->getId());
    }
}
