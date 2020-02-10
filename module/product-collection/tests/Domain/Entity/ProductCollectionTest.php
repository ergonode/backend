<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionTest extends TestCase
{
    /**
     * @var ProductCollectionId
     */
    private ProductCollectionId $id;

    /**
     * @var ProductCollectionCode
     *
     */
    private ProductCollectionCode $code;

    /**
     * @var TranslatableString
     */
    private TranslatableString $name;

    /**
     * @var TranslatableString
     */
    private TranslatableString $description;

    /**
     * @var ProductCollectionTypeId
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @var ProductId
     */
    private ProductId $productId;


    /**
     */
    public function setUp(): void
    {
        $this->id = $this->createMock(ProductCollectionId::class);
        $this->code = $this->createMock(ProductCollectionCode::class);
        $this->name = $this->createMock(TranslatableString::class);
        $this->description = $this->createMock(TranslatableString::class);
        $this->typeId = $this->createMock(ProductCollectionTypeId::class);
        $this->productId = ProductId::fromString('test');
    }

    /**
     */
    public function testCreateEntity(): void
    {
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        $this->assertEquals($this->id, $entity->getId());
        $this->assertEquals($this->name, $entity->getName());
        $this->assertEquals($this->description, $entity->getDescription());
        $this->assertEquals($this->code, $entity->getCode());
        $this->assertEquals($this->typeId, $entity->getTypeId());
        $this->assertNotNull($entity->getCreatedAt());
    }

    /**
     */
    public function testElementManipulation(): void
    {
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        $entity->addElement($this->productId, true);
        $this->assertTrue($entity->hasElement($this->productId));
        $this->assertSame($this->productId, $entity->getElement($this->productId)->getProductId());
        $this->assertSame($this->productId, $entity->getElements()[array_key_first($entity->getElements())]
        ->getProductId());
        $newName = new TranslatableString(['en' => 'english']);
        $entity->changeName($newName);
        $this->assertEquals($newName, $entity->getName());
        $newDescription = new TranslatableString(['en' => 'english']);
        $entity->changeDescription($newDescription);
        $this->assertEquals($newDescription, $entity->getDescription());
        $newTypeId = ProductCollectionTypeId::generate();
        $entity->changeType($newTypeId);
        $this->assertEquals($newTypeId, $entity->getTypeId());
        $this->assertNotNull($entity->getEditedAt());
    }

    /**
     * @expectedException  \RuntimeException
     */
    public function testRemovingElement(): void
    {
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        $entity->addElement($this->productId, true);
        $entity->removeElement($this->productId);
        $entity->getElement($this->productId);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddingSameElement(): void
    {
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        $entity->addElement($this->productId, true);
        $entity->addElement($this->productId, true);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNotExistingElement(): void
    {
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        $entity->getElement($this->productId);
    }
}
