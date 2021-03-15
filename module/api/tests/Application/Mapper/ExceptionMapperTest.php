<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Tests\Application\Mapper;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Ergonode\Api\Application\Mapper\ExceptionMapper;
use PHPUnit\Framework\TestCase;

class ExceptionMapperTest extends TestCase
{
    /**
     * @param array $map
     * @param array $expected
     *
     * @dataProvider dataProvider
     */
    public function testMapper(array $map, \Throwable $exception, ?array $expected): void
    {
        $mapper = new ExceptionMapper($map);
        $result = $mapper->map($exception);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'map' => [
                    'Doctrine\DBAL\Exception\InvalidArgumentException' => [
                        'http' => [
                            'code' => '403',
                        ],
                        'content' => [
                            'code' => '403',
                            'message' => 'test message',
                        ],
                    ],
                ],
                'exception' => new InvalidArgumentException(),
                'expected' => [
                    'http' => [
                        'code' => '403',
                    ],
                    'content' => [
                        'code' => '403',
                        'message' => 'test message',
                    ],
                ],
            ],
            [
                'map' => [
                    'Doctrine\DBAL\DBALException' => [
                        'http' => [
                            'code' => '403',
                        ],
                        'content' => [
                            'code' => '403',
                            'message' => 'test message',
                        ],
                    ],
                ],
                'exception' => new InvalidArgumentException(),
                'expected' => [
                    'http' => [
                        'code' => '403',
                    ],
                    'content' => [
                        'code' => '403',
                        'message' => 'test message',
                    ],
                ],
            ],
        ];
    }
}
