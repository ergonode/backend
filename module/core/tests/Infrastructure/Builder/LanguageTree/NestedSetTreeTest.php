<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Infrastructure\Builder\LanguageTree;

use Ergonode\Core\Infrastructure\Builder\LanguageTree\NestedSetTree;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\TestCase;

/**
 */
class NestedSetTreeTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testRoot(): void
    {
        $tree = new NestedSetTree();

        $id = AggregateId::generate();
        $tree->addRoot($id, 'en');

        $this->assertEquals('en', $tree->getData()[0]->getCode());
        $this->assertEquals('1', $tree->getData()[0]->getLeft());
        $this->assertEquals('2', $tree->getData()[0]->getRight());
    }

    /**
     * @throws \Exception
     */
    public function testNode(): void
    {
        $tree = new NestedSetTree();

        $idEn = AggregateId::generate();
        $idPl = AggregateId::generate();
        $idDe = AggregateId::generate();


        $tree->addRoot($idEn, 'en');
        $tree->addNode($idPl, 'pl', $idEn);
        $tree->addNode($idDe, 'de', $idEn);

        $this->assertEquals('en', $tree->getData()[0]->getCode());
        $this->assertEquals('1', $tree->getData()[0]->getLeft());
        $this->assertEquals('6', $tree->getData()[0]->getRight());

        $this->assertEquals('pl', $tree->getData()[1]->getCode());
        $this->assertEquals('2', $tree->getData()[1]->getLeft());
        $this->assertEquals('3', $tree->getData()[1]->getRight());

        $this->assertEquals('de', $tree->getData()[2]->getCode());
        $this->assertEquals('4', $tree->getData()[2]->getLeft());
        $this->assertEquals('5', $tree->getData()[2]->getRight());
    }
}
