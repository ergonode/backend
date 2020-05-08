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
use PHPUnit\Framework\TestCase;

/**
 */
class ChannelTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testEntityCreation(): void
    {
        /** @var ChannelId $id */
        $id = $this->createMock(ChannelId::class);
        $name = 'TEST CHANNEL NAME';
        /** @var ExportProfileId $exportProfileId */
        $exportProfileId = $this->createMock(ExportProfileId::class);

        $entity = new Channel($id, $name, $exportProfileId);
        $this->assertSame($id, $entity->getId());
        $this->assertSame($name, $entity->getName());
        $this->assertSame($exportProfileId, $entity->getExportProfileId());
    }
}
