<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Command;

use Ergonode\Account\Domain\Command\ChangeUserPasswordCommand;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\ValueObject\Password;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ChangeUserPasswordCommandTest extends TestCase
{
    /**
     */
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
