<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Command\User;

use Ergonode\Account\Domain\Command\User\ChangeUserAvatarCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ChangeUserAvatarCommandTest extends TestCase
{
    /**
     */
    public function testCreateCommand(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        /** @var AvatarId|MockObject $avatarId */
        $avatarId = $this->createMock(AvatarId::class);
        $command = new ChangeUserAvatarCommand($id, $avatarId);

        $this->assertEquals($id, $command->getId());
        $this->assertEquals($avatarId, $command->getAvatarId());
    }
}
