<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\ValueObject;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;

/**
 */
class LanguagePrivilegesTest extends TestCase
{
    /**
     * @param $array1
     * @param $array2
     * @param $expected
     *
     * @dataProvider dataProvider1
     */
    public function testIsEqualFunction($array1, $array2, $expected): void
    {
        $value1 = new LanguagePrivileges($array1);
        $value2 = new LanguagePrivileges($array2);

        $this->assertSame($expected, $value1->isEqual($value2));
    }

    /**
     * @param $array1
     * @param $type
     * @param $array2
     * @param $expected
     *
     * @dataProvider dataProvider2
     */
    public function testIsEqualByTypeFunction($array1, $type, $array2, $expected): void
    {
        $value = new LanguagePrivileges($array1);
        $this->assertSame($expected, $value->isEqualByType($type, $array2));
    }

    /**
     * @param $array
     * @param $expected
     *
     * @dataProvider dataProvider3
     */
    public function testIsValidFunction($array, $expected)
    {
        $this->assertSame(LanguagePrivileges::isValid($array), $expected);
    }

    /**
     */
    public function testDataManipulation(): void
    {
        $array = [
            'read' =>
                [
                    'en_US',
                    'pl_PL',
                ],
            'edit' =>
                [
                    'en_US',
                    'pl_PL',
                ],
        ];

        $value = new LanguagePrivileges($array);

        $this->assertSame($array, $value->getValue());

        $this->assertTrue($value->existLanguageByType('edit', new Language('en_US')));
        $this->assertFalse($value->existLanguageByType('read', new Language('en_EN')));

        $value->removeLanguageByType('edit', new Language('en_US'));
        $this->assertFalse($value->existLanguageByType('edit', new Language('en_US')));

        $value->addLanguageByType('read', new Language('en_AU'));
        $this->assertTrue($value->existLanguageByType('read', new Language('en_AU')));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Type: "delete" does not exist');
        $value->existLanguageByType('delete', new Language('en_US'));
    }


    /**
     * @return array
     */
    public function dataProvider1(): array
    {
        return
            [
                [
                    'array1' => [
                        'read' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'array2' => [
                        'read' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'expected' => true,
                ],
                [
                    'array1' => [
                        'read' =>
                            [
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'array2' => [
                        'read' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'expected' => false,
                ],
                [
                    'array1' => [
                        'read' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'array2' => [
                        'create' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'expected' => false,
                ],
                [
                    'array1' => [
                        'read' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'array2' => [
                        'read' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'delete' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'expected' => false,
                ],
            ];
    }

    /**
     * @return array
     */
    public function dataProvider2(): array
    {
        return
            [
                [
                    'array1' => [
                        'read' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'type' => 'read',
                    'array2' => [
                        'en_US',
                        'pl_PL',
                    ],
                    'expected' => true,
                ],
                [
                    'array1' => [
                        'read' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'type' => 'read',
                    'array2' => [
                        'pl_PL',
                    ],
                    'expected' => false,
                ],
            ];
    }

    /**
     * @return array
     */
    public function dataProvider3(): array
    {
        return
            [
                [
                    'array' => [
                        'read' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'expected' => true,
                ],
                [
                    'array' => [
                        'read' =>
                            [],
                        'edit' =>
                            [],
                    ],
                    'expected' => true,
                ],
                [
                    'array' => [
                        'read' => 'test',
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'expected' => false,
                ],
                [
                    'array' => [
                        'edit' =>
                            [
                                'en_US',
                                'pl_PL',
                            ],
                    ],
                    'expected' => false,
                ],
            ];
    }
}
