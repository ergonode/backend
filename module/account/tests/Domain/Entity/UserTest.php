<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Entity;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UserTest extends TestCase
{
    /**
     * @var UserId|MockObject
     */
    private $userId;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var Email|MockObject
     */
    private $email;

    /**
     * @var MultimediaId|MockObject
     */
    private $multimediaId;

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
    private $languagePrivilegesCollection;

    /**
     */
    protected function setUp(): void
    {
        $this->userId = $this->createMock(UserId::class);
        $this->firstName = 'Any first name';
        $this->lastName = 'Any last name';
        $this->email = $this->createMock(Email::class);
        $this->password = $this->createMock(Password::class);
        $this->language = $this->createMock(Language::class);
        $this->multimediaId = $this->createMock(MultimediaId::class);
        $this->roleId = $this->createMock(RoleId::class);
        $this->languagePrivilegesCollection = [];
    }

    /**
     */
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
            $this->multimediaId
        );
        $this->assertEquals($this->userId, $user->getId());
        $this->assertEquals($this->firstName, $user->getFirstName());
        $this->assertEquals($this->lastName, $user->getLastName());
        $this->assertEquals($this->email, $user->getEmail());
        $this->assertEquals($this->language, $user->getLanguage());
        $this->assertEquals($this->multimediaId, $user->getAvatarId());
        $this->assertEquals($this->roleId, $user->getRoleId());
        $this->assertEquals($this->languagePrivilegesCollection, $user->getLanguagePrivilegesCollection());
    }

    /**
     */
    public function testUserEdit(): void
    {
        $firstName = 'New first name';
        $lastName = 'New last name';
        /** @var Language|MockObject $language */
        $language = $this->createMock(Language::class);
        $language->method('isEqual')->willReturn(false);
        /** @var MultimediaId|MockObject $multimediaId */
        $multimediaId = $this->createMock(MultimediaId::class);
        /** @var Password|MockObject $password */
        $password = $this->createMock(Password::class);
        /** @var RoleId|MockObject $roleId */
        $roleId = $this->createMock(RoleId::class);
        $languagePrivileges = $this->createMock(LanguagePrivileges::class);
        $languagePrivileges->method('isReadable')->willReturn(true);
        $languagePrivileges->method('isEditable')->willReturn(false);
        $languagePrivilegesCollection = ['en' => $languagePrivileges];

        $user = new User(
            $this->userId,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->language,
            $this->password,
            $this->roleId,
            $this->languagePrivilegesCollection,
            $this->multimediaId
        );
        $user->changeFirstName($firstName);
        $user->changeLastName($lastName);
        $user->changeLanguage($language);
        $user->changeAvatar($multimediaId);
        $user->changePassword($password);
        $user->changeRole($roleId);
        $user->changeLanguagePrivilegesCollection($languagePrivilegesCollection);
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($language, $user->getLanguage());
        $this->assertEquals($multimediaId, $user->getAvatarId());
        $this->assertEquals($roleId, $user->getRoleId());
        $this->assertEquals($languagePrivilegesCollection, $user->getLanguagePrivilegesCollection());
        $this->assertTrue($user->hasReadLanguagePrivilege(new Language('en')));
        $this->assertFalse($user->hasEditLanguagePrivilege(new Language('en')));
    }
}
