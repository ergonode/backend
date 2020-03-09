<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Collection;
use Ergonode\Attribute\Infrastructure\Provider\Strategy\PriceAttributeValueConstraintStrategy;

/**
 */
class PriceAttributeValueConstraintStrategyTest extends TestCase
{
    /**
     * @var PriceAttributeValueConstraintStrategy|MockObject
     */
    private $strategy;

    /**
     * @var PriceAttribute|MockObject
     */
    private $attribute;

    /**
     */
    protected function setUp(): void
    {
        $this->strategy = new PriceAttributeValueConstraintStrategy();
        $this->attribute = $this->createMock(PriceAttribute::class);
    }

    /**
     */
    public function testSupportValidAttribute(): void
    {
        $this->assertTrue($this->strategy->supports($this->attribute));
    }

    /**
     */
    public function testNotSupportValidAttribute(): void
    {
        $this->assertFalse($this->strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    /**
     */
    public function testReturnConstraint(): void
    {
        $constraint = $this->strategy->get($this->attribute);
        $this->assertInstanceOf(Collection::class, $constraint);
    }
}
