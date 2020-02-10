<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Tests\Domain\Entity;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
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
        /** @var TranslatableString $name */
        $name = $this->createMock(TranslatableString::class);
        /** @var SegmentId $segmentId */
        $segmentId = $this->createMock(SegmentId::class);

        $entity = new Channel($id, $name, $segmentId);
        $this->assertSame($id, $entity->getId());
        $this->assertSame($name, $entity->getName());
        $this->assertSame($segmentId, $entity->getSegmentId());
    }
}
