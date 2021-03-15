<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Command\User;

use Ergonode\Account\Domain\Command\User\UpdateUserCommand;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateUserCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $firstName = 'New first name';
        $lastName = 'New last name';
        /** @var Language|MockObject $language */
        $language = $this->createMock(Language::class);
        $language->method('isEqual')->willReturn(false);
        /** @var Password|MockObject $password */
        $password = $this->createMock(Password::class);
        /** @var RoleId|MockObject $roleId */
        $roleId = $this->createMock(RoleId::class);
        $languagePrivilegesCollection = [];
        $isActive = true;

        $command = new UpdateUserCommand(
            $id,
            $firstName,
            $lastName,
            $language,
            $roleId,
            $languagePrivilegesCollection,
            $isActive,
            $password
        );

        $this->assertEquals($id, $command->getId());
        $this->assertEquals($firstName, $command->getFirstName());
        $this->assertEquals($lastName, $command->getLastName());
        $this->assertEquals($language, $command->getLanguage());
        $this->assertEquals($roleId, $command->getRoleId());
        $this->assertEquals($languagePrivilegesCollection, $command->getLanguagePrivilegesCollection());
        $this->assertEquals($password, $command->getPassword());
        $this->assertTrue($command->isActive());
    }
}
