<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Command\Relations;

use Ergonode\Product\Domain\Command\Relations\AddProductChildrenBySegmentsCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class AddProductChildrenBySegmentsCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var AbstractAssociatedProduct|MockObject $product */
        $product = $this->createMock(AbstractAssociatedProduct::class);
        $product->method('getId')->willReturn($this->createMock(ProductId::class));
        /** @var SegmentId|MockObject $segmentId */
        $segmentId = $this->createMock(SegmentId::class);

        $command = new AddProductChildrenBySegmentsCommand($product, [$segmentId]);
        $this->assertSame($product->getId(), $command->getId());
        $this->assertSame([$segmentId], $command->getSegments());
    }
}
