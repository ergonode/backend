<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Infrastructure\Builder\LanguageTree;

use Ergonode\Core\Infrastructure\Builder\LanguageTree\Branch;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\TestCase;

class BranchTest extends TestCase
{
    private AggregateId $id;

    private string $code;

    private int $left;

    private int $right;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->id = AggregateId::generate();
        $this->code = 'code';
        $this->left = 1;
        $this->right = 2;
    }

    public function testCreate(): void
    {
        $branch = new Branch($this->id, $this->code, $this->left, $this->right);

        $this->assertEquals($this->id, $branch->getId());
        $this->assertEquals($this->code, $branch->getCode());
        $this->assertEquals($this->left, $branch->getLeft());
        $this->assertEquals($this->right, $branch->getRight());
    }

    public function testAdded(): void
    {
        $branch = new Branch($this->id, $this->code, $this->left, $this->right);
        $branch->addToRight(100);
        $branch->addToLeft(10);


        $this->assertEquals($this->left + 10, $branch->getLeft());
        $this->assertEquals($this->right + 100, $branch->getRight());
    }
}
