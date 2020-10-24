<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\Account\Domain\Event\User\UserCreatedEvent;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserCreatedEventTest extends TestCase
{
    public function testCreateEvent(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $firstName = 'New first name';
        $lastName = 'New last name';
        $email = new Email('correct_email@email.com');
        /** @var Language|MockObject $language */
        $language = $this->createMock(Language::class);
        $language->method('isEqual')->willReturn(false);
        $avatarFilename = 'filename.jpg';
        /** @var Password|MockObject $password */
        $password = $this->createMock(Password::class);
        /** @var RoleId|MockObject $roleId */
        $roleId = $this->createMock(RoleId::class);
        $languagePrivilegesCollection = [];

        $event = new UserCreatedEvent(
            $id,
            $firstName,
            $lastName,
            $email,
            $language,
            $password,
            $roleId,
            $languagePrivilegesCollection,
            true,
            $avatarFilename
        );

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($firstName, $event->getFirstName());
        $this->assertEquals($lastName, $event->getLastName());
        $this->assertEquals($language, $event->getLanguage());
        $this->assertEquals($avatarFilename, $event->getAvatarFilename());
        $this->assertEquals($roleId, $event->getRoleId());
        $this->assertEquals($languagePrivilegesCollection, $event->getLanguagePrivilegesCollection());
        $this->assertEquals($email, $event->getEmail());
        $this->assertEquals($password, $event->getPassword());
        $this->assertTrue($event->isActive());
    }
}
