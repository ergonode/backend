<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Command\Role;

use Ergonode\Account\Domain\Command\Role\UpdateRoleCommand;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateRoleCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        /** @var RoleId|MockObject $id */
        $id = $this->createMock(RoleId::class);
        $name = 'Any name';
        $description = 'any description';
        /** @var Privilege[] $privileges */
        $privileges = [$this->createMock(Privilege::class)];
        $command = new UpdateRoleCommand(
            $id,
            $name,
            $description,
            $privileges
        );

        $this->assertEquals($id, $command->getId());
        $this->assertEquals($name, $command->getName());
        $this->assertEquals($description, $command->getDescription());
        $this->assertEquals($privileges, $command->getPrivileges());
    }
}
