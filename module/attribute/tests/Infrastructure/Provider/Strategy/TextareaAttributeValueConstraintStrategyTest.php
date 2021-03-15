<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Infrastructure\Provider\Strategy\TextareaAttributeValueConstraintStrategy;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Component\Validator\Constraints\Collection;

class TextareaAttributeValueConstraintStrategyTest extends TestCase
{
    /**
     * @var TextareaAttributeValueConstraintStrategy|MockObject
     */
    private TextareaAttributeValueConstraintStrategy $strategy;

    /**
     * @var TextareaAttribute|MockObject
     */
    private TextareaAttribute $attribute;

    protected function setUp(): void
    {
        $this->strategy = new TextareaAttributeValueConstraintStrategy();
        $this->attribute = $this->createMock(TextareaAttribute::class);
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
