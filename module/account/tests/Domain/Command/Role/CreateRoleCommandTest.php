<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Command\Role;

use Ergonode\Account\Domain\Command\Role\CreateRoleCommand;
use Ergonode\Account\Domain\ValueObject\Privilege;
use PHPUnit\Framework\TestCase;

class CreateRoleCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        $name = 'Any name';
        $description = 'any description';
        /** @var Privilege[] $privileges */
        $privileges = [$this->createMock(Privilege::class)];
        $command = new CreateRoleCommand(
            $name,
            $description,
            $privileges
        );

        $this->assertNotNull($command->getId());
        $this->assertEquals($name, $command->getName());
        $this->assertEquals($description, $command->getDescription());
        $this->assertEquals($privileges, $command->getPrivileges());
    }
}
