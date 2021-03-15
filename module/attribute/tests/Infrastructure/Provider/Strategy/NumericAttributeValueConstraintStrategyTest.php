<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Infrastructure\Provider\Strategy\NumericAttributeValueConstraintStrategy;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Component\Validator\Constraints\Collection;
use PHPUnit\Framework\TestCase;

class NumericAttributeValueConstraintStrategyTest extends TestCase
{
    /**
     * @var NumericAttributeValueConstraintStrategy|MockObject
     */
    private NumericAttributeValueConstraintStrategy $strategy;

    /**
     * @var NumericAttribute|MockObject
     */
    private NumericAttribute $attribute;

    protected function setUp(): void
    {
        $this->strategy = new NumericAttributeValueConstraintStrategy();
        $this->attribute = $this->createMock(NumericAttribute::class);
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
