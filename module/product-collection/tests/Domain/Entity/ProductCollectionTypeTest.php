<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionTypeTest extends TestCase
{
    /**
     * @var ProductCollectionTypeId
     */
    private ProductCollectionTypeId $id;

    /**
     * @var ProductCollectionTypeCode
     *
     */
    private ProductCollectionTypeCode $code;

    /**
     * @var TranslatableString
     */
    private TranslatableString $name;

    /**
     */
    public function setUp(): void
    {
        $this->id = $this->createMock(ProductCollectionTypeId::class);
        $this->code = $this->createMock(ProductCollectionTypeCode::class);
        $this->name = $this->createMock(TranslatableString::class);
    }

    /**
     */
    public function testCreationEntity(): void
    {
        $entity = new ProductCollectionType($this->id, $this->code, $this->name);
        $this->assertEquals($this->id, $entity->getId());
        $this->assertEquals($this->code, $entity->getCode());
        $this->assertEquals($this->name, $entity->getName());
    }

    /**
     */
    public function testTypeManipulation(): void
    {
        $entity = new ProductCollectionType($this->id, $this->code, $this->name);
        $newName = new TranslatableString(['en' => 'english']);
        $entity->changeName($newName);
        $this->assertEquals($newName, $entity->getName());
    }
}
