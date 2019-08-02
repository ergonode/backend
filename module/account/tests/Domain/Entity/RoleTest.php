<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Entity;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class RoleTest extends TestCase
{
    /**
     * @var RoleId|MockObject
     */
    private $roleId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Privilege|MockObject
     */
    private $privilege;

    /**
     */
    protected function setUp()
    {
        $this->roleId = $this->createMock(RoleId::class);
        $this->name = 'Any Name';
        $this->description = 'Any Description';
        $this->privilege = $this->createMock(Privilege::class);
    }

    /**
     */
    public function testRoleCreation(): void
    {
        $role = new Role($this->roleId, $this->name, $this->description, [$this->privilege]);
        $this->assertEquals($this->roleId, $role->getId());
        $this->assertEquals($this->name, $role->getName());
        $this->assertEquals($this->description, $role->getDescription());
        $this->assertEquals([$this->privilege], $role->getPrivileges());
    }

    /**
     */
    public function testChangingNameAndDescription(): void
    {
        $newName = 'New name';
        $newDescription = 'New description';

        $role = new Role($this->roleId, $this->name, $this->description);
        $role->changeName($newName);
        $role->changeDescription($newDescription);
        $this->assertEquals($newName, $role->getName());
        $this->assertEquals($newDescription, $role->getDescription());
    }

    /**
     */
    public function testChangingPrivilege(): void
    {
        $this->privilege->method('isEqual')->willReturn(true);

        $role = new Role($this->roleId, $this->name, $this->description, [$this->privilege]);
        $this->assertTrue($role->hasPrivilege($this->privilege));
        $role->removePrivilege($this->privilege);
        $this->assertFalse($role->hasPrivilege($this->privilege));
        $role->addPrivilege($this->privilege);
        $this->assertTrue($role->hasPrivilege($this->privilege));
        $role->removePrivilege($this->privilege);
        $role->changesPrivileges([$this->privilege]);
        $this->assertEquals([$this->privilege], $role->getPrivileges());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRemoveNotExistsPrivilege(): void
    {
        $role = new Role($this->roleId, $this->name, $this->description);
        $role->removePrivilege($this->privilege);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddExistsPrivilege(): void
    {
        $this->privilege->method('isEqual')->willReturn(true);
        $role = new Role($this->roleId, $this->name, $this->description, [$this->privilege]);
        $role->addPrivilege($this->privilege);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRoleCreationWithIncorrectType(): void
    {
        $privileges = $this->createMock(\stdClass::class);

        new Role($this->roleId, $this->name, $this->description, [$privileges]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testChangePrivilegesWithIncorrectType(): void
    {
        $privileges = $this->createMock(\stdClass::class);

        $role = new Role($this->roleId, $this->name, $this->description);
        $role->changesPrivileges([$privileges]);
    }

    /**
     */
    public function testDelete(): void
    {
        $role = new Role($this->roleId, $this->name, $this->description);
        $role->remove();
        $this->assertTrue($role->isDeleted());
    }
}
