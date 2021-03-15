<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\Command;

use Ergonode\Channel\Domain\Command\DeleteChannelCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteChannelCommandTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $id;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
    }

    /**
     * @throws \Exception
     */
    public function testCreateCommand(): void
    {
        $command = new DeleteChannelCommand($this->id);
        self::assertEquals($this->id, $command->getId());
    }
}
