<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Infrastructure\Provider\Strategy\OptionAttributeValueConstraintStrategy;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Component\Validator\Constraints\Collection;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;

class OptionAttributeValueConstraintStrategyTest extends TestCase
{
    /**
     * @var OptionAttributeValueConstraintStrategy|MockObject
     */
    private $strategy;

    /**
     * @var PriceAttribute|MockObject
     */
    private $attribute;

    protected function setUp(): void
    {
        $query = $this->createMock(OptionQueryInterface::class);
        $query->method('getOptions')->willReturn([]);
        $this->strategy = new OptionAttributeValueConstraintStrategy($query);
        $this->attribute = $this->createMock(AbstractOptionAttribute::class);
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
