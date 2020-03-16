<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Grid\Column\Builder;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Category\Domain\Entity\Attribute\CategorySystemAttribute;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Category\Infrastructure\Grid\Column\Builder\CategorySystemAttributeColumnBuilderStrategy;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CategorySystemAttributeColumnBuilderStrategyTest extends TestCase
{
    /**
     * @var CategoryQueryInterface|MockObject
     */
    private $query;

    /**
     * @var AbstractAttribute|MockObject
     */
    private $attribute;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(CategoryQueryInterface::class);
        $this->attribute = $this->createMock(CategorySystemAttribute::class);
    }

    /**
     */
    public function testValidSupport(): void
    {
        $strategy = new CategorySystemAttributeColumnBuilderStrategy($this->query);

        $this->assertTrue($strategy->supports($this->attribute));
    }

    /**
     */
    public function testInvalidSupport(): void
    {
        $strategy = new CategorySystemAttributeColumnBuilderStrategy($this->query);

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
        $strategy = new CategorySystemAttributeColumnBuilderStrategy($this->query);
        $strategy->create($this->attribute, $language);
    }
}
