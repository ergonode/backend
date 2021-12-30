<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Command\User;

use Ergonode\Account\Domain\Command\User\GenerateUserResetPasswordTokenCommand;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GenerateUserResetPasswordTokenCommandTest extends TestCase
{
    /**
     * @var UserId|MockObject
     */
    private UserId $userId;

    private string $url;

    protected function setUp(): void
    {
        $this->userId = $this->createMock(UserId::class);
        $this->url = 'http://path/';
    }

    public function testCreateCommand(): void
    {
        $command = new GenerateUserResetPasswordTokenCommand(
            $this->userId,
            $this->url
        );

        self::assertEquals($this->userId, $command->getId());
        self::assertEquals($this->url, $command->getUrl());
    }
}
