<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Entity;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var UserId|MockObject
     */
    private $userId;

    private string $firstName;

    private string $lastName;

    /**
     * @var Email|MockObject
     */
    private $email;

    /**
     * @var Language|MockObject
     */
    private $language;

    /**
     * @var Password|MockObject
     */
    private $password;

    /**
     * @var RoleId|MockObject
     */
    private $roleId;

    /**
     * @var array
     */
    private array $languagePrivilegesCollection;

    protected function setUp(): void
    {
        $this->userId = $this->createMock(UserId::class);
        $this->firstName = 'Any first name';
        $this->lastName = 'Any last name';
        $this->email = $this->createMock(Email::class);
        $this->password = $this->createMock(Password::class);
        $this->language = $this->createMock(Language::class);
        $this->roleId = $this->createMock(RoleId::class);
        $this->languagePrivilegesCollection = [];
    }

    public function testUserCreation(): void
    {
        $user = new User(
            $this->userId,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->language,
            $this->password,
            $this->roleId,
            $this->languagePrivilegesCollection,
        );
        self::assertEquals($this->userId, $user->getId());
        self::assertEquals($this->firstName, $user->getFirstName());
        self::assertEquals($this->lastName, $user->getLastName());
        self::assertEquals($this->email, $user->getEmail());
        self::assertEquals($this->language, $user->getLanguage());
        self::assertEquals($this->roleId, $user->getRoleId());
        self::assertEquals($this->languagePrivilegesCollection, $user->getLanguagePrivilegesCollection());
    }

    public function testUserEdit(): void
    {
        $firstName = 'New first name';
        $lastName = 'New last name';
        /** @var Language|MockObject $language */
        $language = $this->createMock(Language::class);
        $language->method('isEqual')->willReturn(false);
        /** @var Password|MockObject $password */
        $password = $this->createMock(Password::class);
        /** @var RoleId|MockObject $roleId */
        $roleId = $this->createMock(RoleId::class);
        $avatarFilename = 'filename.jpg';
        $languagePrivileges = $this->createMock(LanguagePrivileges::class);
        $languagePrivileges->method('isReadable')->willReturn(true);
        $languagePrivileges->method('isEditable')->willReturn(false);
        $languagePrivilegesCollection = ['en_GB' => $languagePrivileges];

        $user = new User(
            $this->userId,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->language,
            $this->password,
            $this->roleId,
            $this->languagePrivilegesCollection,
        );
        $user->changeFirstName($firstName);
        $user->changeLastName($lastName);
        $user->changeLanguage($language);
        $user->changeAvatar($avatarFilename);
        $user->changePassword($password);
        $user->changeRole($roleId);
        $user->changeLanguagePrivilegesCollection($languagePrivilegesCollection);
        self::assertEquals($firstName, $user->getFirstName());
        self::assertEquals($lastName, $user->getLastName());
        self::assertEquals($language, $user->getLanguage());
        self::assertEquals($roleId, $user->getRoleId());
        self::assertEquals($languagePrivilegesCollection, $user->getLanguagePrivilegesCollection());
        self::assertTrue($user->hasReadLanguagePrivilege(new Language('en_GB')));
        self::assertFalse($user->hasEditLanguagePrivilege(new Language('en_GB')));
    }
}
