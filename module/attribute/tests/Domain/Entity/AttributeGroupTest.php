<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeGroupTest extends TestCase
{
    /**
     * @var AttributeGroupId
     */
    private AttributeGroupId $id;

    /**
     * @var AttributeGroupCode
     */
    private AttributeGroupCode $code;

    /**
     * @var TranslatableString
     */
    private TranslatableString $name;

    /**
     */
    protected function setUp(): void
    {
        $this->id  = $this->createMock(AttributeGroupId::class);
        $this->code = $this->createMock(AttributeGroupCode::class);
        $this->name = $this->createMock(TranslatableString::class);
    }

    /**
     * @throws \Exception
     */
    public function testEntityCreation(): void
    {
        $entity = new AttributeGroup($this->id, $this->code, $this->name);
        self::assertSame($this->id, $entity->getId());
        self::assertSame($this->code, $entity->getCode());
        self::assertSame($this->name, $entity->getName());
    }

    /**
     * @throws \Exception
     */
    public function testEntityNameChanging(): void
    {
        /** @var TranslatableString $name */
        $name = $this->createMock(TranslatableString::class);
        $entity = new AttributeGroup($this->id, $this->code, $this->name);
        $entity->changeName($name);
        self::assertNotSame($this->name, $entity->getName());
        self::assertSame($name, $entity->getName());
    }
}
