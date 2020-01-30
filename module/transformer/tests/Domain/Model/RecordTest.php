<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Transformer\Domain\Model;

use Ergonode\Transformer\Domain\Model\Record;
use PHPUnit\Framework\TestCase;

/**
 */
class RecordTest extends TestCase
{
    /**
     */
    public function testRecordManipulation(): void
    {
        $collection = 'example';
        $name = 'name';
        $value = 'string';
        $record = new Record();
        $this->assertFalse($record->has($name));
        $record->add($collection, $name, $value);
        $column = $record->get($name);
        $this->assertSame('string', $column);
        $this->assertTrue($record->has($name));
        $columns = $record->getColumns($collection);
        $this->assertSame('string', $columns['name']);
        $this->assertTrue($record->hasColumns($collection));
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @expectedExceptionMessage Record haven't column test
     */
    public function testGetException(): void
    {
        $record = new Record();
        $record->get('test');
    }
}
