<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Tests\Domain\Command;

use Ergonode\Channel\Domain\Command\UpdateChannelCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateChannelCommandTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $id;

    /**
     * @var string
     */
    private string $name;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
        $this->name = 'Any Channel Name';
    }

    /**
     */
    public function testCreateCommand(): void
    {
        $command = new UpdateChannelCommand(
            $this->id,
            $this->name
        );

        $this->assertEquals($this->id, $command->getId());
        $this->assertEquals($this->name, $command->getName());
    }
}
