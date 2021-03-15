<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Infrastructure\Builder\LanguageTree;

use Ergonode\Core\Infrastructure\Builder\LanguageTree\NestedSetTree;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\TestCase;

class NestedSetTreeTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testRoot(): void
    {
        $tree = new NestedSetTree();

        $id = AggregateId::generate();
        $tree->addRoot($id, 'en_GB');
        self::assertEquals('en_GB', $tree->getData()[0]->getCode());
        self::assertEquals('1', $tree->getData()[0]->getLeft());
        self::assertEquals('2', $tree->getData()[0]->getRight());
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


        $tree->addRoot($idEn, 'en_GB');
        $tree->addNode($idPl, 'pl_PL', $idEn);
        $tree->addNode($idDe, 'de_DE', $idEn);

        self::assertEquals('en_GB', $tree->getData()[0]->getCode());
        self::assertEquals('1', $tree->getData()[0]->getLeft());
        self::assertEquals('6', $tree->getData()[0]->getRight());

        self::assertEquals('pl_PL', $tree->getData()[1]->getCode());
        self::assertEquals('2', $tree->getData()[1]->getLeft());
        self::assertEquals('3', $tree->getData()[1]->getRight());

        self::assertEquals('de_DE', $tree->getData()[2]->getCode());
        self::assertEquals('4', $tree->getData()[2]->getLeft());
        self::assertEquals('5', $tree->getData()[2]->getRight());
    }
}
