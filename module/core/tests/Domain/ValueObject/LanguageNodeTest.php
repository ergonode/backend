<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\LanguageNode;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LanguageNodeTest extends TestCase
{
    public const NAMESPACE = '53db6054-f4c2-4753-a786-297009377be2';
    /**
     * @var LanguageId|MockObject
     */
    private $languageId;

    protected function setUp(): void
    {
        $this->languageId = $this->createMock(LanguageId::class);
    }

    public function testCreateNode(): void
    {
        $node = new LanguageNode($this->languageId);
        self::assertEquals($this->languageId, $node->getLanguageId());
    }

    public function testAddChildren(): void
    {
        $language = LanguageId::generateIdentifier(self::NAMESPACE, 'en_GB');

        /** @var LanguageNode|MockObject $children */
        $children = new LanguageNode($language);
        $node = new LanguageNode($this->languageId);
        self::assertFalse($node->hasChild($language));
        $node->addChild($children);
        self::assertEquals($children, $node->getChildren()[0]);
        self::assertTrue($node->hasChild($language));
        self::assertTrue($node->hasChildren());
    }

    public function testHasSuccessor(): void
    {
        $languagePl = LanguageId::generateIdentifier(self::NAMESPACE, 'pl_PL');
        $languageEnGb = LanguageId::generateIdentifier(self::NAMESPACE, 'en_GB');
        $languageEnNz = LanguageId::generateIdentifier(self::NAMESPACE, 'en_NZ');
        $languageEnUs = LanguageId::generateIdentifier(self::NAMESPACE, 'en_US');

        $child1 = new LanguageNode($languageEnGb);
        $child2 = new LanguageNode($languageEnNz);
        $child3 = new LanguageNode($languageEnUs);

        $child1->addChild($child2);
        $child1->addChild($child3);

        $node = new LanguageNode($languagePl);
        $node->addChild($child1);

        self::assertTrue($node->hasSuccessor($languageEnGb));
        self::assertTrue($node->hasSuccessor($languageEnNz));
        self::assertTrue($node->hasSuccessor($languageEnUs));
    }
}
