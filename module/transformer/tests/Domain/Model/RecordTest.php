<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Transformer\Domain\Model;

use Ergonode\Transformer\Domain\Model\Record;
use PHPUnit\Framework\TestCase;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class RecordTest extends TestCase
{
    /**
     */
    public function testRecordManipulation(): void
    {
        $name = 'name';
        $value = $this->createMock(ValueInterface::class);
        $record = new Record();
        $this->assertFalse($record->has($name));
        $record->set($name, $value);
        $result = $record->get($name);
        $this->assertSame($value, $result);
        $this->assertTrue($record->has($name));
    }

    /**
     */
    public function testGetException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $record = new Record();
        $record->get('test');
    }
}
