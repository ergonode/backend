<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\LanguageNode;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class LanguageNodeTest extends TestCase
{
    public const NAMESPACE = '53db6054-f4c2-4753-a786-297009377be2';
    /**
     * @var LanguageId|MockObject
     */
    private $languageId;

    /**
     */
    protected function setUp(): void
    {
        $this->languageId = $this->createMock(LanguageId::class);
    }

    /**
     */
    public function testCreateNode(): void
    {
        $node = new LanguageNode($this->languageId);
        $this->assertEquals($this->languageId, $node->getLanguageId());
    }

    /**
     */
    public function testSettingParent(): void
    {
        /** @var LanguageNode|MockObject $parent */
        $parent = $this->createMock(LanguageNode::class);
        $node = new LanguageNode($this->languageId);
        $node->setParent($parent);
        $this->assertEquals($parent, $node->getParent());
    }

    /**
     */
    public function testAddChildren(): void
    {
        $language = LanguageId::generateIdentifier(self::NAMESPACE, 'en');

        /** @var LanguageNode|MockObject $children */
        $children = new LanguageNode($language);
        $node = new LanguageNode($this->languageId);
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
        $languagePl = LanguageId::generateIdentifier(self::NAMESPACE, 'pl');
        $languageEn = LanguageId::generateIdentifier(self::NAMESPACE, 'en');
        $languageEnGb = LanguageId::generateIdentifier(self::NAMESPACE, 'en_GB');
        $languageEnUs = LanguageId::generateIdentifier(self::NAMESPACE, 'en_US');

        $child1 = new LanguageNode($languageEn);
        $child2 = new LanguageNode($languageEnGb);
        $child3 = new LanguageNode($languageEnUs);

        $child1->addChild($child2);
        $child1->addChild($child3);

        $node = new LanguageNode($languagePl);
        $node->addChild($child1);

        $this->assertTrue($node->hasSuccessor($languageEn));
        $this->assertTrue($node->hasSuccessor($languageEnGb));
        $this->assertTrue($node->hasSuccessor($languageEnUs));
    }
}
