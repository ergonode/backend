<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Attribute\TemplateSystemAttribute;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Infrastructure\Grid\Column\Provider\Strategy\TemplateSystemAttributeColumnStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateSystemAttributeColumnStrategyTest extends TestCase
{
    /**
     * @var TemplateQueryInterface|MockObject
     */
    private MockObject $query;

    /**
     * @var AbstractAttribute|MockObject
     */
    private MockObject $attribute;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(TemplateQueryInterface::class);
        $this->attribute = $this->createMock(TemplateSystemAttribute::class);
    }

    /**
     */
    public function testValidSupport(): void
    {
        $strategy = new TemplateSystemAttributeColumnStrategy($this->query);

        $this->assertTrue($strategy->supports($this->attribute));
    }

    /**
     */
    public function testInvalidSupport(): void
    {
        $strategy = new TemplateSystemAttributeColumnStrategy($this->query);

        $this->assertFalse($strategy->supports($this->createMock(AbstractAttribute::class)));
    }

    /**
     */
    public function testCreation(): void
    {
        $this->attribute->expects($this->once())->method('getCode');
        $this->attribute->expects($this->once())->method('getLabel');
        $this->query->expects($this->once())->method('getDictionary');
        $language = $this->createMock(Language::class);
        $strategy = new TemplateSystemAttributeColumnStrategy($this->query);
        $strategy->create($this->attribute, $language);
    }
}
