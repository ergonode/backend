<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
            true
        );

        self::assertEquals($id, $event->getAggregateId());
        self::assertEquals($firstName, $event->getFirstName());
        self::assertEquals($lastName, $event->getLastName());
        self::assertEquals($language, $event->getLanguage());
        self::assertEquals($roleId, $event->getRoleId());
        self::assertEquals($languagePrivilegesCollection, $event->getLanguagePrivilegesCollection());
        self::assertEquals($email, $event->getEmail());
        self::assertEquals($password, $event->getPassword());
        self::assertTrue($event->isActive());
    }
}
