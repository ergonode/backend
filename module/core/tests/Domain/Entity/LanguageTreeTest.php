<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\Entity;

use Ergonode\Core\Domain\Entity\LanguageTree;
use Ergonode\Core\Domain\ValueObject\LanguageNode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LanguageTreeTest extends TestCase
{
    /**
     * @var LanguageNode|MockObject
     */
    protected $languageNode;

    protected function setUp(): void
    {
        $this->languageNode = $this->createMock(LanguageNode::class);
    }

    public function testCreateEntity(): void
    {
        $entity = new LanguageTree($this->languageNode);
        $this->assertEquals($this->languageNode, $entity->getLanguages());
    }

    public function testUpdateEntity(): void
    {
        $entity = new LanguageTree($this->languageNode);

        $node = $this->createMock(LanguageNode::class);
        $entity->updateLanguages($node);

        $this->assertEquals($node, $entity->getLanguages());
    }
}
