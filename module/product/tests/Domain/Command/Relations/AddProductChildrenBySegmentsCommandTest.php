<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Relations;

use Ergonode\Product\Domain\Command\Relations\AddProductChildrenBySegmentsCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class AddProductChildrenBySegmentsCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        $productId = $this->createMock(ProductId::class);
        $segmentId = $this->createMock(SegmentId::class);

        $command = new AddProductChildrenBySegmentsCommand($productId, [$segmentId]);
        self::assertSame($productId, $command->getId());
        self::assertSame([$segmentId], $command->getSegments());
    }
}
