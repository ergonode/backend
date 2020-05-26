<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Infrastructure\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductAttributeLanguageResolverTest extends TestCase
{
    /**
     * @var LanguageQueryInterface|MockObject
     */
    private LanguageQueryInterface $query;

    /**
     * @var Language
     */
    private Language $rootLanguage;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(LanguageQueryInterface::class);
        $this->rootLanguage = new Language('en');
        $this->query->method('getLanguageNodeInfo')->willReturn(['lft' => 1, 'rgt' => 10]);
        $this->query->method('getRootLanguage')->willReturn($this->rootLanguage);
    }

    /**
     */
    public function testResolveLocal(): void
    {
        $resolver = new ProductAttributeLanguageResolver($this->query);
        $language = new Language('pl');
        $attribute = $this->createMock(AbstractAttribute::class);
        $attribute->method('getScope')->willReturn(new AttributeScope(AttributeScope::LOCAL));

        $result = $resolver->resolve($attribute, $language);

        $this->assertEquals($language, $result);
    }

    /**
     */
    public function testResolveGlobal(): void
    {
        $resolver = new ProductAttributeLanguageResolver($this->query);
        $language = new Language('pl');
        $attribute = $this->createMock(AbstractAttribute::class);
        $attribute->method('getScope')->willReturn(new AttributeScope(AttributeScope::GLOBAL));

        $result = $resolver->resolve($attribute, $language);

        $this->assertNotEquals($language, $result);
        $this->assertEquals($this->rootLanguage, $result);
    }
}
