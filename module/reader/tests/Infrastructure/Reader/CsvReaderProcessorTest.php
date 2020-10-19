<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Reader\Tests\Infrastructure\Reader;

use Ergonode\Reader\Infrastructure\Processor\CsvReaderProcessor;
use PHPUnit\Framework\TestCase;

/**
 */
class CsvReaderProcessorTest extends TestCase
{
    private const FILE_NAME = 'test.csv';

    /**
     */
    public function testFileRead(): void
    {
        $file = \sprintf('%s/../../%s', __DIR__, self::FILE_NAME);

        $reader = new CsvReaderProcessor();
        $reader->open($file);

        $result = null;

        foreach ($reader as $line) {
            $result = $line;
        }

        self::assertCount(2, $result);
        self::assertArrayHasKey('id', $result);
        self::assertArrayHasKey('value', $result);
        self::assertNotEmpty($result);
    }

    /**
     */
    public function testIncorrectFileRead(): void
    {
        $this->expectException(\RuntimeException::class);
        $file = 'unknown file';

        $reader = new CsvReaderProcessor();
        $reader->open($file);
    }
}
