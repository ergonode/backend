<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Entity;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionElementId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionElementTest extends TestCase
{
    /**
     * @var ProductCollectionElementId
     */
    private ProductCollectionElementId $id;

    /**
     * @var ProductId
     */
    private ProductId $productId;

    /**
     * @var AbstractAggregateRoot
     */
    private AbstractAggregateRoot $aggregateRoot;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ProductCollectionElementId::class);
        $this->productId = $this->createMock(ProductId::class);
        $this->aggregateRoot = $this->createMock(AbstractAggregateRoot::class);
        $this->aggregateRoot->method('getId')->willReturn($this->createMock(ProductCollectionId::class));
    }

    /**
     */
    public function testElementCreation(): void
    {
        $entity = new ProductCollectionElement($this->id, $this->productId, true, new \DateTime());
        $this->assertSame($this->id, $entity->getId());
        $this->assertSame($this->productId, $entity->getProductId());
        $this->assertTrue($entity->isVisible());
        $this->assertNotNull($entity->getCreatedAt());
    }

    /**
     */
    public function testElementManipulation(): void
    {
        $this->aggregateRoot->expects($this->once())->method('apply');
        $entity = new ProductCollectionElement($this->id, $this->productId, true, new \DateTime());
        $entity->setAggregateRoot($this->aggregateRoot);
        $entity->changeVisible(false);
    }
}
