<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Tests\Domain\Command;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use PHPUnit\Framework\TestCase;

/**
 */
class SendNotificationCommandTest extends TestCase
{
    /**
     * @param string      $message
     * @param array       $parameters
     * @param RoleId      $roleId
     * @param UserId|null $authorId
     *
     * @dataProvider dataProvider
     */
    public function testCommandCreation(string $message, array $parameters, RoleId $roleId, UserId $authorId = null): void
    {
        $command = new SendNotificationCommand($message, $parameters, $roleId, $authorId);
        $this->assertSame($message, $command->getMessage());
        $this->assertSame($parameters, $command->getParameters());
        $this->assertSame($roleId, $command->getRoleId());
        $this->assertSame($authorId, $command->getAuthorId());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'Any Message',
                [],
                $this->createMock(RoleId::class),
                null,
            ],
            [
                'Any Message',
                ['parameter' => 'value'],
                $this->createMock(RoleId::class),
                $this->createMock(UserId::class),
            ],
        ];
    }
}
