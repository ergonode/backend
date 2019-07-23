<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Factory;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Factory\RoleFactory;
use Ergonode\Account\Domain\ValueObject\Privilege;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class RoleFactoryTest extends TestCase
{
    /**
     */
    public function testCreateObject(): void
    {
        /** @var RoleId|MockObject $id */
        $id = $this->createMock(RoleId::class);
        $name = 'Any name';
        $description = 'any description';
        /** @var Privilege[] $privileges */
        $privileges = [$this->createMock(Privilege::class)];

        $factory = new RoleFactory();
        $role = $factory->create($id, $name, $description, $privileges);

        $this->assertNotNull($role->getId());
        $this->assertEquals($name, $role->getName());
        $this->assertEquals($description, $role->getDescription());
        $this->assertEquals($privileges, $role->getPrivileges());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateObjectWitchIncorrectPrivileges(): void
    {
        /** @var RoleId|MockObject $id */
        $id = $this->createMock(RoleId::class);
        $name = 'Any name';
        $description = 'any description';
        /** @var Privilege[] $privileges */
        $privileges = [$this->createMock(\stdClass::class)];

        $factory = new RoleFactory();
        $factory->create($id, $name, $description, $privileges);
    }
}
