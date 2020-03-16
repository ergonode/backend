<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Factory\Group;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Factory\Group\AttributeGroupFactory;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeGroupFactoryTest extends TestCase
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
        $factory = new AttributeGroupFactory();
        $entity = $factory->create($this->id, $this->code, $this->name);
        $this->assertSame($this->id, $entity->getId());
        $this->assertSame($this->code, $entity->getCode());
        $this->assertSame($this->name, $entity->getName());
    }
}
