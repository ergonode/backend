<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\Event\User\UserCreatedEvent;
use Ergonode\Account\Domain\ValueObject\Email;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UserCreatedEventTest extends TestCase
{
    /**
     */
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
        /** @var MultimediaId|MockObject $multimediaId */
        $multimediaId = $this->createMock(MultimediaId::class);
        /** @var Password|MockObject $password */
        $password = $this->createMock(Password::class);
        /** @var RoleId|MockObject $roleId */
        $roleId = $this->createMock(RoleId::class);
        $event = new UserCreatedEvent(
            $id,
            $firstName,
            $lastName,
            $email,
            $language,
            $password,
            $roleId,
            true,
            $multimediaId
        );

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($firstName, $event->getFirstName());
        $this->assertEquals($lastName, $event->getLastName());
        $this->assertEquals($language, $event->getLanguage());
        $this->assertEquals($multimediaId, $event->getAvatarId());
        $this->assertEquals($roleId, $event->getRoleId());
        $this->assertEquals($email, $event->getEmail());
        $this->assertEquals($password, $event->getPassword());
        $this->assertTrue($event->isActive());
    }
}
