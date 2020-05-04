<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\LanguageNode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class LanguageNodeTest extends TestCase
{
    /**
     * @var Language|MockObject
     */
    private $language;

    /**
     */
    protected function setUp(): void
    {
        $this->language = $this->createMock(Language::class);
    }

    /**
     */
    public function testCreateNode(): void
    {
        $node = new LanguageNode($this->language);
        $this->assertEquals($this->language, $node->getLanguage());
    }

    /**
     */
    public function testSettingParent(): void
    {
        /** @var LanguageNode|MockObject $parent */
        $parent = $this->createMock(LanguageNode::class);
        $node = new LanguageNode($this->language);
        $node->setParent($parent);
        $this->assertEquals($parent, $node->getParent());
    }

    /**
     */
    public function testAddChildren(): void
    {
        $language = new Language('en');

        /** @var LanguageNode|MockObject $children */
        $children = new LanguageNode($language);
        $node = new LanguageNode($this->language);
        $this->assertFalse($node->hasChild($language));
        $node->addChild($children);
        $this->assertEquals($children, $node->getChildren()[0]);
        $this->assertTrue($node->hasChild($language));
        $this->assertTrue($node->hasChildren());
    }

    /**
     */
    public function testHasSuccessor(): void
    {
        $languagePl = new Language('pl');
        $languageEn = new Language('en');
        $languageEnGb = new Language('en_GB');
        $languageEnUs = new Language('en_US');

        $child1 = new LanguageNode($languageEn);
        $child2 = new LanguageNode($languageEnGb);
        $child3 = new LanguageNode($languageEnUs);

        $child1->addChild($child2);
        $child1->addChild($child3);

        $node = new LanguageNode($languagePl);
        $node ->addChild($child1);

        $this->assertTrue($node->hasSuccessor($languageEn));
        $this->assertTrue($node->hasSuccessor($languageEnGb));
        $this->assertTrue($node->hasSuccessor($languageEnUs));
    }
}
