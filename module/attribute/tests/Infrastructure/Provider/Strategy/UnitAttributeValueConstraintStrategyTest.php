<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Provider\Strategy;

use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Infrastructure\Provider\Strategy\UnitAttributeValueConstraintStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Component\Validator\Constraints\Collection;

class UnitAttributeValueConstraintStrategyTest extends TestCase
{
    /**
     * @var UnitAttributeValueConstraintStrategy|MockObject
     */
    private UnitAttributeValueConstraintStrategy $strategy;

    /**
     * @var UnitAttribute|MockObject
     */
    private UnitAttribute $attribute;

    protected function setUp(): void
    {
        $this->strategy = new UnitAttributeValueConstraintStrategy();
        $this->attribute = $this->createMock(UnitAttribute::class);
    }

    public function testSupportValidAttribute(): void
    {
        $this->assertTrue($this->strategy->supports($this->attribute));
    }

    public function testNotSupportValidAttribute(): void
    {
        $this->assertFalse($this->strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    public function testReturnConstraint(): void
    {
        $constraint = $this->strategy->get($this->attribute);
        $this->assertInstanceOf(Collection::class, $constraint);
    }
}
