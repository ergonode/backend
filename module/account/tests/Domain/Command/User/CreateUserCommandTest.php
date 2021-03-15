<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Command\User;

use Ergonode\Account\Domain\Command\User\CreateUserCommand;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        $firstName = 'New first name';
        $lastName = 'New last name';
        $email = new Email('correct_email@email.com');
        /** @var Language|MockObject $language */
        $language = $this->createMock(Language::class);
        $language->method('isEqual')->willReturn(false);
        /** @var Password|MockObject $password */
        $password = $this->createMock(Password::class);
        /** @var RoleId|MockObject $roleId */
        $roleId = $this->createMock(RoleId::class);
        $command = new CreateUserCommand(
            $firstName,
            $lastName,
            $email,
            $language,
            $password,
            $roleId,
            true,
        );

        $this->assertNotNull($command->getId());
        $this->assertEquals($firstName, $command->getFirstName());
        $this->assertEquals($lastName, $command->getLastName());
        $this->assertEquals($language, $command->getLanguage());
        $this->assertEquals($roleId, $command->getRoleId());
        $this->assertEquals($email, $command->getEmail());
        $this->assertEquals($password, $command->getPassword());
        $this->assertTrue($command->isActive());
    }
}
