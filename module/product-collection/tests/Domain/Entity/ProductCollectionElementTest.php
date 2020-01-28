<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Entity;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElementId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
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
    private $aggregateRoot;

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
        $entity = new ProductCollectionElement($this->id, $this->productId, true);
        $this->assertSame($this->id, $entity->getId());
        $this->assertSame($this->productId, $entity->getProductId());
        $this->assertTrue($entity->isVisible());
    }

    /**
     */
    public function testElementManipulation(): void
    {
        $this->aggregateRoot->expects($this->once())->method('apply');
        $entity = new ProductCollectionElement($this->id, $this->productId, true);
        $entity->setAggregateRoot($this->aggregateRoot);
        $entity->changeVisible(false);
    }
}
