<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Application\Security\Voter;

use Ergonode\Account\Application\Security\Voter\LanguageVoter;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LanguageVoterTest extends TestCase
{
    /**
     * @param mixed $subject
     *
     * @dataProvider supportsDataProvider
     */
    public function testSupports(string $attribute, $subject, bool $expectedResult): void
    {
        $voter = new LanguageVoter();
        $result = $voter->supports($attribute, $subject);
        $this->assertSame($expectedResult, $result);
    }

    public function testVoteOnAttributeNoUser(): void
    {
        $attribute = 'attribute';
        $subject = 'subject';
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(null);

        $voter = new LanguageVoter();
        $result = $voter->voteOnAttribute($attribute, $subject, $token);
        $this->assertFalse($result);
    }

    /**
     * @dataProvider voteOnAttributeDataProvider
     */
    public function testVoteOnAttribute(string $attribute, bool $read, bool $edit, bool $expectedResult): void
    {
        $user = $this->createMock(User::class);
        $user->method('hasReadLanguagePrivilege')->willReturn($read);
        $user->method('hasEditLanguagePrivilege')->willReturn($edit);

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        $subject = $this->createMock(Language::class);

        $voter = new LanguageVoter();
        $result = $voter->voteOnAttribute($attribute, $subject, $token);
        $this->assertSame($expectedResult, $result);
    }

    public function testVoteOnAttributeNotSupportedAttribute(): void
    {
        $this->expectException(\LogicException::class);
        $attribute = 'NOT SUPPORTED';
        $user = $this->createMock(User::class);

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        $subject = $this->createMock(Language::class);

        $voter = new LanguageVoter();
        $voter->voteOnAttribute($attribute, $subject, $token);
    }

    /**
     * @return array
     */
    public function supportsDataProvider(): array
    {
        return [
            ['read', $this->createMock(Language::class), true],
            ['edit', $this->createMock(Language::class), true],
            ['OTHER NOT SUPPORTED', $this->createMock(Language::class), false],
            ['read', new \stdClass(), false],
            ['edit', new \stdClass(), false],
        ];
    }

    /**
     * @return array
     */
    public function voteOnAttributeDataProvider(): array
    {
        return [
            ['read', true, true, true],
            ['read', true, false, true],
            ['read', false, true, false],
            ['read', false, false, false],
            ['edit', true, true, true],
            ['edit', false, true, true],
            ['edit', true, false, false],
            ['edit', false, false, false],
        ];
    }
}
