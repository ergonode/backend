<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\TestCase;

class ProductCollectionTest extends TestCase
{
    private ProductCollectionId $id;

    private ProductCollectionCode $code;

    private TranslatableString $name;

    private TranslatableString $description;

    private ProductCollectionTypeId $typeId;

    private ProductId $productId;

    public function setUp(): void
    {
        $this->id = $this->createMock(ProductCollectionId::class);
        $this->code = $this->createMock(ProductCollectionCode::class);
        $this->name = $this->createMock(TranslatableString::class);
        $this->description = $this->createMock(TranslatableString::class);
        $this->typeId = $this->createMock(ProductCollectionTypeId::class);
        $this->productId = ProductId::generate();
    }

    public function testCreateEntity(): void
    {
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        self::assertEquals($this->id, $entity->getId());
        self::assertEquals($this->name, $entity->getName());
        self::assertEquals($this->description, $entity->getDescription());
        self::assertEquals($this->code, $entity->getCode());
        self::assertEquals($this->typeId, $entity->getTypeId());
        self::assertNotNull($entity->getCreatedAt());
    }

    public function testElementManipulation(): void
    {
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        $entity->addElement($this->productId, true);
        self::assertTrue($entity->hasElement($this->productId));
        self::assertSame($this->productId, $entity->getElement($this->productId)->getProductId());
        self::assertSame(
            $this->productId,
            $entity->getElements()[array_key_first($entity->getElements())]->getProductId()
        );
        $newName = new TranslatableString(['en_GB' => 'english']);
        $entity->changeName($newName);
        self::assertEquals($newName, $entity->getName());
        $newDescription = new TranslatableString(['en_GB' => 'english']);
        $entity->changeDescription($newDescription);
        self::assertEquals($newDescription, $entity->getDescription());
        $newTypeId = ProductCollectionTypeId::generate();
        $entity->changeType($newTypeId);
        self::assertEquals($newTypeId, $entity->getTypeId());
        self::assertNotNull($entity->getEditedAt());
    }

    public function testRemovingElement(): void
    {
        $this->expectException(\RuntimeException::class);
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        $entity->addElement($this->productId, true);
        $entity->removeElement($this->productId);
        $entity->getElement($this->productId);
    }

    public function testAddingSameElement(): void
    {
        $this->expectException(\RuntimeException::class);
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        $entity->addElement($this->productId, true);
        $entity->addElement($this->productId, true);
    }

    public function testNotExistingElement(): void
    {
        $this->expectException(\RuntimeException::class);
        $entity = new ProductCollection($this->id, $this->code, $this->name, $this->description, $this->typeId);
        $entity->getElement($this->productId);
    }
}
