<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Tests\Domain\Command;

use Ergonode\Channel\Domain\Command\CreateChannelCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateChannelCommandTest extends TestCase
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var ExportProfileId|MockObject
     */
    private ExportProfileId $exportProfileId;

    /**
     */
    protected function setUp(): void
    {
        $this->name = 'Any Channel Name';
        $this->exportProfileId = $this->createMock(ExportProfileId::class);
    }

    /**
     * @throws \Exception
     */
    public function testCreateCommand(): void
    {
        $command = new CreateChannelCommand($this->name, $this->exportProfileId);

        $this->assertEquals($this->name, $command->getName());
        $this->assertEquals($this->exportProfileId, $command->getExportProfileId());
    }
}
