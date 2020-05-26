<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Tests\Domain\Entity;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ChannelTest extends TestCase
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
     * @var ExportProfileId|MockObject
     */
    private ExportProfileId $exportProfileId;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
        $this->name = 'Any Channel Name';
        $this->exportProfileId = $this->createMock(ExportProfileId::class);
    }

    /**
     * @throws \Exception
     */
    public function testCreateEntity(): void
    {
        $entity = new Channel(
            $this->id,
            $this->name,
            $this->exportProfileId
        );

        $this->assertSame($this->id, $entity->getId());
        $this->assertSame($this->name, $entity->getName());
        $this->assertSame($this->exportProfileId, $entity->getExportProfileId());
    }

    /**
     * @throws \Exception
     */
    public function testChangeName():void
    {
        $entity = new Channel(
            $this->id,
            $this->name,
            $this->exportProfileId
        );

        $name = 'New Channel Name';
        $entity->changeName($name);

        $this->assertSame($this->id, $entity->getId());
        $this->assertSame($name, $entity->getName());
        $this->assertSame($this->exportProfileId, $entity->getExportProfileId());
    }
}
