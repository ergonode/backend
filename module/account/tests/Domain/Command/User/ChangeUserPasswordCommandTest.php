<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Command\User;

use Ergonode\Account\Domain\Command\User\ChangeUserPasswordCommand;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\ValueObject\Password;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChangeUserPasswordCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        /** @var Password|MockObject $password */
        $password = $this->createMock(Password::class);
        $command = new ChangeUserPasswordCommand($id, $password);

        $this->assertEquals($id, $command->getId());
        $this->assertEquals($password, $command->getPassword());
    }
}
