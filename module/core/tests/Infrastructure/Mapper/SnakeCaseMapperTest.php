<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Infrastructure\Mapper;

use Ergonode\Core\Infrastructure\Mapper\SnakeCaseMapper;
use PHPUnit\Framework\TestCase;

class SnakeCaseMapperTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testMapper(string $source, string $destination): void
    {
        $mapper  = new SnakeCaseMapper();
        $result = $mapper->map([$source => null]);
        $this->assertEquals([$destination => null], $result);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'source' => 'TestKey',
                'destination' => 'test_key',
            ],
            [
                'source' => 'snake_case',
                'destination' => 'snake_case',
            ],
            [
                'source' => 'word',
                'destination' => 'word',
            ],
        ];
    }
}
