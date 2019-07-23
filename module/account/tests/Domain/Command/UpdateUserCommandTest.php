<?php

namespace Ergonode\Account\Tests\Domain\Command;

use Ergonode\Account\Domain\Command\UpdateUserCommand;
use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateUserCommandTest extends TestCase
{
    /**
     */
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

        $command = new UpdateUserCommand(
            $id,
            $firstName,
            $lastName,
            $language,
            $roleId,
            $password
        );

        $this->assertEquals($id, $command->getId());
        $this->assertEquals($firstName, $command->getFirstName());
        $this->assertEquals($lastName, $command->getLastName());
        $this->assertEquals($language, $command->getLanguage());
        $this->assertEquals($roleId, $command->getRoleId());
        $this->assertEquals($password, $command->getPassword());
    }
}
