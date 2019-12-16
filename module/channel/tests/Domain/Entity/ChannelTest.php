<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Tests\Domain\Entity;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Segment\Domain\Entity\SegmentId;
use PHPUnit\Framework\TestCase;

/**
 */
class ChannelTest extends TestCase
{
    public function testEntityCreation(): void
    {
        $id = $this->createMock(ChannelId::class);
        $name = 'name';
        $segmentId = $this->createMock(SegmentId::class);

        $entity = new Channel($id, $name, $segmentId);
        $this->assertSame($id, $entity->getId());
        $this->assertSame($name,);
    }
}
