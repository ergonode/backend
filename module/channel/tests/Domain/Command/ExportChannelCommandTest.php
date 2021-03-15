<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Channel\Domain\Command\ExportChannelCommand;

class ExportChannelCommandTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $channelId;

    /**
     * @var ExportId|MockObject
     */
    private ExportId $exportId;

    protected function setUp(): void
    {
        $this->channelId = $this->createMock(ChannelId::class);
        $this->exportId = $this->createMock(ExportId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new ExportChannelCommand(
            $this->exportId,
            $this->channelId,
        );

        self::assertEquals($this->channelId, $command->getChannelId());
        self::assertEquals($this->exportId, $command->getExportId());
    }
}
