<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Application\Service;

use Ergonode\Multimedia\Application\Service\DuplicateFilenameSuffixGeneratingService;
use PHPUnit\Framework\TestCase;

class DuplicateFilenameSuffixGeneratingServiceTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testGenerateSuffix(string $name, int $iterationIndex, string $expected): void
    {
        $generator = new DuplicateFilenameSuffixGeneratingService();

        $result = $generator->generateSuffix($name, $iterationIndex);
        $this->assertSame($result, $expected);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'name' => str_repeat('a', 15),
                'iterationIndex' => 2,
                'expected' => str_repeat('a', 15).'(2)',
            ],
            [
                'name' => str_repeat('b', 15),
                'iterationIndex' => 123,
                'expected' => str_repeat('b', 15).'(123)',
            ],
            [
                'name' => str_repeat('c', 130),
                'iterationIndex' => 2,
                'expected' => str_repeat('c', 125).'(2)',
            ],
            [
                'name' => str_repeat('d', 130),
                'iterationIndex' => 123,
                'expected' => str_repeat('d', 123).'(123)',
            ],
            [
                'name' => str_repeat('©', 130),
                'iterationIndex' => 123,
                'expected' => str_repeat('©', 123).'(123)',
            ],
        ];
    }
}
