<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Application\Security\Voter;

use Ergonode\Account\Application\Security\Voter\ErgonodeRoleVoter;
use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Account\Domain\ValueObject\PrivilegeEndPoint;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ErgonodeRoleVoterTest extends TestCase
{
    /**
     * @var RoleRepositoryInterface|MockObject
     */
    private RoleRepositoryInterface $repository;

    /**
     * @var PrivilegeQueryInterface|MockObject
     */
    private PrivilegeQueryInterface $query;

    /**
     * @var TokenInterface| MockObject
     */
    private TokenInterface $token;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RoleRepositoryInterface::class);
        $this->query = $this->createMock(PrivilegeQueryInterface::class);
        $this->token = $this->createMock(TokenInterface::class);
    }


    /**
     * @dataProvider supportsDataProvider
     */
    public function testSupports(string $privilege, bool $expectedResult): void
    {
        $voter = new ErgonodeRoleVoter($this->repository, $this->query);

        $result = $voter->supports($privilege, null);

        $this->assertSame($expectedResult, $result);
    }

    public function testNoUser(): void
    {
        $this->token->expects($this->once())->method('getUser')->willReturn(null);

        $voter = new ErgonodeRoleVoter($this->repository, $this->query);

        $result = $voter->voteOnAttribute(null, null, $this->token);

        $this->assertTrue($result);
    }

    public function testNotExistingRole(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->repository->expects($this->once())->method('load')->willReturn(null);
        $roleId = $this->createMock(RoleId::class);
        $roleId
            ->expects($this->once())
            ->method('getValue')
            ->willReturn('test');

        $user = $this->createMock(User::class);
        $user
            ->expects($this->exactly(2))
            ->method('getRoleId')
            ->willReturn($roleId);

        $this->token->expects($this->once())->method('getUser')->willReturn($user);
        $voter = new ErgonodeRoleVoter($this->repository, $this->query);

        $voter->voteOnAttribute(null, null, $this->token);
    }

    /**
     * @dataProvider privilegeCheckDataProvider
     */
    public function testPrivilegeCheck(string $privilege, bool $expectedResult): void
    {
        $role = $this->createMock(Role::class);
        $role
            ->expects($this->once())
            ->method('getPrivileges')
            ->willReturn(
                [
                    new Privilege('CORRECT_PRIVILEGE'),
                    new Privilege('CORRECT_PRIVILEGE_2'),
                ]
            );

        $this->repository
            ->expects($this->once())
            ->method('load')
            ->willReturn($role);

        if ($expectedResult) {
            $this->query->method('getEndPointPrivilegesByPrivileges')->willReturn(
                [
                    new PrivilegeEndPoint($privilege),
                ]
            );
        }

        $roleId = $this->createMock(RoleId::class);

        $user = $this->createMock(User::class);
        $user
            ->expects($this->once())
            ->method('getRoleId')
            ->willReturn($roleId);

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $voter = new ErgonodeRoleVoter($this->repository, $this->query);

        $result = $voter->voteOnAttribute($privilege, null, $token);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function supportsDataProvider(): array
    {
        return [
            ['IS_AUTHENTICATED_FULLY', false],
            ['IS_AUTHENTICATED_ANNONYMOUSLY', false],
            ['ROLE_ADMIN', false],
            ['ROLE_USER', false],
            ['ERGONODE_ROLE_ABC', true],
            ['ERGONODE_ROLE_CORRECT_PRIVILEGE', true],
        ];
    }

    /**
     * @return array
     */
    public function privilegeCheckDataProvider(): array
    {
        return [
            ['ABC', false],
            ['CORRECT', false],
            ['CORRECT_PRIVILEGE', true],
            ['CORRECT_PRIVILEGE_2', true],
        ];
    }
}
