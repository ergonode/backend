<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Infrastructure\Model;

use Ergonode\Core\Infrastructure\Model\Relationship;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class RelationshipTest extends TestCase
{
    public function testCreation(): void
    {
        $array = [$this->createMock(RelationshipGroup::class)];

        $collection = new Relationship($array);

        self::assertCount(1, $collection);
        self::assertSame($array[0], $collection->current());
    }

    public function testCreationEmptyArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Relationship([]);
    }

    public function testCreationIncorrectElementClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Relationship([new \stdClass()]);
    }
}
