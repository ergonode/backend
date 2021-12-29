<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Command\User;

use Ergonode\Account\Domain\Command\User\ApplyUserResetTokenCommand;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Account\Domain\ValueObject\ResetToken;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ApplyUserResetTokenCommandTest extends TestCase
{
    /**
     * @var ResetToken|MockObject
     */
    private ResetToken $token;

    /**
     * @var Password|MockObject
     */
    private Password $password;

    protected function setUp(): void
    {
        $this->token = $this->createMock(ResetToken::class);
        $this->password = $this->createMock(Password::class);
        parent::setUp();
    }

    public function testCreateCommand(): void
    {
        $command = new ApplyUserResetTokenCommand(
            $this->token,
            $this->password
        );

        self::assertEquals($this->token, $command->getToken());
        self::assertEquals($this->password, $command->getPassword());
    }
}
